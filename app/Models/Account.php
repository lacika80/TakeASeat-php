<?php

namespace App\Models;

use App\Models\DTOs\Accountdto;
use JetBrains\PhpStorm\NoReturn;
use PDO;

require_once("Db.php");

class Account extends Db
{
    #[NoReturn] public function registration($post)
    {
        /*$stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM accounts WHERE username = ?');
         if ($stmt->execute([$_POST["username"]])) {
             $account = $stmt->fetch(PDO::FETCH_ASSOC);
             if ($account != false) {
                 header("Location: /admin?login=false&reservedid=true");
                 die;
             }
         }
         $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM accounts WHERE email = ?');
         if ($stmt->execute([$_POST["email"]])) {
             $account = $stmt->fetch(PDO::FETCH_ASSOC);
             if ($account != false) {
                 header("Location: /admin?login=false&reservedemail=true");
                 die;
             }
         }
         if ($_POST["password1"] != $_POST["password2"]) {
             header("Location: /admin?login=false&notSame=true");
             die;
         }
         $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
         $stmt = $this->pdo_connect_mysql()->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)');
         if ($stmt->execute([$_POST["username"],$password,$_POST["email"]])) {
             $_SESSION["LoggedIn"] = true;
             header("Location: /admin/slider");
             die;
         }*/

        //adatok ellenörzése, hogy minden megjött-e
        $required = ['rFamilyName', 'rGivenName', 'remail', 'rpassword', 'rpassword2'];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                $data = ['StatusCode' => 1, 'Message' => 'Field is missing'];
                echo json_encode($data);
                die();
            }
        }
        if ($post['rpassword2'] != $post['rpassword']) {
            $data = ['StatusCode' => 2, 'Message' => 'Password mismatch'];
            echo json_encode($data);
            die();
        }

        //e-mail cím ellenörzése
        if ($this->isEmailExists($post["remail"])) {
            $data = ['StatusCode' => 3, 'Message' => 'Email exists'];
            echo json_encode($data);
            die();
        }
        $newDb = new PDO_DB();
        if ($newDb->insert('user',
            array(
                'first_name'      =>  $post['rGivenName'],
                'last_name'  =>  $post['rFamilyName'],
                'email'     =>  $post['remail'],
                'password'  =>  password_hash($post['rpassword'], PASSWORD_DEFAULT)
            ))){
            $data = ['StatusCode' => 0, 'Message' => 'ok'];
            echo json_encode($data);
            die();
        }
        $data = ['StatusCode' => 4, 'Message' => 'An unknown error occurred'];
        echo json_encode($data);
        die();
    }

    public function login()
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT password FROM user WHERE email = ? LIMIt 1');
        if ($stmt->execute([$_POST["email"]])) {
            $userPW = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($_POST['password'], $userPW["password"])) {
                $_SESSION["LoggedIn"] = true;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function isEmailExists($email): bool
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT email FROM user WHERE email = ?');
        $stmt->execute([$email]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        return $account != false;
    }

    public function  verify_session() {
        $newdb = new PDO_DB();
        $userEmail = $_SESSION['userEmail'];

        if ( empty( $userEmail ) && ! empty( $_COOKIE['rememberme'] ) ) {
            list($selector, $authenticator) = explode(':', $_COOKIE['rememberme']);

            $results = $newdb->get_results("SELECT * FROM auth_tokens WHERE selector = :selector", ['selector'=>$selector]);
            $auth_token = $results[0];

            if ( hash_equals( $auth_token->token, hash( 'sha256', base64_decode( $authenticator ) ) ) ) {
                $userEmail = $auth_token->email;
                $_SESSION['userEmail'] = $userEmail;
            }
        }

        $user =  $this->isEmailExists( $userEmail );

        if ( false !== $user ) {
            return true;
        }

        return false;
    }

    public function getAllUser()
    {
    }

    public function setPrivilage()
    {
    }
}
