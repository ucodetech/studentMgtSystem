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
                        <form action="{{ route('admin.ad.course.add') }}" method="POST" id="addCourseForm">
                            @csrf
                            @method("POST")
                            <div class="mb-3">
                                <label class="form-label">Course Title</label>
                                <input class="form-control form-control-lg" type="text" id="course_title" name="course_title" placeholder="" value="{{ old('course_title') }}"/>
                                <span class="text-error text-danger course_title_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Code</label>
                                <input class="form-control form-control-lg" type="text" id="course_code" name="course_code" placeholder="" value="{{ old('course_code') }}">
                                <span class="text-error text-danger course_code_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level</label>
                                <select class="form-control form-control-lg" type="text" id="level" name="level">
                                    <option value="">Select level</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                                    @endforeach
                                </select>
                                <span class="text-error text-danger semester_error"></span>
                            </div> 
                            <div class="mb-3">
                                <label class="form-label">Credit Unit</label>
                                <input class="form-control form-control-lg" type="text" id="credit_unit" name="credit_unit" placeholder="" value="{{ old('credit_unit') }}">
                                <span class="text-error text-danger credit_unit_error"></span>
                            </div> 
                            <div class="mb-3">
                                <label class="form-label">Semester</label>
                                <select class="form-control form-control-lg" type="text" id="semester" name="semester">
                                    <option value="">Select Semester</option>
                                    @foreach ($semesters as $sem)
                                        <option value="{{ $sem }}">{{ ucfirst($sem) }}</option>
                                    @endforeach
                                </select>
                                <span class="text-error text-danger semester_error"></span>
                            </div> 
                            <div class="mb-3">
                                <label class="form-label">Lecturer</label>
                                <select class="form-control form-control-lg" type="text" id="lecturer" name="lecturer">
                                    <option value="">Select Lecturer</option>
                                    @foreach ($lecturers as $lect)
                                        <option value="{{ $lect->id }}">{{ ucfirst($lect->lecturer_fullname) }}</option>
                                    @endforeach
                                </select>
                                <span class="text-error text-danger semester_error"></span>
                            </div> 
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-lg btn-primary">Add Course</button>
                              
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-md-8">
            <div class="table-responsive">
            
                <table class="table table-hover table-condensed table-striped" id="coursesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lecturer</th>
                            <th>Course Title</th>
                            <th>Course Code</th>
                            <th>Level</th>
                            <th>Credit Unit</th>
                            <th>Semester</th>
                            <th>Action</th>
                           
                        </tr>
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>
    
</div>


<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="editClassModal" tabindex="-1" 
data-bs-backdrop="static" 
data-bs-keyboard="false" 
role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Edit Class Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                <form action="{{ route('admin.ad.class.room.update') }}" method="POST" id="editClassRoomForm">
                    @csrf
                    @method("POST")
                    <div class="row" id="showRoomEditForm">

                    </div>
                </form>
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

           $('#coursesTable').DataTable({
                processing:true,
                info:true,
                pageLength:5,
                ajax: "{{ route('admin.ad.list.courses') }}",
                columns: [
                    {data:"DT_RowIndex"},
                    {data:"lecturer"},
                    {data:"course_title"},
                    {data:"course_code"},
                    {data:"level"},
                    {data:"credit"},
                    {data:"semester"},
                    {data:"action"}
                ],
               
      });

      $('#addCourseForm').on('submit', function(e){
        e.preventDefault();
        let form = this;
        $.ajax({
            url:$(form).attr('action'),
            method:$(form).attr('method'),
            data:new FormData(form),
            processData:false,
            contentType:false,
            cache:false,
            beforeSend:function(){
                $(form).find('span.text-error').text('');
            },
            success:function(data){
                if(data.code == 0){
                    $.each(data.error, function(prefix, val){
                        $(form).find('span.'+prefix+'_error').text(val[0]);
                    });
                }else{
                    $('#addCourseForm')[0].reset();
                    $('#coursesTable').DataTable().ajax.reload(null,false);
                    toastr.success(data.msg);
                }
            }
        });
    })

    $('body').on('click', '.deleteCourseBtn', function(e){
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
                        $('#coursesTable').DataTable().ajax.reload(null,false);
                })
            }
        });

        })


        // $('body').on('click', '.editCourseBtn', function(e){
        //     e.preventDefault();
        //     let id = $(this).data('id');
        //     let url = $(this).data('url');
        //     let _token = "{{ csrf_token() }}";
        //     $.get(url, {id:id, _token:_token}, function(data){
        //         $('#editCourseModal').modal('show');
        //         $('#showCourseEditForm').html(data);
        //     })
        // })

       

          

})
   
   
   </script>
@endsection