@extends('layouts.authapp')

@section('auths')
<div class="text-center mt-4">
    <h1 class="h2">Welcome back!</h1>
    <p class="lead">
        Sign in to your account to continue
    </p>
    @include('inc.messages')
    <div class="clearfix"></div>
</div>
<div class="card">
    <div class="card-body">
        <div class="m-sm-3">
            <form action="{{ route('user.user.process.login') }}" method="post">
                @csrf
                @method("POST")
                <div class="mb-3">
                    <label class="form-label">Matric No</label>
                    <input class="form-control form-control-lg" type="text" id="matric_no" name="matric_no" value="{{ old('matric_no') }}" placeholder="Enter your matric number" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control form-control-lg" type="password" name="password" placeholder="Enter your password" />
                </div>
                <div>
                    <div class="form-check align-items-center">
                        <input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me" name="remember-me">
                        <label class="form-check-label text-small" for="customControlInline">Remember me</label>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-lg btn-primary">Sign in</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="text-center mb-3">
    Don't have an account? <a href="{{ route('user.user.register') }}">Sign up</a>
</div>
@endsection