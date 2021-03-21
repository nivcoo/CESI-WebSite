<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\PermissionsRoles;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Permission;

class PanelController extends Controller
{
    public function index()
    {

        if (!Permission::can("ACCESS_PANEL"))
            redirect()->route("login")->send();

        $title = 'Panel';
        return view('panel.index')->with(compact("title"));

    }


    public function panel_permissions(Request $request)
    {

        if (!Permission::can("ACCESS_PERMISSIONS"))
            return abort("404");


        $roles_model = new Roles();
        $permissions_model = new Permissions();
        $permissions_roles_model = new PermissionsRoles();
        $roles = $roles_model->get();
        $permissions = $permissions_model->get();
        $permissions_roles = $permissions_roles_model->get();
        if (!$request->ajax()) {
            $title = 'Permissions';
            $roles_has_permissions = [];
            foreach ($permissions_roles as $permission_role) {
                foreach ($roles as $role) {
                    if ($permission_role['role_id'] == $role["id"])
                        $roles_has_permissions[$role["id"]][$permission_role['permission_id']] = true;

                }
            }

            return view('panel.permissions')->with(compact("title", "roles", "permissions", "roles_has_permissions"));
        } else {
            $roles = [];
            foreach ($request->all() as $permission => $checked) {
                list($permission, $rank) = explode('-', $permission);
                $roles[$rank][$permission] = $checked;
            }
            $connected_user = Auth::user();
            foreach ($roles as $roleID => $permissions) {
                if($connected_user->id > $roleID)
                    continue;
                foreach ($permissions as $permissionID => $checked) {
                    if ($checked) {
                        PermissionsRoles::firstOrCreate(['role_id' => $roleID, 'permission_id' => $permissionID]);
                    } else {
                        $permissions_roles_model->where(['role_id' => $roleID, 'permission_id' => $permissionID])->delete();
                    }

                }
            }
            return response()->json(array(
                'success' => true,
                'data' => ["Permission Edited !"]
            ));

        }

    }
}
