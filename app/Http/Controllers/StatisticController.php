<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class StatisticController extends Controller
{
    public function getStatistics(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'dateDebut' => 'required',
            'dateFin' => 'required',
        ]);



        if ($validator->fails()) {
            return (new Controller)->sendError("Please validate errors", $validator->errors(), 404);
        } else {

            $data = DB::table('categories')->get();
            $res = array();

            $dateDebut = $input['dateDebut'];
            $dateFin   = $input['dateFin'];


            foreach ($data as $cat) {
                $nbdecs = DB::table('declarations')->whereBetween('created_at', [$dateDebut, $dateFin])->count();

                $nbdecsr = DB::table('declarations')->whereBetween('created_at', [$dateDebut, $dateFin])->where('state', 'resolu')->count();
                $decs = DB::table('declarations')->where('idCategorie', $cat->id)->where('state', 'resolu')->get();
                $moy = 0;

                foreach ($decs as $dec) {
                    $date = new DateTime($dec->dateValidation);
                    $date2 = new DateTime($dec->dateResolution);

                    $tr = $date->diff($date2)->format('%d%') * 24 + $date->diff($date2)->format('%h%');

                    $moy = $moy + $tr;
                }

                $days = intdiv($moy, 30);
                $hours = $moy % 24;
                $a = [
                    "name" => $cat->name,
                    "nbDecTotal" => DB::table('declarations')->where('idCategorie', $cat->id)->count(),
                    "nbDecNew" => DB::table('declarations')->where('idCategorie', $cat->id)->where('state', 'new')->count(),
                    "nbDecValide" => DB::table('declarations')->where('idCategorie', $cat->id)->where('state', 'valid')->count(),
                    "nbDecRejeter" => DB::table('declarations')->where('idCategorie', $cat->id)->where('state', 'rejeter')->count(),
                    "nbDecResolu" => DB::table('declarations')->where('idCategorie', $cat->id)->where('state', 'resolu')->count(),
                    "averageResolutionTime" => $days . 'd, ' . $hours . 'h',
                ];
                array_push($res, $a);
            }
            return [
                "totalNumber" => $nbdecs,
                "totalNumberResolu" => $nbdecsr,
                "categories" => $res,
            ];
        }
    }
}
