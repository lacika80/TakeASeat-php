Étterem választó, új étterem létrehozása, saját adatok megjelenítése, menüben felhasználói beállítások, global admin beállítások
<a class="btn btn-primary" href="/App/restaurant" role="button">egy étterem</a>
<?php

if (\App\Libs\PermissionControl::checkGlobalPermission(\App\Libs\GlobalPermissions::ListUsers))
    print_r("jogosult");
else print_r("nem megy");?>