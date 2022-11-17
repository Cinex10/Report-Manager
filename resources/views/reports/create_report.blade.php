@extends('layouts/contentNavbarLayout') @section('title', $title) @section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>

@endsection @section('content')
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>

<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Reports /</span> {{$title}}
</h4>

@if(session()->has('message'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session()->get('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    </button>
</div>

@endif

<form class="row justify-content-center" action="{{route('report.create')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Text -->
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Text</h5>
            <div class="card-body">
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
                    <label for="exampleFormControlSelect1" class="form-label">Category</label>
                    <select class="form-select" id="exampleFormControlSelect1" name="idCategorie" aria-label="Default select example">


                        @foreach($categories as $category)

                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select> @error('idCategorie')
                    <span class="text-danger">{{$message}}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Location</label>
                    <input class="form-control" id="exampleFormControlInput1" name="Lieu" placeholder="Entre a title for your report" /> @error('Titre')
                    <span class="text-danger">{{$message}}</span> @enderror
                </div>




            </div>
        </div>
    </div>
    <div class="col-md-1Z">
        <div class="card mb-4">
            <h5 class="card-header">Media</h5>
            <div class="card-body">
                <!-- Images -->
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload images</label>
                    <div class="mb-3">

                        <input class="form-control" type="file" accept="image/*" id="photo-add" name="photos[]" multiple> @error('photos')
                        <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                    <!-- <img src="" id="category-img-tag" width="200px" /> -->
                    <div class="gallery-photo" style="display: inline-block; "></div>

                    <!--for preview purpose -->


                    <script type="text/javascript">
                        $(function() {
                            // Multiple images preview in browser
                            var imagesPreview = function(input, placeToInsertImagePreview) {

                                if (input.files) {
                                    var filesAmount = input.files.length;

                                    for (i = 0; i < filesAmount; i++) {
                                        var reader = new FileReader();

                                        reader.onload = function(event) {
                                            $($.parseHTML('<img style="max-width: 300px; margin:5px;border-radius: 10px;align-content:center;">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                                        }

                                        reader.readAsDataURL(input.files[i]);
                                    }
                                }

                            };

                            $('#photo-add').on('change', function() {
                                imagesPreview(this, 'div.gallery-photo');
                            });
                        });
                    </script>
                </div>
                <!-- Video -->
                <!-- <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Videos</label>
                    <div class="mb-3">

                        <input class="form-control" type="file" accept="video/*" id="video-add" name="videos[]" multiple>
                    </div>
                    
                    <div class="gallery-video" style="display: inline-block; "></div>


                    


                    <script type="text/javascript">
                        // document.getElementById("video-add")
                        //     .onchange = function(event) {
                        //         let file = event.target.files[0];
                        //         let blobURL = URL.createObjectURL(file);
                        //         document.querySelector("video").src = blobURL;
                        //     }

                        $(function() {
                            // Multiple images preview in browser
                            var imagesPreview = function(input, placeToInsertImagePreview) {

                                if (input.files) {
                                    var filesAmount = input.files.length;

                                    for (i = 0; i < filesAmount; i++) {
                                        var reader = new FileReader();

                                        let file = event.target.files[i];
                                        let blobURL = URL.createObjectURL(file);

                                        reader.onload = function(event) {
                                            $($.parseHTML('<video  style="max-width: 300px;margin:5px;border-radius: 10px;"  controls>')).attr('src', blobURL).appendTo(placeToInsertImagePreview);
                                        }

                                        reader.readAsDataURL(input.files[i]);
                                    }
                                }

                            };

                            $('#video-add').on('change', function() {
                                imagesPreview(this, 'div.gallery-video');
                            });
                        });
                    </script>
                </div> -->
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-center">

        <button type="submit" class="btn btn-primary col-md-2">Create</button>
    </div>








</form>
@endsection