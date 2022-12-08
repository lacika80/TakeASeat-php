<?php

namespace App\Controllers;

use App\Libs\GoogleConfig;

class AppController extends BaseController
{
    function __construct() {
        $google = new GoogleConfig();
        if (!$google->isValid()){
            header("Location: /login");
            die();
        }
    }
    public function choose(){
        $data = [
            "bootstrap",
            /* "swiper"*/
        ];
        $this->View("choose", $data);
    }
    public function restaurant(){
        $data = [
            /* "bootstrap",
             "swiper"*/
        ];
        $this->View("restaurant", $data);
    }
}