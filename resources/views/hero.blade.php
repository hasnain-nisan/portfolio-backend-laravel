@extends('layouts.app')

@section('title')
<title>Hero | Portfolio | Admin</title>
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header h2">Hero section</div>
                <div class="divider" style="border-top: dotted 1px lightgray; margin: 0px 0px 20px 0px;"></div>

                <div class="card-body">
                    <form method="post" action="{{ route('hero-post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div class="">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <img src="{{ asset($hero->image) }}" alt="user-avatar" class="d-block rounded" height="200" width="200" id="uploadedAvatar">
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
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Tags</label>
                            <div class="col-sm-10">
                                <select class="tags-multiselect form-select" name="tags[]" multiple="multiple" required>
                                    @php $tags = json_decode($hero->tags) @endphp
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag }}" selected>{{ $tag }}</option>   
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-end mt-3">
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(".tags-multiselect").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    })

    let tags = @json($tags);
    console.log(tags);

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
