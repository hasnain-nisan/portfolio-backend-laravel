@extends('layouts.app')

@section('title')
<title>Contact | Portfolio | Admin</title>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <style>
        .flex > * {
            width: auto !important;
        }

        .datetime {
            position: absolute;
            top: 0px;
            right: 20px;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h2 class="mb-0">Contacts</h2>
                </div>
                <div class="divider" style="border-top: dotted 1px lightgray; margin: 0px 0px 20px 0px;"></div>

                <div class="card-body">
                   <div class="table-responsive text-nowrap">
                    <table class="table" id="myTable">
                      <thead>
                        <tr>
                          <th>Serial</th>
                          <th>Sender name</th>
                          <th>Sender email</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>
                                    <strong>{{ $loop->index + 1 }}</strong>
                                </td>
                                <td style="width: 25%"> 
                                    {{ $contact->name }}
                                </td>
                                <td style="width: 25%">
                                    {{ $contact->email }}
                                </td>
                                <td style="width: 25%">
                                    @if ($contact->is_seen == 1)
                                        <span class="badge bg-label-success" id="isSeen_{{ $contact->id }}">Seen</span>
                                    @else
                                        <span class="badge bg-label-danger" id="isSeen_{{ $contact->id }}">Not seen</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-icon btn-outline-secondary" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#viewContactModal" onclick="viewModal({{ $contact->id }})">
                                            <span class="tf-icons bx bxs-info-circle"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-secondary" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#replyContactModal" onclick="replyModal({{ $contact->id }})">
                                            <span class="tf-icons bx bx-reply"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#deleteContactModal" onclick="deleteModal({{ $contact->id }})">
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

<!-- view contact Modal -->
<div class="modal fade" id="viewContactModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card p-4">
                <div class="row d-flex mb-3 datetime">
                    <span class="badge bg-label-dark" id="viewTimeDate"></span>
                </div>
                <div class="row d-flex mb-3 flex">
                    <strong>Name:</strong>
                    <span id="viewName"></span>
                </div>
                <div class="row d-flex mb-3 flex">
                    <strong>Email:</strong>
                    <span id="viewEmail"></span>
                </div>
                <div class="row d-flex mb-3 flex">
                    <strong>Replied:</strong>
                    <span class="badge" id="viewReplied"></span>
                </div>
                <div class="row">
                    <strong>Message:</strong>
                    <span id="viewMessage"></span>
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

<!-- reply contact Modal -->
<div class="modal fade" id="replyContactModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="replyContactModalTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card p-4">
                <div class="row d-flex mb-3 datetime">
                    <span class="badge bg-label-dark" id="replyTimeDate"></span>
                </div>
                <div class="row d-flex mb-3 flex">
                    <strong>Name:</strong>
                    <span id="replyName"></span>
                </div>
                <div class="row d-flex mb-3 flex">
                    <strong>Email:</strong>
                    <span id="replyEmail"></span>
                </div>
                <div class="row mb-3">
                    <strong>Message:</strong>
                    <span id="replyMessage"></span>
                </div>
            <form action="{{ route('update-contact') }}" method="post">
                @csrf
                <div class="row">
                    <input type="hidden" name="type" id="replyType">
                    <input type="hidden" name="id" id="replyId">
                    <strong>Reply:</strong>
                    <div class="col-12">
                        <textarea class="form-control" name="reply" id="replyRes" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>  
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-outline-primary">
                Reply
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Close
            </button>
        </form>
        </div>
    </div>
    </div>
</div>

<!-- delete contact modal -->
<div class="modal fade" id="deleteContactModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Delete Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('delete-contact') }}" method="post">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure to delete this contact ???</p>
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
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    let contacts = @json($contacts);

    $(document).ready( function () {
        $('#myTable').DataTable();
    } );

    const viewModal = (id) => {
        let contact = contacts.filter(contact => contact.id == id)[0];
        document.getElementById('modalCenterTitle').innerHTML = 'View contact message'
        document.getElementById('viewName').textContent = contact.name
        document.getElementById('viewEmail').textContent = contact.email
        document.getElementById('viewMessage').textContent = contact.message 

        let date = new Date(contact.created_at);
        let dateString = date.toDateString();
        document.getElementById('viewTimeDate').textContent = dateString

        if(contact.is_replied === 1){
            document.getElementById('viewReplied').textContent = 'Yes';
            document.getElementById('viewReplied').classList.add('bg-label-success')
        } else {
            document.getElementById('viewReplied').textContent = 'No';
            document.getElementById('viewReplied').classList.add('bg-label-danger')
        }

        let isseen = document.getElementById(`isSeen_${contact.id}`);
        let unreadContacts = document.getElementById(`unreadContacts`);

        const options = {
            url: "{{ route('update-contact') }}",
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json;charset=UTF-8'
            },
            data: {
                type: 'is_seen',
                id: contact.id
            }
        };

        if(contact.is_seen === 0){
            axios(options)
            .then(response => {
                isseen.classList.remove('bg-label-danger')
                isseen.classList.add('bg-label-success')
                isseen.textContent = "Seen"
                unreadContacts.textContent = response.data.unread_contacts
            });
        }
    }

    const replyModal = (id) => {
        let contact = contacts.filter(contact => contact.id == id)[0];
        document.getElementById('replyContactModalTitle').innerHTML = 'Reply contact message'
        document.getElementById('replyName').textContent = contact.name
        document.getElementById('replyEmail').textContent = contact.email
        document.getElementById('replyMessage').textContent = contact.message 

        let date = new Date(contact.created_at);
        let dateString = date.toDateString();
        document.getElementById('replyTimeDate').textContent = dateString

        document.getElementById('replyType').value = 'is_replying'
        document.getElementById('replyId').value = id
        document.getElementById('replyRes').value = ""
    }

    const deleteModal = (id) => {
        document.getElementById('deleteId').value = id
    }
</script>
@endsection
