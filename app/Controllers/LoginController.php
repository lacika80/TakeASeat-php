<?php

namespace App\Controllers;

use App\Libs\GoogleConfig;
use App\Models\Account;
use App\Models\DTOs\Accountdto;
use Google\Client;
use Google_Client;
use Google_Service_Oauth2;

class LoginController extends BaseController
{
    private Client $client;

    function __construct()
    {
        $google = new GoogleConfig();
        $this->client = $google->getClient();
    }

    public function index()
    {
        $google = new GoogleConfig();
        if ($google->isValid()){
            header("Location: app/choose");
            die();
        }
        $data = [
            "bootstrap",
            "loginURL" => $this->client->createAuthUrl(),
            /* "swiper",
            "srimages" => $image->GetActives(),
            "blogs" => $content->Get3Blog(),
            "introduction" => $content->GetIntroduction($strJsonFileContents["introductionId"]),
            "meta" => [["google-signin-client_id", "609239401095-3sc7srtqc53q6bgb2l1o7e06cbgdkjr6.apps.googleusercontent.com"]]*/
        ];

        $this->View("Login/login", $data);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET["code"])) {
                $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
                $this->client->setAccessToken($token);
                $_SESSION["token"] = $token;
                header("Location: /app/choose");
            } else header("Location: /login");
        }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $account = new Account();
            if ($account->login()){
                //$data = ['StatusCode' => 0, 'Message' => 'ok'];
                header("Location: /app/choose");
            }else{
                //$data = ['StatusCode' => 1, 'Message' => 'Sikertelen bejelentkezés'];
                header("Location: /login?StatusCode=1");
            }
            die();
            echo json_encode($data);
            die();
        }
    }

    public function logout()
    {

        $this->client->revokeToken($_SESSION["token"]);
        session_destroy();
        header("Location: /login");
    }

    public function register($post)
    {
        //header('Content-Type: application/json; charset=utf-8');
        $account = new Account();
        $account->registration($post);


    }


}