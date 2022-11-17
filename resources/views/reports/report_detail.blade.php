@extends('layouts/contentNavbarLayout') @section('title', $title) @section('page-script')
<script src="{{ asset('assets/js/form-basic-inputs.js') }}"></script>
@endsection @section('content')
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>

<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Reports /</span> {{$title}}
</h4>

@if(session()->has('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session()->get('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

@endif

<!-- Report -->
<div class="row justify-content-center">
    <form action="{{ route('report.validate') }}" method="POST">
        @csrf
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <!-- Left Part -->
                        <div class="col-md-6 mb-3">
                            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                                <ol class="carousel-indicators">
                                    @foreach (range(0, count($report->pic)-1) as $index)

                                    <li data-bs-target="#carouselExample" data-bs-slide-to="{{ $index }}" class="{{ $index == 1 ? 'active' : '' }}"></li>

                                    @endforeach
                                </ol>
                                <div class="carousel-inner">
                                    @foreach ($report->pic as $picture )

                                    <div class="carousel-item {{($report->pic[0] == $picture) ? 'active' : '' }}">
                                        <img class="d-block w-100 rounded" src="{{asset('public/Image/'.$picture->picture)}}" alt="First slide" />
                                        <!-- <div class="carousel-caption d-none d-md-block">
                                        <h3>{{$picture->picture}}</h3>
                                        <p>Eos mutat malis maluisset et, agam ancillae quo te, in vim congue pertinacia.</p>
                                    </div> -->
                                    </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div>
                        <!-- Right Part -->
                        <div class="col-md-6 mb-3 d-flex flex-column justify-content-between">
                            <div class="d-flex flex-column justify-content-start">
                                <h4>{{$report->titre}}</h4>
                                <p>{{$report->description}}</p>
                            </div>
                            <div class="d-flex flex-column justify-content-start">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">Location</label>
                                        <h6>{{$report->lieu}}</h6>
                                    </div>
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">Date</label>
                                        <h6>{{time_elapsed_string($report->created_at)}}</h6>
                                    </div>
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">Category</label>
                                        <div class="w-100"></div>

                                        @if(((auth()->user()->roles->pluck('name')[0] == 'responsable') || (auth()->user()->id == $report->idUser) ) && ($report->state == 'new'))


                                        <select class="btn btn-primary btn-sm dropdown-toggle rounded-pill" style="
                                    padding: 0.18em 0.5em;
                                    font-size: 0.8125em;
                                    font-weight: 500;
                                    text-transform: uppercase;
                                    line-height: 0.75;
                                  " id="exampleFormControlSelect1" name="idCategorie" aria-label="Default select example">


                                            @foreach($categories as $category)

                                            <option @if (($report->categorie()->pluck('name')[0]) == $category->name )
                                                selected
                                                @endif
                                                value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select> @else
                                        <span class="badge rounded-pill bg-primary">{{$report->categorie()->pluck('name')[0]}}</span> @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">State</label>
                                        <div class="w-100"></div>
                                        <span class="badge rounded-pill bg-primary">{{$report->state}}</span>
                                    </div>
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">Validation Date</label>
                                        <h5>
                                            {{($report->dateValidation == null) ? '-' : $report->dateValidation}}
                                        </h5>
                                    </div>
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">Solved Date</label>
                                        <h5>
                                            {{($report->dateResolution == null) ? '-' : $report->dateResolution}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @role('responsable') @if (($report->state == 'new'))

            <div class="mt-2 d-flex flex-row justify-content-end">


                <input value="{{$report->id}}" name="id" hidden />
                <button type="submit" formaction="{{ route('report.validate') }}" class="btn btn-primary me-2">Validate</button>
                <button type="submit" formaction="{{ route('report.reject') }}" class="btn btn-outline-secondary">Reject</button>
            </div>

            @endif @endrole
        </div>
    </form>
</div>


<!-- Solution -->

<!-- RESP,CHEF SERVICE,USER LI CRIYAHA -->
@if (($solution != null)) @if ( (auth()->user()->roles->pluck('name')[0] == 'responsable') || ( (auth()->user()->id == $report->idUser) && ($solution->state == 'accepted') ) || ( (auth()->user()->roles->pluck('name')[0] == 'chef service') && ($solution->state
!= 'incomplete') ) )
<div class="row justify-content-center">
    <form method="POST" action="{{route('solution.accepte')}}">
        @csrf
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <!-- Left Part -->

                        <div class="col-md-6 mb-3">
                            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                                <ol class="carousel-indicators">
                                    @foreach (range(0, count($solution->pic)-1) as $index)

                                    <li data-bs-target="#carouselExample" data-bs-slide-to="{{ $index }}" class="{{ ($index == 1) ? 'active' : ' ' }}"></li>

                                    @endforeach
                                </ol>
                                <div class="carousel-inner">
                                    @foreach ($solution->pic as $picture )

                                    <div class="carousel-item {{($solution->pic[0] == $picture) ? 'active' : '' }}">
                                        <img class="d-block w-100 rounded" src="{{asset('public/Image/'.$picture->picture)}}" alt="First slide" />
                                        <!-- <div class="carousel-caption d-none d-md-block">
                                        <h3>{{$picture->picture}}</h3>
                                        <p>Eos mutat malis maluisset et, agam ancillae quo te, in vim congue pertinacia.</p>
                                    </div> -->
                                    </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div>
                        <!-- Right Part -->
                        <div class="col-md-6 mb-3 d-flex flex-column justify-content-between">
                            <div class="d-flex flex-column justify-content-start">
                                <h4>{{$solution->titre}}</h4>
                                <p>{{$solution->description}}</p>
                            </div>
                            <div class="d-flex flex-column justify-content-start">

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">State</label>
                                        <div class="w-100"></div>
                                        <span class="badge rounded-pill bg-primary">{{$solution->state}}</span>
                                    </div>
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">State</label>
                                        <div class="w-100"></div>
                                        <span class="badge rounded-pill bg-primary">{{$solution->state}}</span>
                                    </div>
                                    <div class="col">
                                        <label for="exampleFormControlInput1" class="form-label">Date</label>
                                        <h6>
                                            {{($solution->dateResolution == null) ? '-' : $solution->dateResolution}}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @role('responsable') @if (($report->state == 'valid') && ($solution != null) && ($solution->state == 'in review'))
            <div class="mb-3 mt-2 d-flex flex-row justify-content-end">


                <input value="{{$solution->id}}" name="id" hidden />
                <button type="submit" formaction="{{route('solution.accepte')}}" class="btn btn-primary me-2">Accept</button>

                <input value="{{$solution->id}}" name="id" hidden />
                <button type="submit" formaction="{{route('solution.rejecte')}}" class="btn btn-outline-secondary">Reject</button>

            </div>
            @endif @endrole
        </div>
    </form>
    @elseif ((auth()->user()->roles->pluck('name')[0] == 'chef service') && ($solution->state == 'incomplete'))
    <!-- complete or re solve -->
    <div class="alert alert-success alert-dismissible" role="alert">
        the solution is rejected by the responsbale, you have to complete the solution
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="row justify-content-center">
        <form action="{{ route('solution.complete') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div id="carouselExample1" class="carousel slide" data-bs-ride="carousel">
                                    <ol class="carousel-indicators" id="alpha">
                                        <div id="carousel-complete1" style="display: inline-flex;">
                                            @foreach (range(0, count($solution->pic)-1) as $index)

                                            <li data-bs-target="#carouselExample" data-bs-slide-to="{{ $index }}" class="{{ ($index == 1) ? 'active' : ' ' }}"></li>

                                            @endforeach
                                        </div>
                                    </ol>
                                    <div class="carousel-inner" id="alpha2">
                                        <div id="carousel-complete2">
                                            @foreach ($solution->pic as $picture )

                                            <div class="carousel-item {{($solution->pic[0] == $picture) ? 'active' : '' }}">
                                                <img class="d-block w-100 rounded" src="{{asset('public/Image/'.$picture->picture)}}" alt="First slide" />
                                                <!-- <div class="carousel-caption d-none d-md-block">
                                        <h3>{{$picture->picture}}</h3>
                                        <p>Eos mutat malis maluisset et, agam ancillae quo te, in vim congue pertinacia.</p>
                                    </div> -->
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExample1" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExample1" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3 d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column justify-content-start">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Title</label>
                                        <input class="form-control" id="exampleFormControlInput1" name="Titre" placeholder="Entre a title for your solution" value="{{$solution->titre}}" /> @error('Titre')
                                        <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Describe your solution" name="description" rows="2">{{$solution->description}}</textarea> @error('description')
                                        <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Solved Date</label>
                                        <input class="form-control" type="datetime-local" name="dateResolution" value="{{$solution->dateResolution}}" /> @error('description')
                                        <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                    <label for="formFileMultiple" class="form-label">Upload images</label>
                                    <div class="mb-3">

                                        <input class="form-control" type="file" accept="image/*" id="photo-complete" name="photos[]" multiple> @error('photos')
                                        <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 d-flex flex-row justify-content-end">
                <input value="{{$solution->id}}" name="idSolution" hidden>
                <button type="submit" class="btn btn-primary me-2">Complete</button>
            </div>
            <script type="text/javascript">
                $(function() {

                    function setupReader(file, i, placeToInsertImagePreview) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            $($.parseHTML(`<li data-bs-target="#carouselExample1" data-bs-slide-to="${i}" class="${i == 0 ? "active" : " "}"></li>`)).appendTo(placeToInsertImagePreview);
                        }

                        reader.readAsDataURL(file);
                    }
                    // Multiple images preview in browser
                    var imagesPreview = function(input, placeToInsertImagePreview) {
                        $($.parseHTML(``)).appendTo('#carousel-complete1');
                        $($('#carousel-complete1').replaceWith(``));
                        if (input.files) {
                            var filesAmount = input.files.length;
                            for (i = 0; i < filesAmount; i++) {
                                setupReader(input.files[i], i, placeToInsertImagePreview);
                            }
                        }
                    };

                    function setupReader2(file, i, placeToInsertImagePreview) {
                        var reader = new FileReader();


                        reader.onload = function(event) {

                            $($.parseHTML(`<div class="${i == 0 ? "carousel-item active" : "carousel-item"}"><img id="image" class="d-block w-100 rounded" alt="First slide" src="${event.target.result}" /></div>`)).appendTo(placeToInsertImagePreview);
                        }

                        reader.readAsDataURL(file);
                    }

                    var imagesPreview2 = function(input, placeToInsertImagePreview) {
                        $($('#carousel-complete2').replaceWith(``));

                        if (input.files) {
                            var filesAmount = input.files.length;

                            for (i = 0; i < filesAmount; i++) {
                                setupReader2(input.files[i], i, placeToInsertImagePreview);
                            }
                        }

                    };

                    $('#photo-complete').on('change', function() {
                        imagesPreview(this, '#alpha');
                        imagesPreview2(this, '#alpha2');
                    });
                });
            </script>
        </form>
    </div>
    @endif @elseif (($solution == null) && (auth()->user()->roles->pluck('name')[0] == 'chef service'))
    <div class="row justify-content-center">
        <form action="{{ route('solution.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div id="carouselExample1" class="carousel slide" data-bs-ride="carousel">
                                    <ol class="carousel-indicators" id="alpha">

                                    </ol>
                                    <div class="carousel-inner" id="alpha2">

                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExample1" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExample1" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3 d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column justify-content-start">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Title</label>
                                        <input class="form-control" id="exampleFormControlInput1" name="Titre" placeholder="Entre a title for your report" /> @error('Titre')
                                        <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Describe your report" name="description" rows="2"></textarea> @error('description')
                                        <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Solved Date</label>
                                        <input class="form-control" type="datetime-local" value="2021-06-18T12:30:00" name="dateResolution" /> @error('description')
                                        <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                    <label for="formFileMultiple" class="form-label">Upload images</label>
                                    <div class="mb-3">

                                        <input class="form-control" type="file" accept="image/*" id="photo-add" name="photos[]" multiple> @error('photos')
                                        <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 d-flex flex-row justify-content-end">
                <input value="{{$report->id}}" name="idDeclaration" hidden>
                <button type="submit" class="btn btn-primary me-2">Solve</button>
            </div>
        </form>
    </div>


    <!-- <img src="" id="category-img-tag" width="200px" /> -->


    <!--for preview purpose -->


    <script type="text/javascript">
        $(function() {

            function setupReader(file, i, placeToInsertImagePreview) {
                var reader = new FileReader();


                reader.onload = function(event) {



                    $($.parseHTML(`<li data-bs-target="#carouselExample1" data-bs-slide-to="${i}" class="${i == 0 ? "active" : " "}"></li>`)).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(file);
            }
            // Multiple images preview in browser
            var imagesPreview = function(input, placeToInsertImagePreview) {


                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        setupReader(input.files[i], i, placeToInsertImagePreview);
                    }
                }

            };

            function setupReader2(file, i, placeToInsertImagePreview) {
                var reader = new FileReader();


                reader.onload = function(event) {

                    $($.parseHTML(`<div class="${i == 0 ? "carousel-item active" : "carousel-item"}"><img id="image" class="d-block w-100 rounded" alt="First slide" src="${event.target.result}" /></div>`)).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(file);
            }

            var imagesPreview2 = function(input, placeToInsertImagePreview) {

                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        setupReader2(input.files[i], i, placeToInsertImagePreview);
                    }
                }

            };

            $('#photo-add').on('change', function() {
                imagesPreview(this, '#alpha');
                imagesPreview2(this, '#alpha2');
            });
        });
    </script>


    @endif

    <!-- ++ CHEF SERVICE Y9DR YZID SOL -->
    <!-- ++ RESP Y9DR YACCEPTER / YREJETER SOL -->



    @endsection