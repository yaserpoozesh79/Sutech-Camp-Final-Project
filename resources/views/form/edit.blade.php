@extends('layouts.justForm', [
    'title' => 'ویرایش اطلاعات'
])

@section('body')
    @include('partials.toast')
    <form action="{{url('/form/update/'.$theUser->id)}}" method="POST" enctype="multipart/form-data" class="" style="border-radius:20px;width:500px">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="name">نام و نام خانوادگی</label>
            <input type="text" class="form-control" id="name" name="name" value="{{old('name',$theUser->name)}}">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="role">نقش</label>
            <select class="form-control" id="role" name="role">
                @if(old('role',$theUser->role) == \App\Http\Controllers\MainUserController::NormalUser)
                    <option value="{{\App\Http\Controllers\MainUserController::NormalUser}}" selected>کاربر عادی</option>
                    <option value="{{\App\Http\Controllers\MainUserController::Writer}}">نویسنده</option>
                @else
                    <option value="{{\App\Http\Controllers\MainUserController::NormalUser}}">کاربر عادی</option>
                    <option value="{{\App\Http\Controllers\MainUserController::Writer}}" selected>نویسنده</option>
                @endif
            </select>
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="bio">درباره کاربر</label>
            <textarea type="text" class="form-control" id="bio" name="bio" style="resize:vertical;max-height:180px">{{old('bio', $theUser->bio)}}</textarea>
        </div>
        <div class="form-group">
            <label for="email">ایمیل</label>
            <input type="email" class="form-control" id="email" name="email" value="{{old('email', $theUser->email)}}">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="password">رمز عبور</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="بدون تغییر">
        </div>
        <div class="form-group" id="repass-section">
            <label for="repass">تکرار رمز عبور</label>
            <input type="password" class="form-control" id="repass" name="repass">
        </div>
        <div class="form-group">
            <label for="selectAvatar"></label>
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
        <input type="reset"  class="btn btn-secondary" value="پاک کردن">
        <button type="submit" class="btn btn-primary">تایید</button>
    </form>
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

        var pass = document.getElementById('password');
        var repass = document.getElementById('repass-section');
        const defDisplay = repass.style.display;
        const responseTime = 500;
        setTimeout(hideIfEmpty,0);
        function hideIfEmpty(){
            if(pass.value === '' || pass.value == null){
                if(repass.style.display == 'none'){}
                else
                    $(repass).slideUp(responseTime/2);
            }else{
                if(repass.style.display == defDisplay){}
                else
                    $(repass).slideDown(responseTime/2);
            }
            setTimeout(hideIfEmpty,responseTime/2);
        }
    </script>
    @include('partials.toast_script')
@endsection
