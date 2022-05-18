<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{'/assets/css/bootstrap.rtl.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/all.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/animate.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/slick.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/slick-theme.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/aos.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/rating-stars.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/custom.css'}}">
</head>

<body>
    <div id="ToastsBox" class="position-fixed top-0 p-3" style="z-index: 11; left:40vw">
        <div id="success-message" class="toast bg-primary text-center system-message" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body text-white">
                سلام
            </div>
        </div>
    </div>
</body>
<button onclick="showToast()">
    نشان بده
</button>

<script src="{{'/assets/js/bootstrap.bundle.js'}}"></script>
<script src="{{'/assets/js/jquery-3.6.0.min.js'}}"></script>
<script src="{{'/assets/js/slick.min.js'}}"></script>
<script src="{{'/assets/js/aos.js'}}"></script>
<script src="{{'/assets/js/ckeditor/ckeditor.js'}}"></script>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<script>

    var successMessage = document.getElementById('success-message');
    function showToast(){
        let toast = new bootstrap.Toast(successMessage);
        toast.show();
        setTimeout(hideToast,5000);
    }
    function hideToast(){
        let toast = new bootstrap.Toast(successMessage);
        toast.hide();
    }
</script>
