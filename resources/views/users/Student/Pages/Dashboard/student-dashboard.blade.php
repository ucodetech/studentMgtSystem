@extends('layouts.studentapp')

@section('contents')
<div class="container-fluid p-0">

@include('inc.titlestud')
<div class="row">

    <div class="col-xl-6 col-xxl-7">
        <div class="card flex-fill w-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Running Class</h5>
            </div>
            <div class="card-body py-3">
                <div class="chart chart-sm" id="">
                    @if ($running)
                    <div class="d-flex">
                        <div class="container">
                            <h4>
                                Course Title: {{ $running->course->course_title }}
                            </h4>
                            <h5>
                                Course Code: {{ $running->course->course_code }} 
                            </h5>
                            <h5 class="text-warning">
                                Lecturer: {{ $running->course->lecturer->lecturer_fullname }} 
                            </h5>
                            <span>
                                Level: {{ $running->course->level }}
                            </span>
                            <div class="d-flex justify-content-between mt-4">
                                <span>Start Time: {{ formatTime($running->start_time) }}</span>
                                <span>End Time: {{ formatTime($running->end_time) }}</span>
                            </div>
                            <hr>
                            @if ($onging !=null)
                                @if ($running->attendance == 1)
                                    @if($attended == null)
                                        <button type="button" 
                                        data-id="{{ $running->id }}" 
                                        data-student-id="{{ student()->id }}" 
                                        data-schedule-id="{{ $running->id }}" 
                                        id="markPresent"
                                        data-mode="1"
                                        class="btn btn-outline-success btn-block">Mark Present</button>  
                                    @else
                                        <button type="button" id="openAttendaceBtn" class="btn btn-outline-warning btn-block">You have marked your attendance!</button>  
                                    @endif                              
                            @else
                                <button type="button" id="openAttendaceBtn" class="btn btn-outline-warning btn-block">The lecturer is yet to open attendance for this class or is taking attendance him/herself</button>
                            @endif
                            @else
                                <button type="button" data-student-id="{{ student()->id }}" data-id="{{ $running->id }}" id="joinClassBtn" class="btn btn-outline-info btn-block">Join Class</button> 
                            @endif
                            
                            <hr>
                            
                        </div>
                        <div class="fa-sp text-success">
                            <i class="fas fa-spinner fa-pulse fa-lg"></i>
                        </div>
                    </div>
                        @else
                        <h3 class="text-center">No running class</h3>
                    @endif
                </div>
            </div>
        </div>
        
        
    </div>
    <div class="col-xl-6 col-xxl-7">
        <div class="card flex-fill w-100">
            <div class="card-header">

                <h5 class="card-title mb-0">Upcoming Schedule</h5>
            </div>
            <div class="card-body py-3">
                <div class="chart chart-sm" id="calendar">

                </div>
            </div>
        </div>
</div>
</div>




    </div>
@endsection

@section('scripts')
    <script>
       


        $(function() {


            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: "{{ route('user.user.get.schedules.calendar') }}",
                displayEventTime: true,
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                }
            });

            $('body').on('click', '#markPresent', function(e){
                    e.preventDefault();
                    let url = "{{ route('user.user.mark.attendance') }}";
                    let mode = $(this).data('mode');
                    let student_id = $(this).data('student-id');
                    let schedule_id = $(this).data('schedule-id');
                    let _token = "{{ csrf_token() }}";
                    $.post(url, {mode:mode,student_id:student_id,schedule_id:schedule_id,_token:_token}, 
                    function(response){
                        toastr.info(response);
                        location.reload();
                    })
                })         


        $('body').on('click', '#joinClassBtn', function(e){
            e.preventDefault();
            let scheduleid = $(this).data('id');
            let userid = $(this).data('student-id');
            joinClass(scheduleid, userid);
            
        })

        function joinClass(scheduleid, userid){
            let url = "{{ route('user.user.start.class') }}";
            let _token = "{{ csrf_token() }}";
            Swal.fire({
                title: "Are you sure?",
                text: "You want to join the class!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes Join",
            }).then((result) => {
                if(result.isConfirmed){
                    $.post(url, {scheduleid:scheduleid, userid:userid,_token:_token}, function(data){
                        Swal.fire(
                            'Success',
                            data,
                            'success'
                        );
                        setTimeout(() => {
                        location.reload();
                        }, 4000);
                    })
                }
            })
            
        }


        })
    </script>
@endsection
