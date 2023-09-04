@extends('layouts.lecturerapp')

@section('contents')
<div class="container-fluid p-0">

@include('inc.titlelect')
<div class="row">
    <div class="col-xl-6 col-xxl-5 d-flex">
        <div class="w-100">
            <h3 class="text-center text-info">Students who are currently attanding this class!</h3>
            <hr>
            <div class="row">
                @if ($ongoing != null)
                    {{-- loop --}}
                    @foreach ($ongoing as $item)
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h6 class="text-sm">{{ $item->user->name }}</h6>
                                            <h6 class="text-sm">{{ strtoupper($item->user->matric_no) }}</h6>
                                            <h6 class="text-sm">{{ $item->user->level }}</h6>
                                            IP: <span class="text-danger">{{ $item->user->ip_address }}</span>
                                            Mac: <span class="text-danger">{{ $item->user->mac_address }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <img src="{{ asset('storage/uploads/userProfile/'.$item->user->photo) }}" alt="" class="img-fluid rounded-circle">
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3"></h1>
                                    <div class="mb-0">
                                        <span class="text-success"> 
                                            Status: 
                                            @if ($item->user->last_login == carbonNow())
                                                <span class="badge badge-pill text-bg-success">
                                                     Online
                                                </span>
                                            @else
                                                <span class="badge badge-pill text-bg-warning">
                                                    {{ timeAgo($item->user->last_login) }}
                                                </span>
                                            @endif
                                            
                                           
                                        </span>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <button type="button" 
                                            data-student-id="{{ $item->user->id }}" 
                                            data-schedule-id="{{ $item->schedule_id }}" 
                                            id="markPresent"
                                            data-mode="1" 
                                            class="btn btn-info markPresent">Present</button>
        
                                            <button type="button" 
                                            data-student-id="{{ $item->user->id }}" 
                                            data-schedule-id="{{ $item->schedule_id }}" 
                                            id="markAbsent"
                                            data-mode="0"  
                                            class="btn btn-danger markAbsent">Absent</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
              
                {{-- //end loop --}}
                @else
                    <h4 class="text-center">No student has joined the class yet!</h4>
                @endif
            
              
                
            </div>
        </div>
    </div>

  
</div>




    </div>
@endsection

@section('scripts')
    <script>
       


        $(function() {
            


                $('body').on('click', '.markPresent', function(e){
                    e.preventDefault();
                    let url = "{{ route('lecturer.lect.mark.student') }}";
                    let mode = $(this).data('mode');
                    let student_id = $(this).data('student-id');
                    let schedule_id = $(this).data('schedule-id');
                    let _token = "{{ csrf_token() }}";
                    $.post(url, {mode:mode,student_id:student_id,schedule_id:schedule_id,_token:_token}, function(response){
                        toastr.info(response);
                        
                    })
                })           

                $('body').on('click', '.markAbsent', function(e){
                    e.preventDefault();
                    let url = "{{ route('lecturer.lect.mark.student') }}";
                    let mode = $(this).data('mode');
                    let student_id = $(this).data('student-id');
                    let schedule_id = $(this).data('schedule-id');
                    let _token = "{{ csrf_token() }}";
                    $.post(url, {mode:mode,student_id:student_id,schedule_id:schedule_id,_token:_token}, function(response){
                        toastr.danger(response);
                        
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
