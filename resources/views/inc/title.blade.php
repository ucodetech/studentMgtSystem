@php
$title = basename($_SERVER['PHP_SELF'], '.blade.php');
$title = explode('-', $title);
$title = Str::ucfirst($title[1]);
@endphp

<h1 class="h3 mb-3">
    <strong>
        {{ loggedAs() }}
     
    </strong> 
    {{ $title }}
</h1>
