<?php

namespace App\Services;

use App\Repositories\InstagramRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Cookie\{SetCookie, CookieJar};
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Instagram\Utils\CacheResponse;

class InstagramService
{
    /**
     * @var \stdClass
     */
    public \stdClass $user;

    /**
     * @var \App\Repositories\InstagramRepository
     */
    public InstagramRepository $Instagram;

    /*
     * Initialization property $Instagram
     */
    public function __construct(InstagramRepository $instagram)
    {
        $this->Instagram = $instagram;

        if (!empty(request()->header('user-agent'))) {
            $this->Instagram->setUserAgent(request()->header('user-agent'));
        }
    }

    /*
     * Get id of user login
     *
     * @return string
     */
    public function getSelfId(): string
    {
        if (!empty($this->user)) {
            return (string) $this->user->id;
        }

        $session = $this->Instagram->getSession();
        $userId = $session->getCookieByName('ds_user_id')->getValue();

        return (string) $userId;
    }

    /*
     * Login into instagram with credentials
     *
     * @param string $username
     * @param string $password
     *
     * @return string
     */
    public function login(string $username, string $password): string
    {
        // Login with instagram credentials
        $this->Instagram->login($username, $password);

        // Generate jwt token after login
        $token = $this->generateToken();

        return $token;
    }

    /*
     * Login into instagram with cookie session id
     *
     * @param array $cookie
     *
     * @return string
     */
    public function loginWithCookie(array $cookie): string
    {
        $cookie = new SetCookie(array_merge($cookie, [
            'Name'     => 'sessionid',
            'Max-Age'  => '31536000',
            "Secure"   => true,
            "Discard"  => false,
            "HttpOnly" => true,
        ]));

        $cookieJar = new CookieJar(false, [$cookie]);

        // Login with cookies
        $this->Instagram->loginWithCookies($cookieJar);

        // Generate jwt token after login with cookie
        $token = $this->generateToken();

        return $token;
    }

    /*
     * Generate token
     *
     * @return string
     */
    public function generateToken(): string
    {
        // Get data user login
        $me = $this->Instagram->getProfileById($this->getSelfId());
        $this->user = (object) $me->toArray();

        // Get instagram  session id from login with credentials
        $session   = $this->Instagram->getSession();
        $sessionId = $session->getCookieByName('sessionId');

        // Generate Json Web Token
        $jwt = JWT::encode([
            "iss" => request()->getHost(),
            "sub" => $me->getId(),
            "iat" => time(),
            "exp" => $sessionId->getExpires(),
            'session' => Crypt::encrypt([
                'user'    => [
                    'id'       => $me->getId(),
                    'fullName'     => $me->getFullName(),
                    'userName' => $me->getUserName(),
                ],
                'cookies' => [
                    $session->getCookieByName('sessionid')->toArray(),
                    $session->getCookieByName('csrftoken')->toArray()
                ]
            ])
        ], env('JWT_SECRET'), 'HS256');

        return $jwt;
    }

    /*
     * Authorize token
     *
     * @param string $token
     *
     * @return array
     */
    public function authorize(string $token): array
    {
        try {
            // Decode and get payload of Json Web Token
            $payload = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $session = Crypt::decrypt($payload->session);
            $this->user = (object) $session['user'];

            $cookies = $session['cookies'];
            $cookieJar = new CookieJar(false, $cookies);

            if ($cookieJar->getCookieByName('sessionId')->getExpires() > time()) {
                $this->Instagram->loginWithCookies($cookieJar);
            }

            return [
                'valid'  => true,
                'message' => 'authorized'
            ];
        } catch (\Exception $e) {
            return [
                'valid'  => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /*
     * Generate json response
     *
     * @param array $data
     * @param array $options
     *
     * @return 
     */
    public function jsonResponse(array $data, array $options)
    {
        $response = CacheResponse::getResponse();
        $session  = $this->Instagram->getSession();

        return response()->json([
            'status'  => $options['status'] ?? $response->getReasonPhrase(),
            'code'    => $options['code'] ?? $response->getStatusCode(),
            'message' => $options['message'] ?? '',
            'data'    => $data
        ], $options['code'] ?? $response->getStatusCode())->withHeaders([
            'x-ig-header'  => collect($response->getHeaders())->toJson(),
            'x-ig-session' => collect($session->toArray())->toJson()
        ])->cookie(
            'token',
            $this->generateToken(),
            now()->parse(
                $session->getCookieByName('sessionId')->getExpires()
            )->diffInMinutes(),
            '/',
            request()->getHost(),
            request()->secure(),
            true
        );
    }
}
