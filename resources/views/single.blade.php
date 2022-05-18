<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مطلب</title>
    <link rel="stylesheet" href="{{'/assets/css/bootstrap.rtl.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/all.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/animate.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/slick.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/slick-theme.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/aos.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/rating-stars.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/custom.css'}}">
</head>

<style>
    .damnYouButton{
        position: relative;bottom: 20px;right: 300px;
    }
</style>
<body dir="rtl" style="overflow-x:hidden">
@if(!is_null($cu = session('currentUser')) &&  $cu->role != \App\Http\Controllers\MainUserController::Admin)
    @include('partials.upgrade',[
        'userID' => $cu->id,
        'cu' => $cu
        ])
    @include('partials.chargebox')
@endif
    @include('partials.rate')

    @include('partials.header')

    @include('partials.defaultNavbar',[
        'with_subscribe_button' =>! $pageData['author-hasSubscribtion']
    ])

    <main>

        @include('partials.toast')


        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card single-post" data-aos="zoom-in">
                        <div class="post-img">
                            <img src="{{$pageData['post-thumbnail']}}" class="card-img-top" alt="تصویر مطلب">
                            <div class="overlay"></div>

                            <div class="post-btns">
                                <div class="post-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="به اشتراک گذاری">
                                    <i class="fas fa-share-square" onclick="share()"></i>
                                </div>
                                <div class="share-link-dialog sharew" style="display: none">
                                    <ul class="icons">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{request()->url()}}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        <a href="https://twitter.com/home?status={{request()->url()}}" target="_blank"><i class="fab fa-twitter"></i></a>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{request()->url()}}" target="_blank"><i class="fab fa-linkedin"></i></a>
                                        <a href="https://web.whatsapp.com/send?text={{request()->url()}}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                        <a href="https://telegram.me/share/url?url={{request()->url()}}" target="_blank"><i class="fab fa-telegram-plane"></i></a>
                                    </ul>
                                </div>
                                <div id="rate" class="post-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$pageData['post-rate'] == 0 ? '-':$pageData['post-rate']}}">
                                    <i class="fas fa-star" data-bs-toggle="modal" data-bs-target="#rating_stars"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">{{$pageData['post-title']}}</h4>
                            <div class="card-text">
                                {!!$pageData['post-content']!!}

                                @if($pageData['post-isVIP'])
                                    <div class="need-vip">
                                        <div class="vip-box">
                                            <div class="vip-box-icons">
                                                <i class="fas fa-sad-tear"></i>
                                            </div>
                                            <div class="text">
                                                برای خواندن ادامه این مطلب بایستی حساب خود را ارتقا دهید!
                                            </div>
                                            @if(is_null($cu))
                                                <a href="{{route('loginForm')}}" class="btn btn-primary">ورود</a>
                                                <a href="{{route('registerForm')}}" class="btn btn-success">ثبت نام</a>
                                            @else
                                                <button class="btn-upgrade" type="submit" data-bs-toggle="modal"
                                                        data-bs-target="#upgrade_account">
                                                    <i class="fas fa-crown"></i>
                                                    <span>ارتقاء حساب</span>
                                                </button>
                                            @endif
                                        </div>
                                        <div class="blurred-bg">
                                            <p>
                                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از
                                                طراحان
                                                گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم
                                                است و
                                                برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                                کاربردی
                                                می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان
                                                جامعه و
                                                متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای
                                                علی
                                                الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می
                                                توان
                                                امید
                                                داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد
                                                وزمان
                                                مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای
                                                موجود
                                                طراحی اساسا مورد استفاده قرار گیرد.

                                            </p>

                                            <p>
                                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از
                                                طراحان
                                                گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم
                                                است و
                                                برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                                کاربردی
                                                می باشد. کتابهای زیادی در شصت و سه درصد گذشته.
                                            </p>
                                            <p>
                                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از
                                                طراحان
                                                گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم
                                                است و
                                                برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                                کاربردی
                                                می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان
                                                جامعه و
                                                متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای
                                                علی
                                                الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد.

                                            </p>

                                            <p>
                                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از
                                                طراحان
                                                گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم
                                                است و
                                                برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                                کاربردی
                                                می باشد. کتابهای زیادی در شصت و سه درصد گذشته.
                                            </p>

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <section class="container" style="margin-top: 40px;width: 100vw">
                        <div class="section-intro">
                            <h3>داغترین مطالب مشابه</h3>
                            <p>
                                شما نیز این مطالب دنبال را کنید!
                            </p>
                        </div>
                        @if(count($suggestions) > 0)
                            <div class="trending-posts">
                                @foreach($suggestions as $suggested)
                                    <a class="trending-post-wrapper" href="{{url('/post/single-post/'.$suggested['slug'])}}">
                                        <div class="trending-post">
                                            <img class="trending-post-image" src="{{\Illuminate\Support\Facades\Storage::url($suggested['thumbnail'])}}"  alt="trend back image">
                                            <section class="trending-image-overlay">
                                                <div class="trending-post-items">
                                                    <button class="trending-post-button">{{$suggested['category']}}</button>
                                                    <h4>{{$suggested['title']}}</h4>
                                                    <img src="{{$suggested['author-avatar']}}" style="border:2px solid #c9c9c9; aspect-ratio:1;object-fit: cover; object-position: top" alt="author-icon">
                                                </div>
                                            </section>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="d-flex justify-content-center align-items-center">
                                <p style=" border-radius: 10px;display: inline-block; padding: 5px 10px; background: rgb(230,230,230); width:fit-content">این صفحه موجود نیست</p>
                            </div>
                        @endif
                    </section>


                    <div class="card mt-4 comment-section" data-aos="zoom-in">
                        <div class="section-header">
                            <h4>دیدگاه‌ها</h4>
                            <p>یک دیدگاه برای این پست بزارید!</p>
                        </div>

                        <div class="commtent-box">
                            @if(!is_null($cu))
                                <img src="{{\Illuminate\Support\Facades\Storage::url($cu->avatar)}}" style="border:2px solid #c9c9c9; aspect-ratio:1;object-fit: cover; object-position: top" alt="" class="comment-user">
                                <div class="comment-editor">
                                    <form action="{{url("new-comment/$post_id")}}" method="post">
                                        @csrf
                                        <textarea name="comment" id="editor"></textarea>
                                        <button type="submit" class="btn btn-dark mt-3 float-end">ثبت کن!</button>
                                    </form>
                                </div>
                            @else
                                <img src="{{'/assets/img/default-avatar.png'}}" alt="" class="comment-user">
                                 <p>
                                    برای ثبت دیدگاه
                                    <a href="{{route('loginForm')}}">وارد شوید</a>
                                    و یا
                                    <a href="{{route('registerForm')}}">یا ثبت‌نام کنید!</a>
                                </p>
                            @endif
                        </div>
                        <div class="comments-container">

                            @foreach($pageData['post-comments'] as $comment)
                            <div class="comment-wrapper">
                                <div class="commtent-box">
                                    <img src="{{$comment['user-avatar']}}" style="border:2px solid #c9c9c9; aspect-ratio:1;object-fit: cover; object-position: top" alt="" class="comment-user">
                                    <div class="comment-text">
                                        <h6 class="comment-author">{{$comment['user-name']}}</h6>
                                        <div class="comment-date">
                                            @include('partials.age', ['age'=>$comment['age']])
                                        </div>
                                        <div class="content">
                                            {!!$comment['body']!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 sidebar">
                    <div class="card author" data-aos="zoom-in">
                            <a href="{{url('/profile/'.$author_id)}}">
                                <img src="{{$pageData['author-avatar']}}" class="article-writer" alt="نویسنده مطلب">
                            </a>
                            <h5 class="author-name">{{$pageData['author-name']}}</h5>
                        <div class="card-body">

                            <h6 class="card-subtitle">درباره نویسنده</h6>
                            <p class="bio">
                                {{$pageData['author-bio']}}
                            </p>

                            <h6 class="card-subtitle mt-4 mb-3">آمار نویسنده</h6>
                            <div class="author-stats">
                                <div class="author-stat">
                                    <span class="num">{{$pageData['author-number-of-posts']}}</span>
                                    <span class="text">مطلب</span>
                                </div>
                                <div class="author-stat">
                                    <span id="author-rate" class="num">{{$pageData['author-rate']}}</span>
                                    <span class="text">امتیاز کل</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(is_null($cu) || $cu->id != $author_id)
                        <div class="card donate" data-aos="zoom-in">
                        <h5 class="card-title">
                            <i class="fas fa-donate"></i>
                            حمایت از نویسنده
                        </h5>

                        <div class="card-text">
                            <p>
                                @if(!$pageData['post-isVIP'])
                                    {{' این مطلب رایگان بود!'}}
                                @endif
                                 درصورتی که محتوا برای شما مفید بود میتوانید از نویسنده این مطلب حمایت
                                کنید.
                            </p>
                            @if(is_null($cu))
                                <p>
                                    برای حمایت
                                    <a href="{{route('loginForm')}}">وارد شوید</a>
                                    و یا
                                    <a href="{{route('registerForm')}}">یا ثبت‌نام کنید!</a>
                                </p>
                            @else
                                <input type="number" id="charge-amount" class="form-control"
                                       placeholder="...مبلغ حمایت را وارد کنید">
                                <button type="button" onclick="donate()" class="blue-btn mt-3">بفرست بره!</button>
                            @endif
                        </div>

                        <div id="payment_result" class="success-overlay">

                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="section-break-md"></div>
    </main>

    @include('partials.footer')
    <script src="{{'/assets/js/bootstrap.bundle.js'}}"></script>
    <script src="{{'/assets/js/jquery-3.6.0.min.js'}}"></script>
    <script src="{{'/assets/js/slick.min.js'}}"></script>
    <script src="{{'/assets/js/aos.js'}}"></script>
    <script src="{{'/assets/js/ckeditor/ckeditor.js'}}"></script>

    <script>
        AOS.init();
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        function donate() {
            // alert(1);
            let amount = $("#charge-amount").val();
            $.ajax({
                type: "GET",
                url: "/donate",
                data: {amount:amount,author_id:{{$author_id}}}, // serializes the form's elements.
                success: function(data) {
                    $('#payment_result').html("");
                    $('#payment_result').css('background-color', data['bg']);
                    $('#payment_result').append(
                        '<div class="content">' +
                        data['sticker'] +
                        "<h4>" + data['header'] + "</h4>" +
                        "<p>" + data['msg'] + "</p>" +
                        '</div>'
                    );
                    $('#payment_result').fadeIn().css({ display: 'flex' }).delay(3000).fadeOut();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });


        }

        var rateResponseCounter = 0;

        function rate(value){
            $.ajax({
                type: "GET",
                url: "/rate",
                data: {postID:{{$post_id}}, rate:value},
                success: function(data) {
                    let id = 'rateResponse-'+rateResponseCounter;
                    $('#ToastsBox').append(
                        '<div id="'+ id +'" class="toast bg-primary text-center system-message" role="alert" aria-live="assertive" aria-atomic="true">' +
                            '<div class="toast-body text-white">' +
                                data['message'] +
                            '</div>' +
                        '</div>'
                    );
                    new bootstrap.Toast(document.getElementById(id)).show();
                    rateResponseCounter = rateResponseCounter+1;
                    $('#author-rate').text(data['new-author-rate']);
                    // alert(data['post-rate']);
                    $('#rate').attr('title',data['post-rate']);
                    $('#rate').attr('data-bs-original-title',data['post-rate']);
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                    $('#author-rate').fadeOut(1000).fadeIn(1000);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        CKEDITOR.replace('editor');

        function share() {
            $(".sharew").toggle();
        }
    </script>
    @include('partials.slicks-code')
    @if(!is_null($cu))
        @include('partials.chargebox_script')
    @endif
    @include('partials.toast_script')
</body>

</html>
