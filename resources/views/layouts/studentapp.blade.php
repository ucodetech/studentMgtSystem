<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <meta name="csrf-token" content="{{ csrf_token() }}">


	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/" />
    @php
        $title = basename($_SERVER['PHP_SELF'], '.blade.php');
        $title = explode('-', $title);
        $title = Str::ucfirst($title[1]);
    @endphp

	<title>{{ $title }}-{{ Config::get('app.name', 'Student') }}</title>
{{-- bootstrap --}}
	 <!-- Bootstrap CSS v5.2.1 -->
	 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
	 integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- FontAwesome 6.2.0 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- datatables --}}
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css' integrity='sha512-BMbq2It2D3J17/C7aRklzOODG1IQ3+MHw3ifzBHMBwGO/0yUqYmsStgBjI0z5EYlaDEFnvYV7gNYdD3vFLRKsA==' crossorigin='anonymous'/>
    {{-- toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- sweetalert --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.26/sweetalert2.min.css" integrity="sha512-IScV5kvJo+TIPbxENerxZcEpu9VrLUGh1qYWv6Z9aylhxWE4k4Fch3CHl0IYYmN+jrnWQBPlpoTVoWfSMakoKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- summernote --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" integrity="sha512-ngQ4IGzHQ3s/Hh8kMyG4FC74wzitukRMIcTOoKT3EyzFZCILOPF0twiXOQn75eDINUfKBYmzYn2AA8DkAk8veQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- site css --}}
	<link href="{{ asset('general_asset/assets/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @vite(['resources/js/app.js'])

</head>

<body>
	<div class="wrapper">
	
			@include('inc.adminnavs')

			<main class="content">
                @yield('contents')
            </main>

<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-6 text-start">
                <p class="mb-0">
                    <a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>AdminKit</strong></a> - <a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>Bootstrap Admin Template</strong></a>								&copy;
                </p>
            </div>
            <div class="col-6 text-end">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a class="text-muted" href="https://adminkit.io/" target="_blank">Support</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="https://adminkit.io/" target="_blank">Help Center</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="https://adminkit.io/" target="_blank">Privacy</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="https://adminkit.io/" target="_blank">Terms</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</div>
</div>
  {{-- scripts --}}
 <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js' integrity='sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==' crossorigin='anonymous'></script>
  <!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
  integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>

  <!-- (Optional) Use CSS or JS implementation -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"
        integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- datatables --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js" integrity="sha512-F0E+jKGaUC90odiinxkfeS3zm9uUT1/lpusNtgXboaMdA3QFMUez0pBmAeXGXtGxoGZg3bLmrkSkbK1quua4/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- sweetalert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.26/sweetalert2.min.js" integrity="sha512-BIHdMyxdl8bg4QOZYwJUivf6MTa97s/cfN7miqW4DLBIhIDgQ6TFjmWXvtvtBFu/Qrt1LIdGcQ2XqM56Vj1RIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- summernote --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('general_asset/assets/js/app.js') }}"></script>
<script>
    toastr.options.preventDuplicates = true;
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content');
        }
    });
</script>

@yield('scripts')

<script>
document.addEventListener("DOMContentLoaded", function() {
var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
var gradient = ctx.createLinearGradient(0, 0, 0, 225);
gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
gradient.addColorStop(1, "rgba(215, 227, 244, 0)");
// Line chart
new Chart(document.getElementById("chartjs-dashboard-line"), {
    type: "line",
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Sales ($)",
            fill: true,
            backgroundColor: gradient,
            borderColor: window.theme.primary,
            data: [
                2115,
                1562,
                1584,
                1892,
                1587,
                1923,
                2566,
                2448,
                2805,
                3438,
                2917,
                3327
            ]
        }]
    },
    options: {
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        tooltips: {
            intersect: false
        },
        hover: {
            intersect: true
        },
        plugins: {
            filler: {
                propagate: false
            }
        },
        scales: {
            xAxes: [{
                reverse: true,
                gridLines: {
                    color: "rgba(0,0,0,0.0)"
                }
            }],
            yAxes: [{
                ticks: {
                    stepSize: 1000
                },
                display: true,
                borderDash: [3, 3],
                gridLines: {
                    color: "rgba(0,0,0,0.0)"
                }
            }]
        }
    }
});
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
// Pie chart
new Chart(document.getElementById("chartjs-dashboard-pie"), {
    type: "pie",
    data: {
        labels: ["Chrome", "Firefox", "IE"],
        datasets: [{
            data: [4306, 3801, 1689],
            backgroundColor: [
                window.theme.primary,
                window.theme.warning,
                window.theme.danger
            ],
            borderWidth: 5
        }]
    },
    options: {
        responsive: !window.MSInputMethodContext,
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        cutoutPercentage: 75
    }
});
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
// Bar chart
new Chart(document.getElementById("chartjs-dashboard-bar"), {
    type: "bar",
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "This year",
            backgroundColor: window.theme.primary,
            borderColor: window.theme.primary,
            hoverBackgroundColor: window.theme.primary,
            hoverBorderColor: window.theme.primary,
            data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
            barPercentage: .75,
            categoryPercentage: .5
        }]
    },
    options: {
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                gridLines: {
                    display: false
                },
                stacked: false,
                ticks: {
                    stepSize: 20
                }
            }],
            xAxes: [{
                stacked: false,
                gridLines: {
                    color: "transparent"
                }
            }]
        }
    }
});
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
var markers = [{
        coords: [31.230391, 121.473701],
        name: "Shanghai"
    },
    {
        coords: [28.704060, 77.102493],
        name: "Delhi"
    },
    {
        coords: [6.524379, 3.379206],
        name: "Lagos"
    },
    {
        coords: [35.689487, 139.691711],
        name: "Tokyo"
    },
    {
        coords: [23.129110, 113.264381],
        name: "Guangzhou"
    },
    {
        coords: [40.7127837, -74.0059413],
        name: "New York"
    },
    {
        coords: [34.052235, -118.243683],
        name: "Los Angeles"
    },
    {
        coords: [41.878113, -87.629799],
        name: "Chicago"
    },
    {
        coords: [51.507351, -0.127758],
        name: "London"
    },
    {
        coords: [40.416775, -3.703790],
        name: "Madrid "
    }
];
var map = new jsVectorMap({
    map: "world",
    selector: "#world_map",
    zoomButtons: true,
    markers: markers,
    markerStyle: {
        initial: {
            r: 9,
            strokeWidth: 7,
            stokeOpacity: .4,
            fill: window.theme.primary
        },
        hover: {
            fill: window.theme.primary,
            stroke: window.theme.primary
        }
    },
    zoomOnScroll: false
});
window.addEventListener("resize", () => {
    map.updateSize();
});
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
document.getElementById("datetimepicker-dashboard").flatpickr({
    inline: true,
    prevArrow: "<span title=\"Previous month\">&laquo;</span>",
    nextArrow: "<span title=\"Next month\">&raquo;</span>",
    defaultDate: defaultDate
});
});
</script>

</body>

</html>