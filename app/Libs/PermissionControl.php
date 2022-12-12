<?php

namespace App\Libs;

class PermissionControl
{
    public static function checkPermission(Permissions $permission): bool
    {
        if (isset($_SESSION["permissions"]) && ($_SESSION["permissions"] & $permission->value))
            return true;
        else
            return false;
    }

    public static function checkGlobalPermission(GlobalPermissions $permission): bool
    {
        if (isset($_SESSION["globalPermissions"]) && ($_SESSION["globalPermissions"] & $permission->value))
            return true;
        else
            return false;
    }
}