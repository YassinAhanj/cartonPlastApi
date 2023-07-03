<?php

namespace App\Login\class;

use App\Builder\QueryBuilder;

class GetRole
{
    public static function getUserRole($phone)
    {
        $query = new  QueryBuilder('contacts');
        $role = $query->select('role')->where('phone', '=', "$phone")->get();
        return $role;
    }
}
