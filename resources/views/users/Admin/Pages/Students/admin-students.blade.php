@extends('layouts.adminapp')

@section('contents')
<div class="container-fluid p-0">

    @include('inc.title')
    @include('inc.messages')

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
            
                <table class="table table-hover table-condensed table-striped" id="studentTables">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Unique ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Matric</th>
                            <th>level</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Locked Out</th>
                            <th>Date Lockedout</th>
                            <th>Action</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('storage/uploads/userProfile/'.$student->photo) }}" class="avatar img-fluid rounded me-1" alt="{{ $student->name }}" />
                                </td>
                                <td>
                                    <span class="badge badge-btn text-bg-primary">{{ $student->uniqueid }}</span>
                                </td>
                                <td>
                                    {{ $student->name }}
                                </td>
                                <td>
                                    {{ $student->email }}
                                </td>
                               
                                <td>
                                    <span class="badge badge-btn text-bg-info">{{ Str::ucfirst($student->department) }}</span>
                                </td>
                                <td>
                                    {{ $student->matric_no }}
                                </td>
                                <td>
                                    {{ $student->level }}
                                </td>
                                <td>
                                   
                                    <div class="form-check form-switch">
                                        <input class="form-check-input studentStatus" type="checkbox" role="switch" 
                                        id="studentStatus{{ $student->id }}" {{ $student->status == "active" ? " checked" : "" }}
                                        data-id="{{ $student->id }}" value="{{ $student->status }}">
                                        <label class="form-check-label" for="studentStatus">{{ $student->status }}</label>
                                      </div>
                                  
                                </td>
                                <td class="{{ $student->last_login == carbonNow() ? " text-success" : "text-warning" }}">
                                  
                                    {{ ($student->last_login == carbonNow()) ? "Online":timeAgo($student->last_login) }}
                                </td>
                                <td>
                                    @if ($student->locked_out == 1)
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
                                    {{ $student->date_locked_out == null ? "" : pretty_dates($student->date_locked_out)  }}
                                </td>
                                
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-info studentDetail" id="studentDetail{{ $student->id }}" data-bs-toggle="modal" data-bs-target="#studentDetails" data-id="{{ $student->id }}" data-url="{{ route('admin.ad.student.details') }}">
                                            <i class="fa fa-eye fa-lg"></i>
                                        </button>
                                        
    
                                        <button type="button" class="btn btn-sm btn-outline-danger deleteStudent" id="deleteStudent{{ $student->id }}"
                                        data-id="{{ $student->id }}" data-url="{{ route('admin.ad.delete.student') }}">
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
<div class="modal fade" id="studentDetails" tabindex="-1" role="dialog" aria-labelledby="studmodalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studmodalTitleId">Student Details <span id="studid"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div class="container-fluid" id="showStudentDetails">
                    
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

           $('#studentTables').DataTable({
                "processing":true,
                "info":true,
                "pageLength":5,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#studentTables_wrapper .col-md-6:eq(0)');


           $('.studentStatus').on('change', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            var mode = $('#studentStatus'+id).is(':checked') ? " active" : " inactive";
            let _token = "{{ csrf_token() }}";
            let url = "{{ route('admin.ad.toggle.student.status') }}";
            $.post(url, {id:id,mode:mode,_token:_token}, function(data){
                location.reload();
                // alert(data);

            });
           })

           $('body').on('click', '.studentDetail',function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let url = $(this).data('url');
            let _token = "{{ csrf_token() }}";
            $('#lectid').html(' '+id); 
            $.get(url, {id:id,_token:_token}, function(data){
                $('#showStudentDetails').html(data);
            })
           });

           $('body').on('click', '.deleteStudent', function(e){
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