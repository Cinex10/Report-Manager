@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')
@inject('reports', 'App\Models\Declaration')


@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Reports /</span> My Reports
</h4>


<hr class="my-5">

<!-- Hoverable Table rows -->
<div class="card">
    <!-- <h5 class="card-header">Hoverable rows</h5> -->


    <div class="table-responsive text-nowrap">
        <table class="table table-hover" style="overflow: visible;position: absolute;">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach($reports::where('idUser',auth()->user()->id)->get() as $report)

                <tr>
                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$report->titre}}</strong></td>
                    <td>{{$report->lieu}}</td>
                    <td>
                        {{$report->created_at}}
                    </td>
                    <td><span class="badge rounded-pill bg-primary">{{$report->categorie()->pluck('name')[0]}}</span></td>
                    <td><span class="badge rounded-pill bg-primary">{{$report->state}}</span></td>
                    <td>
                        <!-- <div class="dropdown"> -->
                        <a href="{{url('reports/'.$report->id)}}">

                            <button type="button" class="btn p-0" onclick="location.href=google"><i class="bx bx-show"></i></button>
                        </a>
                        <!-- <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div> -->
                        <!-- </div> -->
                    </td>
                </tr>
                @endforeach
                <!-- <tr>
                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>Angular Project</strong></td>
                    <td>Albert Cook</td>
                    <td>
                        <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                <img src="{{asset('assets/img/avatars/5.png')}}" alt="Avatar" class="rounded-circle">
                            </li>
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                <img src="{{asset('assets/img/avatars/6.png')}}" alt="Avatar" class="rounded-circle">
                            </li>
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Christina Parker">
                                <img src="{{asset('assets/img/avatars/7.png')}}" alt="Avatar" class="rounded-circle">
                            </li>
                        </ul>
                    </td>
                    <td><span class="badge bg-label-primary me-1">Active</span></td>
                    <td><span class="badge bg-label-primary me-1">Active</span></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr> -->



            </tbody>
        </table>
    </div>
</div>
<!--/ Hoverable Table rows -->
@endsection