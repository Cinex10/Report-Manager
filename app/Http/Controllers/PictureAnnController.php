<?php

namespace App\Http\Controllers;

use App\Models\Picture_ann;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PictureAnnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexIdAnn(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idAnnonce' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {

            $data = DB::table('picture_anns')->where('idAnnonce', $request->input('idAnnonce'))->get();
            if ($data != null) {
                return response()->json($data);
            } else {
                return response()->json('no pictures');
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
            'idAnnonce' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {

            $image = $request->file('image');
            if ($request->hasFile('image')) {

                $new_name = time() . '.' . 'jpg';
                $data = [
                    'idAnnonce' => $request->input('idAnnonce'),
                    'picture' => $new_name
                ];

                $inst = DB::table('picture_anns')->where('idAnnonce', $request->input('idAnnonce'))->insert($data);

                if ($inst == 1) {
                    $image->move(public_path('upload/pictures/'), $new_name);
                    return (new Controller)->sendResponse(response()->json($data),  'uploaded picture successfully');
                } else {
                    return response()->json('no inserted');
                }
            } else {
                return response()->json('image null');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Picture_ann  $pictureAnn
     * @return \Illuminate\Http\Response
     */
    public function show(Picture_ann $pictureAnn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Picture_ann  $pictureAnn
     * @return \Illuminate\Http\Response
     */
    public function edit(Picture_ann $pictureAnn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Picture_ann  $pictureAnn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idAnnonce' => 'required',
            'picture' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {
            $pic = DB::table('picture_anns')->where('idAnnonce', $request->input('idAnnonce'))
                ->where('picture', '' . $request->input('picture'))
                ->get();
            if ($pic != null) {
                if ($request->hasfile('image')) {
                    $destination = 'upload/pictures/' . $request->input('picture');
                    if (File::exists($destination)) {
                        $file = $request->file('image');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;

                        $data = [
                            "idAnnonce" => $request->input('idAnnonce'),
                            "picture" => $filename
                        ];
                        $inst = DB::table('picture_anns')->where('idAnnonce', $request->input('idAnnonce'))
                            ->where('picture', $request->input('picture'))
                            ->update($data);
                        if ($inst == 1) {
                            File::delete($destination);
                            $destination = 'upload/pictures/' . $request->input('picture');
                            return (new Controller)->sendResponse(response()->json($data), 'updated picture successfully');
                        } else {
                            return response()->json('no inserted');
                        }
                    } else {
                        return response()->json('picture not exists');
                    }
                } else {
                    return response()->json('please select a picture');
                }
            } else {
                return response()->json('new picture not exists');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Picture_ann  $pictureAnn
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idAnnonce' => 'required',
            'picture' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {
            $inst = DB::table('picture_anns')->where('idAnnonce', $request->input('idAnnonce'))
                ->where('picture', $request->input('picture'))
                ->get();

            if ($inst != null) {
                $destination = 'upload/pictures/' . $request->input('picture');
                if (File::exists($destination)) {
                    $it = DB::table('picture_anns')->where('idAnnonce', $request->input('idAnnonce'))
                        ->where('picture', $request->input('picture'))
                        ->delete();
                    if ($it == 1) {
                        File::delete($destination);
                        return (new Controller)->sendResponse(response()->json($inst), 'deleted picture successfully');
                    } else {
                        return response()->json('picture not delete from DB');
                    }
                } else {
                    return response()->json('picture not exists in file');
                }
            } else {
                return response()->json('picture not exists');
            }
        }
    }
}
