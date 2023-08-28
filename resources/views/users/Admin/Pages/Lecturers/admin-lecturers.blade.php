@extends('layouts.adminapp')

@section('contents')
<div class="container-fluid p-0">

    @include('inc.title')
    @include('inc.messages')

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix"></div>
                    <div class="">
                        <form action="{{ route('admin.ad.process.register.lecturer') }}" method="POST">
                            @csrf
                            @method("POST")
                            <div class="mb-3">
                                <label class="form-label">Full name</label>
                                <input class="form-control form-control-lg" type="text" id="lecturer_fullname" name="lecturer_fullname" placeholder="Enter your name" value="{{ old('lecturer_fullname') }}"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input class="form-control form-control-lg" type="email" id="lecturer_email" name="lecturer_email" placeholder="Enter your email" value="{{ old('lecturer_email') }}"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone No</label>
                                <input class="form-control form-control-lg" type="tel" id="lecturer_tel" name="lecturer_tel" placeholder="Enter your phone no" value="{{ old('lecturer_tel') }}" minlength="11" maxlength="11"/>
                            </div>
                            <div class="mb-3">
                                <label for="lecturer_department" class="form-label">Department</label>
                                <select name="lecturer_department" id="lecturer_department" class="form-select">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->department_name }}" {{ $department->department_name == "COMPUTER SCIENCE" ? " selected":""}}>{{ Str::ucfirst($department->department_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input class="form-control form-control-lg" type="password" name="password" id="password" placeholder="Enter password" />
                                <span class="text-danger mt-5" id="previewPwd"></span>
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-outline-warning" id="generatePassword">Generate Password</button>
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-lg btn-primary">Create Lecturer</button>
                              
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-md-8">
            <div class="table-responsive">
            
                <table class="table table-hover table-condensed table-striped" id="lecturerTables">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Unique ID</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Locked Out</th>
                            <th>Date Lockedout</th>
                            <th>Action</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lecturers as $lecturer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('storage/uploads/lecturerProfile/'.$lecturer->lecturer_photo) }}" class="avatar img-fluid rounded me-1" alt="{{ $lecturer->lecturer_fullname }}" />
                                </td>
                                <td>
                                    <span class="badge badge-btn text-bg-primary">{{ $lecturer->lecturer_uniqueid }}</span>
                                </td>
                                <td>
                                    {{ $lecturer->lecturer_fullname }}
                                </td>
                                <td>
                                    {{ $lecturer->lecturer_email }}
                                </td>
                                <td>
                                    <span class="badge badge-btn text-bg-info">{{ Str::ucfirst($lecturer->lecturer_department) }}</span>
                                </td>
                                <td>
                                   
                                    <div class="form-check form-switch">
                                        <input class="form-check-input lecturerStatus" type="checkbox" role="switch" 
                                        id="lecturerStatus{{ $lecturer->id }}" {{ $lecturer->status == "active" ? " checked" : "" }}
                                        data-id="{{ $lecturer->id }}" value="{{ $lecturer->status }}">
                                        <label class="form-check-label" for="lecturerStatus">{{ $lecturer->status }}</label>
                                      </div>
                                  
                                </td>
                                <td class="{{ $lecturer->lecturer_last_login == carbonNow() ? " text-success" : "text-warning" }}">
                                  
                                    {{ ($lecturer->lecturer_last_login == carbonNow()) ? "Online":timeAgo($lecturer->lecturer_last_login) }}
                                </td>
                                <td>
                                    @if ($lecturer->locked_out == 1)
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
                                    {{ $lecturer->date_locked_out == null ? "" : pretty_dates($lecturer->date_locked_out)  }}
                                </td>
                                
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-info lecturerDetail" id="lecturerDetail{{ $lecturer->id }}" data-bs-toggle="modal" data-bs-target="#lecturerDetails" data-id="{{ $lecturer->id }}" data-url="{{ route('admin.ad.lecturer.details') }}">
                                            <i class="fa fa-eye fa-lg"></i>
                                        </button>
                                        
    
                                        <button type="button" class="btn btn-sm btn-outline-danger deleteLecturer" id="deleteLecturer{{ $lecturer->id }}"
                                        data-id="{{ $lecturer->id }}" data-url="{{ route('admin.ad.delete.lecturer') }}">
                                            <i class="fa fa-trash fa-lg"></i>
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

<!-- Modal -->
<div class="modal fade" id="lecturerDetails" tabindex="-1" role="dialog" aria-labelledby="lectmodalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lectmodalTitleId">Lecturer Details <span id="lectid"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div class="container-fluid" id="showLecturersDetails">
                    
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

           $('#lecturerTables').DataTable({
                "processing":true,
                "info":true,
                "pageLength":5,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#lecturerTables_wrapper .col-md-6:eq(0)');


           $('.lecturerStatus').on('change', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            var mode = $('#lecturerStatus'+id).is(':checked') ? " active" : " inactive";
            let _token = "{{ csrf_token() }}";
            let url = "{{ route('admin.ad.toggle.lecturer.status') }}";
            $.post(url, {id:id,mode:mode,_token:_token}, function(data){
                location.reload();
                // alert(data);

            });
           })

           $('body').on('click', '.lecturerDetail',function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let url = $(this).data('url');
            let _token = "{{ csrf_token() }}";
            $('#lectid').html(' '+id); 
            $.get(url, {id:id,_token:_token}, function(data){
                $('#showLecturersDetails').html(data);
            })
           });

           $('body').on('click', '.deleteLecturer', function(e){
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


           $('#generatePassword').on('click', function(e){
            e.preventDefault();
            let url = "{{ route('admin.ad.generate.password') }}";
            let _token = "{{ csrf_token() }}";
            $.post(url, {_token:_token},function(data){
                $('#password').val(data);
                $('#previewPwd').html(data);

            })
           })

        })
   </script>
@endsection