@extends('layouts.app')

@section('title')
<title>Works | Portfolio | Admin</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <style>
        .tab-content {
            padding: 2px 3px 2px 3px !important;
            box-shadow: none !important;
        }
        .select2 {
            width: 100% !important;
        }
        .ck-editor__editable {
            min-height: 500px;
        }

        .viewDiv {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        .techStackImg {
            display: flex;
            flex-direction: column;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h2 class="mb-0">Works</h2>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter" onclick="addModal()">
                        Add new work
                    </button>
                </div>
                <div class="divider" style="border-top: dotted 1px lightgray; margin: 0px 0px 20px 0px;"></div>

                <div class="card-body">
                   <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                      <thead>
                        <tr>
                          <th>Serial</th>
                          <th>Image</th>
                          <th>Name</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($works as $work)
                            <tr>
                                <td>
                                    <strong>{{ $loop->index + 1 }}</strong>
                                </td>
                                <td style="width: 25%"> 
                                    <img src="{{ asset($work->image) }}" alt="" width="30" height="30">
                                </td>
                                <td style="width: 25%">
                                    {{ $work->name }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-icon btn-outline-secondary" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="viewModal({{ $work->id }})">
                                            <span class="tf-icons bx bxs-info-circle"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-secondary" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#modalCenter" onclick="editModal({{ $work->id }})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="deleteModal({{ $work->id }})">
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
                        
<!-- Add/Edit work Modal -->
<div class="modal fade" id="modalCenter" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('add-works') }}" method="post" enctype="multipart/form-data" id="addWorkForm">
                @csrf
                <div class="row">
                    <div class="col mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('img/upload-icon.png') }}" alt="user-avatar" class="d-block rounded" height="200" width="300" id="uploadedAvatar">
                        </div>
                        <div class="button-wrapper mt-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload company logo</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input onchange="showImage(event)" type="file" name="image" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg">
                                </label>
                            </div>
                            <p class="text-muted text-center mb-0">Allowed JPG, GIF or PNG</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <input type="hidden" name="id" id="id" class="form-control">
                        <label for="projectName" class="form-label">Project Name</label>
                        <input type="text" name="name" id="projectName" class="form-control" placeholder="Enter project name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="projectUrl" class="form-label">Project URL</label>
                        <input type="text" name="url" id="projectUrl" class="form-control" placeholder="Enter project url" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="usedTech" class="form-label">Technology used</label>
                        <select id="usedTech" class="tags-multiselect form-select" name="used_tech[]" multiple="multiple" required>
                            @foreach ($stacks as $stack)
                                <option value="{{ $stack->id }}">{{ $stack->name }}</option>   
                            @endforeach
                        </select> 
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="projectDesc" class="form-label">Project Description</label>
                        <textarea name="desc" id="projectDesc" class="form-control" cols="30" rows="6"></textarea>
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

<!-- view work Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewModalTitle">View experience</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="viewDiv">
                <img src="" alt="" srcset="" id="viewExImage" width="350" height="200">
                <div class="row text-center">
                    <h3 id="viewExCompanyName"></h3>
                </div>
                <div id="viewExTech" style="display: flex; gap: 25px;"></div>
                <div>
                    <span class="d-flex py-2" style="gap: 5px;"><strong>url:</strong><p class="ml-3" id="viewUrl"></p></span>
                    <p id="viewDesc"></p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Close
            </button>
        </div>
    </div>
    </div>
</div>

<!-- Delete work Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Delete work</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('delete-works') }}" method="post">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure to delete this work ???</p>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    let works = @json($works);
    let stacks = @json($stacks);

    $(document).ready( function () {
        $('#myTable').DataTable();
    } );

    $(".tags-multiselect").select2({
        tags: true,
        dropdownParent: $('#modalCenter'),
        tokenSeparators: [',', ' ']
    })

    const showImage = (e) => {
        let file = e.currentTarget.files[0]
        var reader = new FileReader();
        
        reader.onload = function() {
            document.getElementById("uploadedAvatar").src = reader.result;
        };
        reader.readAsDataURL(file);
    }

    const addModal = () => {
        document.getElementById('modalCenterTitle').textContent = 'Add Work';
    }

    const viewModal = (id) => {
        let work = works.filter(work => work.id == id)[0];
        var host = window.location.protocol + "//" + window.location.host;
        document.getElementById('viewExImage').src = host + "/" + work.image;
        document.getElementById('viewExCompanyName').textContent = work.name;
        
        let techStack = "";
        work.tech_stacks.forEach(element => {
            techStack += `<div class="techStackImg"><img src="${host + "/" + element.image}" alt="" srcset="" id="viewExImage" width="50" height="50" 
                data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true" title="${element.name}" 
                data-bs-original-title="<span>${element.name}</span>" />
                <span>${element.name}</span></div>`
        });

        document.getElementById('viewExTech').innerHTML = techStack

        document.getElementById('viewUrl').textContent = work.url
        document.getElementById('viewDesc').textContent = work.description
    }

    const editModal = (id) => {
        let work = works.filter(work => work.id == id)[0];
        var host = window.location.protocol + "//" + window.location.host;
        document.getElementById('modalCenterTitle').textContent = 'Edit Work';
        document.getElementById('id').value = id;
        document.getElementById('uploadedAvatar').src = host + "/" + work.image;
        document.getElementById('upload').required = false;
        document.getElementById('projectName').value = work.name;
        document.getElementById('projectUrl').value = work.url;
        document.getElementById('projectDesc').value = work.description;


        document.getElementById('addWorkForm').action = "{{ route('edit-works') }}"

        let exStack = JSON.parse(work.tech_used);
        let options = "";
        stacks.forEach(stack => {
            options += `<option value="${stack.id}" ${exStack.includes(JSON.stringify(stack.id)) && 'selected'}>${stack.name}</option>`
        })

        console.log(options);

        document.getElementById('usedTech').innerHTML = options
    }
    
    const deleteModal = (id) => {
        document.getElementById('deleteId').value = id;
    }
</script>
@endsection
