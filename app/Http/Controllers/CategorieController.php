<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //verified
    public function index()
    {
        // $data = DB::table('categories')->get();
        $data = Categorie::with('user')->get();

        return response()->json($data);
    }

    public function indexChefService($idChefService)
    {
        // $data = DB::table('categories')->get();
        $data = Categorie::with('user')->where('idChefService', $idChefService)->get()->first();
        // $data = DB::table('categories')->where('idChefService', $idChefService)->get()->first();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    //verified
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'idChefService' => 'required'
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {

            $data = [
                // "idCategorie" => DB::table('categories')->max('idCategorie') + 1,
                "name" => $request->input('name'),
                "idChefService" => $request->input('idChefService'),
                "description" => $request->input('description'),
            ];

            $isrt = DB::table('categories')->insert($data);
            if ($isrt == 1) {
                return (new Controller)->sendResponse(response()->json($data), 'categorie created successfully');
            } else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : categorie not created');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idCategorie' => 'required',
            'name' => 'required',
            'description' => 'required',
            'idChefService' => 'required'
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $data = [
                'name' => $request->input('name'),
                'Description' => $request->input('description'),
                'idChefService' => $request->input('idChefService')
            ];
            $upd = DB::table('categories')->where('idCategorie', $request->input('idCategorie'))->update($data);
            if ($upd == 1) {
                return (new Controller)->sendResponse(response()->json($data), 'categorie updated successfully');
            } else {
                return (new Controller)->sendResponse(response()->json($data), 'categorie not exist');
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idCategorie' => 'required',
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $dlt = DB::table('categories')->where('idCategorie', $request->input('idCategorie'))->delete();
            if ($dlt == 1) {
                return (new Controller)->sendResponse(
                    response()->json(["idCategorie" => $request->input('idCategorie')]),
                    'categorie deleted successfully'
                );
            } else {
                return (new Controller)->sendResponse(
                    response()->json(["idCategorie" => $request->input('idCategorie')]),
                    'categorie not exist'
                );
            }
        }
    }
}
