<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use DateTime;



class DeclarationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //verified
    public function indexAll($index)
    {
        // if ($index != null) return $index;
        // $data = DB::table('declarations')->get();
        if ($index == 'valid') {
            $data = Declaration::with(['user', 'categorie', 'categorie.user', 'attachesParent', 'attachesParent.user', 'attachesParent.categorie', 'attachesParent.categorie.user', 'pic', 'rapport'])
                ->where('idDeclarationParent', null)->where('state', '!=', 'new')->where('state', '!=', 'rejeter')->where('state', '!=', 'local')->get();
            return $data;
        }

        $data = Declaration::with(['user', 'categorie', 'categorie.user', 'attachesParent', 'attachesParent.user', 'attachesParent.categorie', 'attachesParent.categorie.user', 'pic', 'rapport'])
            ->where('idDeclarationParent', null)->get();
        return $data;
    }

    //verified
    public function indexSelf(Request $request)
    {
        $input = $request->all();
        // return $input;
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);

        $id = $input['id'];

        $data =
            Declaration::with(['user', 'categorie', 'categorie.user', 'attachesParent', 'attachesParent.user', 'attachesParent.categorie', 'attachesParent.categorie.user', 'pic', 'rapport'])
            ->where('idUser', $id)->get();

        return response(
            $data,
            200
        );
    }



    //verfied
    public function indexService($idCategory)
    {
        // $data = DB::table('declarations', 'user')->where('idCategorie', $idCategory)->where('state', 'valid')
        //     ->get();
        $data = Declaration::with(['user', 'categorie', 'categorie.user', 'attachesParent', 'attachesParent.user', 'attachesParent.categorie', 'attachesParent.categorie.user', 'pic', 'rapport'])->where('idCategorie', $idCategory)->where('state', "<>", 'resolu')->where('state', "<>", 'rejeter')
            ->get();
        return (new Controller)->sendResponse(
            $data,
            'service Declarations'
        );
    }

    public function indexAttached($id)
    {
        // $data = DB::table('declarations', 'user')->where('idCategorie', $idCategory)->where('state', 'valid')
        //     ->get();
        $data = Declaration::with('user', 'categorie', 'categorie.user')->where('idDeclarationParent', $id)
            ->get();
        return (new Controller)->sendResponse(
            $data,
            'service Declarations',
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    //verfied
    public function valid(Request $request)
    {
        $input = $request->all();
        // return $input;
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {
            $date = now()->setTimezone('Africa/Algiers')->toArray()['formatted'];


            $data = DB::table('declarations')->where('id', $request->input('idDeclaration'))->where('state', 'new')
                ->update(['state' => 'valid', 'dateValidation' => $date]);
            if ($data == 1) return (new Controller)->sendResponse(
                [],
                'Declarations valid'
            );
            else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist', 404);
            }
        }
    }

    public function markAsRapportAttached(Request $request)
    {
        $input = $request->all();
        // return $input;
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {
            $data = DB::table('declarations')->where('id', $request->input('idDeclaration'))
                ->update(['state' => 'avec rapport']);
            if ($data == 1) return (new Controller)->sendResponse(
                [],
                'Declarations valid'
            );
            else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist', 404);
            }
        }
    }

    public function markAsIncomplete(Request $request)
    {
        $input = $request->all();
        // return $input;
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {
            $data = DB::table('declarations')->where('id', $request->input('idDeclaration'))
                ->update(['state' => 'incomplete']);
            if ($data == 1) return (new Controller)->sendResponse(
                [],
                'Declarations valid'
            );
            else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist', 404);
            }
        }
    }

    public function markAsComplete(Request $request)
    {
        $input = $request->all();
        // return $input;
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {
            $data = DB::table('declarations')->where('id', $request->input('idDeclaration'))
                ->update(['state' => 'resolu']);
            if ($data == 1) return (new Controller)->sendResponse(
                [],
                'Declarations valid'
            );
            else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist', 404);
            }
        }
    }

    //verfied
    public function rejete(Request $request)
    {
        $input = $request->all();
        // return $input;
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {
            $data = DB::table('declarations')->where('id', $request->input('idDeclaration'))->where('state', 'new')
                ->update(['state' => 'rejeter']);
            if ($data == 1) return (new Controller)->sendResponse(
                [],
                'Declarations rejected'
            );
            else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist', 404);
            }
        }
    }

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
        // $id = Auth::user()->getAuthIdentifier();

        $validator = Validator::make($input, [
            'idUser' => 'required',
            'titre' => 'required',
            'description' => 'required',
            'lieu' => 'required',
            'idCategorie' => 'required',
            'idDeclarationParent' => 'nullable',
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $data = [
                'idUser' => $request->input('idUser'),
                'titre' => $request->input('titre'),
                'description' => $request->input('description'),
                'lieu' => $request->input('lieu'),
                'idCategorie' => $request->input('idCategorie'),
                'idDeclarationParent' => $request->input('idDeclarationParent'),
            ];
            $id = DB::table('declarations')->insertGetId($data);
            if ($id != null) {
                return (new Controller)->sendResponse(['id' => $id, 'declaration' => $data], 'declaration created successfully');
            } else {
                return (new Controller)->sendResponse($data, 'Error : declaration not created');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Declaration  $declaration
     * @return \Illuminate\Http\Response
     */
    public function show(Declaration $declaration)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Declaration  $declaration
     * @return \Illuminate\Http\Response
     */
    public function edit(Declaration $declaration)
    {
        //
    }

    public function attache(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclarationMain' => 'required',
            'idDeclarationAttached' => 'required',
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {
            $data = [
                'idDeclarationParent' => $request->input('idDeclarationMain'),
            ];
            $isrt = DB::table('declarations')->where('id', $request->input('idDeclarationAttached'))->update($data);

            if ($isrt == 1) {
                return (new Controller)->sendResponse(response()->json($data), 'declaration attached successfully', 201);
            } else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist', 404);
            }
        }
    }

    public function dettache(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclarationAttached' => 'required',
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {
            $data = [
                'idDeclarationParent' => null,
            ];
            $isrt = DB::table('declarations')->where('id', $request->input('idDeclarationAttached'))->update($data);

            if ($isrt == 1) {
                return (new Controller)->sendResponse(response()->json($data), 'declaration dettached successfully', 201);
            } else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist', 404);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Declaration  $declaration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
            'titre' => 'required',
            'description' => 'required',
            'lieu' => 'required',
            'idCategorie' => 'required'
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $data = [
                'titre' => $request->input('titre'),
                'description' => $request->input('description'),
                'lieu' => $request->input('lieu'),
                'idCategorie' => $request->input('idCategorie')
            ];
            $isrt = DB::table('declarations')->where('id', $request->input('idDeclaration'))->update($data);

            if ($isrt == 1) {
                return (new Controller)->sendResponse(response()->json($data), 'declaration updated successfully', 200);
            } else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist', 404);
            }
        }
    }


    public function updateState(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
            'state' => 'required'
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $data = [
                'state' => $request->input('state')
            ];
            $isrt = DB::table('declarations')->where('idDeclaration', $request->input('idDeclaration'))->update($data);
            if ($isrt == 1) {
                return (new Controller)->sendResponse(response()->json($data), 'declaration state updated successfully');
            } else {
                return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist');
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Declaration  $declaration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
        ]);
        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors());
        } else {
            $dlt = DB::table('declarations')->where('idDeclaration', $request->input('idDeclaration'))->delete();
            if ($dlt == 1) {
                return (new Controller)->sendResponse(response()->json(["idDeclaration" => $request->input()]), 'declaration deleted successfully');
            } else {
                return (new Controller)->sendResponse('$data', 'declaration not exist');
            }
        }
    }
}













// public function attacheService(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'idDeclaration' => 'required',
    //         'service' => 'required'
    //     ]);
    //     if ($validator->fails()) {
    //         return (new Controller)->sendError("Please validate errors", $validator->errors());
    //     } else {
    //         $data = [
    //             'service' => $request->input('service')
    //         ];
    //         $isrt = DB::table('declarations')->where('idDeclaration', $request->input('idDeclaration'))->update($data);
    //         if ($isrt == 1) {
    //             return (new Controller)->sendResponse(response()->json($data), 'declaration service updated successfully');
    //         } else {
    //             return (new Controller)->sendResponse(response()->json($data), 'Error : declaration not exist');
    //         }
    //     }
    // }
