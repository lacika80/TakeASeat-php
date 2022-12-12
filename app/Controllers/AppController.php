<?php

namespace App\Controllers;

use App\Libs\GoogleConfig;
use App\Libs\Permissions;
use App\Models\Account;
use Google_Service_Oauth2;

class AppController extends BaseController
{

    function __construct() {
        if(!$this->loginCheck())
        {$loginCtrl = new LoginController();
            $loginCtrl->logout();}
    }
    public function choose(){
       /* $_SESSION["permissions"]=4;
        if ($this->checkPermission(Permissions::DeleteReservation))
            print_r("delete");
        else print_r("bukta");*/
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