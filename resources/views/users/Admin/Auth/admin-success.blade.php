@extends('layouts.authapp')

@section('auths')

<div class="card">
    <div class="card-body">
        <div class="m-sm-3">
           <h3 class="text-info text-center"><i class="fa fa-info-o"></i> You Registered a new Admin, 
            The admin will receive an email for verification in seconds</h3>
            <hr>
           <div class="d-flex justify-content-between">
            <a class="btn btn-outline-info" href="{{ route('admin.ad.superusers') }}">Back to superusers</a>
            <a class="btn btn-outline-primary" href="{{ route('admin.ad.register') }}">Add new superuser</a>
           </div>
        </div>
    </div>
</div>

@endsection