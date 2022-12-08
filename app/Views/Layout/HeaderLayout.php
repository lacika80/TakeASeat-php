<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    <!--    title kiirás-->
    <?php if (isset($data["title"])) { ?>
        <title><?= $data["title"] ?></title>
    <?php } ?>

    <!--    ha igényel bootstrappet-->
    <?php if (in_array("bootstrap", $data)) { ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
              crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
                crossorigin="anonymous"></script>
    <?php } ?>
    <!--    ha igényel ckeditort-->
    <?php if (in_array("ckeditor", $data)) { ?>
        <!--<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>-->
        <script src="/../public/resources/js/ckeditor.js" crossorigin="anonymous"></script>
    <?php } ?>
    <!--    ha igényel swipert-->
    <?php if (in_array("swiper", $data)) { ?>
        <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
        <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <?php } ?>
    <!--    ha igényel jqueryt-->
    <?php if (in_array("jquery", $data)) { ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
                integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"
                integrity="sha256-hlKLmzaRlE8SCJC1Kw8zoUbU8BxA+8kR3gseuKfMjxA=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css "/>
    <?php }
    if (isset($data["meta"])) {
        foreach ($data["meta"] as $meta){
            ?> <meta name="<?php print_r($meta[0]); ?>" content="<?php print_r($meta[1]);?>"><?php
        }}?>





    <?php if (isset($data["styles"])) {
        foreach ($data["styles"] as $style) : ?>
            <link href="<?= "/../app/Views/Application/".$style ?>" rel="stylesheet" crossorigin="anonymous">
        <?php endforeach;
    } ?>
    <!--<link href="/../public/resources/css/style.css" rel="stylesheet" crossorigin="anonymous">-->
    <?php if (!isset($_GET["Controller"]) || $_GET["Controller"] == "browse" || $_GET["Controller"] == "index") {
        echo '<link href="/../public/resources/css/listStyle.css" rel="stylesheet" crossorigin="anonymous">';
    } ?>
    <?php if (isset($data["hscript"])) {
        foreach ($data["hscript"] as $hscript) : ?>
            <script src="<?= $hscript ?>" crossorigin="anonymous"></script>
        <?php endforeach;
    } ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono&display=swap');
    </style>
    <!--<script src="/../public/resources/js/script.js" crossorigin="anonymous"></script> -->
</head>