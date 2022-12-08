<!DOCTYPE html>
<html lang="hu">

<?php use App\Libs\GoogleConfig;

require_once('HeaderLayout.php');?>

<body>
<?php

require_once('DevsVariables.php');
$google = new GoogleConfig();
if ($google->isValid()){
    require_once('Nav.php');
}
require_once('app/Views/Application/' . $view . '.php'); ?>
</body>

</html>

<style>
    body,h1,h2,h3,h4,h5,h6,p,a,span,label,input,li,ul,ol,div, button, label{
        font-family: 'JetBrains Mono', monospace;
        color: #333333;
        font-size: 16px;
        color: white;
    }

    .vertical-center {
        margin: 0;
        position: absolute;
        top: 50%;
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }
    .center {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }
    .horizontal-center {
        margin: 0;
        position: absolute;
        left: 50%;
        -ms-transform: translateX(-50%);
        transform: translateX(-50%);
    }
</style>