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
            Auth::login($data, $remember_me);
            return response()->json(array(
                'success' => true,
                'data' => ["Connection successful ! Redirection..."]
            ));
        } else

            return response()->json(array(
                'success' => false,
                'data' => ["Email or password incorrect"]

            ));


    }

    public function register(Request $request)
    {
        if (!$request->isMethod('post'))
            return abort(404);


        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email|min:6|max:255|unique:users',
            'username' => 'required|min:4|max:255|unique:users',
            'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[#?!@$%^&*-_.]).*$/|same:password_confirm',
            'password_confirm' => 'required'
        ], [
                'required' => "Veuillez remplir votre :attribute",
                'email' => "Votre :attribute n'est pas dans le bon format",
                'regex' => "Les mots de passe doivent contenir les catégories suivantes : majuscules, minuscules, chiffres et symboles (#?!@$%^&*-_.).",
                'same' => "Votre :attribute n'est pas identique",
                'min' => "Votre :attribute doit contenir au minimum :min caractères",
                'max' => "Votre :attribute doit contenir au maximum :max caractères",
                'unique' => "Un compte avec votre :attribute existe déjà",
            ]
        );

        if ($validator->fails())
            return response()->json(array(
                'success' => false,
                'data' => $validator->errors()->all()
            ));
        $recaptcha = htmlspecialchars($request->input('g-recaptcha-response'));
        $ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? htmlentities($_SERVER["HTTP_CF_CONNECTING_IP"]) : htmlentities($_SERVER["REMOTE_ADDR"]);
        if(!$this->isValidReCaptcha($recaptcha, $ip, "6LctZLoUAAAAAB8zy8GDlF-rBraXYlCM4FDXoLHk"))
            return response()->json(array(
                'success' => false,
                'data' => ["Captcha invalide !"]
            ));
        $first_name = htmlspecialchars($request->input('first_name'));
        $last_name = htmlspecialchars($request->input('last_name'));
        $email = htmlspecialchars($request->input('email'));
        $username = htmlspecialchars($request->input('username'));
        $password = htmlspecialchars($request->input('password'));

        $password = Hash::make($password);

        (new User())->insert(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'username' => $username, 'password' => $password, 'created_at' => Carbon::now()]);

        $data = (new User())->where('username', $username)->first();

        Auth::login($data);

        return response()->json(array(
            'success' => true,
            'data' => ["Inscription reussie ! Redirection..."]

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
