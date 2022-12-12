<?php

namespace App\Models;

use App\Models\DTOs\Accountdto;
use App\Libs\GoogleConfig;
use DateInterval;
use DateTime;
use Google\Client;
use Google_Service_Oauth2;
use http\Cookie;
use JetBrains\PhpStorm\NoReturn;
use PDO;

require_once("Db.php");

class Account extends Db
{
    private Client $client;

    function __construct()
    {
        $google = GoogleConfig::getInstance();
        $this->client = $google->getClient();
    }

    /**
     * @throws \Exception
     */
    public function registration($post): int
    {


        //adatok ellenörzése, hogy minden megjött-e
        $required = ['rFamilyName', 'rGivenName', 'remail', 'rpassword', 'rpassword2'];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                return 2;
            }
        }
        if ($post['rpassword2'] != $post['rpassword']) {
            return 3;
        }

        //e-mail cím ellenörzése
        if ($this->isEmailExists($post["remail"])) {
            return 4;
        }
        $newDb = new PDO_DB();
        if ($newDb->insert('user',
            array(
                'first_name' => $post['rGivenName'],
                'last_name' => $post['rFamilyName'],
                'email' => $post['remail'],
                'password' => password_hash($post['rpassword'], PASSWORD_DEFAULT)
            ))) {
            $user = $this->getUserByEmail($post["remail"]);
            if ($this->createEmail($post["remail"], 3, null, $user["id"]))
                echo "asd";
            else echo "false";
            die();
            return 0;
        }
        return 5;
    }

    /**
     * take together the email, send it and saves into database
     * @param $to string
     * @param $type int 1: reset pw (1h), 2: invited registration (1d), 3: verify email (1h)
     * @param $from string
     * @param int|null $senderId
     * @param int|null $receiverId
     * @param int|null $permissions
     * @param int|null $restaurantId
     * @return bool
     * @throws \Exception
     */
    private function createEmail(string $to, int $type, int $senderId = null, int $receiverId = null, ?int $permissions = null, ?int $restaurantId = null, string $from = 'Rendszer(Take A Seat)'): bool
    {
        //create spec link
        $token = bin2hex(random_bytes(32));
        $url = "https://takeaseat.com/" . http_build_query([
                'validator' => $token
            ]);
        $url = str_replace("validator=", "", "$url");

        //insert into database
        $expires = new DateTime('NOW');
        switch ($type) {
            case 1:
                $expires->add(new DateInterval('PT01H')); // 1 hour
                $stmt = $this->pdo_connect_mysql()->prepare('INSERT INTO dynamic_links (type,   date_valid_until, email, receiver_id,   link) VALUES (?, ?, ?, ?, ?)');
                if ($stmt->execute([$type, $expires->format('U'), $to, $receiverId, $token])) {
                    $message = '<p>Pw reset kérelem ';
                    $message .= 'Ha nem te kérted, akkor hagyd figyelmen kívül</p>';
                    $message .= '<p>A visszaállításhoz itt a link:</br>';
                    $message .= sprintf('<a href="%s">%s</a></p>', $url, $url);
                    $message .= '<p>Üdv!</p>';
                    $subject = "Your password reset link";

                    $systemMail = "noreply@takeaseat.hu";

                    $headers = "From: " . $from . " <" . $systemMail . ">\r\n";
                    $headers .= "Content-type: text/html\r\n";
                    // Send email
                    return mail($to, $subject, $message, $headers);
                }
                return false;
                break;
            case 2:
                $expires->add(new DateInterval('PT01D')); // 1 day
                $stmt = $this->pdo_connect_mysql()->prepare('INSERT INTO dynamic_links (type, sender_id, restaurant_id, date_valid_until, email,  permissions,  link) VALUES (?, ?, ?, ?, ?, ?,?)');
                if ($stmt->execute([$type, $senderId, $restaurantId, $expires->format('U'), $to, $permissions, $token])) {
                    $message = '<p>Meghívtak, hogy csatlakozz az éttermed kezelésébe.... ';
                    $message .= 'igen majd át kell irni de nah</p>';
                    $message .= '<p>Ezen a linken tudsz regisztálni:</br>';
                    $message .= sprintf('<a href="%s">%s</a></p>', $url, $url);
                    $message .= '<p>Üdv</p>';
                    $subject = "Meghívó";

                    $systemMail = "noreply@takeaseat.hu";

                    $headers = "From: " . $from . " <" . $systemMail . ">\r\n";
                    $headers .= "Content-type: text/html\r\n";
                    // Send email
                    return mail($to, $subject, $message, $headers);
                }
                return false;
                break;
            case 3:
                $expires->add(new DateInterval('PT01H')); // 1 hour
                $stmt = $this->pdo_connect_mysql()->prepare('INSERT INTO dynamic_links (type,  date_valid_until, email, receiver_id,  link) VALUES (?, ?, ?, ?, ?)');
                if ($stmt->execute([$type, $expires->format('U'), $to, $receiverId, $token])) {
                    $message = '<p>A regisztrációdat kérlek erősítsd meg az alábbi linken: </br>';
                    $message .= sprintf('<a href="%s">%s</a></p>', $url, $url);
                    $message .= '<p>Thanks!</p>';
                    $subject = "E-mail aktiválás";

                    $systemMail = "noreply@takeaseat.hu";

                    $headers = "From: " . $from . " <" . $systemMail . ">\r\n";
                    $headers .= "Content-type: text/html\r\n";
                    // Send email
                    return mail($to, $subject, $message, $headers);
                }
                return false;
                break;
        }
        return false;
        //send the email
    }

    public function login()
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM user WHERE email = ? LIMIt 1');
        if ($stmt->execute([$_POST["email"]])) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($_POST['password'], $user["password"])) {
                if ($_POST['rememberMe'])
                    $this->rememberMe($user);
                $this->sessionFiller($user);
                $_SESSION["loggedInWithPW"] = true;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function rememberMe($user)
    {
        try {
            $selector = base64_encode(random_bytes(9));
            $authenticator = random_bytes(33);
        } catch (\Exception $e) {
            header("Location: /login?StatusCode=1");
            die();
        }

        // Clean up old tokens
        $this->revokeRememberMeToken();

        // Set rememberme cookie
        $authValue = $selector . ':' . base64_encode($authenticator);
        setcookie(
            'rememberMe',
            $authValue,
            time() + 864000,
            '/',
            '',
            true,
            true
        );

        // Insert auth token into database
        $newDb = new PDO_DB();
        $insert = $newDb->insert('auth_tokens',
            array(
                'selector' => $selector,
                'token' => hash('sha256', $authenticator),
                'email' => $user["email"],
                'expires' => date('Y-m-d\TH:i:s', time() + 864000),
            )
        );
    }

    public function revokeRememberMeToken()
    {
        if (isset($_COOKIE["rememberMe"])) {
            list($selector, $authenticator) = explode(':', $_COOKIE['rememberMe']);
            try {
                $stmt = $this->pdo_connect_mysql()->prepare('UPDATE auth_tokens SET is_active = ? where selector = ?');
                $stmt->execute([FALSE, $selector]);
            } catch (\Exception $e) {

            }

        }
    }

    public function stillRememberMe()
    {
        list($selector, $authenticator) = explode(':', $_COOKIE['rememberMe']);

        $stmt = $this->pdo_connect_mysql()->prepare('Select email, token from auth_tokens  where selector = ?');
        $stmt->execute([$selector]);
        $auth_token = $stmt->fetch(PDO::FETCH_ASSOC);

        if (hash_equals($auth_token["token"], hash('sha256', base64_decode($authenticator)))) {
            $this->sessionFiller($this->getUserByEmail($auth_token["email"]));
            return true;
        }
        return false;

    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM user WHERE email = ? LIMIt 1');
        if ($stmt->execute([$email])) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        }
        return false;
    }

    public function sessionFiller($user)
    {
        $_SESSION["loggedIn"] = true;
        $_SESSION["permissions"] = $user["permissions"];
        $_SESSION["globalPermissions"] = $user["global_permissions"];
        $_SESSION["firstName"] = $user["first_name"];
        $_SESSION["lastName"] = $user["last_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["isEmailVerified"] = $user["is_verified"];
        $_SESSION["userId"] = $user["id"];
    }

    public function loginViaGoogle(): bool
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['refresh_token'] = $this->client->getRefreshToken();
        $google_oauth = new Google_Service_Oauth2($this->client);
        $google_account_info = $google_oauth->userinfo->get();
        $email = $google_account_info->email;
        $firstName = $google_account_info->givenName;
        $lastName = $google_account_info->familyName;
        $googleIdentifier = $google_account_info->id;
        $isVerified = $google_account_info->verifiedEmail;
        if ($this->isEmailExists($email)) {
            $user = $this->getUserByEmail($email);

            if ($user) {


                if (is_null($user["password"])) {
                    $this->sessionFiller($user);
                    $google = GoogleConfig::getInstance();
                    $google->setClient($this->client);
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
                } else {
                    $this->client->revokeToken($_SESSION["refresh_token"]);
                    session_destroy();
                    return false;
                }
            }
            return false;
        } elseif (!$isVerified)
            return false;
        else return $this->registrationViaGoogle($email, $firstName, $lastName, $googleIdentifier);
    }

    private
    function isEmailExists($email): bool
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT email FROM user WHERE email = ?');
        $stmt->execute([$email]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        return $account != false;
    }

    public
    function verify_session()
    {
        $newdb = new PDO_DB();
        $userEmail = $_SESSION['userEmail'];

        if (empty($userEmail) && !empty($_COOKIE['rememberme'])) {
            list($selector, $authenticator) = explode(':', $_COOKIE['rememberme']);

            $results = $newdb->get_results("SELECT * FROM auth_tokens WHERE selector = :selector", ['selector' => $selector]);
            $auth_token = $results[0];

            if (hash_equals($auth_token->token, hash('sha256', base64_decode($authenticator)))) {
                $userEmail = $auth_token->email;
                $_SESSION['userEmail'] = $userEmail;
            }
        }

        $user = $this->isEmailExists($userEmail);

        if (false !== $user) {
            return true;
        }

        return false;
    }

    public
    function getAllUser()
    {
    }

    public
    function setPrivilage()
    {
    }

    private
    function registrationViaGoogle($email, $firstName, $lastName, $googleIdentifier): bool
    {
        $newDb = new PDO_DB();
        if ($newDb->insert('user',
            array(
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'google_identifier' => $googleIdentifier,
                'is_verified' => true
            ))) {
            $user = $this->getUserByEmail($email);
            if ($user)
                $this->sessionFiller($user);
            return true;
        }
        return false;
    }

    public function validateSession()
    {

    }
}
