<?php

namespace App\Libs;

use App;

session_start();

class Router
{
    public function __construct()
    {
        $this->parse_url();
    }

    public function parse_url()
    {
            if (empty($_GET)) {
                $_GET["Controller"] = "Index";
                $_GET["Action"] = "index";
            }
            $controllerName = "App\Controllers\\" . $_GET["Controller"] . "Controller";
            if (class_exists($controllerName)){
                $controller = new $controllerName;
                $action = $_GET["Action"];
                if(method_exists($controller, $action)){
                    $controller->$action();
                }
                 else{
                     header("HTTP/1.0 404 Not Found");
                     die();
                }
            exit();
            }
            else{
                $indexController = new App\Controllers\IndexController();
                $indexController->specURL();
            }
            exit();
        /* }
                    if ($_GET["Controller"] == "" && $_GET["Action"] == "") {
                        $home = new IndexController();
                        $home->Index();
                    }
                    if ($_GET["Controller"] != "" && strtolower($_GET["Controller"]) != "admin") {
                        $home = new IndexController();
                        if (method_exists($home, $_GET["Controller"])) {
                            $action = $_GET["Controller"];
                            $home->$action();
                        } else $home->blogPost();
                    }
                    if (strtolower($_GET["Controller"]) == "admin") {
                        if (strtolower($_GET["Action"]) == "login" || strtolower($_GET["Action"]) == "registration") {
                            $login = new LoginController();
                            $action = $_GET["Action"];
                            $login->$action();
                        } elseif ($_SESSION["loggedIn"]) {

                            $action;
                            if (strtolower($_GET["Action"]) == "index") {
                                $action = "slider";
                            } else {
                                $action = $_GET["Action"];
                            }
                            $admin = $home = new AdminController();

                            $admin->$action();
                        } else {
                            $login = new LoginController();
                            $login->login();
                        }
                    }
                    if (strtolower($_GET["Controller"]) == "login") {
                        $login = new LoginController();
                        $action = $_GET["Action"];
                        $login->$action();
                    }
                } else {
                    $controllerName = $_GET["Controller"] . "Controller";
                    $controller = new $controllerName;
                    $action = $_GET["Action"];
                    $controller->$action();*/
    }
}