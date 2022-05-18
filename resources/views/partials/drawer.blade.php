
@if(!is_null($CU = session('currentUser')) && $CU->role == \App\Http\Controllers\MainUserController::Admin)
    <input type="checkbox" id="nav-trigger" class="nav-trigger" />
    <label for="nav-trigger"></label>
    <ul class="navigation" style="overflow-y: auto">
        <li class="nav-item"><a href="{{url('/profile/'.$CU->id)}}">پنل مدیریت</a></li>
        <li class="nav-item"><a href="{{route('users-list')}}">مشاهده کاربران</a></li>
        <li class="nav-item"><a href="{{route('add-user')}}">ایجاد کاربر جدید</a></li>
        <li class="nav-item"><a href="{{route('posts-list')}}">پست های کاربران</a></li>
        <li class="nav-item"><a href="{{url('/panel/transactions')}}">گزارش تراکنش های کاربران</a></li>
        <li class="nav-item"><a href="{{url('/user-posts/'.$CU->id)}}">پست های من</a></li>
        <li class="nav-item"><a href="{{route('newPostForm')}}">ایجاد پست جدید</a></li>
    </ul>
@elseif(!is_null($CU = session('currentUser')) && $CU->role == \App\Http\Controllers\MainUserController::Writer)
    <input type="checkbox" id="nav-trigger" class="nav-trigger" />
    <label for="nav-trigger"></label>
    <ul class="navigation">
        <li class="nav-item"><a href="{{url('/profile/'.$CU->id)}}">پروفایل من</a></li>
        <li class="nav-item"><a href="{{route('newPostForm')}}">ایجاد پست جدید</a></li>
        <li class="nav-item"><a href="{{url('/user-posts/'.$CU->id)}}">پست های من</a></li>
        <li class="nav-item"><a href="{{route('posts-list')}}">پست های کاربران</a></li>
    </ul>
@else

@endif


