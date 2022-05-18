<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <link rel="stylesheet" href="{{'/assets/css/bootstrap.rtl.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/fontawesome.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/all.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/animate.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/slick.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/slick-theme.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/aos.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/rating-stars.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/custom.css'}}">
    <link rel="stylesheet" href="{{'/profile-assets/css/custom.css'}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .cke {
            visibility: hidden;
        }
    </style>
</head>

<body dir="rtl" style="width: 100vw">

@include('partials.header')

@include('partials.defaultNavbar')

@include('partials.drawer')

<main>
    @yield('body')
</main>

@include('partials.footer')

<script src="{{'/assets/js/bootstrap.bundle.js'}}"></script>
<script src="{{'/assets/js/jquery-3.6.0.min.js'}}"></script>
<script src="{{'/assets/js/slick.min.js'}}"></script>
<script src="{{'/assets/js/aos.js'}}"></script>
<script src="{{'/assets/js/ckeditor/ckeditor.js'}}"></script>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<script>
    AOS.init();
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@yield('script')
</body>

</html>
