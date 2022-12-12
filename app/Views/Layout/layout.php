<!DOCTYPE html>
<html lang="hu">

<?php use App\Libs\GoogleConfig;

require_once('HeaderLayout.php');?>

<body>
<?php

require_once('DevsVariables.php');
$google = new GoogleConfig();
if (isset($_SESSION["loggedIn"])){
    require_once('Nav.php');
}
if (isset($view))
require_once('app/Views/Application/' . $view . '.php'); ?>
</body>

</html>

<style>
    body,h1,h2,h3,h4,h5,h6,p,a,span,label,input,li,ul,ol,div, button, label{
        font-family: 'JetBrains Mono', monospace;
        color: #333333;
        font-size: 16px;
        /*color: white;*/
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
<?php if ( isset( $_GET["StatusCode"] ) ) { ?>
    <script>
        function delay(time) {
            return new Promise(resolve => setTimeout(resolve, time));
        }

        delay(500).then(() => alert('<?php switch ($_GET["StatusCode"]){
            case 0:
                echo "ok";
                break;
            case 1:
                echo "Sikertelen bejelentkezés";
                break;
            case 2:
                echo "Kötelező mező hiányzik";
                break;
            case 3:
                echo "Jelszavak nem egyeznek";
                break;
            case 4:
                echo "Ezzel az e-maillel már regisztáltak";
                break;
            case 5:
                echo "Ismeretlen hiba lépett fel";
                break;
            case 6:
                echo "Sikeres regisztrálás, kérem érvényesítse e-mail címét";
                break;
            case 7:
                echo "A link már nem érvényes";
                break;
            case 8:
                echo "Érvénytelen link";
                break;
            case 9:
                echo "Sikeres aktiválta e-mail címét";
                break;
            case 10:
                echo "Kérése sikeresen végrehajtva";
                break;
            case 11:
                echo "Kérését nem sikerült végrehajtani";
                break;
            case 12:
                echo "A jelszavak nem eggyeznek";
                break;
            case 13:
                echo "a jelszó változtatás sikeresen végrehajtva, mostmár bejelentkezhet.";
                break;
        }?>'));

    </script>
<?php } ?>