@extends('layouts.justForm', [
    'title' => 'ویرایش پست',
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
    <form action="{{route('editPost')}}" enctype="multipart/form-data" method="POST" style="border-radius:20px;width:500px">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{$post->id}}">
        <div class="form-group">
            <label for="title">عنوان</label>
            <input type="text" id="title" class="form-control" name="title" value="{{old('title',$post->title)}}">
        </div>
        <div class="form-group">
            <label for="category">دسته بندی</label>
            <select class="form-control" id="category" name="category">
                @foreach(\App\Models\Post::getCategories() as $category)
                    <option value="{{$loop->iteration-1}}" @if(old('category',$post->category_id) == $loop->iteration-1) selected @endif>{{$category}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="slug">نامک (پیوند یکتا)</label>
            <input type="text" id="slug" class="form-control" name="slug" value="{{old('slug',$post->slug)}}">
        </div>
        <div class="form-group">
            <textarea id="content" name="content">{{old('content',$post->content)}}</textarea>
        </div>

        <div class="form-group">
            <input type="radio" class="btn-check" name="select-thumbnail" id="success-outlined" value="1" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off" checked/>
            <label class="btn btn-outline-success" for="success-outlined" onclick="show_image_selection()">انتخاب تصویر برای مقاله</label>

            <input type="radio" class="btn-check" name="select-thumbnail" id="danger-outlined" value="0" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off">
            <label class="btn btn-outline-danger" for="danger-outlined" onclick="hide_image_selection()">حذف تصویر قبلی</label>
        </div>
        <div class="form-group" id="thumbnail-select-group">
            <label for="thumbnail"></label>
            <input type="file" accept="image/*" class="form-control" onchange="loadThumbnail(event)" id="thumbnail" name="thumbnail"/>
            @if(!is_null($post->thumbnail))
                <p><img id="thumbnail-show" width="100%" src="{{\Illuminate\Support\Facades\Storage::url($post->thumbnail)}}"/></p>
            @endif
        </div>


        <div class="form-group">
            @if(old('type', $post->type) == 0)
                <input type="radio" class="btn-check" name="type" id="success-outlined" value="1" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off"/>
                <label class="btn btn-outline-warning" for="success-outlined">محتوای ویژه</label>

                <input type="radio" class="btn-check" name="type" id="danger-outlined" value="0" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off" checked/>
                <label class="btn btn-outline-secondary" for="danger-outlined">محتوای عادی</label>
            @else
                <input type="radio" class="btn-check" name="type" id="success-outlined" value="1" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off" checked/>
                <label class="btn btn-outline-warning" for="success-outlined">محتوای ویژه</label>

                <input type="radio" class="btn-check" name="type" id="danger-outlined" value="0" style="position: absolute;clip: rect(0,0,0,0);pointer-events: none;" autocomplete="off"/>
                <label class="btn btn-outline-secondary" for="danger-outlined">محتوای عادی</label>
            @endif
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

        var thumbanilSelection = document.getElementById('thumbnail-select-group');
        function show_image_selection(){
            thumbanilSelection.style.display = 'block';
        }
        function hide_image_selection(){
            thumbanilSelection.style.display = 'none';
        }
    </script>
    @include('partials.toast_script')
@endsection
