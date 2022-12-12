<?php

namespace App\Controllers;

use App\Libs\GoogleConfig;
use App\Models\Account;
use App\Models\DTOs\Accountdto;
use Google\Client;
use http\Cookie;

class LoginController extends BaseController
{

    function __construct()
    {

    }

    public function index()
    {
        if ($this->loginCheck())
            header("Location: /app/choose");


        $google = new GoogleConfig();

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
                $account = new Account();
                if ($account->loginViaGoogle()) {
                    header("Location: /app/choose");
                } else {
                    header("Location: /login?StatusCode=1");
                }

                die();
            } else header("Location: /login?StatusCode=1");
            die();
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $account = new Account();
            if ($account->login()) {
                //$data = ['StatusCode' => 0, 'Message' => 'ok'];
                header("Location: /app/choose");
            } else {
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
        if (isset($this->client))
        $this->client->revokeToken($_SESSION['refresh_token']);
        $account = new Account();
        $account->revokeRememberMeToken();
        session_destroy();
        setcookie("rememberMe", "", time() - 3600, '/');
        setcookie('google_refresh_token', "", time() - 3600, '/');
        header("Location: /");
    }

    public function register($post)
    {
        //header('Content-Type: application/json; charset=utf-8');
        $account = new Account();
        $registration = $account->registration($post);
        if ($registration == 0) {
            header("Location: /app/choose?StatusCode=6");
        } else {
            header("Location: /login?register=true&StatusCode=" . $registration);
        }
        die();
    }
    public function pwreset()
    {
        if ($this->loginCheck())
            header("Location: /app/choose");
        $data = [
            "bootstrap",
            /* "swiper",
            "srimages" => $image->GetActives(),
            "blogs" => $content->Get3Blog(),
            "introduction" => $content->GetIntroduction($strJsonFileContents["introductionId"]),
            "meta" => [["google-signin-client_id", "609239401095-3sc7srtqc53q6bgb2l1o7e06cbgdkjr6.apps.googleusercontent.com"]]*/
        ];

        $this->View("Login/resetpw", $data);
    }
    public function pwresetpost(){
$account=new Account();
if ($account->resetpw($_POST)){
    header("Location: /login?StatusCode=" . 10);
}
        else header("Location: /login?StatusCode=" . 11);

    }

}