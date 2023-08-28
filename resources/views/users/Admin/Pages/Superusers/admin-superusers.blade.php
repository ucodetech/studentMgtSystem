@extends('layouts.adminapp')

@section('contents')
<div class="container-fluid p-0">

    @include('inc.title')
    @include('inc.messages')

    <div class="row">
        <div class="table-responsive">
            <div class="d-grid">
                <a href="{{ route('admin.ad.register') }}" class="btn btn-outline-primary mb-3">Add new Superuser</a>
                <hr class="invisible">
            </div>
            <table class="table table-hover table-condensed table-striped" id="superTables">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Unique ID</th>
                        <th>Fullname</th>
                        <th>Email</th>
                        <th>Permission</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Locked Out</th>
                        <th>Date Lockedout</th>
                        <th>Action</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach ($superusers as $super)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('storage/uploads/adminProfile/'.$super->admin_photo) }}" class="avatar img-fluid rounded me-1" alt="{{ $super->admin_fullname }}" />
                            </td>
                            <td>
                                <span class="badge badge-btn text-bg-primary">{{ $super->admin_uniqueid }}</span>
                            </td>
                            <td>
                                {{ $super->admin_fullname }}
                            </td>
                            <td>
                                {{ $super->admin_email }}
                            </td>
                            <td>
                                <span class="badge badge-btn text-bg-info">{{ Str::ucfirst($super->admin_permission) }}</span>
                            </td>
                            <td>
                                @if ($super->admin_permission == 'superuser')
                                    <span class="badge rounded-pill text-bg-danger">Superuser</span>
                                @else
                              
                                <div class="form-check form-switch">
                                    <input class="form-check-input adminStatus" type="checkbox" role="switch" 
                                    id="adminStatus{{ $super->id }}" {{ $super->status == "active" ? " checked" : "" }}
                                    data-id="{{ $super->id }}" value="{{ $super->status }}">
                                    <label class="form-check-label" for="adminStatus">{{ $super->status }}</label>
                                  </div>
                                @endif
                            </td>
                            <td class="{{ $super->admin_last_login == carbonNow() ? " text-success" : "text-warning" }}">
                              
                                {{ ($super->admin_last_login == carbonNow()) ? "Online":timeAgo($super->admin_last_login) }}
                            </td>
                            <td>
                                @if ($super->locked_out == 1)
                                    <span class="badge badge-btn  text-bg-danger">
                                            Locked Out 
                                    </span>
                                @else
                                <span class="badge badge-btn  text-bg-primary" >
                                    Not Locked Out 
                            </span>
                                @endif
                            </td>
                            <td>
                                {{ $super->date_locked_out == null ? "" : pretty_dates($super->date_locked_out)  }}
                            </td>
                            
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-info superDetail" id="superDetail{{ $super->id }}" data-bs-toggle="modal" data-bs-target="#superDetails" data-id="{{ $super->id }}" data-url="{{ route('admin.ad.details') }}">
                                        <i class="fa fa-eye fa-lg"></i>
                                    </button>
                                    @if ($super->admin_permission == 'superuser')
                                        <span class="badge badge-btn text-bg-danger">Superuser</span>
                                     @else

                                    <button type="button" class="btn btn-sm btn-outline-danger deleteAdmin" id="deleteAdmin{{ $super->id }}"
                                    data-id="{{ $super->id }}" data-url="{{ route('admin.ad.delete.admin') }}">
                                        <i class="fa fa-trash fa-lg"></i>
                                    </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>

<!-- Modal -->
<div class="modal fade" id="superDetails" tabindex="-1" role="dialog" aria-labelledby="supermodalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supermodalTitleId">Superuser Details <span id="superid"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div class="container-fluid" id="showSuperDetails">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('school/previewfile.js') }}"></script>
   <script>
         $(function(){

           $('#superTables').DataTable({
                processing:true,
                info:true
           });

           $('.adminStatus').on('change', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            var mode = $('#adminStatus'+id).is(':checked') ? " active" : " inactive";
            let _token = "{{ csrf_token() }}";
            let url = "{{ route('admin.ad.toggle.status') }}";
            $.post(url, {id:id,mode:mode,_token:_token}, function(data){
                location.reload();
                // alert(data);

            });
           })

           $('body').on('click', '.superDetail',function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let url = $(this).data('url');
            let _token = "{{ csrf_token() }}";
            $('#superid').html(' '+id); 
            $.get(url, {id:id,_token:_token}, function(data){
                $('#showSuperDetails').html(data);
            })
           });

           $('body').on('click', '.deleteAdmin', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let url = $(this).data('url');
            let _token = "{{ csrf_token() }}";
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {id:id, _token:_token}, function(data){
                        Swal.fire(
                            'Deleted!',
                            data,
                            'success'
                          );

                          location.reload();
                    })
                }
            });

           })

        })
   </script>
@endsection