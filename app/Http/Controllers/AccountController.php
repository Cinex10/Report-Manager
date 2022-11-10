<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function editAccountInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'adress' => 'nullable',
            'tel' => 'nullable',
        ]);
        $user = auth()->user();
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->email = $request['email'];
        $user->adress = $request['adress'];
        $user->tel = $request['tel'];
        if ($request->hasFile('photo')) {
            $allowedfileExtension = ['jpg', 'png'];
            $file = $request->file('photo');



            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            //dd($check);
            if ($check) {
                $filename = date('YmdHi') . $file->getClientOriginalName();

                $file->move(public_path('public/Image'), $filename);

                $user->photo = $filename;


                /** @var \App\Models\User $user **/
                $user->save();
                return redirect()->back()->with('message', 'Account updated successfully');
            }
        }
        return redirect()->back()->with('error', 'Something went wrong');
    }
}
