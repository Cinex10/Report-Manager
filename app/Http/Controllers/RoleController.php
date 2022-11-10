<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;




class RoleController extends Controller
{
    public function addRole(Request $request)
    {
        $input = $request->input();
        $validator = Validator::make($input, [
            'name' => 'required',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {
            $role = Role::create(['name' => $input['name']]);


            // return $input["permissions"];
            foreach ($input["permissions"] as $permission) {
                $role->givePermissionTo($permission);
            }


            return (new Controller)->sendResponse(
                $role,
                'Role ajoute',
                201,
            );
            return (new Controller)->sendResponse([], 'Error : role not created', 404);
        }
    }

    public function getRoles()
    {
        $data = Role::with('permissions')->get();
        return $data;
    }

    public function editRole(Request $request)
    {
    }
}
