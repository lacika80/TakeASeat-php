<?php

namespace App\Libs;

use Google\Client;
use Google\Exception;

class GoogleConfig
{
    private Client $client;
    public function __construct()
    {
        $this->createClient();
    }

    public function createClient(){
        $this->client = new Client();
        try {
            $this->client->setAuthConfig("app/Libs/client_secret_609239401095-3sc7srtqc53q6bgb2l1o7e06cbgdkjr6.apps.googleusercontent.com.json");
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
    public function isValid(): bool{
        if (empty($_SESSION["token"])) {
           return false;
        } else {
            $this->client->setAccessToken($_SESSION["token"]);
            if ($this->client->getAccessToken()) {
                if ($this->client->isAccessTokenExpired()) {
                    unset($_SESSION["token"]);
                   return false;
                }
            }
            return true;
        }
    }

}