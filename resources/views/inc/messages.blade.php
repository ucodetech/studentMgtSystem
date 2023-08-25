@if($errors)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        
            <strong>Holy guacamole!</strong> {{ $error }}
        </div>
    @endforeach
@endif


@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

        <strong>Just you know!</strong> {{ session('info') }}
    </div>
@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

    <strong>Amazing!</strong> {{ session('success') }}
</div>
@endif

@if (session('fail'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

    <strong>Sorry!</strong> {{ session('fail') }}
</div>
@endif