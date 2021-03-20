<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

class PanelController extends Controller
{
    public function index()
    {

        if (!\Permission::can("ACCESS_PANEL"))
            redirect()->route("login")->send();

        $title = 'Panel';
        $data = [];
        $data['title'] = $title;
        return view('panel.index')->with($data);

    }
}
