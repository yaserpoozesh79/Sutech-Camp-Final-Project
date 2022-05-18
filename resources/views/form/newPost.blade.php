@extends('layouts.justForm', [
    'title' => 'پست جدید',
])

@section('style')
    <style>
        .form-group{
            margin: 10px 0px;
        }
    </style>
@endsection

@section('body')
    @include('partials.toast')
    <form action="{{route('newPost')}}" enctype="multipart/form-data" method="POST" style="border-radius:20px;width:500px">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="title">عنوان</label>
            <input type="text" id="title" class="form-control" name="title" value="{{old('title','')}}">
        </div>
        <div class="form-group">
            <label for="category">دسته بندی</label>
            <select class="form-control" id="category" name="category">
                <option value="-1" @if(old('category',-1)==null ||old('category',-1)==-1) selected @endif disabled>انتخاب کنید</option>
                @foreach($categories as $category)
                    <option value="{{$loop->iteration-1}}" @if(old('category',-1) == $loop->iteration-1) selected @endif>{{$category}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="slug">نامک (پیوند یکتا)</label>
            <input type="text" id="slug" class="form-control" name="slug" value="{{old('slug','')}}">
        </div>
        <div class="form-group">
            <textarea id="content" name="content">{{old('content')}}</textarea>
        </div>
        <div class="form-group">
            <label for="thumbnail">تصویر روی مقاله</label>
            <input type="file" accept="image/*" id="thumbnail" onchange="loadThumbnail(event)" class="form-control" name="thumbnail">
            <p><img id="thumbnail-show" width="100%"/></p>
        </div>
        <div class="form-group">
            <input type="radio" class="btn-check" name="type" id="success-outlined" value="1" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off"/>
            <label class="btn btn-outline-warning" for="success-outlined">محتوای ویژه</label>

            <input type="radio" class="btn-check" name="type" id="danger-outlined" value="0" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off" checked/>
            <label class="btn btn-outline-secondary" for="danger-outlined">محتوای عادی</label>
        </div>
        <button type="submit" class="btn btn-primary" onclick="pureValue()">تایید</button>
    </form>
@endsection

@section('script')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.config.language = 'fa';
        CKEDITOR.replace( 'content' );
    </script>
    <script>
        var loadThumbnail = function(event) {
            var image = document.getElementById('thumbnail-show');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
    @include('partials.toast_script')
@endsection
