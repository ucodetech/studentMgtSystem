@extends('layouts.adminapp')

@section('contents')
<div class="container-fluid p-0">

    @include('inc.title')
    <div class="row">
        <div class="col-xl-6 col-xxl-5 d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Students</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="users"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3" id="totStudents">0</h1>
                                <div class="mb-0">
                                    <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> <span id="totStudentsPerc"></span> </span>
                                    <span class="text-muted">Since last week</span>
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
                                            <i class="align-middle" data-feather="users"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3" id="totSchedules">0</h1>
                                <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> <span id="totSchedulesPerc"></span> </span>
                                    <span class="text-muted">Since last week</span>
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
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3" id="totActiveSchedules">0</h1>
                                <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i><span id="totActiveSchedulesPerc"></span> </span>
                                    <span class="text-muted">Since last week</span>
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
                                <h1 class="mt-1 mb-3 text-danger" id="totClosedSchedules">0</h1>
                                <div class="mb-0">
                                    <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> <span id="totClosedSchedulesPerc"></span> </span>
                                    <span class="text-muted">Since last week</span>
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

                    <h5 class="card-title mb-0">Recent Movement</h5>
                </div>
                <div class="card-body py-3">
                    <div class="chart chart-sm">
                        <canvas id="chartjs-dashboard-line"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
 

</div>

@endsection

@section('scripts')
    <script>
        $(function(){
            setInterval(() => {
                closeSchedule();
            }, 86400000);

            function closeSchedule(){
                let url = "{{ route('admin.ad.schedule.close') }}";
                let _token = "{{ csrf_token() }}";
                $.post(url, {_token:_token}, function(data){
                    toastr.info(data.msg);
                })
            }
                //get tot students, schedules , active schedules and closed schedules
                realTimeData();
            function realTimeData(){
                let url = "{{ route('admin.ad.realtime.data') }}";
                let _token = "{{ csrf_token() }}";
                $.post(url, {_token:_token}, function(data){
                   $('#totStudents').html(data.students_count);
                   $('#totSchedules').html(data.schedule_count);
                   $('#totActiveSchedules').html(data.schedule_active_count);
                   $('#totClosedSchedules').html(data.schedule_closed_count);

                   $('#totStudentsPerc').html("{{ N2P("+data.students_count+") }}");
                   $('#totSchedulesPerc').html("{{ N2P("+data.schedule_count+") }}");
                   $('#totActiveSchedulesPerc').html("{{ N2P("+data.schedule_active_count+") }}");
                   $('#totClosedSchedulesPerc').html("{{ N2P("+data.schedule_closed_count+") }}");


                })
            }






        })
    </script>
@endsection