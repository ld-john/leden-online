<?php

namespace App\Helpers;

use App\User;
use Auth;
use DB;

class Helper
{
    public static function roleCheck($user_id)
    {
        $user_role = DB::table('users')
            ->select('role')
            ->where('id', $user_id)
            ->first();

        return $user_role;
    }

    public static function getCompanyName($company_id)
    {
        if (!is_null($company_id)) {
            $company_name = DB::table('company')
                ->select('company_name')
                ->where('id', $company_id)
                ->first();

            return $company_name->company_name;
        }
    }
}
