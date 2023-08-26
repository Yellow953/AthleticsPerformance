</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Athletics Performance') }}</title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('assets/css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet"
        media="all">

    <!-- Bootstrap CSS-->
    <link href="{{asset('assets/vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('assets/vendor/animsition/animsition.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet"
        media="all">
    <link href="{{asset('assets/vendor/wow/animate.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/slick/slick.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('assets/css/theme.css')}}" rel="stylesheet" media="all">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/logo.ico')}}">

    {{-- Custom Css --}}
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

    {{-- Ajax --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body class="animsition">
    <div class="page-wrapper p-0">
        @include('layouts._mobile_header')

        @include('layouts._sidebar')

        <!-- PAGE CONTAINER-->
        <div class="page-container bg-gray">
            @include('layouts._header')

            <main class="pb-2">
                <!-- MAIN CONTENT-->
                <div class="main-content">
                    <div class="section__content section__content--p30">
                        @include('layouts._flash')

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
        <!-- END PAGE CONTAINER-->
    </div>



    <!-- Jquery JS-->
    <script src="{{asset('assets/vendor/jquery-3.2.1.min.js')}}"></script>
    <!-- Bootstrap JS-->
    <script src="{{asset('assets/vendor/bootstrap-4.1/popper.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-4.1/bootstrap.min.js')}}"></script>
    <!-- Vendor JS -->
    <script src="{{asset('assets/vendor/slick/slick.min.js')}}">
    </script>
    <script src="{{asset('assets/vendor/wow/wow.min.js')}}"></script>
    <script src="{{asset('assets/vendor/animsition/animsition.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')}}">
    </script>
    <script src="{{asset('assets/vendor/counter-up/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('assets/vendor/counter-up/jquery.counterup.min.js')}}">
    </script>
    <script src="{{asset('assets/vendor/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{asset('assets/vendor/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('assets/vendor/chartjs/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/select2/select2.min.js')}}">
    </script>

    <!-- Main JS-->
    <script src="{{asset('assets/js/main.js')}}"></script>

    {{-- Sweet Alert --}}
    <script src="{{asset('assets/js/sweetalert.min.js')}}"></script>
    {{-- Delete Confirmation --}}
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
           var form =  $(this).closest("form");
           var name = $(this).data("name");
           event.preventDefault();
           swal({
               title: `Are you sure you want to delete this record?`,
               text: "If you delete this, it will be gone forever.",
               icon: "warning",
               buttons: true,
               dangerMode: true,
           })
           .then((willDelete) => {
               if (willDelete) {
                   form.submit();
               }
           });
       });
    </script>

</body>

</html>
<!-- end document-->