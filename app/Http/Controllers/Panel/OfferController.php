<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\InternshipOffers;
use App\Models\Societies;
use App\Models\Applications;
use App\Models\Wishlists;
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
        $get_offers = $offer_model->join('societies', 'societies.id', '=', 'internship_offers.society_id')->select('internship_offers.*', 'societies.name')->where("archived", false)->where("end_date", ">", Carbon::now())->get();

        if ($request->ajax()) {
            return Datatables::of($get_offers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($can) {
                    $btn = '';
                    $wishlists_model = new Wishlists();
                    $wishlist = $wishlists_model->where(['user_id' => Auth::user()->id, 'internship_offer_id' => $row['id']])->first();
                    $applications_model = new Applications();
                    $application = $applications_model->where(['user_id' => Auth::user()->id, 'internship_offer_id' => $row['id']])->first();
                    if ($can["participate"] && !$application)
                        $btn = '<a href="' . route("panel_applications_participate", [$row['id']]) . '" class="btn btn-warning btn-sm">Participate</a> ';
                    if ($can["wishlist_add"] && !$wishlist)
                        $btn .= '<a href="' . route("panel_personal_wishlist_add", [$row['id']]) . '" class="btn btn-info btn-sm">Add Wishlist</a> ';
                    if ($can["edit"])
                        $btn .= '<a href="' . route("panel_offers_edit", [$row['id']]) . '" class="btn btn-success btn-sm">Edit</a> ';
                    if ($can["delete"])
                        $btn .= '<a onClick="confirmDel(\'' . route("panel_offers_delete", [$row['id']]) . '\')" class="btn btn-danger btn-sm">Delete</button>';
                    return $btn;
                })

                ->addColumn('content', function ($row) {
                    return '<button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#content-' . $row["id"] . '">
                                Show content
                            </button>
                        
                        <div class="modal fade" id="content-' . $row["id"] . '" tabindex="-1" role="dialog" aria-hidden="true">
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

    public function panel_offers_add(Request $request)
    {
        $can = self::has_permission();
        if (!$can['add'])
            return abort("403");
        if (!$request->ajax()) {
            $title = "Add an Offer";
            $societies_model = new Societies();
            $get_societies = $societies_model->get();
            return view('panel.offers.offers_add_form')->with(compact('title', 'get_societies'));
        } else {

            $validator = Validator::make($request->all(), [
                'content' => 'required',
                'offer_start' => 'required|date|date_format:Y-m-d',
                'offer_end' => 'required|date|date_format:Y-m-d',
                'end_date' => 'required|date|date_format:Y-m-d'
            ]);

            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'data' => $validator->errors()->all()
                ]);


            $society_id = htmlspecialchars($request->input('society_id'));
            $content = $request->input('content');
            $offer_start = htmlspecialchars($request->input('offer_start'));
            $offer_end = htmlspecialchars($request->input('offer_end'));
            $end_date = htmlspecialchars($request->input('end_date'));

            (new InternshipOffers())->insert(['content' => $content, 'society_id' => $society_id, 'offer_start' => $offer_start, 'offer_end' => $offer_end, 'end_date' => $end_date, 'archived' => false, 'created_at' => Carbon::now()]);

            return response()->json([
                'success' => true,
                'data' => ["Offer added !"]

            ]);

        }

    }

    public function panel_offers_edit(Request $request, $id)
    {
        $can = self::has_permission();
        if (!$can['edit'])
            return abort("403");

        $internship_offers_model = new InternshipOffers();
        $internship_offer = $internship_offers_model->where('id', $id)->first();
        if (!$internship_offer)
            return redirect(route("panel_societies"));
        if (!$request->ajax()) {
            $title = "Edit the Offer " . $internship_offer->name;
            $societies_model = new Societies();
            $get_societies = $societies_model->get();
            return view('panel.offers.offers_edit_form')->with(compact('title', 'get_societies', 'internship_offer', 'id'));

        } else {
            $validator = Validator::make($request->all(), [
                'content' => 'required',
                'offer_start' => 'required|date|date_format:Y-m-d',
                'offer_end' => 'required|date|date_format:Y-m-d',
                'end_date' => 'required|date|date_format:Y-m-d'
            ]);

            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'data' => $validator->errors()->all()
                ]);

            $society_id = htmlspecialchars($request->input('society_id'));
            $content = $request->input('content');
            $offer_start = htmlspecialchars($request->input('offer_start'));
            $offer_end = htmlspecialchars($request->input('offer_end'));
            $end_date = htmlspecialchars($request->input('end_date'));
            $internship_offer->update(['content' => $content, 'society_id' => $society_id, 'offer_start' => $offer_start, 'offer_end' => $offer_end, 'end_date' => $end_date, 'archived' => false, 'updated_at' => Carbon::now()]);

            return response()->json([
                'success' => true,
                'data' => ["Offer Edited !"]

            ]);

        }

    }

    public function panel_offers_delete($id)
    {
        $can = self::has_permission();
        if (!$can['delete'])
            return abort("403");
        $intership_offer_model = new InternshipOffers();
        $intership_offer_model->where('id', $id)->delete();
        return redirect(route("panel_offers"))->with('message', 'Offer deleted with Success !');
    }


    private function has_permission()
    {
        $can["participate"] = Permission::can("APPLICATIONS_PARTICIPATE");
        $can["wishlist_add"] = Permission::can("PERSONAL_ADD_WISHLIST");
        $can["show"] = Permission::can("OFFERS_SHOW_SOCIETIES");
        $can["add"] = Permission::can("OFFERS_ADD_SOCIETIES");
        $can["edit"] = Permission::can("OFFERS_EDIT_SOCIETIES");
        $can["delete"] = Permission::can("OFFERS_DELETE_SOCIETIES");
        return $can;

    }
}
