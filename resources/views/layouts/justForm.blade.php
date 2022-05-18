<!DOCTYPE html>
<html>
<head>
    <title>{{$title}}</title>
    <link rel="stylesheet" href="{{'/assets/css/bootstrap.rtl.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/all.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/animate.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/slick.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/slick-theme.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/aos.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/rating-stars.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/custom.css'}}">
    <style>
        *{
            font-family: 'shabnam FD';
        }
        body{
            background-image: linear-gradient(to left, rgb(156, 156, 248), rgb(90, 90, 255));
            /*background-size: cover;*/
            background-repeat:repeat-y;
            margin: 0px;
            padding: 0px;
            width: 100vw;
            overflow-x: hidden;
        }
        .main{
            margin: 30px 0px;
            padding: 20px;
        }
    </style>
    @yield('style')
</head>
<body lang="fa" dir="rtl">
    <div class="d-flex flex-row justify-content-center main">
        @yield('body')
    </div>
    <script src="{{'/assets/js/bootstrap.bundle.js'}}"></script>
    <script src="{{'/assets/js/jquery-3.6.0.min.js'}}"></script>
    <script src="{{'/assets/js/slick.min.js'}}"></script>
    <script src="{{'/assets/js/aos.js'}}"></script>
    <script src="{{'/assets/js/ckeditor/ckeditor.js'}}"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    @yield('script')
</body>
</html>
