<?php

namespace App\Http\Controllers;

use App\Models\Picture_sol;
use App\Models\Solution;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    public function create(Request $request)
    {

        // return $request;

        $request->validate([
            'Titre' => 'required',
            'idDeclaration' => 'required',
            'description' => 'required',
            'dateResolution' => 'required',
            'photos' => 'required',
        ], [
            'photos.required' => 'You must attache pictures to your reports.',
        ]);

        $data = Solution::create([
            'Titre' => $request->Titre,
            'idDeclaration' => $request->idDeclaration,
            'Description' => $request->description,
            'dateResolution' => $request->dateResolution,
            'idChefService' => auth()->user()->id,
        ]);




        if ($request->hasFile('photos')) {
            $allowedfileExtension = ['jpg', 'png'];
            $files = $request->file('photos');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $filename = date('YmdHi') . $file->getClientOriginalName();

                    $file->move(public_path('public/Image'), $filename);

                    Picture_sol::create([
                        'idSolution' => $data->id,
                        'picture' => $filename
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'Solution added successfully');
    }

    public function accepte(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $sol = Solution::find($request->id);
        $dec = $sol->declaration;
        $sol->state = 'accepted';
        $dec->state = 'solved';
        $dec->save();
        $sol->save();
        return redirect()->back()->with('success', 'Solution accepted successfully');
    }
    public function rejecte(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $sol = Solution::find($request->id);
        $sol->state = 'incomplete';
        $sol->save();
        return redirect()->back()->with('success', 'Solution rejected successfully');
    }
    public function complete(Request $request)
    {
        $sol = Solution::find($request->idSolution);


        // {
        //     "id": 3,
        //     "idDeclaration": 3,
        //     "idChefService": 6,
        //     "titre": "Solution Pro Max",
        //     "description": "keur he",
        //     "dateResolution": "2021-06-18 12:30:00",
        //     "state": "incomplete",
        //     "created_at": "2022-11-08T23:03:09.000000Z",
        //     "updated_at": "2022-11-08T23:06:48.000000Z"
        //   }

        // Request


        // {
        //     "_token": "FkD1KID55nfonlzmoOo7gu6Nc4oUiIeZKVO70My8",
        //     "Titre": "Solution Vrai de pro max",
        //     "description": "keur he max nouvelle",
        //     "dateResolution": "2000-12-07T12:30",
        //     "idSolution": "3",
        //     "photos": [
        //       {

        //       },
        //       {

        //       }
        //     ]
        //   }

        if ($sol->titre != $request->Titre) {
            $sol->titre = $request->Titre;
        }
        if ($sol->description != $request->description) {
            $sol->description = $request->description;
        }
        if ($sol->dateResolution != $request->dateResolution) {
            $sol->dateResolution = $request->dateResolution;
        }
        $sol->state = 'in review';
        $sol->save();

        $old_pics = $sol->pic;
        foreach ($old_pics as $old_pic) {
            $old_pic->delete();
        }
        //delete old pics
        if ($request->hasFile('photos')) {
            $allowedfileExtension = ['jpg', 'png'];
            $files = $request->file('photos');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $filename = date('YmdHi') . $file->getClientOriginalName();

                    $file->move(public_path('public/Image'), $filename);

                    Picture_sol::create([
                        'idSolution' => $sol->id,
                        'picture' => $filename
                    ]);
                }
            }
        }

        $message = 'Solution updated successfully';
        return redirect()->back()->with('success', $message);
    }
}
