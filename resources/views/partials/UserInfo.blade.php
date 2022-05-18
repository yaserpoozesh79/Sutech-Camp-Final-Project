@if(!is_null($currentUser = session('currentUser')))

    <div class="user-bar">
    <div class="avatar">
        <img src="{{\Illuminate\Support\Facades\Storage::url($currentUser->avatar ?? 'avatars/default-avatar.png')}}" style="border:2px solid #c9c9c9; aspect-ratio:1;object-fit: cover; object-position: top" alt="">
        <div class="overlay"></div>
    </div>
    <div class="user-info">
        <div class="dropdown">
            <a class="usermenu" href="#" type="button" id="usermenu" data-bs-toggle="dropdown"
               aria-expanded="false">
                <span>{{$currentUser->name ?? null}}</span>
                <i class="fas fa-sort-down"></i>
            </a>
            <ul class="dropdown-menu animate__animated animate__fadeInUp animate__faster"
                aria-labelledby="usermenu">
                <li>
                    <a class="dropdown-item" href="{{route('my-transactions')}}">
                        <i class="fas fa-exchange-alt"></i>
                        <span>گزارش تراکنش ها</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{url("/profile/$currentUser->id")}}">
                        <i class="fas fa-user-alt"></i>
                        <span>مشاهده پروفایل</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{route('exitAccount')}}">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>خروج از حساب</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="account-type">{{session('roleDcb')?? null}} @if(\App\Http\Controllers\MainUserController::hasSubscribtion($currentUser))ویژه@endif</div>
    </div>
    </div>
    <div class="notif-bell">
    <i class="fas fa-bell"></i>
    <div class="new"></div>
</div>

@else
    <a href="{{route('loginForm')}}" class="btn">ورود</a>
    <a href="{{route('registerForm')}}" class="btn">ثبت‌نام</a>
@endif
