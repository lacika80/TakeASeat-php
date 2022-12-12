<?php

namespace App\Libs;

use App\Models\Account;
use Google\Client;
use Google\Exception;
use Google_Service_Oauth2;

class GoogleConfig
{
    private static $instance = null;
    private Client $client;

    public function __construct()
    {
        $this->getOauth2Client();
    }

    public static function getInstance(): ?GoogleConfig
    {
        if (self::$instance == null) {
            self::$instance = new GoogleConfig();
        }

        return self::$instance;
    }

    private function buildClient(): Client
    {
        $client = new Client();
        $client->setAccessType("offline");
        try {
            $client->setAuthConfig("app/Libs/client_secret_609239401095-3sc7srtqc53q6bgb2l1o7e06cbgdkjr6.apps.googleusercontent.com.json");
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        $client->addScope("email");
        $client->addScope("profile");
        //$this->client->setApprovalPrompt('force');
        return $client;
    }

    private function getOauth2Client()
    {

        $this->client = $this->buildClient();
        $this->isValid();
    }

    /**
     * @return Client
     */
    public
    function getClient(): Client
    {
        return $this->client;
    }

    function setClient($client): void
    {
        $this->client = $client;
    }

    /**
     * Check the Google client instance and try to validate if the session contains a valid refresh token
     * @return bool
     */
    public function isValid(): bool
    {
        if (!isset($_SESSION["refresh_token"]) && !isset($_COOKIE["google_refresh_token"])) {
            return false;
        } else {
            if (!isset($_SESSION["refresh_token"]) && isset($_COOKIE["google_refresh_token"]))
                $_SESSION["refresh_token"]=$_COOKIE['google_refresh_token'];
            try {
                if ($this->client->isAccessTokenExpired()) {


                    // update access token
                    $this->client->fetchAccessTokenWithRefreshToken($_SESSION["refresh_token"]);

                }


                /*  $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                  $this->client->setAccessToken($this->client->getAccessToken());
                  $_SESSION['access_token'] = $this->client->getAccessToken();*/
            } catch (Exception $e) {
                $this->client->fetchAccessTokenWithRefreshToken($_SESSION["refresh_token"]);

            }
            if ($this->client->isAccessTokenExpired()) {
                return false;
            }
        }
        setcookie(
            'google_refresh_token',
            $_SESSION['refresh_token'],
            time() + 864000,
            '/',
            '',
            true,
            true
        );

        return true;
    }

}