<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل {{$pageData['name']}}</title>
    <link rel="stylesheet" href="{{'/assets/css/bootstrap.rtl.min.css'}}">
    <link rel="stylesheet" href="{{'/assets/css/fontawesome.min.css'}}">
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

<body dir="rtl" style="overflow-x: hidden">
@if(!is_null($cu = session('currentUser')) &&  $cu->role != \App\Http\Controllers\MainUserController::Admin)
    @include('partials.upgrade',[
        'userID' => $cu->id,
        'cu' => $cu
        ])
@endif

@include('partials.header')

@include('partials.defaultNavbar')

@include('partials.drawer')

@include('partials.toast')

<main>
    <div class="container">
        <div class="infoCard" style="background-image: url('{{'/profile-assets/img/userpanel.png'}}')">
            <div class="bottomLayer">
                <img class="" src="{{$pageData['avatar']}}" style="border:2px solid #c9c9c9; aspect-ratio:1;object-fit: cover; object-position: top" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$pageData['name']}}">
                <div class="dataFileds">

                    <div class="dataFiled">
                        <div><i class="fas fa-clock"></i> تاریخ عضویت</div>
                        <small>{{$pageData['join-date']}}</small>
                    </div>
                    @if(isset($pageData['posts-count']))
                        <div class="dataFiled">
                            <div><i class="fas fa-clone"></i> تعداد مطالب</div>
                            <small>{{$pageData['posts-count']}}</small>
                        </div>
                        <div class="dataFiled">
                            <div><i class="fas fa-star"></i> امتیاز</div>
                            <small>{{$pageData['rate']==0 ? '-' : $pageData['rate']}}</small>
                        </div>
                    @endif
                    <div class="dataFiled">
                        <div><i class="fas fa-clock"></i> آخرین بازدید</div>
                        <small>{{$pageData['last-visit']}}</small>
                    </div>
                    @if(isset($pageData['posts-count']))
                        <div class="dataFiled" style="border: none">
                            <div><i class="fas fa-comments"></i> بازخوردها</div>
                            <small>{{$pageData['feedbacks-count']}}</small>
                        </div>
                    @endif
                </div>
            </div>
            @if(!is_null($currentUser = session('currentUser')))
                @if($currentUser->role == \App\Http\Controllers\MainUserController::Admin)
                    <div class="card_top">
                        <a href="{{url("/panel/edit/".$pageData['id'])}}" class="editProfile_link">
                            <i class="fas fa-pen"></i>
                        </a>
                    </div>
                @elseif($currentUser->id == $pageData['id'])
                    <div class="card_top">
                        <a href="{{url("/form/edit-user/".$pageData['id'])}}" class="editProfile_link">
                            <i class="fas fa-pen"></i>
                        </a>
                    </div>
                @else
                    {{""}}
                @endif
            @endif
        </div>
        <div class="d-flex flex-row align-content-start the_rest">
            <div class="sideItems">
                <div class="sideCard" id="walletCard">
                    <img src="{{'/assets/img/wallet.png'}}">
                    <div class="d-flex flex-row align-content-center justify-content-center wallet">
                        <div id="wallet-info">
                            <h5>موجودی کیف پول</h5>
                            <p>{{$pageData['wallet']." تومان"}}</p>
                        </div>
                        @if(!is_null($currentUser) && $pageData['id'] == $currentUser->id)
                            <button onclick="modalfunc(false)"><i class="fas fa-rocket"></i> افزایش موجودی</button>
                        @endif
                    </div>
                </div>
                <div class="sideCard" id="aboutMeCard">
                    <h6>کمی درباره من :)</h6>
                    <p>{{$pageData['bio']}}</p>
                </div>
            </div>
            <div class="main_card">
                <nav class="nav_container">
                    <div class="nav nav-tabs navTabs" id="nav-tab" role="tablist">
                        <button class="nav-link active Tab" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">فعالیت</button>
                        @if(isset($pageData['received-comments']))
                            <button class="nav-link Tab" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">بازخوردها</button>
                        @endif
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                         aria-labelledby="nav-home-tab">
                        @if($pageData['activities'] == [])
                            <div class="bg-gray" style="width: fit-content;padding:0px 10px;margin: auto">
                                هیچ فعالیتی ثبت نشده است
                            </div>
                        @endif
                        @foreach($pageData['activities'] as $act)
                            @if(isset($act['title']))
                                <div class="row m-4">
                                    <div class="col-lg-2">
                                        <img class="rounded-circle" src="{{$act['author-avatar']}}" style="border:2px solid #c9c9c9; aspect-ratio:1;object-fit: cover; object-position: top" width="60">
                                    </div>
                                    <div class="col-lg-10">
                                        <div class="bg-gray">
                                            <div class="grd"></div><b>{{$act['author-name']}}</b> یک مطلب جدید در دسته <b>{{$act['category']}}</b> ارسال کرد.
                                        </div>
                                        <p class="mt-2 clr">{!!$act['content']!!}...</p>
                                        <div class="row">
                                            <p class="col-lg-3"><i class="fa fa-clock-o clock" style="font-size:20px"></i>@include('partials.age',['age'=>$act['age']])</p>
                                            <p class="col-lg-3 disinline"><i class="fas fa-comments"></i>{{$act['comments-count']}} دیدگاه</p>
                                        </div>

                                    </div>
                                    <hr class="mt-3">
                                </div>
                            @else
                                <div class="row m-4">
                                    <div class="col-lg-2">
                                        <div class=" bg-gray wi">
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-10">
                                        <div class="bg-gray">
                                            <div class="grd"></div><b>{{$act['receiver-name']}}</b> یک بازخورد برای <b>{{$act['user-name']}}</b> ثبت کرد .
                                        </div>
                                        <p class="col-lg-3 mt-3"><i class="fa fa-clock-o clock" style="font-size:20px"></i> @include('partials.age',['age'=>$act['age']])</p>

                                    </div>
                                    <hr class="mt-3">
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if(isset($pageData['received-comments']))
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        @if($pageData['received-comments'] == [])
                            <div class="bg-gray" style="width: fit-content;padding:0px 10px;margin: auto">
                                هیچ بازخوردی ثبت نشده است
                            </div>
                        @else
                            <h5>آخرین بازخوردها</h5>
                        @endif
                        @foreach($pageData['received-comments'] as $comment)
                            <div class="row m-4">
                                <div class="col-lg-2">
                                    <img class="rounded-circle" src="{{$comment['user-avatar']}}" style="border:2px solid #c9c9c9; aspect-ratio:1;object-fit: cover; object-position: top" width="60">
                                </div>
                                <div class="col-lg-10">
                                    <div class="bg-gray">
                                        <div class="grd"></div>
                                        <b>{{$comment['user-name']}}</b> یک بازخورد ثبت کرد .
                                    </div>
                                    <p class="mt-2 clr">{!!$comment['body']!!}</p>
                                    <p>
                                        <i class="fa fa-clock-o clock" style="font-size:20px"></i>
                                        @include('partials.age',['age'=>$comment['age']])
                                    </p>
                                </div>
                                @if(count($pageData['received-comments']) != $loop->iteration)
                                    <hr class="mt-3">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('partials.chargebox')
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
@include('partials.chargebox_script')
</body>

</html>
