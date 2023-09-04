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
                        <form action="{{ route('admin.ad.schedule.add') }}" method="POST" id="addScheduleForm">
                            @csrf
                            @method("POST")
                            <div class="mb-4">
                                <small class="text-info text-justify">Note: The system automatically selects today as schedule date, you can change it if you want to schedule class for another day!</small> 
                                <hr>
                                <label for="day_of_week" class="form-label">Day Of the Week</label>
                               <input type="date" name="dayOfWeek" id="dayOfWeek" class="form-control" value="{{ carbonToday() }}">
                                {{-- <select class="form-select" name="dayOfWeek" id="dayOfWeek">
                                    <option value="">Select Day Of The Week</option>
                                    @foreach ($days as $key=>$day)
                                        <option value="{{ ucfirst($day) }}" {{ Str::ucfirst($day) == carbonNow()->shortDayName ?  " selected" : "" }}>{{ Str::ucfirst($day) }}</option>
                                    @endforeach
                                  
                                </select> --}}
                                <span class="text-error text-danger dayOfWeek_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course</label>
                                <select class="form-select course" name="course" id="course">
                                    <option value="">Select Course</option>
                                    @foreach ($courses as $key=>$course)
                                        <option value="{{ $course->id }},{{ $course->lecturer->id }},{{ $course->level }}">{{ Str::ucfirst($course->course_title) }} -- {{ $course->level }} </option>
                                    @endforeach
                                  
                                </select>
                                <span class="text-error text-danger course_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Classroom</label>
                                <select class="form-select" name="classroom" id="classroom">
                                    <option value="">Select Classroom</option>
                                    @foreach ($classrooms as $key=>$classroom)
                                        <option value="{{ $classroom->id }}">{{ Str::ucfirst($classroom->class_name) }}</option>
                                    @endforeach
                                  
                                </select>
                                <span class="text-error text-danger classroom_error"></span>
                            </div>
                         
                            <div class="mb-3">
                                <label class="form-label">Start Time</label>
                                <input class="form-control form-control-lg" type="datetime-local" id="start_time" name="start_time">
                                <span class="text-error text-danger start_time_error"></span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">End Time</label>
                                <input class="form-control form-control-lg" type="datetime-local" id="end_time" name="end_time">
                                <span class="text-error text-danger end_time_error"></span>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-lg btn-primary">Schedule</button>
                              
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-md-8">
            <div class="table-responsive">
            
                <table class="table table-hover table-condensed table-striped" id="scheduleTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Status</th>
                            <th>Lecturer</th>
                            <th>Course Code</th>
                            <th>Level</th>
                            <th>Classroom</th>
                            <th>Day Of Week</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                           
                        </tr>
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>
    
</div>


@endsection

@section('scripts')
    <script src="{{ asset('school/previewfile.js') }}"></script>
   <script>
         $(function(){

           $('#scheduleTable').DataTable({
                processing:true,
                info:true,
                pageLength:5,
                ajax: "{{ route('admin.ad.list.schedules') }}",
                columns: [
                    {data:"DT_RowIndex"},
                    {data:"status"},
                    {data:"lecturer"},
                    {data:"course_code"},
                    {data:"level"},
                    {data:"classroom_name"},
                    {data:"day_of_week"},
                    {data:'created_at'},
                    {data:"start_time"},
                    {data:"end_time"},
                    {data:"action"}
                ],
               
      });

 

      $('#addScheduleForm').on('submit', function(e){
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
                    toastr.warning(data.err)
                }
                else{
                    $('#addScheduleForm')[0].reset();
                    $('#scheduleTable').DataTable().ajax.reload(null,false);
                    toastr.success(data.msg);
                }
            }
        });
    })

    $('body').on('click', '.deleteScheduleBtn', function(e){
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
                        $('#scheduleTable').DataTable().ajax.reload(null,false);
                })
            }
        });

        })

$('body').on('click', '.closeScheduleBtn', function(e){
    e.preventDefault();
    let scheduleid = $(this).data('id');
    closeSchedule(scheduleid);
})

function closeSchedule(scheduleid){
    let url = "{{ route('admin.ad.schedule.close') }}";
    let _token = "{{ csrf_token() }}";
    Swal.fire({
        title: "Are you sure?",
        text: "You want to end this schedule!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes End it",
    }).then((result) => {
        if(result.isConfirmed){
            $.post(url, {scheduleid:scheduleid,_token:_token}, function(data){
                Swal.fire(
                    'Success',
                     data,
                    'success'
                );
                $('#scheduleTable').DataTable().ajax.reload(null,false);

            })
        }
    })
    
}

          

})
   
   
   </script>
@endsection