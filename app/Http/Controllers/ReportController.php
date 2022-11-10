<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Picture_dec;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create(Request $request)
    {


        $request->validate([
            'Titre' => 'required',
            'idCategorie' => 'required',
            'description' => 'required',
            'Lieu' => 'required',
            'photos' => 'required',
        ], [
            'photos.required' => 'You must attache pictures to your reports.',
        ]);

        $data = Declaration::create([
            'Titre' => $request->Titre,
            'idCategorie' => $request->idCategorie,
            'Description' => $request->description,
            'Lieu' => $request->Lieu,
            'idUser' => auth()->user()->id,
        ]);




        if ($request->hasFile('photos')) {
            $allowedfileExtension = ['jpg', 'png'];
            $files = $request->file('photos');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                //dd($check);
                if ($check) {
                    $filename = date('YmdHi') . $file->getClientOriginalName();

                    $file->move(public_path('public/Image'), $filename);

                    Picture_dec::create([
                        'idDeclaration' => $data->id,
                        'picture' => $filename
                    ]);
                }
            }
        }
        return redirect()->back()->with('message', 'Report added successfully');
    }

    public function makeValide(Request $request)
    {


        $message = 'Report validated successfully';
        $id = $request->id;

        $report = Declaration::find($id);

        $report->state = 'valid';
        if ($request->idCategorie != null) {
            $catid = intval($request->idCategorie);
            // return [$report->idCategorie, $catid];
            if ($report->idCategorie != $catid) {
                $report->idCategorie = $catid;
                $message = 'Report updated and validated successfully';
            }
        }
        $report->save();
        return redirect()->back()->with('success', $message);
    }


    public function reject(Request $request)
    {
        $message = 'Report rejected successfully';
        $id = $request->id;

        $report = Declaration::find($id);

        $report->state = 'rejected';
        $report->save();
        return redirect()->back()->with('success', $message);
    }
}
