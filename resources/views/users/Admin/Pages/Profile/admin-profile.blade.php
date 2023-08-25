@extends('layouts.adminapp')

@section('contents')
<div class="container-fluid p-0">

    @include('inc.title')

<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"> {{ userFirstName(admin()->admin_fullname) ."'s" }} Profile Details</h5>
            </div>
            <div class="card-body text-center">
                <div class="previewPhoto">
                    <label for="admin_photo" class="cursor-pointer" title="Click to select new photo">
                    <img src="{{ asset('storage/uploads/adminProfile/'. admin()->admin_photo) }}" alt="{{ userFirstName(admin()->admin_fullname) }}" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                    </label>
                </div>
                <form action="{{ route('admin.ad.update.photo') }}" method="POST" enctype="multipart/form-data" id="updateAdminPhotoForm">
                    @csrf
                    @method("POST")
                    <input type="file" name="admin_photo" id="admin_photo" class="d-none">
                    <button type="submit" class="btn btn-outline-info">Update</button>
                </form>
                <hr>
                <h5 class="card-title mb-0">{{ admin()->admin_fullname }}</h5>
                <div class="text-muted mb-2">{{ Str::ucfirst(admin()->admin_permission) }}</div>

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
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Messages</button>
                  </li>
                </ul>
                
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab"> home </div>
                  <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab"> profile </div>
                  <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab"> messages </div>
                </div>

               
            </div>
        </div>
    </div>
</div>
    
</div>

@endsection

@section('scripts')
    {{-- <script src="{{ asset('school/previewfile.js') }}"></script> --}}
   <script>
    function readURL(input, imageholder,labelname,imgclass, imgwidth=false, imgheight=false){
        if(input.files && input.files[0]){
            let reader = new FileReader();
            reader.onload = function(e){
                $(imageholder).html('<label for="'+labelname+'" class="cursor-pointer" title="click to select new photo"><img src="'+e.target.result+'" class="'+imgclass+'" width="'+imgwidth+'" height="'+imgheight+'"></label>');
            }
            reader.readAsDataURL(input.files[0]);
            }
        }
        function readURLProd(input){
            if(input.files && input.files[0]){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewPhoto').html('<label for="product_file"><img src="'+e.target.result+'" alt="product image" class="img-fluid" width="100px" height="100px"></label>');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
         $(function(){

            $('#admin_photo').on('change',function(e){
                e.preventDefault();
                // input, imageholder,labelname,imgclass, imgwidth=false, imgheight=false
                let imageholder = "#previewPhoto";
                let labelname = "admin_photo";
                let imgclass = "img-fluid rounded-circle mb-2";
                let imgwidth = "128";
                let imgheight = "128";
                // readURL(this, imageholder,labelname,imgclass,imgwidth,imgheight);
                readURLProd(this);
            })

         

        })
   </script>
@endsection