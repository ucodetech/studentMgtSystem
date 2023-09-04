@extends('layouts.authapp')

@section('auths')
<div class="text-center mt-4">
    <h1 class="h2">Student Panel</h1>
    <p class="lead text-info">
        New Student
        
    </p>
</div>

<div class="card">
    <div class="card-body">
        @include('inc.messages')
        <div class="clearfix"></div>
        <div class="m-sm-3">
            <form action="{{ route('user.user.process.register') }}" method="POST">
                @csrf
                @method("POST")
                <div class="mb-3">
                    <label class="form-label">Full name</label>
                    <input class="form-control form-control-lg" type="text" id="name" name="name" placeholder="Enter your name" value="{{ old('name') }}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control form-control-lg" type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone No</label>
                    <input class="form-control form-control-lg" type="tel" id="phone_no" name="phone_no" placeholder="Enter your phone no" value="{{ old('phone_no') }}" maxlength="11" minlength="11"  />
                </div>
                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-select">
                        <option value="">Select department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->department_name }}" {{ $department->department_name == "COMPUTER SCIENCE" ? " selected":"" }}>{{ Str::ucfirst($department->department_name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Matric No</label>
                    <input class="form-control form-control-lg" type="text" id="matric_no" name="matric_no" placeholder="Enter your phone no" value="{{ old('matric_no') }}" />
                </div>
                <div class="mb-3">
                    <label for="level" class="form-label">Level</label>
                    <select name="level" id="level" class="form-select">
                        <option value="">Select level</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level }}">{{ Str::ucfirst($level) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control form-control-lg" type="password" name="password" id="password" placeholder="Enter password" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Comfirm Password</label>
                    <input class="form-control form-control-lg" type="password" name="comfirm_password" id="comfirm_password" placeholder="Verify password" />
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-lg btn-primary">Sign up</button>
                    <div class="text-center mb-3">
                        Already have an acount? <a href="{{ route('user.user.login') }}">login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection