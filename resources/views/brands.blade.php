@extends('layouts.app')

@section('title')
<title>About | Portfolio | Admin</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h2 class="mb-0">Brands</h2>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter" onclick="addModal()">
                        Add new brand
                    </button>
                </div>
                <div class="divider" style="border-top: dotted 1px lightgray; margin: 0px 0px 20px 0px;"></div>

                <div class="card-body">
                   <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                      <thead>
                        <tr>
                          <th>Serial</th>
                          <th>Name</th>
                          <th>Image</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>
                                    <strong>{{ $loop->index + 1 }}</strong>
                                </td>
                                <td style="width: 25%"> 
                                    {{ $brand->name }}
                                </td>
                                <td style="width: 40%">
                                    <img src="{{ asset($brand->image) }}" alt="Avatar" class="rounded-circle" style="width: 50px; height: 50px;">
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-icon btn-outline-secondary" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#modalCenter" onclick="editModal({{ $brand->id }})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#smallModal" onclick="deleteModal({{ $brand->id }})">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr> 
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
                        
<!-- Add/Edit brands Modal -->
<div class="modal fade" id="modalCenter" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('add-brand') }}" method="post" enctype="multipart/form-data" id="brandForm">
                @csrf
                <div class="row">
                    <div class="col mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('img/upload-icon.png') }}" alt="user-avatar" class="d-block rounded" height="200" width="200" id="uploadedAvatar">
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
                <div class="row">
                    <div class="col mb-3">
                        <input type="hidden" name="id" id="id" class="form-control">
                        <label for="nameWithTitle" class="form-label">Name</label>
                        <input type="text" name="name" id="nameWithTitle" class="form-control" placeholder="Enter Name" required>
                    </div>
                </div>
            
        </div>
        <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
        </div>
    </div>
    </div>
</div>

<!-- Delete brands Modal -->
<div class="modal fade" id="smallModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Delete brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('delete-brand') }}" method="post">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure to delete this brand ???</p>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-danger">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    let brands = @json($brands);

    $(document).ready( function () {
        $('#myTable').DataTable();
    } );

    const showImage = (e) => {
        let file = e.currentTarget.files[0]
        var reader = new FileReader();
        
        reader.onload = function() {
            document.getElementById("uploadedAvatar").src = reader.result;
        };
        reader.readAsDataURL(file);
    }

    const addModal = () => {
        var host = window.location.protocol + "//" + window.location.host;
        $('#modalCenterTitle').html('Add brand')
        document.getElementById('uploadedAvatar').src = host + "/" + 'img/upload-icon.png';
        document.getElementById('nameWithTitle').value = "";
        document.getElementById('upload').required = true;
        document.getElementById('brandForm').action = "{{ route('add-brand') }}"
    }

    const editModal = (id) => {
        let brand = brands.filter(brand => brand.id == id)[0];
        var host = window.location.protocol + "//" + window.location.host;
        $('#modalCenterTitle').html('Edit brand')
        document.getElementById('id').value = id;
        document.getElementById('uploadedAvatar').src = host + "/" + brand.image;
        document.getElementById('nameWithTitle').value = brand.name;
        document.getElementById('upload').required = false;
        document.getElementById('brandForm').action = "{{ route('edit-brand') }}"
    }

    const deleteModal = (id) => {
        document.getElementById('deleteId').value = id;
    }

</script>
@endsection
