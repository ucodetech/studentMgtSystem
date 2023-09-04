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
                        <form action="{{ route('admin.ad.class.room.add') }}" method="POST" id="addclassRoomForm">
                            @csrf
                            @method("POST")
                            <div class="mb-3">
                                <label class="form-label">Class Room</label>
                                <input class="form-control form-control-lg" type="text" id="class_name" name="class_name" placeholder="Enter your name" value="{{ old('class_name') }}"/>
                                <span class="text-error text-danger class_name_error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <textarea class="form-control form-control-lg" id="class_location" name="class_location" placeholder="" >{{ old('class_location') }}</textarea>
                                <span class="text-error text-danger class_location_error"></span>
                            </div>
                           
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-lg btn-primary">Add Classroom</button>
                              
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-md-8">
            <div class="table-responsive">
            
                <table class="table table-hover table-condensed table-striped" id="classroomTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Class Room</th>
                            <th>Location</th>
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

           $('#classroomTable').DataTable({
                processing:true,
                info:true,
                pageLength:5,
                ajax: "{{ route('admin.ad.list.classroom') }}",
                columns: [
                    {data:"DT_RowIndex"},
                    {data:"class_name"},
                    {data:"class_location"},
                    {data:"action"}
                ],
               
      });

      $('#addclassRoomForm').on('submit', function(e){
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
                    $('#addclassRoomForm')[0].reset();
                    $('#classroomTable').DataTable().ajax.reload(null,false);
                    toastr.success(data.msg);
                }
            }
        });
    })

    $('body').on('click', '.deleteClassBtn', function(e){
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
                        $('#classroomTable').DataTable().ajax.reload(null,false);
                })
            }
        });

        })


        $('body').on('click', '.editClassBtn', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let url = $(this).data('url');
            let _token = "{{ csrf_token() }}";
            $.get(url, {id:id, _token:_token}, function(data){
                $('#editClassModal').modal('show');
                $('#showRoomEditForm').html(data);
            })
        })

        $('#editClassRoomForm').on('submit', function(e){
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
                    $('#editClassModal').modal("hide");
                    $('#classroomTable').DataTable().ajax.reload(null,false);
                    toastr.success(data.msg);
                }
            }
        });
    })

          

})
   
   
   </script>
@endsection