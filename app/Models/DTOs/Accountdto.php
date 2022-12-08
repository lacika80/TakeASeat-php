<?php
namespace App\Models\DTOs;

class Accountdto
{
    public $id, $first_name, $last_name, $email, $is_verificated, $google_identifier, $is_active, $registration_date, $birth_date;
    public function __construct($account=NULL)
    {
        if (!is_null($account)){
            foreach ($account as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}