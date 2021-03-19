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
        $title = 'Panel';
        return view('panel.index')->with(['title' => $title]);

    }
}
