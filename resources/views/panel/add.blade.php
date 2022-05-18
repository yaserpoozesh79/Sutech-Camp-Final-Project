@extends('layouts.panel', [
    'title' => 'کاربر جدید'
])

@section('body')
    <div class="d-flex flex-row justify-content-center" style="background-color:transparent;padding: 0px">
        @include('partials.toast')
        <form action="" method="POST" enctype="multipart/form-data" class="" style="border-radius:20px;width:500px">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="name">نام و نام خانوادگی</label>
            <input type="text" class="form-control" id="name" name="name">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="role">نقش</label>
            <select class="form-control" id="role" name="role">
                <option value="0" selected disabled>انتخاب کنید</option>
                <option value="1">کاربر عادی</option>
                <option value="2">نویسنده</option>
                <option value="3">مدیر</option>
            </select>
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="bio">درباره کاربر</label>
            <textarea type="text" class="form-control" id="bio" name="bio" style="resize:vertical;max-height:180px"></textarea>
        </div>
        <div class="form-group">
            <label for="email">ایمیل</label>
            <input type="email" class="form-control" id="email" name="email">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="password">رمز عبور</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="avatar">عکس کاربر</label>
            <input type="file" accept="image/*" class="form-control" onchange="loadAvatar(event)" id="avatar" name="avatar"/>
            <p><img id="avatarShow" width="200"/></p>
        </div>
        <div class="form-group">
            <label for="wallet">موجودی کیف پول</label>
            <input type="number" class="form-control" id="wallet" name="wallet">
        </div>
        <input type="reset"  class="btn btn-secondary" value="پاک کردن">
        <button type="submit" class="btn btn-primary">تایید</button>
    </form>
    </div>
@endsection

@section('script')
    <script>
        var loadAvatar = function(event) {
            var image = document.getElementById('avatarShow');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
    @include('partials.toast_script')
@endsection
