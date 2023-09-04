@extends('layouts.lecturerapp')

@section('contents')
<div class="container-fluid p-0">

    @include('inc.title')
@include('inc.messages')
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"> {{ userFirstName(lecturer()->lecturer_fullname) ."'s" }} Profile Details</h5>
            </div>
            <div class="card-body text-center">
                <div id="previewPhoto">
                    <label for="lecturer_photo" class="cursor-pointer" title="Click to select new photo">
                    <img src="{{ asset('storage/uploads/lecturerProfile/'. lecturer()->lecturer_photo) }}" alt="{{ userFirstName(lecturer()->lecturer_fullname) }}" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                    </label>
                </div>
                <form action="{{ route('lecturer.lect.update.photo') }}" method="POST" enctype="multipart/form-data" id="updateLecturerPhotoForm">
                    @csrf
                    @method("POST")
                    <input type="file" name="lecturer_photo" id="lecturer_photo" class="d-none">
                    <button type="submit" class="btn btn-outline-info">Update</button>
                </form>
                <hr>
                <h5 class="card-title mb-0">{{ lecturer()->lecturer_fullname }}</h5>
                <div class="text-muted mb-2">Lecturer</div>

                {{-- <div>
                    <a class="btn btn-primary btn-sm" href="#">Follow</a>
                    <a class="btn btn-primary btn-sm" href="#"><span data-feather="message-square"></span> Message</a>
                </div> --}}
            </div>
           
            <hr class="my-0" />
            <div class="card-body">
                <h5 class="h6 card-title">Social Handles</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1"><a href="#">staciehall.co</a></li>
                    <li class="mb-1"><a href="#">Twitter</a></li>
                    <li class="mb-1"><a href="#">Facebook</a></li>
                    <li class="mb-1"><a href="#">Instagram</a></li>
                    <li class="mb-1"><a href="#">LinkedIn</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header">

                <h5 class="card-title mb-0">Activities</h5>
            </div>
            <div class="card-body h-100">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Update Details</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Messages</button>
                  </li>
                </ul>
                
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane active" id="details" role="tabpanel" aria-labelledby="details-tab"> 

                    <div class="container mt-3">
                        <form action="{{ route('lecturer.lect.update.details') }}" method="POST">
                            @csrf
                            @method("POST")
                            <div class="mb-3 row">
                                <label for="lecturer_fullname" class="col-4 col-form-label">Name</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" name="lecturer_fullname" id="lecturer_fullname" placeholder="fullname" value="{{ lecturer()->lecturer_fullname }}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="lecturer_email" class="col-4 col-form-label">Email</label>
                                <div class="col-8">
                                    <input type="email" class="form-control" name="lecturer_email" id="lecturer_email" placeholder="email" value="{{ lecturer()->lecturer_email }}" disabled>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="lecturer_tel" class="col-4 col-form-label">Phone Number</label>
                                <div class="col-8">
                                    <input type="tel" class="form-control" name="lecturer_tel" id="lecturer_tel" placeholder="tel phone" value="{{ lecturer()->lecturer_tel }}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="lecturer_department" class="col-4 col-form-label">Department</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" name="" id="" placeholder="tel phone" value="{{ lecturer()->lecturer_department }}" @readonly(true)>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="offset-sm-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>

                        <hr>
                        <p class="text-warning">
                            <i class="fa fa-warning"></i> &nbsp; Note if you change your password, the system will log you out automatically! for you to relogin!
                        </p>
                        <form action="{{ route('lecturer.lect.update.password') }}" method="POST">
                            @csrf
                            @method("POST")
                          
                            <div class="mb-3 row">
                                <label for="password" class="col-4 col-form-label">Current Password</label>
                                <div class="col-8">
                                    <input type="password" class="form-control" name="current_password" id="current_password" placeholder="current_password">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="new_password" class="col-4 col-form-label">Current Password</label>
                                <div class="col-8">
                                    <input type="password" class="form-control" name="new_password" id="new_password" placeholder="new_password">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="verify_new_password" class="col-4 col-form-label">Current Password</label>
                                <div class="col-8">
                                    <input type="password" class="form-control" name="verify_new_password" id="verify_new_password" placeholder="verify_new_password">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="offset-sm-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                 
                
                  <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab"> messages </div>
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

            $('#lecturer_photo').on('change',function(e){
                e.preventDefault();
                // input, imageholder,labelname,imgclass, imgwidth=false, imgheight=false
                let imageholder = "#previewPhoto";
                let labelname = "lecturer_photo";
                let imgclass = "img-fluid rounded-circle mb-2";
                let imgwidth = "128";
                let imgheight = "128";
                readURL(this, imageholder,labelname,imgclass,imgwidth,imgheight);
               
            })

         

        })
   </script>
@endsection