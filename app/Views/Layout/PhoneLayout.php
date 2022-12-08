<!DOCTYPE html>
<html lang=hu>

<?php require_once('HeaderLayout.php');?>

<body>
<?php
require_once('DevsVariables.php');
if (isset($view))
    //mivel telefonos nézet ezért a lekért body-hoz hozzáteszi a P betüt még akkor is ha több mappa mélységben van
    $view = substr($view, 0, strlen($view)-strrpos($view, '/', -1)) . "P" . substr($view, strlen($view)-strrpos($view, '/', -1), strrpos($view, '/', -1));
require_once('app/Views/Application/' . $view . '.php'); ?>
</body>

</html>