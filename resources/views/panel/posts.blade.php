@extends('layouts.panel',[
    'title' => $title
])

@section('body')
    @if(!is_null($cu = session('currentUser')) &&  $cu->role != \App\Http\Controllers\MainUserController::Admin)
        @include('partials.upgrade',[
            'userID' => $cu->id,
            'cu' => $cu
            ])
        @include('partials.chargebox')
    @endif
    @include('partials.toast')
    @forelse($posts as $post)
        <div class="container" style="@if(!$loop->last) margin-bottom:50px; @endif">
            <div class="posts-container">
                <div class="row">
                    <div class="col-12 ">
                        <div class="card post" data-aos="zoom-in">
                            <a class="post-img" href="{{url('/post/single-post/'.$post['slug'])}}">
                                <img src="{{\Illuminate\Support\Facades\Storage::url($post['thumbnail'])}}" class="card-img-top" alt="...">
                                <div class="overlay"></div>
                            </a>
                            <div class="post-info">
                                <img src="{{$post['author-avatar']}}" class="article-writer" alt="نوسینده مطلب"
                                     data-bs-toggle="tooltip" data-bs-placement="top" title="{{$post['author-name']}}">
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mx-3">{{$post['date']}}</h5>
                                </div>

                                <div></div>
                                <div>
                                    <h5 class="mx-3">{{$post['category']}}</h5>
                                </div>
                            </div>
                            <div class="card-body">

                                <h5 class="card-title">{{$post['title']}}</h5>


                                <p class="card-text p-2">
                                    {!!$post['content']!!}...
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="p-2 m-3">
                                @for($i=0; $i<5 ; $i++)
                                    @if($i+1<=round($post['rate']))
                                        <span class="fa fa-star checked"></span>
                                    @else
                                        <span class="fa fa-star"></span>
                                    @endif
                                @endfor
                                    <small>{{$post['rate']}}</small>
                                </div>
                                <div></div>
                                <div>
                                    @if(($cu = session('currentUser')) != null && ($cu->role == \App\Http\Controllers\MainUserController::Admin || $cu->id == $post['author-id']))
                                        <a href="{{url('/form/edit-post/'.$post['id'])}}" class="fas fa-edit green" style="zoom: 1.2;"></a>
                                        <a href="{{url('/delete-post/'.$post['id'])}}" class="fa fa-times red m-3" aria-hidden="true" style="zoom: 1.2;"></a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="d-flex justify-content-center align-items-center">
            <p style=" border-radius: 10px;display: inline-block; padding: 5px 10px; background: rgb(230,230,230); width:fit-content">هیچ پستی یافت نشد</p>
        </div>
    @endforelse
@endsection

@section('script')
    @include('partials.toast_script')
    @if(!is_null($cu))
        @include('partials.chargebox_script')
    @endif
@endsection
