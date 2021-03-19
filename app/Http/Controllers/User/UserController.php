<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

class UserController extends Controller
{
    public function login()
    {
        if (Auth::check())
            redirect()->route("panel")->send();
        $title = 'Connection';
        return view('user.login')->with(['title' => $title]);

    }

    public function logout()
    {
        if (!Auth::check())
            return abort(404);


        redirect()->route("login")->send();
        return Auth::logout();

    }
}
