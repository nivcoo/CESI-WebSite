<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\InternshipOffers;
use App\Models\Societies;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Permission;

class OfferController extends Controller
{
    public function panel_offers(Request $request)
    {


        $title = 'Offers';
        $can = self::has_permission();
        if (!$can['show'])
            return abort("403");
        $offer_model = new InternshipOffers();
        $get_offers = $offer_model->join('societies', 'societies.id', '=', 'internship_offers.societies_id')->where("archived", false)->where("end_date", ">", Carbon::now())->get();

        if ($request->ajax()) {
            return Datatables::of($get_offers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($can) {
                    $btn = '';
                    if ($can["edit"])
                        $btn = '<a href="' . route("panel_offers_edit", [$row['id']]) . '" class="btn btn-success btn-sm">Edit</a> ';
                    if ($can["delete"])
                        $btn .= '<a onClick="confirmDel(\'' . route("panel_offers_delete", [$row['id']]) . '\')" class="btn btn-danger btn-sm">Delete</button>';
                    return $btn;
                })
                ->addColumn('content', function ($row) {
                    return '<button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#exampleModal">
                                Show content
                            </button>
                        
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Society ' . $row["name"] . '</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                ' . $row["content"] . '
                              </div>
                            </div>
                          </div>
                        </div>';
                })
                ->rawColumns(['action', 'content'])
                ->make(true);
        }

        return view('panel.offers.offers_panel')->with(compact('title', 'get_offers', 'can'));

    }

    public function panel_societies_add(Request $request)
    {
        $can = self::has_permission();
        if (!$can['add'])
            return abort("403");
        if (!$request->ajax()) {
            $title = "Add a Society";
            return view('panel.societies.societies_add_form')->with(compact('title'));
        } else {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2|max:255|unique:societies',
                'activity_field' => 'required|min:2|max:255',
                'address' => 'required|min:2|max:255',
                'postal_code' => 'required|integer|max:99999',
                'city' => 'required|max:255',
                'cesi_interns' => 'required|integer',
                'evaluation' => 'required|integer|max:20',
            ]);

            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'data' => $validator->errors()->all()
                ]);


            $name = htmlspecialchars($request->input('name'));
            $activity_field = htmlspecialchars($request->input('activity_field'));
            $address = htmlspecialchars($request->input('address'));
            $postal_code = htmlspecialchars($request->input('postal_code'));
            $city = htmlspecialchars($request->input('city'));
            $cesi_interns = htmlspecialchars($request->input('cesi_interns'));
            $evaluation = htmlspecialchars($request->input('evaluation'));

            (new Societies())->insert(['name' => $name, 'activity_field' => $activity_field, 'address' => $address, 'postal_code' => $postal_code, 'city' => $city, 'cesi_interns' => $cesi_interns, 'evaluation' => $evaluation, 'created_at' => Carbon::now()]);

            return response()->json([
                'success' => true,
                'data' => ["Society added !"]

            ]);

        }

    }

    public function panel_societies_edit(Request $request, $id)
    {
        $can = self::has_permission();
        if (!$can['edit'])
            return abort("403");

        $societies_model = new Societies();
        $society = $societies_model->where('id', $id)->first();
        if (!$society)
            return redirect(route("panel_societies"));
        if (!$request->ajax()) {
            $title = "Edit the Society " . $society->name;
            return view('panel.societies.societies_edit_form')->with(compact('title', 'society', 'id'));

        } else {
            $validator = Validator::make($request->all(), [
                'activity_field' => 'required|min:2|max:255',
                'address' => 'required|min:2|max:255',
                'postal_code' => 'required|integer|max:99999',
                'city' => 'required|max:255',
                'cesi_interns' => 'required|integer',
                'evaluation' => 'required|integer|max:20',
            ]);

            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'data' => $validator->errors()->all()
                ]);

            $activity_field = htmlspecialchars($request->input('activity_field'));
            $address = htmlspecialchars($request->input('address'));
            $postal_code = htmlspecialchars($request->input('postal_code'));
            $city = htmlspecialchars($request->input('city'));
            $cesi_interns = htmlspecialchars($request->input('cesi_interns'));
            $evaluation = htmlspecialchars($request->input('evaluation'));
            $society->update(['activity_field' => $activity_field, 'address' => $address, 'postal_code' => $postal_code, 'city' => $city, 'cesi_interns' => $cesi_interns, 'evaluation' => $evaluation, 'updated_at' => Carbon::now()]);

            return response()->json([
                'success' => true,
                'data' => ["Society Edited !"]

            ]);

        }

    }

    public function panel_societies_delete(Request $request, $id)
    {
        $can = self::has_permission();
        if (!$can['delete'])
            return abort("403");
        $societies_model = new Societies();
        $societies_model->where('id', $id)->delete();
        return redirect(route("panel_societies"))->with('message', 'Society deleted with Success !');
    }


    private function has_permission()
    {
        $can["show"] = Permission::can("OFFERS_SHOW_SOCIETIES");
        $can["add"] = Permission::can("OFFERS_ADD_SOCIETIES");
        $can["edit"] = Permission::can("OFFERS_EDIT_SOCIETIES");
        $can["delete"] = Permission::can("OFFERS_DELETE_SOCIETIES");
        return $can;

    }
}
