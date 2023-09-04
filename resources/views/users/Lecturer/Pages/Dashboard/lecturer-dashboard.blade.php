@extends('layouts.lecturerapp')

@section('contents')
<div class="container-fluid p-0">

@include('inc.titlelect')
<div class="row">
    <div class="col-xl-6 col-xxl-5 d-flex">
        <div class="w-100">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">No. of Courses Assigned</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="book"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ count($courses) }}</h1>
                            <div class="mb-0">
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> <span
                                        id="">{{ N2P(count($courses)) }}</span> </span>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Schedules</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="clock"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3" id="">{{ count($schedules) }}</h1>
                            <div class="mb-0">
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> <span
                                        id="">{{ N2P(count($schedules)) }}</span> </span>
                                <span class="text-muted"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Active Schedules</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="clock"></i>
                                    </div>

                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $countactive }}</h1>
                            <div class="mb-0">
                                <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i><span
                                        id="">{{ N2P($countactive) }}</span> </span>
                                <span class="text-muted"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Closed Schedules</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="clock"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3 text-danger">{{ $closedactive }}</h1>
                            <div class="mb-0">
                                <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> <span
                                        id="">{{ N2P($closedactive) }}</span> </span>
                                <span class="text-muted"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">Schedules</h5>
                                </div>

                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="align-middle" data-feather="clock"></i>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table table-stripped border-0 table-hover table-condensed" id="lectschedulesTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Course Code</th>
                                                <th>Level</th>
                                                <th>Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($schedules)> 0)
                                            @foreach ($schedules as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->course->course_code }}</td>
                                                <td>{{ $item->course->level }}</td>
                                                <td>{{ pretty_dates($item->day_of_week) }}</td>
                                                <td>{{ formatTime($item->start_time) }}</td>
                                                <td>{{ formatTime($item->end_time) }}</td>
                                                <td>
                                                    @if ($item->status == "closed")
                                                     <span class="badge badge-btn text-bg-danger">Closed</span>
                                                    @else
                                                        {{-- @if ($item->day_of_week == carbonToday()) --}}
                                                        <div class="btn-group">
                                                            @if ($item->start == 1)
                                                                <button type="button" class="btn btn-sm btn-outline-info" title="Class is running" ><i class="fas fa-spinner fa-pulse"></i>
                                                                </button>
                                                            @else
                                                                 <button type="button" class="btn btn-sm btn-outline-success startClassBtn" title="Start Class" data-id="{{ $item->id }}"><i class="fa fa-play"></i></button>
                                                            @endif
                                                           

                                                            <button type="button" class="btn btn-sm btn-outline-danger closeScheduleBtn" data-id="{{ $item->id }}" title="End Class"><i class="fa fa-stop"></i></button>
                                                        </div>
                                                        {{-- @else
                                                            <span class="badge badge-btn text-bg-warning">Upcoming</span>
                                                        @endif --}}
                                                    @endif
                                                   
                                                    
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                                No class for you yet!
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

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
                            <span>
                                Level: {{ $running->course->level }}
                            </span>
                            <div class="d-flex justify-content-between">
                                <span>Start Time: {{ formatTime($running->start_time) }}</span>
                                <span>End Time: {{ formatTime($running->end_time) }}</span>
                            </div>  
                            <hr>
                            @if ($running->attendance == 1 || $running->attendance == 2)
                                 <button type="button" data-id="{{ $running->id }}" id="closeAttendaceBtn" class="btn btn-outline-danger btn-block">Close Attendance</button>
                                @if ($running->attendance == 2)
                                    <a href="{{ route('lecturer.lect.logged.in.students',hash('sha256', Str::random(60)).'-'.$running->id) }}" title="You have opened attendance and will mark it yourself" data-id="{{ $running->id }}" id="viewLoggedInStudents" class="btn btn-outline-info btn-block">View Logged In students</a>
                                @else
                                    <a href="{{ route('lecturer.lect.attendance') }}" title="Attendance is open for students" class="btn btn-outline-primary btn-block">View Attendance</a>
                                @endif
                                
                            @else
                                <button type="button" data-id="{{ $running->id }}" data-mode="1" id="openAttendaceBtn" class="btn btn-outline-secondary btn-block">Open Attendance For Students</button>
                                {{-- updates the attendace column and sets it to 2, which means the lecturer is taking attendance himself --}}
                                 <button type="button" data-id="{{ $running->id }}" data-mode="2" id="takeAttendaceBtn" class="btn btn-outline-warning btn-block">Take Attendance Yourself</button>
                            @endif
                        </div>
                        <div class="fa-sp text-success">
                            <i class="fas fa-spinner fa-pulse fa-lg"></i>
                        </div>
                    </div>
                        
                    @endif
                </div>
            </div>
        </div>
        <hr>
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


                $('body').on('click', '#openAttendaceBtn', function(e){
                    e.preventDefault();
                    let url = "{{ route('lecturer.lect.toggle.attendance') }}";
                    let mode = $(this).data('mode');
                    let id = $(this).data('id')
                    let _token = "{{ csrf_token() }}";
                    $.post(url, {mode:mode,id:id,_token:_token}, function(response){
                        toastr.info(response);
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    })
                })           

                $('body').on('click', '#takeAttendaceBtn', function(e){
                    e.preventDefault();
                    let url = "{{ route('lecturer.lect.toggle.attendance') }}";
                    let mode = $(this).data('mode');
                    let id = $(this).data('id')
                    let _token = "{{ csrf_token() }}";
                    $.post(url, {mode:mode,id:id,_token:_token}, function(response){
                        toastr.info(response);
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    })
                })


        $('body').on('click', '#closeAttendaceBtn', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let mode = 0;
            let url = "{{ route('lecturer.lect.toggle.attendance') }}";
            let _token = "{{ csrf_token() }}";
            Swal.fire({
                title: "Are you sure?",
                text: "Attendance will be closed!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes Close it",
            }).then((result) => {
                if(result.isConfirmed){
                    $.post(url, {id:id,mode:mode,_token:_token}, function(data){
                        Swal.fire(
                            'Success',
                            data,
                            'success'
                        );
                        location.reload();

                    })
                }
            })
                    
        })

            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: "{{ route('lecturer.lect.get.schedules.calendar') }}",
                displayEventTime: true,
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                }
            });
            
            setInterval(() => {
                closepreviousSchedule();
            }, 846000);
            function closepreviousSchedule() {
                let url = "{{ route('lecturer.lect.schedule.close.previous') }}";
                let _token = "{{ csrf_token() }}";
                $.post(url, {
                    _token: _token
                }, function(data) {
                    toastr.info(data);
                })
            }

            $('#lectschedulesTable').DataTable({
                    processing:true,
                    info:true
            });
            // }
            


        $('body').on('click', '.startClassBtn', function(e){
            e.preventDefault();
            let scheduleid = $(this).data('id');
            startClass(scheduleid);
            
        })

function startClass(scheduleid){
    let url = "{{ route('lecturer.lect.start.class') }}";
    let _token = "{{ csrf_token() }}";
    Swal.fire({
        title: "Are you sure?",
        text: "Class will start!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes Start it",
    }).then((result) => {
        if(result.isConfirmed){
            $.post(url, {scheduleid:scheduleid,_token:_token}, function(data){
                Swal.fire(
                    'Success',
                     data,
                    'success'
                );
                location.reload();

            })
        }
    })
    
}




$('body').on('click', '.closeScheduleBtn', function(e){
    e.preventDefault();
    let scheduleid = $(this).data('id');
    closeSchedule(scheduleid);
})

function closeSchedule(scheduleid){
    let url = "{{ route('lecturer.lect.schedule.close') }}";
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
                location.reload();

            })
        }
    })
    
}
autoCloseClass();
function autoCloseClass(){
    let _token = "{{ csrf_token() }}";
    let data = "autoCloseClass";
    let url = "{{ route('lecturer.lect.auto.close.class') }}";
    $.post(url, {data:data, _token:_token}, function(response){
        if(response != false){
            toastr.info(response);
        }
    })
}


        })
    </script>
@endsection
