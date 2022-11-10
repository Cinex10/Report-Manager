<?php

namespace App\Http\Controllers;

use App\Models\Attache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;

class AttacheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDec' => 'required'
        ]);

        if ($validator->fails()) {
            return (new BaseController)->sendError('Please validate your errors', $validator->errors());
        } else {
            $data = DB::table('attaches')->where('idDeclaration2', $request->input('idDec'))->get();
            return (new BaseController)->sendResponse(response()->json($data), 'Attached Declarations');
        }
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
        $validator = Validator::make($input, [
            'idDeclaration1' => 'required',
            'idDeclaration2' => 'required'
        ]);
        if ($validator->fails()) {
            return (new BaseController)->sendError("Please validate errors", $validator->errors());
        } else {
            $data = [
                'idDeclaration1' => $request->input('idDeclaration1'),
                'idDeclaration2' => $request->input('idDeclaration2')
            ];
            $isrt = DB::table('attaches')->insert($data);
            if ($isrt ==  1) {
                return (new BaseController)->sendResponse(response()->json($data), 'Attached declarations created successfully');
            } else {
                return (new BaseController)->sendResponse(response()->json($data), 'Attached declarations not created ');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attache  $attache
     * @return \Illuminate\Http\Response
     */
    public function show(Attache $attache)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attache  $attache
     * @return \Illuminate\Http\Response
     */
    public function edit(Attache $attache)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attache  $attache
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration1' => 'required',
            'idDeclaration2' => 'required',
            'newIdDeclaration2' => 'required'
        ]);
        if ($validator->fails()) {
            return (new BaseController)->sendError("Please validate errors", $validator->errors());
        } else {
            $data = [
                'idDeclaration1' => $request->input('idDeclaration1'),
                'idDeclaration2' => $request->input('newIdDeclaration2')
            ];
            $updt = DB::table('attaches')->where('idDeclaration1', $request->input('idDeclaration1'))
                ->where('idDeclaration2', $request->input('idDeclaration2'))
                ->update($data);
            if ($updt == 1) {
                return (new BaseController)->sendResponse(response()->json($data), 'Attached declarations updated successfully');
            } else {
                return (new BaseController)->sendResponse(response()->json($data), 'Attached not exist');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attache  $attache
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration1' => 'required',
            'idDeclaration2' => 'required',
        ]);
        if ($validator->fails()) {
            return (new BaseController)->sendError("Please validate errors", $validator->errors());
        } else {
            $dlt = DB::table('attaches')->where('idDeclaration1', $request->input('idDeclaration1'))
                ->where('idDeclaration2', $request->input('idDeclaration2'))
                ->delete();
            if ($dlt == 1) {
                return (new BaseController)->sendResponse(
                    response()
                        ->json(
                            [
                                "idDeclaration1" => $request->input('idDeclaration1'),
                                "idDeclaration2" => $request->input('idDeclaration2')
                            ]
                        ),
                    'attache deleted successfully'
                );
            } else {
                return (new BaseController)->sendResponse(
                    response()
                        ->json(
                            [
                                "idDeclaration1" => $request->input('idDeclaration1'),
                                "idDeclaration2" => $request->input('idDeclaration2')
                            ]
                        ),
                    'attache not exist'
                );
            }
        }
    }
}
