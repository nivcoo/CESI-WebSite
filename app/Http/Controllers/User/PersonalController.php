<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InternshipOffers;
use App\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Permission;
use App\Models\Wishlists;

class PersonalController extends Controller
{

    public function panel_personal_wishlist(Request $request) {

        $title = 'Wishlist';
        $can = self::has_permission();
        if (!$can['wishlist_show'])
            return abort("403");
        $wishlists_model = new Wishlists();
        $get_wishlists = $wishlists_model->join('users', 'users.id', '=', 'wishlists.user_id')->join('internship_offers', 'internship_offers.id', '=', 'wishlists.internship_offer_id')->join('societies', 'societies.id', '=', 'internship_offers.society_id')->where("archived", false)->where("end_date", ">", Carbon::now())->get();

        if ($request->ajax()) {
            return Datatables::of($get_wishlists)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($can) {
                    $btn = '';
                    if ($can["wishlist_delete"])
                        $btn .= '<a onClick="confirmDel(\'' . route("panel_personal_wishlist_delete", [$row['user_id'], $row['internship_offer_id']]) . '\')" class="btn btn-danger btn-sm">Delete</button>';
                    return $btn;
                })

                ->addColumn('content', function ($row) {
                    return '<button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#content-' . $row["internship_offer_id"] . '">
                                Show content
                            </button>
                        
                        <div class="modal fade" id="content-' . $row["internship_offer_id"] . '" tabindex="-1" role="dialog" aria-hidden="true">
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

        return view('panel.personal.wishlists_panel')->with(compact('title', 'get_wishlists', 'can'));

    }


    public function panel_personal_wishlist_add($id)
    {
        $can = self::has_permission();
        if (!$can['wishlist_add'])
            return abort("403");

        $internship_offers_model = new InternshipOffers();
        $internship_offer = $internship_offers_model->where("id", $id)->first();
        if (!$internship_offer)
            return redirect(route("panel_offers"));
        $connected_user = Auth::user();
        Wishlists::firstOrCreate(['user_id' => $connected_user->id, 'internship_offer_id' => $id]);
        return redirect(route("panel_offers"))->with('message', 'Offer successfully added to your wishlist!');


    }


    public function panel_personal_wishlist_delete($user_id, $internship_offer_id)
    {
        $can = self::has_permission();
        if (!$can['wishlist_delete'])
            return abort("403");
        $wishlists_model = new Wishlists();
        $wishlists_model->where('user_id', $user_id)->where('internship_offer_id', $internship_offer_id)->delete();
        return redirect(route("panel_personal_wishlist"))->with('message', "The wishlist item has been deleted successfully!");
    }

    private function has_permission()
    {
        $can["wishlist_show"] = Permission::can("PERSONAL_SHOW_WISHLIST");
        $can["wishlist_add"] = Permission::can("PERSONAL_ADD_WISHLIST");
        $can["wishlist_delete"] = Permission::can("PERSONAL_DELETE_WISHLIST");

        return $can;

    }
}
