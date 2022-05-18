@extends('layouts.panel', [
    'title' => 'ویرایش کاربر'
])

@section('body')
    <div class="d-flex flex-row justify-content-center" style="background-color:transparent;padding: 0px">
        @include('partials.toast')
        <form action="{{url("/panel/update/{$theUser->id}")}}" method="POST" style="border-radius:20px;width:500px">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="name">نام و نام خانوادگی</label>
            <input type="text" class="form-control" id="name" name="name" value="{{old('name', isset($theUser) ? $theUser->name : '')}}">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="role">نقش</label>
            <select class="form-control" id="role" name="role">
                <option value="1" {{old('role', isset($theUser) ? $theUser->role : '') == '1' ? 'selected':''}}>کاربر عادی</option>
                <option value="2" {{old('role', isset($theUser) ? $theUser->role : '') == '2' ? 'selected':''}}>نویسنده</option>
                <option value="3" {{old('role', isset($theUser) ? $theUser->role : '') == '3' ? 'selected':''}}>مدیر</option>
            </select>
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="bio">درباره کاربر</label>
            <textarea type="text" class="form-control" id="bio" name="bio" style="resize:vertical;max-height:180px">{{old('bio', isset($theUser) ? $theUser->bio : '')}}</textarea>
        </div>
        <div class="form-group">
            <label for="email">ایمیل</label>
            <input type="email" class="form-control" id="email" name="email" value="{{old('email', isset($theUser) ? $theUser->email : '')}}">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="password">رمز عبور</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="بدون تغییر">
        </div>
        <div class="form-group">
            <input type="radio" class="btn-check" name="selectAvatar" id="success-outlined" value="yes" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off" checked/>
            <label class="btn btn-outline-success" for="success-outlined" onclick="show_image_selection()">انتخاب عکس برای کاربر</label>

            <input type="radio" class="btn-check" name="selectAvatar" id="danger-outlined" value="no" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off">
            <label class="btn btn-outline-danger" for="danger-outlined" onclick="hide_image_selection()">حذف عکس کاربر</label>
        </div>
        <div class="form-group" id="avatarSelectGrp">
            <label for="avatar"></label>
            <input type="file" accept="image/*" class="form-control" onchange="loadAvatar(event)" id="avatar" name="avatar"/>
            @if(!is_null($theUser->avatar))
                <p><img id="avatarShow" width="200" src="{{\Illuminate\Support\Facades\Storage::url($theUser->avatar)}}"/></p>
            @endif
        </div>
        <div class="form-group">
            <label for="wallet">موجودی کیف پول</label>
            <input type="number" class="form-control" id="wallet" name="wallet" value="{{old('wallet', isset($theUser) ? $theUser->wallet : '')}}">
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

            var avaterSelection = document.getElementById('avatarSelectGrp');
            function show_image_selection(){
                avaterSelection.style.display = 'block';
            }
            function hide_image_selection(){
                avaterSelection.style.display = 'none';
            }
        </script>
    @include('partials.toast_script')
@endsection

