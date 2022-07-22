<?php

namespace App\Services;

use App\Repositories\InstagramRepository;
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
        
        // Generate Json Web Token
        $jwt = JWT::encode([
            'user' => [
                'id'           => $me->getId(),
                'name'         => $me->getFullName(),
                'username'     => $me->getUserName(),
                'biography'    => $me->getBiography(),
                'followers'    => $me->getFollowers(),
                'following'    => $me->getFollowing(),
                'external_url' => $me->getExternalUrl(),
                'private'      => $me->isPrivate(),
                'verified'     => $me->isVerified(),
            ],
            'cookies' => [
                $this->Instagram->getSession()->getCookieByName('sessionId')->toArray()
            ]
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
            
            $this->user = $payload->user;
            
            $cookies = $payload->cookies;
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
