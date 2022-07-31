<?php

namespace App\Services;

use App\Repositories\InstagramRepository;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Cookie\CookieJar;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

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
    }

    /*
     * Get id of user login
     *
     * @return string
     */
    public function getSelfId(): string
    {
        if (!empty($this->user)) {
            return $this->user->id;
        }

        $session = $this->Instagram->getSession();
        $userId = $session->getCookieByName('ds_user_id')->getValue();

        return $userId;
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
        // Login
        $this->Instagram->login($username, $password);

        // Get data user login
        $me = $this->Instagram->getProfileById($this->getSelfId());

        // Get instagram  session id from login with credentials
        $sessionId = $this->Instagram->getSession()->getCookieByName('sessionId');

        // Generate Json Web Token
        $jwt = JWT::encode([
            "iss" => request()->getHost(),
            "sub" => $me->getId(),
            "iat" => time(),
            "exp" => $sessionId->getExpires(),
            'session' => Crypt::encrypt([
                'user'    => [
                    'id'       => $me->getId(),
                    'name'     => $me->getFullName(),
                    'username' => $me->getUserName(),
                ],
                'cookies' => $sessionId->toArray()
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
            $this->user = $session->user;

            $cookies = $session->cookies;
            $cookieJar = new CookieJar(false, $cookies);
            $this->Instagram->loginWithCookies($cookieJar);

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
    
}
