@extends('layouts.authapp')

@section('auths')
<div class="text-center mt-4">
    <h1 class="h2">Admin Panel</h1>
    <p class="lead text-info">
        New Admin
    </p>
</div>

<div class="card">
    <div class="card-body">
        @include('inc.messages')
        <div class="clearfix"></div>
        <div class="m-sm-3">
            <form action="{{ route('admin.ad.process.register') }}" method="POST">
                @csrf
                @method("POST")
                <div class="mb-3">
                    <label class="form-label">Full name</label>
                    <input class="form-control form-control-lg" type="text" id="admin_fullname" name="admin_fullname" placeholder="Enter your name" value="{{ old('admin_fullname') }}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control form-control-lg" type="email" id="admin_email" name="admin_email" placeholder="Enter your email" value="{{ old('admin_email') }}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone No</label>
                    <input class="form-control form-control-lg" type="tel" id="admin_tel" name="admin_tel" placeholder="Enter your phone no" value="{{ old('admin_tel') }}" />
                </div>
                <div class="mb-3">
                    <label for="admin_permission" class="form-label">Permission</label>
                    <select name="admin_permission" id="admin_permission" class="form-select">
                        <option value="">Select permission</option>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->permission }}">{{ Str::ucfirst($permission->permission) }}</option>
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
                </div>
            </form>
        </div>
    </div>
</div>
<div class="text-center mb-3">
    Already have account? <a href="{{ route('admin.ad.login') }}">Log In</a>
</div>
@endsection