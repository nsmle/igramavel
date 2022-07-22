<?php

namespace App\Repositories;

use Instagram\Api;
use Instagram\Auth\{Checkpoint\ImapClient, Login, Session};
use Instagram\Exception\{InstagramException, InstagramAuthException};
use GuzzleHttp\Cookie\CookieJar;

class InstagramRepository extends Api
{
    /*
     * Overide \Instagram\Api::login()
     * 
     * @param string $username
     * @param string $password
     * @param \Instagram\Auth\Checkpoint\ImapClient $imapClient
     *
     * @return void
     */
    public function login(string $username, string $password, ?ImapClient $imapClient = null): void
    {
        $login = new Login($this->client, $username, $password, $imapClient, $this->challengeDelay);

        // Login
        $cookies = $login->process();
        // Set session login for use in the next request
        $this->session = new Session($cookies);
    }

    /*
     * Get session of \Instagram\Api::$session
     * 
     * @return \GuzzleHttp\Cookie\CookieJar
     */
    public function getSession(): CookieJar
    {
        return $this->session->getCookies();
    }

    /*
     * Set session of \Instagram\Api::$session
     * 
     * @param array $session
     */
    public function setSession(array $session): void
    {
        $session = collect($session)->map(function ($cookie) {
            return collect($cookie)->toArray();
        });
        
        $cookies = new CookieJar(false, $session->toArray());
        
        $this->session = new Session($cookies);
    }
}