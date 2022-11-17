<?php

namespace App\Http\Controllers;

use App\Http\Modules\Reports\Events\ReportCreatedEvent;
use App\Http\Modules\Reports\Events\ReportValidatedEvent;
use App\Models\Categorie;
use App\Models\Declaration;
use App\Models\Picture_dec;
use App\Models\Solution;
use App\Models\User;
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
        // $notif = [
        //     'id' => $data->id,
        //     'title' => $data->Titre,
        // ];

        event(new ReportCreatedEvent($data));
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

        event(new ReportValidatedEvent($report));
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

    public function viewCreateReport()
    {
        return view('reports.create_report', ['title' => 'Create New Report', 'categories' => Categorie::all()]);
    }
    public function viewMyReport()
    {

        auth()->user()->unreadNotifications->markAsRead();
        return view('reports.my_reports', ['title' => 'My Reports', 'reports' => Declaration::where('idUser', auth()->user()->id)->orderBy('id', 'DESC')->paginate(11)]);
    }
    public function viewCurrentReport()
    {

        auth()->user()->unreadNotifications->where('type', 'App\Http\Modules\Reports\Notifications\ReportValidatedNotification')->markAsRead();
        return view('reports.current_reports', ['title' => 'Current Reports', 'reports' => Declaration::where('state', '!=', 'solved')->where('state', '=', 'valid')->orderBy('id', 'DESC')->paginate(11)]);
    }
    public function viewSolvedReport()
    {
        // auth()->user()->unreadNotifications->where('type', 'App\Http\Modules\Reports\Notifications\ReportSolvedNotification')->markAsRead();
        return view('reports.solved_reports', ['title' => 'Solved Reports', 'reports' => Declaration::where('state', '=', 'solved')->orderBy('id', 'DESC')->paginate(11)]);
    }
    public function viewNewReport()
    {

        auth()->user()->unreadNotifications->where('type', 'App\Http\Modules\Reports\Notifications\ReportCreatedNotification')->markAsRead();
        return view('reports.new_reports', ['title' => 'New Reports', 'reports' => Declaration::where('state', '=', 'new')->orderBy('id', 'DESC')->paginate(11), 'notifications' => auth()->user()->unreadNotifications]);
    }
    public function viewServiceCurrentReport()
    {
        auth()->user()->unreadNotifications->where('type', 'App\Http\Modules\Reports\Notifications\ReportValidatedNotification')->markAsRead();
        return view('reports.service_current_reports', ['title' => 'Current Reports', 'reports' => Declaration::with('categorie.service')->whereHas('categorie.service', function ($q) {
            $q->where('idChefService', auth()->user()->id);
        })->where('state', '!=', 'rejected')->where('state', '!=', 'solved')->where('state', '!=', 'new')->orderBy('id', 'DESC')->paginate(11)]);
    }
    public function viewServiceSolvedReport()
    {
        return view('reports.service_solved_reports', ['title' => 'Solved Reports', 'reports' => Declaration::with('categorie.service')->whereHas('categorie.service', function ($q) {
            $q->where('idChefService', auth()->user()->id);
        })->where('state', '=', 'solved')->orderBy('id', 'DESC')->paginate(11)]);
    }
    public function viewSimpleUsers()
    {
        return view('users.simple_users', ['title' => 'Simple Users', 'users' => User::role('user')->orderBy('id', 'DESC')->paginate(11)]);
    }
    public function viewTechnicalUsers()
    {
        return view('users.technical_users', ['title' => 'Technical Users', 'users' => User::whereHas("roles", function ($q) {
            $q->where("name", '!=', "user");
        })->orderBy('id', 'DESC')->paginate(11)]);
    }
    public function viewReportDetail($id)
    {
        return view('reports.report_detail', ['title' => 'Details', 'report' => Declaration::find($id), 'solution' => Solution::where('idDeclaration', $id)->first(), 'categories' => Categorie::all()]);
    }
}
