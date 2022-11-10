<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DeclarationController;

class RapportController extends Controller
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
            'idDeclaration' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {

            $data = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))->get();
            if ($data != null) {
                return response()->json($data);
            } else {
                return response()->json('no rapport');
            }
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
            'idDeclaration' => 'required',
            'title' => 'required',
            'description' => 'required',
            'file' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors(), 404);
        } else {

            $file = $request->file('file');
            if ($request->hasFile('file')) {
                $new_name = time() . '.' . 'pdf';
                $data = [
                    'idDeclaration' => $request->input('idDeclaration'),
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'file' => $new_name,
                ];



                $inst = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))->insert($data);

                if ($inst == 1) {
                    $file->move(public_path('/upload/rapports'), $new_name);
                    (new DeclarationController)->markAsRapportAttached($request);

                    return (new Controller)->sendResponse(response()->json($data), 'uploaded rapport successfully', 201);
                } else {
                    return (new Controller)->sendResponse(response()->json($data), 'fail inserted', 404);
                }
            } else {
                return (new Controller)->sendResponse('fail inserted', 404);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rapport  $rapport
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rapport  $rapport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {
            $pic = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))
                ->get();
            if ($pic != null) {
                if ($request->hasfile('file')) {
                    $destination = 'uploads/rapports/' . $request->input('file');
                    if (File::exists($destination)) {
                        $file = $request->file('file');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . 'pdf';

                        $data = [
                            "idDeclaration" => $request->input('idDeclaration'),
                            "file" => $filename,
                            "state" => 'new'
                        ];
                        $inst = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))
                            ->update($data);
                        if ($inst == 1) {
                            File::delete($destination);
                            $file->move(public_path('/uploads/rapports'), $filename);
                            $update = DB::table('declarations')->where('id', $request->input('idDeclaration'))
                                ->update([
                                    "state" => "avec rapport"
                                ]);
                            return (new Controller)->sendResponse(response()->json($data), 'updated rapport successfully');
                        } else {
                            return response()->json('no inserted');
                        }
                    } else {
                        return response()->json('rapport not exists');
                    }
                } else {
                    return response()->json('please select a rapport');
                }
            } else {
                return response()->json('new rapport not exists');
            }
        }
    }



    public function validateRapport(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {

            $data = [
                "idDeclaration" => $request->input('idDeclaration'),
                "state" => "valid",
            ];

            $inst = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))
                ->update($data);


            $date = now()->setTimezone('Africa/Algiers')->toArray()['formatted'];

            $inst1 = DB::table('declarations')->where('id', $request->input('idDeclaration'))
                ->update([
                    'dateResolution' => $date,
                    'state' => 'resolu',
                ]);
            if (($inst == 1) && ($inst1 == 1)) {
                (new DeclarationController)->markAsComplete($request);
                return (new Controller)->sendResponse(response()->json($data), 'state validated successfully', 200);
            } else {
                return response()->json('state not updated', 404);
            }
        }
    }

    public function rejeteRapport(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {
            $data = [
                "idDeclaration" => $request->input('idDeclaration'),
                "state" => "rejeter"
            ];

            $inst = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))
                ->update($data);

            if ($inst == 1) {

                $update = DB::table('declarations')->where('id', $request->input('idDeclaration'))
                    ->update([
                        "state" => "rapport rejete"
                    ]);
                $update = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))
                    ->update([
                        "state" => "rejeter"
                    ]);
                return (new Controller)->sendResponse(response()->json($data), 'state rejected successfully', 200);
            } else {
                return response()->json('state not updated', 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rapport  $rapport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {
            $inst = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))
                ->get();
            $file = $inst[0]->file;

            if ($inst != null) {
                $destination = 'upload/rapports/' . $file;
                if (File::exists($destination)) {
                    $it = DB::table('rapports')->where('idDeclaration', $request->input('idDeclaration'))
                        ->where('file', $file)
                        ->delete();
                    if ($it == 1) {
                        File::delete($destination);
                        return (new Controller)->sendResponse(response()->json($inst), 'deleted rapport successfully');
                    } else {
                        return response()->json('rapport not delete from DB');
                    }
                } else {
                    return response()->json('rapport not exists in file');
                }
            } else {
                return response()->json('rapport not exists');
            }
        }
    }
}
