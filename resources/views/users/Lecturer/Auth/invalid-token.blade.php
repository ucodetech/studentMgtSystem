@extends('layouts.authapp')

@section('auths')

<div class="card">
    <div class="card-body">
        <div class="m-sm-3">
           <h3 class="text-info text-center"><i class="fa fa-info-o"></i>Invalid! Enter your registered email address to get a fresh token</h3>
           <hr>
           @include('inc.messages')
           <div class="clearfix"></div>
           <form action="{{ route('lecturer.lect.resend.token') }}" method="post">
            @csrf
            @method("POST")
            <div class="mb-3">
              <label for="" class="form-label">Emaill</label>
              <input type="email" name="lecturer_email" id="lecturer_email" class="form-control" placeholder="Entered your registered email address" value="{{ session()->has('registered_lecturer_email')? session()->get('registered_lecturer_email') : "" }}" aria-describedby="helpId">
              <small id="helpId" class="text-muted">Note: This must be your registered email address else you will be locked out completely</small>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-outline-info">Request new Link</button>
            </div>
           </form>
        </div>
    </div>
</div>

@endsection