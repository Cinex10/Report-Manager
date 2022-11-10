<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $email = $request->input('email');
        //Cache::forget($email);
        //verifie that the email of the request existed in cache
        $first_name = DB::table('univ_users')->where('email', $email)->value('first_name');
        $last_name =  DB::table('univ_users')->where('email', $email)->value('last_name');
        $fields = $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
                'adress' => 'nullable',
                'tel' => 'nullable',
            ]
        );

        $user = User::create(
            [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $fields['email'],
                'adress' => $fields['adress'],
                'tel' => $fields['tel'],
                'isActive' => true,
                'password' => bcrypt($fields['password']),
            ]
        );
        $user->assignRole('user');

        $token = $user->createToken('MyAppTest')->plainTextToken;


        $response = [
            'user' => User::where('id', $user->id)->with('roles', 'roles.permissions')->get()[0],
            'token' => $token,
        ];

        return response($response, 201);
    }


    public function login(Request $request)
    {
        $fields = $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
            ]

        );

        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Bad creds',], 401);
        }

        $token = $user->createToken('MyAppTest')->plainTextToken;

        // $user->getRole
        $response = [
            'user' => User::where('id', $user['id'])->with('roles', 'roles.permissions')->get()[0],
            'token' => $token,
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function sendCode(Request $request)
    {
        $email = $request->all()['email'];
        if (DB::table('univ_users')->where('email', $email)->exists()) {
            if (User::where('email', $email)->doesntExist()) {
                //send email with verification code
                $code = generatePIN();
                Mail::to($email)->send(new VerificationMail($code));
                Cache::put($email, $code);
                return response(['message' => 'code sent'], 200);
            } else {
                return response(['message' => 'this email already exists'], 404);
            }
        }
        return response(['message' => 'this email does not exist'], 403);
    }

    public function verify(Request $request)
    {
        $email = $request->all()['email'];
        $code = $request->all()['code'];
        if (Cache::has($email)) {
            if ($code == Cache::get($email)) {
                Cache::forget($email);
                Cache::put($email, 'verified', 600);
                $first_name = DB::table('univ_users')->where('email', $email)->value('first_name');
                $last_name =  DB::table('univ_users')->where('email', $email)->value('last_name');
                return response(['message' => 'success', 'first_name' => $first_name, 'last_name' => $last_name], 200);
            }
        }

        return response(['message' => 'fail'], 404);
    }

    public function getUsers()
    {
        $data = User::all();
        return $data;
    }

    public function getResponsables()
    {
        $data = DB::select('select * from users where role != \'simple\' ', [1]);
        return $data;
    }

    public function checkislogged(Request $request)
    {
        return response(['status' => auth('sanctum')->check(), 'user' => User::where('id', auth('sanctum')->id())->with('roles', 'roles.permissions')->get()[0]], 200);
    }
}

function generatePIN($digits = 4)
{
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while ($i < $digits) {
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return strval($pin);
}
