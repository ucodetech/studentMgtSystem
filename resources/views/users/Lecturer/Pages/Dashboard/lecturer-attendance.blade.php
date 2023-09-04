@extends('layouts.lecturerapp')

@section('contents')
<div class="container-fluid p-0">

@include('inc.titlelect')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="clearfix"></div>
                <div class="">
                    <h4 class="text-center mb-3">Fetch attandance by schedules</h4>
                        
                        <div class="mb-3">
                            <label class="form-label">Schedule</label>
                           <select name="schedule_id" id="schedule_id" class="form-select">
                            <option value="">Select schedule</option>
                            @foreach ($schedules as $item)
                                <option value="{{ $item->id }}">{{ $item->course->course_title }} -Date : {{ pretty_dates($item->day_of_week) }}</option>
                            @endforeach
                           </select>
                            <span class="text-error text-danger schedule_id_error"></span>
                        </div>
                       
                      
                </div>
            </div>
        </div>
        
    </div>
    <div class="col-md-8">
        <div class="table-responsive">
        
            <table class="table table-hover table-condensed table-striped" id="attendanceTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Code</th>
                        <th>Student Name</th>
                        <th>Matric No</th>
                        <th>Level</th>
                        <th>Status</th>
                       
                    </tr>
                </thead>
                <tbody id="showResult">
                    
                </tbody>
                
            </table>
        </div>
    </div>
</div>




    </div>
@endsection

@section('scripts')
    <script>
       


        $(function() {


                $('#schedule_id').on('change', function(e){
                    e.preventDefault();
                    let url = "{{ route('lecturer.lect.fetch.attendance') }}";
                    let schedule_id = $(this).val();
                    let _token = "{{ csrf_token() }}";
                    $.post(url, {schedule_id:schedule_id,_token:_token}, function(response){
                         console.log(response.data);       
                        $('#showResult').html(response.data);
                        
                        
                    })
                })           

              

            $('#attendanceTable').DataTable({
                    processing:true,
                    info:true,
                    pageLength:5,
                    buttons: ['copy','print','excel', 'csv', 'pdf']
            }).buttons().container().appendTo('#attendanceTable_wrapper .col-md-6:eq(0)');
            // }
            


        })
    </script>
@endsection
