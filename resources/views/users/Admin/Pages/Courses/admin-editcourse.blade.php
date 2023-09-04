@extends('layouts.adminapp')

@section('contents')
<div class="container-fluid p-0">

    @include('inc.title')
    @include('inc.messages')

    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix"></div>
                    <div class="">
                        <form action="{{ route('admin.ad.course.update') }}" method="POST" id="editCourseForm">
                            @csrf
                            @method("POST")
                            <input type="hidden" name="course_id" id="course_id" value="{{ $course->id }}">
                            <div class="mb-3">
                                <label class="form-label">Course Title</label>
                                <input class="form-control form-control-lg" type="text" id="course_title" name="course_title" placeholder="" value="{{ $course->course_title }}"/>
                                <span class="text-error text-danger course_title_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Code</label>
                                <input class="form-control form-control-lg" type="text" id="course_code" name="course_code" placeholder="" value="{{ $course->course_code }}">
                                <span class="text-error text-danger course_code_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level</label>
                                <select class="form-control form-control-lg" type="text" id="level" name="level">
                                    <option value="">Select level</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level }}" {{ $course->level == $level ? " selected": "" }}>{{ ucfirst($level) }}</option>
                                    @endforeach
                                </select>
                                <span class="text-error text-danger semester_error"></span>
                            </div> 
                            <div class="mb-3">
                                <label class="form-label">Credit Unit</label>
                                <input class="form-control form-control-lg" type="text" id="credit_unit" name="credit_unit" placeholder="" value="{{$course->credit }}">
                                <span class="text-error text-danger credit_unit_error"></span>
                            </div> 
                            <div class="mb-3">
                                <label class="form-label">Semester</label>
                                <select class="form-control form-control-lg" type="text" id="semester" name="semester">
                                    <option value="">Select Semester</option>
                                    @foreach ($semesters as $sem)
                                        <option value="{{ $sem }}" {{ $course->semester == $sem ? " selected": "" }}>{{ ucfirst($sem) }}</option>
                                    @endforeach
                                </select>
                                <span class="text-error text-danger semester_error"></span>
                            </div> 
                            <div class="mb-3">
                                <label class="form-label">Lecturer</label>
                                <select class="form-control form-control-lg" type="text" id="lecturer" name="lecturer">
                                    <option value="">Select Lecturer</option>
                                    @foreach ($lecturers as $lect)
                                        <option value="{{ $lect->id }}" {{ $course->lecturer_id == $lect->id ? " selected":"" }}>{{ ucfirst($lect->lecturer_fullname) }}</option>
                                    @endforeach
                                </select>
                                <span class="text-error text-danger semester_error"></span>
                            </div> 
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-lg btn-primary">Update Course</button>
                              
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    
</div>



@endsection

@section('scripts')
    <script src="{{ asset('school/previewfile.js') }}"></script>
   <script>
         $(function(){

    $('#editCourseForm').on('submit', function(e){
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
                    toastr.success(data.msg);
                    window.location = "{{ route('admin.ad.courses') }}";
                }
            }
        });
    })

          

})
   
   
   </script>
@endsection