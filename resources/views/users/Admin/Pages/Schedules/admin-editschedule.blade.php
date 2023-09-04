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
                        <form action="{{ route('admin.ad.schedule.update') }}" method="POST" id="editScheduleForm">
                            @csrf
                            @method("POST")
                           <div class="mb-3">
                                <input type="hidden" class="form-control" name="schedule_id" id="schedule_id" value="{{ $schedule->id }}" placeholder="">
                                <input type="hidden" class="form-control" name="schedule_course_id" id="schedule_course_id" value="{{ $schedule->course_id }}" placeholder="">
                                <input type="hidden" class="form-control" name="scheduled_classroom_id" id="scheduled_classroom_id" value="{{ $schedule->classroom_id }}" placeholder="">
                                <input type="hidden" class="form-control" name="scheduled_starttime" id="scheduled_starttime" value="{{ $schedule->end_time }}" placeholder="">
                                <input type="hidden" class="form-control" name="scheduled_starttime" id="scheduled_starttime" value="{{ $schedule->start_time }}" placeholder="">

                            </div>
                            <div class="mb-4">
                                <label for="day_of_week" class="form-label">Day Of the Week</label>
                                <input type="date" name="dayOfWeek" id="dayOfWeek" class="form-control" value="{{ $schedule->day_of_week }}">
                                <span class="text-error text-danger dayOfWeek_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course</label>
                                <select class="form-select" name="course" id="course">
                                    <option value="">Select Course</option>
                                    @foreach ($courses as $key=>$course)
                                        <option value="{{ $course->id }},{{ $course->lecturer_id }},{{ $course->level }}" {{ $schedule->course_id == $course->id ? " selected" : "" }}>{{ Str::ucfirst($course->course_title) }} -- {{ $course->level }} </option>
                                    @endforeach
                                  
                                </select>
                                <span class="text-error text-danger course_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Classroom</label>
                                <select class="form-select" name="classroom" id="classroom">
                                    <option value="">Select Classroom</option>
                                    @foreach ($classrooms as $key=>$classroom)
                                        <option value="{{ $classroom->id }}" {{ $schedule->classroom_id == $classroom->id ? " selected" : "" }}>{{ Str::ucfirst($classroom->class_name) }}</option>
                                    @endforeach
                                  
                                </select>
                                <span class="text-error text-danger classroom_error"></span>
                            </div>
                           
                            <div class="mb-3">
                                <label class="form-label">Start Time</label>
                                <input class="form-control form-control-lg" type="datetime-local" id="start_time" name="start_time" value="{{ $schedule->start_time }}">
                                <span class="text-error text-danger start_time_error"></span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">End Time</label>
                                <input class="form-control form-control-lg" type="datetime-local" id="end_time" name="end_time" value="{{ $schedule->end_time }}">
                                <span class="text-error text-danger end_time_error"></span>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                <a href="{{ route('admin.ad.schedules') }}" class="btn btn-outline-warning">Cancel</a>
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

    $('#editScheduleForm').on('submit', function(e){
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
                }else if(data.code == 2){
                    toastr.error(data.err);
                    // window.location = "{{ route('admin.ad.schedules') }}";
                }else{
                    toastr.success(data.msg);
                    window.location = "{{ route('admin.ad.schedules') }}";
                }
            }
        });
    })

          

})
   
   
   </script>
@endsection