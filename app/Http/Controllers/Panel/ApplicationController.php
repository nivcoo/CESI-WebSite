<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\ApplicationDiscussions;
use App\Models\InternshipOffers;
use App\Models\Wishlists;
use App\Models\Notifications;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Permission;

class ApplicationController extends Controller
{

    public function panel_applications(Request $request)
    {


        $title = 'Applications';
        $can = self::has_permission();
        if (!$can['show'])
            return abort("403");
        $application_model = new Applications();
        $get_applications = $application_model->join('internship_offers', 'internship_offers.id', '=', 'applications.internship_offer_id')->join('societies', 'societies.id', '=', 'internship_offers.society_id')->select('applications.*', 'internship_offers.content', 'societies.name')->where("closed", false)->where("end_date", ">", Carbon::now())->get();

        if ($request->ajax()) {
            return Datatables::of($get_applications)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($can) {
                    $btn = '';
                    if ($can["show"])
                        $btn = '<a href="' . route("panel_applications_show", [$row['id']]) . '" class="btn btn-warning btn-sm">Show</a> ';
                    if ($can["delete"])
                        $btn .= '<a onClick="confirmDel(\'' . route("panel_applications_delete", [$row['id']]) . '\')" class="btn btn-danger btn-sm">Delete</button>';
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

        return view('panel.applications.application_panel')->with(compact('title', 'get_applications', 'can'));


    }


    public function panel_applications_show($id)
    {
        $can = self::has_permission();
        if (!$can['show'])
            return abort("403");
        $application_model = new Applications();
        $get_application = $application_model->join('internship_offers', 'internship_offers.id', '=', 'applications.internship_offer_id')->join('societies', 'societies.id', '=', 'internship_offers.society_id')->select('applications.*', 'internship_offers.content', 'societies.name')->where("applications.id", $id)->first();
        if (!$get_application)
            return abort("403");

        $title = 'Application ' . $get_application->name;


        $application_discussions_model = new ApplicationDiscussions();
        $get_discussions = $application_discussions_model->join('users', 'users.id', '=', 'application_discussions.application_id')->where("application_id", $get_application->id)->select('application_discussions.*', 'users.first_name', 'users.last_name')->get();
        return view('panel.applications.application_show')->with(compact('title', 'get_application', 'can', 'get_discussions', 'id'));


    }

    public function panel_applications_participate(Request $request, $id)
    {

        $can = self::has_permission();
        if (!$can['participate'])
            return abort("403");
        if (!$request->ajax()) {
            $title = "Participate";
            $internship_offers_model = new InternshipOffers();
            $get_internship_offer = $internship_offers_model->where('id', $id)->first();
            return view('panel.applications.application_participate_form')->with(compact('title', 'get_internship_offer', 'id'));
        } else {

            $validator = Validator::make($request->all(), [
                'cv' => 'required|mimes:pdf|max:2048',
                'cover_letter' => 'required|mimes:pdf|max:2048',
            ]);

            if ($validator->fails())
                return response()->json([
                    'success' => false,
                    'data' => $validator->errors()->all()
                ]);

            $cv = $request->file('cv');
            $cover_letter = $request->file('cover_letter');

            $cv_path = self::saveFile($cv, "/cv");
            $cover_letter_path = self::saveFile($cover_letter, "/cv");
            $connected_user = Auth::user();

            (new Applications())->insert(['state' => '1', 'closed' => 0, 'cv_path' => $cv_path, 'cover_letter_path' => $cover_letter_path, 'internship_offer_id' => $id, 'user_id' => $connected_user->id, 'created_at' => Carbon::now()]);

            return response()->json([
                'success' => true,
                'data' => ["Application added !"]

            ]);

        }

    }

    public function panel_applications_delete($id)
    {
        $can = self::has_permission();
        if (!$can['delete'])
            return abort("403");
        $applications_model = new Applications();
        $applications_model->where('id', $id)->delete(); // I don't use update(['closed' => true])
        return redirect(route("panel_applications"))->with('message', 'Application deleted with Success !');
    }

    private function saveFile($file, $extra = "")
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->extension();;
        return $file->storeAs('uploads' . $extra, $fileName, 'public');

    }

    public function panel_applications_change_step(Request $request)
    {
        $can = self::has_permission();
        if (!$can['change_state'])
            return abort("403");

        $applications_model = new Applications();
        $id = $request->input("id");
        $state = $request->input("state");
        $get_application = $applications_model->where('id', $id)->first();
        if (!$get_application)
            return response()->json([
                'success' => false,
                'data' => ["The application cannot be found"]
            ]);

        /**if ($state == $get_application->state)
            return response()->json([
                'success' => true,
                'data' => ["The state is already to " . $state]
            ]);**/
        $message = "";

        switch ($state) {
            case 1:
                $message = "You are now at step 1 on your $get_application->name business application. You can chat with the company !";
                break;
            case 2:
                $message = "You are now at step 2 on your $get_application->name business application. An internship validation form has been issued by the company";
                break;
            case 3:
                $message = "You are now at step 3 on your $get_application->name business application. An internship validation form has been signed by the pilot";
                break;
            case 4:
                $message = "You are now at step 4 on your $get_application->name business application. The internship agreements have been issued to the company that an internship validation form has been signed by the pilot";
                break;
            case 5:
                $message = "You are now at step 5 on your $get_application->name business application. The internship agreements have been returned signed by the company";
                break;
        }

        self::notify("application", $message, $get_application->user_id, $get_application->id);
        $get_application->update(['state' => $state]);
        return response()->json([
            'success' => true,
            'data' => ["The state is changed to " . $state]
        ]);
    }

    private function notify($type, $content, $user_id, $application_id) {

        (new Notifications())->insert(['type' => $type, 'content' => $content, 'user_id' => $user_id, 'application_id' => $application_id, 'created_at' => Carbon::now()]);

    }


    private function has_permission()
    {
        $can["participate"] = Permission::can("APPLICATIONS_PARTICIPATE");
        $can["show"] = Permission::can("APPLICATIONS_SHOW");
        $can["delete"] = Permission::can("APPLICATIONS_DELETE");
        $can["reply"] = Permission::can("APPLICATIONS_REPLY");
        $can["change_state"] = Permission::can("APPLICATIONS_CHANGE_STATE");
        return $can;

    }
}
