<?php

namespace App\Controllers;
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
}