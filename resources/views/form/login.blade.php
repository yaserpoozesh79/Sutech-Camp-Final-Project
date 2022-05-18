
@extends('layouts.justForm', [
    'title' => 'ورود'
])

@section('body')
    @include('partials.toast')
    <form action="{{route('login')}}" method="POST" style="border-radius:20px;width:500px">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="email">ایمیل</label>
            <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}">
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="password">رمز عبور</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">تایید</button>
    </form>
@endsection

@section('script')
    @include('partials.toast_script')
@endsection
