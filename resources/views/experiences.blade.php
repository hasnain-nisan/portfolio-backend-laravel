@extends('layouts.app')

@section('title')
<title>Experiences | Portfolio | Admin</title>
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
                    <h2 class="mb-0">Experiences</h2>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter" onclick="addModal()">
                        Add new experience
                    </button>
                </div>
                <div class="divider" style="border-top: dotted 1px lightgray; margin: 0px 0px 20px 0px;"></div>

                <div class="card-body">
                   <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                      <thead>
                        <tr>
                          <th>Serial</th>
                          <th>Company</th>
                          <th>Position</th>
                          <th>Duration</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($experiences as $experience)
                            <tr>
                                <td>
                                    <strong>{{ $loop->index + 1 }}</strong>
                                </td>
                                <td style="width: 25%"> 
                                    <img src="{{ asset($experience->company_logo) }}" alt="" width="30" height="30">
                                    {{ $experience->company_name }}
                                </td>
                                <td style="width: 25%">
                                    {{ $experience->position }}
                                </td>
                                <td style="width: 25%">
                                    {{ $experience->start_date }}
                                    {{ " to " }}
                                    @if ($experience->is_present == 0)
                                        {{ $experience->end_date }}
                                    @else
                                        {{ "present" }}    
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-icon btn-outline-secondary" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#modalCenter" onclick="editModal({{ $experience->id }})">
                                            <span class="tf-icons bx bx-edit-alt"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="deleteModal({{ $experience->id }})">
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
                        
<!-- Add/Edit experience Modal -->
<div class="modal fade" id="modalCenter" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('add-experience') }}" method="post" enctype="multipart/form-data" id="addExperienceForm">
                @csrf
                <div class="row">
                    <div class="col mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('img/upload-icon.png') }}" alt="user-avatar" class="d-block rounded" height="200" width="200" id="uploadedAvatar">
                        </div>
                        <div class="button-wrapper mt-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload company logo</span>
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
                        <label for="nameWithTitle" class="form-label">Company Name</label>
                        <input type="text" name="company_name" id="companyName" class="form-control" placeholder="Enter Name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameWithTitle" class="form-label">Position</label>
                        <input type="text" name="company_position" id="companyPosition" class="form-control" placeholder="Enter postion" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="html5-date-input" class="form-label">Start Date</label>
                        <input class="form-control" name="start_date" type="date" value="" id="startDateInput">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="html5-date-input" class="form-label">End Date</label>
                        <div class="row align-items-end">
                            <div class="col-3">
                                <div class="form-check">
                                    <input name="end_date_radio" class="form-check-input" type="radio" value="present" id="endDatePresent" checked="true" onclick="showEndDateInput(this)">
                                    <label class="form-check-label" for="defaultRadio2"> Present </label>
                                </div>
                                <div class="form-check">
                                    <input name="end_date_radio" class="form-check-input" type="radio" value="date" id="endDateDate" onclick="showEndDateInput(this)">
                                    <label class="form-check-label" for="defaultRadio2"> Date </label>
                                </div>
                            </div>
                            <div class="col-9">
                                <input class="form-control d-none" name="end_date" type="date" value="" id="endDateInput">
                            </div>
                        </div>
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

<!-- Delete experience Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Delete experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('delete-experience') }}" method="post">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure to delete this experience ???</p>
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

    let experiences = @json($experiences);

    $(document).ready( function () {
        $('#myTable').DataTable();
    } );

    showImage = (e) => {
        let file = e.currentTarget.files[0]
        var reader = new FileReader();
        
        reader.onload = function() {
            document.getElementById("uploadedAvatar").src = reader.result;
        };
        reader.readAsDataURL(file);
    }

    const addModal = () => {
        document.getElementById('modalCenterTitle').textContent = 'Add Experience';
    }

    const showEndDateInput = (el) => {
        if(el.id === 'endDateDate'){
            document.getElementById('endDatePresent').checked = false;
            document.getElementById('endDateInput').classList.remove('d-none');
        } else{
            document.getElementById('endDateDate').checked = false;
            document.getElementById('endDateInput').classList.add('d-none');
        }
    }

    const editModal = (id) => {
        let experience = experiences.filter(experience => experience.id == id)[0];
        var host = window.location.protocol + "//" + window.location.host;
        document.getElementById('modalCenterTitle').textContent = 'Edit Experience';
        document.getElementById('id').value = id;
        document.getElementById('uploadedAvatar').src = host + "/" + experience.company_logo;
        document.getElementById('companyName').value = experience.company_name;
        document.getElementById('companyPosition').value = experience.position;
        document.getElementById('startDateInput').value = experience.start_date;
        document.getElementById('upload').required = false;

        if(experience.is_present === 0){
            document.getElementById('endDatePresent').checked = false;
            document.getElementById('endDateDate').checked = true;
            document.getElementById('endDateInput').classList.remove('d-none');
            document.getElementById('endDateInput').value = experience.end_date
        } else {
            document.getElementById('endDatePresent').checked = true;
            document.getElementById('endDateDate').checked = false;
            document.getElementById('endDateInput').classList.add('d-none');
        }

        document.getElementById('addExperienceForm').action = "{{ route('edit-experience') }}"
    }

    const deleteModal = (id) => {
        document.getElementById('deleteId').value = id;
    }
</script>
@endsection
