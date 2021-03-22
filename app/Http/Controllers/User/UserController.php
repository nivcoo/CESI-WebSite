<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Centers;
use App\Models\Promotions;
use App\Models\Roles;
use App\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
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
        switch ($type) {
            case "student":
                $title = "Add a Students";
                $role_id = 1;
                break;
            case "delegate":
                $title = "Add a Delegates";
                $role_id = 2;
                break;
            case "pilote":
                $title = "Add a Pilotes";
                $role_id = 3;
                break;
            case "admin":
                $title = "Add an Admins";
                $role_id = 4;
                break;
            default:
                return abort("404");
        }
        $connected_user = Auth::user();
        if (!$request->ajax()) {

            $centers_model = new Centers();
            $promotions_model = new Promotions();
            $roles_model = new Roles();

            $role = $roles_model->where("id", $role_id)->first();

            $centers = $centers_model->get();
            $promotions = $promotions_model->get();

            return view('panel.users_add_form')->with(compact('title', 'type', 'role', 'centers', 'promotions'));
        } else {

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|min:2|max:255',
                'last_name' => 'required|min:2|max:255',
                'email' => 'required|email:rfc,dns|min:6|max:255|unique:users',
                'birth_date' => 'required|date|date_format:Y-m-d',
                'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[#?!@$%^&*-_.]).*$/',
            ], [
                    'regex' => "Passwords must contain the following categories: upper case, lower case, numbers and symbols (#?!@$%^&*-_.).",
                ]
            );

            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'data' => $validator->errors()->all()
                ]);


            $first_name = htmlspecialchars($request->input('first_name'));
            $last_name = htmlspecialchars($request->input('last_name'));
            $email = htmlspecialchars($request->input('email'));
            $password = htmlspecialchars($request->input('password'));
            $birth_date = htmlspecialchars($request->input('birth_date'));
            $center_id = htmlspecialchars($request->input('center_id'));
            $promotion_id = htmlspecialchars($request->input('promotion_id'));

            $password = Hash::make($password);

            (new User())->insert(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'password' => $password, 'birth_date' => $birth_date, 'role_id' => $role_id, 'center_id' => $center_id, 'promotion_id' => $promotion_id, 'created_at' => Carbon::now()]);


            return response()->json(array(
                'success' => true,
                'data' => ["User added !"]

            ));

        }

    }


    public function panel_users_edit(Request $request, $type, $id)
    {
        $can = self::has_permission($type);
        if (!$can['edit'])
            return abort("403");

        $user_model = new User();
        $user = $user_model->where('id', $id)->first();

        if (!$request->ajax()) {

        } else {

        }

    }

    public function panel_users_delete(Request $request, $type, $id)
    {
        $can = self::has_permission($type);
        if (!$can['delete'])
            return abort("403");
        $user_model = new User();
        if ($id == Auth::user()->id)
            return redirect(route("panel_users", [$type]));
        $user_model->where('id', $id)->delete();
        return redirect(route("panel_users", [$type]))->with('message', 'User deleted with Success !');
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
