@extends('layouts.justForm', [
    'title' => 'ثبت نام'
])

@section('body')
    @include('partials.toast')
    <form action="{{route('register')}}" method="POST" enctype="multipart/form-data" class="" style="border-radius:20px;width:500px">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="name">نام و نام خانوادگی</label>
            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="role">نقش</label>
            <select class="form-control" id="role" name="role">
                <option value="0" selected disabled>انتخاب کنید</option>
                <option value="1">کاربر عادی</option>
                <option value="2">نویسنده</option>
            </select>
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="bio">درباره کاربر</label>
            <textarea type="text" class="form-control" id="bio" name="bio" style="resize:vertical;max-height:180px">{{old('bio')}}</textarea>
        </div>
        <div class="form-group">
            <label for="email">ایمیل</label>
            <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="password">رمز عبور</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="repass">تکرار رمز عبور</label>
            <input type="password" class="form-control" id="repass" name="repass">
        </div>
        <div class="form-group">
            <label for="avatar">عکس کاربر</label>
            <input type="file" accept="image/*" class="form-control" onchange="loadAvatar(event)" id="avatar" name="avatar"/>
            <p style="display:none"><img id="avatarShow" width="200"/></p>
        </div>
        <input type="reset"  class="btn btn-secondary" value="پاک کردن">
        <button type="submit" class="btn btn-primary">تایید</button>
    </form>
@endsection

@section('script')
    <script>
        var image = document.getElementById('avatarShow');
        @if(!is_null(old('avatar')))
            image.parentElement.style.display = 'block';
            image.src = {{old('avatar')}};
        @endif
        var loadAvatar = function(event) {
            image.parentElement.style.display = 'block';
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
    @include('partials.toast_script')
@endsection
