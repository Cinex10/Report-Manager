<?php

namespace App\Http\Controllers;

use App\Models\Picture_dec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PictureDecController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_idDeclaration($idDeclaration)
    {


        $data = DB::table('picture_decs')->where('idDeclaration', $idDeclaration)->get('picture');

        return $data;
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
            'state' => 'required|in:avant,apres',
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {

            $pic = new Picture_dec;
            $pic->idDeclaration = $request->input('idDeclaration');
            if ($request->hasfile('picture_add'))        //picture_add is name of forme
            {
                $file = $request->file('picture_add');
                $extension = $file->getClientOriginalExtension();
                $filename = $input['state'] . time() . '.' . 'jpg';
                // return $filename;
                $file->move('upload/pictures/', $filename);
                $pic->picture = $filename;
            }
            $pic->save();
            return (new Controller)->sendResponse($pic, 'uploaded picture successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Picture_dec  $picture_dec
     * @return \Illuminate\Http\Response
     */
    public function show(Picture_dec $picture_dec)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Picture_dec  $picture_dec
     * @return \Illuminate\Http\Response
     */
    public function edit(Picture_dec $picture_dec)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Picture_dec  $picture_dec
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
            'picture' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {
            $pic = DB::table('picture_decs')->where('idDeclaration', $request->input('idDeclaration'))
                ->where('picture', $request->input('picture'))
                ->get();
            $pic->idDeclaration = $request->input('idDeclaration');
            if ($request->hasfile('picture_add')) {
                $destination = 'upload/pictures/' . $request->input('picture');
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $file = $request->file('picture_add');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('upload/pictures/', $filename);
                $pic->picture = $filename;
            }
            $pic->update();
            return (new Controller)->sendResponse(new Picture_decResources($pic), 'updated picture successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Picture_dec  $picture_dec
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'idDeclaration' => 'required',
            'picture' => 'required'
        ]);

        if ($validator->fails()) {
            return (new Controller)->sendError('Please validate your errors', $validator->errors());
        } else {
            $pic = DB::table('picture_decs')->where('idDeclaration', $request->input('idDeclaration'))
                ->where('picture', $request->input('picture'))
                ->get();

            $destination = 'upload/pictures/' . $request->input('picture');
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $pic->delete();
            return (new Controller)->sendResponse(new Picture_decResources($pic), 'deleted picture successfully');
        }
    }
}
