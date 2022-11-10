<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAll($state)
    {
        $date = now()->setTimezone('Africa/Algiers')->toArray()['formatted'];
        if ($state == 'all')
            $data = Annonce::with('pic', 'user')->get();
        else if ($state == 'valid') $data = Annonce::with('pic', 'user')->where('state', 'valid')->whereDate('dateDebut', '<=', $date)->whereDate('dateFin', '>=', $date)->get();
        else  $data = Annonce::with('pic', 'user')->where('state', $state)->get();
        return response($data, 200);
    }
    public function indexSelf($idUser)
    {

        $data = Annonce::with('pic', 'user')->where('idUser', $idUser)
            ->get();
        return response(
            $data,
            200
        );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        //$id = Auth::user()->id();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'dateDebut' => 'required',
            'dateFin' => 'required',
            'file' => 'required',
            'idUser' => 'required'
        ]);
        // return $request;
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $file = $request->file('file');
            if ($request->hasFile('file')) {
                $new_name = time() . '.' . 'pdf';
                $data = [
                    'id' => DB::table('annonces')->max('id') + 1,
                    'idUser' => $request->input('idUser'),
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'dateDebut' => $request->input('dateDebut'),
                    'dateFin' => $request->input('dateFin'),
                    'file' => $new_name,
                ];

                $isrt = DB::table('annonces')->insert($data);
                if ($isrt == 1) {
                    return (new Controller)->sendResponse($data, 'annonce created successfully');

                    //can't delete user
                    $it = DB::table('users')->where('id', $request->input('idUser'))
                        ->update(["can_delete" => false]);
                } else {
                    return (new Controller)->sendResponse($data, 'Error : annonce not created');
                }
            }
        }
    }

    public function updateAnnonce(Request $request)
    {
        $input = $request->all();
        //$id = Auth::user()->id();
        $validator = Validator::make($input, [
            'idAnnonce' => 'required',
            'title' => 'required',
            'description' => 'required',
            'dateDebut' => 'required',
            'dateFin' => 'required',
            'file' => 'required',
        ]);
        // return $request;
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $file = $request->file('file');
            if ($request->hasFile('file')) {
                $new_name = time() . '.' . 'pdf';
                $data = [
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'dateDebut' => $request->input('dateDebut'),
                    'dateFin' => $request->input('dateFin'),
                    'state' => 'new',
                    'file' => $new_name,
                ];

                $isrt = DB::table('annonces')->where('id', $request->input('idAnnonce'))->update($data);
                if ($isrt == 1) {
                    return (new Controller)->sendResponse(["id" => $request->input('idAnnonce'), $data], 'annonce created successfully');

                    //can't delete user
                    $it = DB::table('users')->where('id', $request->input('idUser'))
                        ->update(["can_delete" => false]);
                } else {
                    return (new Controller)->sendResponse($data, 'Error : annonce not created');
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function show(Annonce $annonce)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function edit(Annonce $annonce)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'date' => 'required'
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $data = [
                'title' => $request->input('title'),
                'description' => $request->input('description'),

            ];
            $isrt = DB::table('annonces')->where('id', $request->input('id'))->update($data);

            if ($isrt == 1) {
                return (new Controller)->sendResponse(response()->json($data), 'annonce updated successfully');
            } else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : annonce not exist');
            }
        }
    }


    public function updateState(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'state' => 'required'
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $data = [
                'state' => $request->input('state')
            ];
            $isrt = DB::table('annonces')->where('id', $request->input('id'))->update($data);
            if ($isrt == 1) {
                return (new Controller)->sendResponse(response()->json($data), 'annonce state updated successfully');
            } else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : annonce not exist');
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $dec = DB::table('annonces')->where('id', $request->input('id'))->get('state');
            $dec = json_decode($dec, true);
            $dec = $dec[0]['state'];
            if ($dec == "en_cour") {
                return response()->json('On peux pas supprimer la procedure est encour');
            } else {
                $dlt = DB::table('annonces')->where('id', $request->input('id'))->delete();
                if ($dlt == 1) {
                    return (new Controller)->sendResponse(response()->json(["id" => $request->input('id')]), 'annonce deleted successfully');
                } else {
                    return (new Controller)->sendResponse($dlt, 'annonce not exist');
                }
            }
        }
    }
}
