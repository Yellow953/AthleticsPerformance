</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Athletics Performance') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/logo.png')}}" load="lazy">

    <!-- Non-critical CSS -->
    <link href="{{asset('assets/css/font-face.css')}}" rel="preload" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link href="{{asset('assets/vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="preload" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link href="{{asset('assets/vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="preload" as="style"
        onload="this.onload=null;this.rel='stylesheet'">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"
        defer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"
        defer>
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" defer>

    <!-- Critical CSS -->
    <link href="{{asset('assets/css/theme.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('assets/vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/slick/slick.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">
</head>

<body style="overflow-y: hidden;">
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

                        <div class="scrollbar" id="style-1" style="height: 85vh!important; overflow-y: scroll">
                            @yield('content')
                            <br><br>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <!-- END PAGE CONTAINER-->
    </div>

    <!-- Move these to the bottom before closing the body tag -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
    </script>

    <script src="{{asset('assets/vendor/perfect-scrollbar/perfect-scrollbar.js')}}" defer></script>
    <script src="{{asset('assets/vendor/select2/select2.min.js')}}" defer></script>
    <script src="{{asset('assets/js/main.js')}}" defer></script>

    <!-- Delete Confirmation -->
    <script src="{{asset('assets/js/sweetalert.min.js')}}" defer></script>
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