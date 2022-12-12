<?php

namespace App\Controllers;
use App\Models\Account;

class IndexController extends BaseController{

    public function index(){
        header("Location: /login");
        exit();
        $data = [
           /* "bootstrap",
            "swiper",
            "srimages" => $image->GetActives(),
            "blogs" => $content->Get3Blog(),
            "introduction" => $content->GetIntroduction($strJsonFileContents["introductionId"]),*/
        ];
        $this->View("index", $data);
    }
    public function blogPost(){
        return "index blogpost";
    }

    public function specURL()
    {

        $account = new Account();
        $code = $account->specURL($_GET["Controller"]);
        switch ($code){
            case 3:
                header("Location: /app/choose?StatusCode=9");
                break;
            case 1:
            header("Location: /login?StatusCode=13");
            break;
        }
        if ($code>3)
            switch ($code){
                case 12:
                    $linkbuild = $_SERVER["HTTP_REFERER"]."&StatusCode=".$code;
                    header("Location: ".$linkbuild);
                    break;
                default:
                    header("Location: /login?StatusCode=".$code);
            }

        die();
    }
}