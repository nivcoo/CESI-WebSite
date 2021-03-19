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
        $title = 'Connexion';
        return view('user.login')->with(['title' => $title]);

    }

    public function profile()
    {
        $title = 'Accueil';


        return view('home.home')->with(['title' => $title]);

    }

    public function logout()
    {
        if (!Auth::check())
            return abort(404);


        redirect()->route("home")->send();
        return Auth::logout();

    }
}
