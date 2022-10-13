@extends('layouts.app')

@section('title')
<title>About | Portfolio | Admin</title>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header h2">About</div>
                <div class="divider" style="border-top: dotted 1px lightgray; margin: 0px 0px 20px 0px;"></div>

                <div class="card-body">
                    <form method="post" action="{{ route('about-post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div class="">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <img src="{{ asset($about->image) }}" alt="user-avatar" class="d-block rounded" height="200" width="200" id="uploadedAvatar">
                                    </div>
                                    <div class="button-wrapper mt-4">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                                <span class="d-none d-sm-block">Upload new photo</span>
                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                <input onchange="showImage(event)" type="file" name="image" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg">
                                            </label>
                                            {{-- <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button> --}}
                                        </div>
                                        <p class="text-muted text-center mb-0">Allowed JPG, GIF or PNG</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Title</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" class="form-control" id="basic-default-name" placeholder="John Doe" value="{{ $about->title }}"  required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-message">Description</label>
                            <div class="col-sm-10">
                                <textarea
                                    id="basic-default-message"
                                    class="form-control"
                                    placeholder="Hi, Do you have a moment to talk Joe?"
                                    aria-label="Hi, Do you have a moment to talk Joe?"
                                    aria-describedby="basic-icon-default-message2"
                                    rows="8"
                                    name="description"
                                    required
                                >{{ $about->description }}</textarea>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    showImage = (e) => {
        let file = e.currentTarget.files[0]
        var reader = new FileReader();
        
        reader.onload = function() {
            document.getElementById("uploadedAvatar").src = reader.result;
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
