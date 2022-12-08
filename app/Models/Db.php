<?php
namespace App\Models;
use PDO;
use PDOException;

class Db
{
    public function pdo_connect_mysql()
    {
        $strJsonFileContents = json_decode(file_get_contents("app/Libs/App.setting.json"), true);
        $DATABASE_HOST = $strJsonFileContents[$strJsonFileContents["ActiveDb"]]["DATABASE_HOST"];
        $DATABASE_USER = $strJsonFileContents[$strJsonFileContents["ActiveDb"]]["DATABASE_USER"];
        $DATABASE_PASS = $strJsonFileContents[$strJsonFileContents["ActiveDb"]]["DATABASE_PASS"];
        $DATABASE_NAME = $strJsonFileContents[$strJsonFileContents["ActiveDb"]]["DATABASE_NAME"];
        try {
            return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
        } catch (PDOException $exception) {
            // If there is an error with the connection, stop the script and display the error.
            exit('Failed connect to database!');
        }
    }
}
