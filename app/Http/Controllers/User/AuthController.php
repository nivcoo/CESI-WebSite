<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Response;
use App\User;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Permission;


class AuthController extends Controller
{



    public function login(Request $request)
    {
        if (!$request->isMethod('post'))
            return abort(404);

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255|email:rfc,dns',
            'password' => 'required|max:255',
        ], [
                'required' => "You must fill :attribute",
                'max' => "The :attribute must contain less than :max caracter",
            ]
        );

        if ($validator->fails())
            return response()->json(array(
                'success' => false,
                'data' => $validator->errors()->all()
            ));

        $email = htmlspecialchars($request->input('email'));
        $password = htmlspecialchars($request->input('password'));
        $remember_me = htmlspecialchars($request->input('remember_me'));

        $user = new User();
        $data = $user->where('email', $email)->first();
        if (!$data)
            return response()->json(array(
                'success' => false,
                'data' => ["Email or password incorrect"]

            ));



        if (Hash::check($password, $data['password'])) {
            if(Permission::can("LOGIN", $data)) {
                Auth::login($data, $remember_me);
                return response()->json([
                    'success' => true,
                    'data' => ["Connection successful !"]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'data' => ["You do not have permission to log in with this account !"]
                ]);
            }
        } else

            return response()->json(array(
                'success' => false,
                'data' => ["Email or password incorrect"]

            ));


    }

    public function isValidReCaptcha($code, $ip = null, $secret)
    {
        if (empty($code)) {
            return false;
        }
        $params = [
            'secret' => $secret,
            'response' => $code
        ];
        if ($ip) {
            $params['remoteip'] = $ip;
        }
        $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query($params);
        if (function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
        } else {
            $response = file_get_contents($url);
        }

        if (empty($response) || !isset($response)) {
            return false;
        }

        $json = json_decode($response);
        return $json->success;
    }
}
