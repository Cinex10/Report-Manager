<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    public function createUser(Request $request)
    {
        $fields = $request->input();

        $validator = Validator::make(
            $fields,
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'roles' => 'required',
                'password' => 'required',
                'service' => 'nullable',
            ]

        );

        // $first_name = DB::table('univ_users')->where('email', $email)->value('first_name');
        // $last_name =  DB::table('univ_users')->where('email', $email)->value('last_name');

        if ($validator->fails()) {
            return response($validator->errors(), 404);
        } else {
            $user = User::create(
                [
                    'first_name' => $fields['first_name'],
                    'last_name' => $fields['last_name'],
                    'email' => $fields['email'],
                    'service' => $fields['service'],
                    'isActive' => true,
                    'password' => bcrypt($fields['password']),
                ]
            );


            $user->assignRole($fields["roles"]);



            $response = [
                'idUser' => $user->id,
            ];

            return response($response, 201);
        }
    }

    public function activeUser(int $id)
    {
        $update = DB::table('users')
            ->where('id', $id)
            ->update(['isActive' => true]);

        return response(['message' => 'user activated succesfully'], 200);
    }

    public function desactiveUser(int $id)
    {
        DB::table('users')
            ->where('id', $id)
            ->update(['isActive' => false]);

        return response(['message' => 'user desactivated succesfully'], 200);
    }
    public function destroy(int $id)
    {
        //Verify that the user havn't any trace in the systéme
    }
}
