<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Permission;

class UserController extends Controller
{
    public function login()
    {
        if (Auth::check())
            redirect()->route("panel")->send();
        $title = 'Connection';
        return view('user.login')->with(compact('title'));

    }

    public function logout()
    {
        if (!Auth::check())
            return abort(404);

        redirect()->route("login")->send();
        return Auth::logout();

    }

    public function panel_users(Request $request, $type)
    {
        $user_model = new User();
        $get_users = [];
        switch ($type) {
            case "student":
                $title = "Students";
                $get_users = $user_model->where("role_id", 1);
                break;
            case "delegate":
                $title = "Delegates";
                $get_users = $user_model->where("role_id", 2);
                break;
            case "pilote":
                $title = "Pilotes";
                $get_users = $user_model->where("role_id", 3);
                break;
            case "admin":
                $title = "Admins";
                $get_users = $user_model->where("role_id", 4);
                break;
            default:
                return abort("404");
        }
        $can = self::has_permission($type);
        if (!$can['show'])
            return abort("403");
        $get_users = $get_users->get();
        if ($request->ajax()) {
            return Datatables::of($get_users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($can, $type) {
                    $btn = '';
                    if ($can["edit"])
                        $btn = '<a href="' . route("panel_users_edit", [$type, $row['id']]) . '" class="btn btn-success btn-sm">Edit</a> ';
                    if ($can["delete"])
                        $btn .= '<a onClick="confirmDel(\'' . route("panel_users_delete", [$type, $row['id']]) . '\')" class="btn btn-danger btn-sm">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('panel.users_panel')->with(compact('title', 'get_users', 'can', 'type'));
    }


    public function panel_users_add(Request $request, $type = "student")
    {
        $can = self::has_permission($type);
        if (!$can['add'])
            return abort("403");
        if ($request->ajax()) {

        }

    }


    public function panel_users_edit(Request $request, $type, $id)
    {
        $can = self::has_permission($type);
        if (!$can['edit'])
            return abort("403");

        $user_model = new User();
        $user = $user_model->where('id', $id)->first();

        if ($request->ajax()) {

        }

    }

    public function panel_users_delete(Request $request, $type, $id)
    {
        $can = self::has_permission($type);
        if (!$can['delete'])
            return abort("403");
        $user_model = new User();
        $user_model->where('id', $id)->delete();
        return redirect(route("panel_users", [$type]))->with('message', 'User deleted with Success !');;
    }


    private function has_permission($type)
    {

        $can["show"] = Permission::can("USERS_SHOW_" . strtoupper($type));
        $can["add"] = Permission::can("USERS_ADD_" . strtoupper($type));
        $can["edit"] = Permission::can("USERS_EDIT_" . strtoupper($type));
        $can["delete"] = Permission::can("USERS_DELETE_" . strtoupper($type));

        return $can;

    }
}
