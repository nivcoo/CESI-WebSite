<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\InternshipOffers;
use App\Models\Permissions;
use App\Models\PermissionsRoles;
use App\Models\Roles;
use App\Models\Societies;
use App\User;
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
        $can = self::has_permission();
        if (!$can['access'])
            redirect()->route("login")->send();
        $title = 'Panel';
        $statistics = [];
        $users_model = new User();
        $societies_model = new Societies();
        $internship_offers_model = new InternshipOffers();
        $statistics['students_number'] = $users_model->where('role_id', 1)->count();
        $statistics['societies_number'] = $societies_model->count();
        $statistics['internship_offers_number'] = $internship_offers_model->count();
        return view('panel.index')->with(compact('title', 'can', 'statistics'));

    }

    private function has_permission()
    {
        $can["access"] = Permission::can("ACCESS_PANEL");
        $can["statistics_students"] = Permission::can("SOCIETIES_ADD_SOCIETIES");
        $can["statistics_societies"] = Permission::can("STATISTIC_SOCIETIES");
        $can["statistics_internship_offers"] = Permission::can("STATISTIC_INTERNSHIP_OFFERS");
        return $can;

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
                if ($connected_user->role_id < $roleID)
                    continue;
                foreach ($permissions as $permissionID => $checked) {
                    if ($checked) {
                        PermissionsRoles::firstOrCreate(['role_id' => $roleID, 'permission_id' => $permissionID]);
                    } else {
                        $permissions_roles_model->where(['role_id' => $roleID, 'permission_id' => $permissionID])->delete();
                    }

                }
            }
            return response()->json([
                'success' => true,
                'data' => ["Permission Edited !"]
            ]);

        }

    }
}
