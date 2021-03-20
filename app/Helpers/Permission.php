<?php

use App\Models\Permissions;
use App\Models\PermissionsRoles;
use App\Models\PermissionsUsers;
use App\Models\Roles;
use Illuminate\Support\Facades\Auth;

class Permission
{

    public static function can($permission)
    {
        if (!Auth::check())
            return false;
        $user = Auth::user();

        if (self::isAdmin()) // admin
            return true;
        $permissions = new Permissions();
        $get_permission = $permissions->where('permission', $permission)->first();

        if (!$get_permission)
            return false;

        $permissions_roles = new PermissionsRoles();
        $get_permission_role = $permissions_roles->where(
            [
                'permission_id' => $get_permission->id,
                'role_id' => $user->role_id
            ]
        )->first();
        if ($get_permission_role) {
            return true;
        }
        $permissions_users = new PermissionsUsers();
        $get_permission_user = $permissions_users->where(
            [
                'permission_id' => $get_permission->id,
                'user_id' => $user->id
            ]
        )->first();
        if ($get_permission_user) {
            return true;
        }
        return false;

    }


    public static function isAdmin()
    {
        if (!Auth::check())
            return false;
        $user = Auth::user();
        if ($user->role_id == 4) // admin
            return true;

    }
}