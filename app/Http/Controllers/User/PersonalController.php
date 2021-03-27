<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\InternshipOffers;
use App\Models\Notifications;
use App\Models\Wishlists;
use App\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Permission;

class PersonalController extends Controller
{

    public function panel_personal_notifications(Request $request)
    {
        $title = 'Notifications';
        $can = self::has_permission();
        if (!$can['notifications_show'])
            return abort("403");

        $connected_user = Auth::user();
        $notifications_model = new Notifications();
        $get_notifications = $notifications_model->join('applications', 'applications.id', '=', 'notifications.application_id')->join('internship_offers', 'internship_offers.id', '=', 'applications.internship_offer_id')->join('societies', 'societies.id', '=', 'internship_offers.society_id')->select(["notifications.*", "societies.name"])->where("notifications.user_id", $connected_user->id)->where("notifications.seen", false)->orderBy('created_at', 'DESC')->get();

        if ($request->ajax()) {
            return Datatables::of($get_notifications)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($can) {
                    $btn = '';
                    $btn .= '<a href="' . route("panel_personal_notifications_see", [$row['id']]) . '" class="btn btn-warning btn-sm">Seen</a> ';
                    if ($can['applications_show'])
                        $btn .= '<a href="' . route("panel_applications_show", [$row['application_id']]) . '" class="btn btn-info btn-sm">Show Application</a> ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('panel.personal.notifications_panel')->with(compact('title', 'can'));
    }

    public function panel_personal_notifications_see($id)
    {

        $notifications_model = new Notifications();
        $get_notification = $notifications_model->where('id', $id)->first();
        if (!$get_notification)
            return abort("404");
        $get_notification->update(['seen' => 1]);

        return redirect(route("panel_personal_notifications"))->with('message', 'The notification has been seen !');

    }

    public function panel_personal_wishlist(Request $request)
    {

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
                    $applications_model = new Applications();
                    $application = $applications_model->where(['user_id' => Auth::user()->id, 'internship_offer_id' => $row['id']])->first();
                    if ($can["applications_participate"] && !$application)
                        $btn = '<a href="' . route("panel_applications_participate", [$row['id']]) . '" class="btn btn-warning btn-sm">Participate</a> ';
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

        $can["notifications_show"] = Permission::can("PERSONAL_SHOW_NOTIFICATIONS");
        $can["applications_show"] = Permission::can("APPLICATIONS_SHOW");
        $can["applications_participate"] = Permission::can("APPLICATIONS_PARTICIPATE");

        return $can;

    }
}
