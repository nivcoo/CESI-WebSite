<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Societies;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Permission;
use DataTables;

class SocietyController extends Controller
{
    public function panel_societies(Request $request)
    {


        $title = 'Societies';
        $can["show"] = Permission::can("SOCIETIES_SHOW_SOCIETIES");
        if (!$can['show'])
            return abort("403");
        $can["add"] = Permission::can("SOCIETIES_ADD_SOCIETIES");
        $can["edit"] = Permission::can("SOCIETIES_EDIT_SOCIETIES");
        $can["delete"] = Permission::can("SOCIETIES_DELETE_SOCIETIES");
        $societies_model = new Societies();
        $get_societies = $societies_model->get();

        if ($request->ajax()) {
            return Datatables::of($get_societies)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($can) {
                    $btn = '';
                    if ($can["edit"])
                        $btn = '<a href="' . route("panel_societies_edit", [$row['id']]) . '" class="btn btn-success btn-sm">Edit</a> ';
                    if ($can["delete"])
                        $btn .= '<a onClick="confirmDel(\'' . route("panel_societies_delete", [$row['id']]) . '\')" class="btn btn-danger btn-sm">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('panel.societies.societies_panel')->with(compact('title', 'get_societies', 'can'));

    }
}
