<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Session;

class AuthenticationController extends Controller
{


    public function login(Request $request)
    {

        // (is_null($request->remember)) ? $data = 'true' : $data = 'false';


        if (Auth::attempt(
            $request->except(['_token'])
        )) {
            return redirect(route('home'));
        } else {

            return 'no-auth';
        }
    }


    public function logout()
    {
        Session::flush();

        Auth::logout();
        return redirect(route('login.view'));
    }

    public function viewLogin()
    {
        return view('authentications.auth-login-basic', ['title' => 'Login']);
    }
    public function viewDashboard()
    {
        return view('dashboard', ['title' => 'Home']);
    }
}
