<?php

use App\Models\Roles;
use Illuminate\Support\Facades\Auth;

class Permission
{

    public static function can($permission)
    {
        if (!Auth::check())
            return false;
        $user = Auth::user();
        if(self::isAdmin()) // admin
            return true;
        $user_permissions = unserialize($user->permissions);
        $roles = new Roles();
        $role = $roles->where('id', $user->role_id)->first();
        $role_permissions = unserialize($role->permissions);

        if ((is_array($user_permissions) && in_array($permission, $user_permissions)) || (is_array($role_permissions) && in_array($permission, $role_permissions)))
            return true;
        else
            return false;

    }


    public static function isAdmin()
    {
        if (!Auth::check())
            return false;
        $user = Auth::user();
        if($user->rank_id == 4) // admin
            return true;

    }
}