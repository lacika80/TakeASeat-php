<?php
namespace App\Libs;
enum Permissions: int{
    case PickupReservation  = 1;
    case DeleteReservation  = 2;
}