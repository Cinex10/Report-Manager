<?php

namespace App\Http\Controllers;

use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function getUsersByRole($role)
    {
        if ($role == 'all') return User::with('roles', 'roles.permissions')->get();

        return $data =
            User::role($role)->with('roles', 'roles.permissions')->get();
    }
    public function getUsersById($id)
    {
        return
            response(User::where('id', $id)->with('roles', 'roles.permissions')->get(), 200);
    }

    public function updateProfilePhoto(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'idUser' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {

            //
            if ($request->hasfile('picture_add')) {
                $file = $request->file('picture_add');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('upload/pictures/', $filename);
                $id = $input['idUser'];
                $user = User::find($id);
                $user->photo = $filename;
            }
            $user->save();
            return (new Controller)->sendResponse($user, 'uploaded picture successfully');
        }
    }

    public function updateNotifToken(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'idUser' => 'required',
            'token' => 'required',
            'device' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {
            $user = User::find($input['idUser']);
            if ($input['device'] == 'mobile') {
                $user->notification_token_mobile = $input['token'];
                $user->save();
            } else if ($input['device'] == 'web') {
                $user->notification_token_web = $input['token'];
                $user->save();
            }
            return Response('succes', 200);
        }
    }
}
