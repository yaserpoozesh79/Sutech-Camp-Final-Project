@extends('layouts.browse')

@section('title')
    صفحه اصلی
@endsection

@section('user-info')
    @include('partials.UserInfo')
@endsection


@section('page-body')
    @if(!is_null($cu = session('currentUser')) &&  $cu->role != \App\Http\Controllers\MainUserController::Admin)
        @include('partials.upgrade',[
            'userID' => $cu->id,
            'cu' => $cu
            ])
        @include('partials.chargebox')
    @endif
    @include('partials.toast')

    <div class="container">
        <div class="posts-container">
            <div class="row">

                @forelse($news as $post)

                    <a class="col-12 mb-5 col-md-6 col-lg-4" href="{{url('/post/single-post/'.$post['slug'])}}">
                    <div class="card post">
                        <div class="post-img">
                            <img src="{{\Illuminate\Support\Facades\Storage::url($post['thumbnail'])}}" class="card-img-top" alt="...">
                            <div class="overlay"></div>
                        </div>
                        <div class="post-info">
                            <div class="info-box right animate__animated animate__faster animate__fadeInUp">
                                <i class=" far fa-eye"></i>
                                <span>{{$post['views']}}</span>
                            </div>
                            <img src="{{$post['author-avatar']}}" class="article-writer" alt="نوسینده مطلب"
                                 data-bs-toggle="tooltip" data-bs-placement="top" title="{{$post['author-name']}}">
                            <div class="info-box left animate__animated animate__faster animate__fadeInUp">
                                <i class="far fa-comments"></i>
                                <span>{{$post['comments-count']}}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                @if($post['type'] == 0)
                                    <span class="badge rounded-pill bg-danger">رایگان!</span>
                                @endif
                                    {{$post['title']}}
                            </h5>
                            <p class="card-text">
                                {!! $post['content'] !!}
                            </p>
                        </div>
                    </div>
                </a>

                @empty
                    <div class="d-flex justify-content-center align-items-center">
                        <p style=" border-radius: 10px;display: inline-block; padding: 5px 10px; background: rgb(230,230,230); width:fit-content">این صفحه موجود نیست</p>
                    </div>
                @endforelse

            </div>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if($pageNumber != 1)
                    <li class="page-item">
                        <a class="page-link" href="{{url('/home/page='.$pageNumber-1)}}">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </li>
                @endif
                @if($pageNumber > 2)
                    <li class="page-item">
                        <button class="page-link">
                            ...
                        </button>
                    </li>
                @endif

                @if($pageNumber != 1 && $lastPageNumber > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{url('/home/page='.$pageNumber-1)}}">
                            {{$pageNumber-1}}
                        </a>
                    </li>
                @endif
                <li class="page-item active">
                    <a class="page-link">
                        {{$pageNumber}}
                    </a>
                </li>
                @if($pageNumber != $lastPageNumber && $lastPageNumber > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{url('/home/page='.$pageNumber+1)}}">
                            {{$pageNumber+1}}
                        </a>
                    </li>
                @endif

                @if($pageNumber +1 < $lastPageNumber)
                    <li class="page-item">
                        <button class="page-link">
                            ...
                        </button>
                    </li>
                @endif

                @if($pageNumber != $lastPageNumber)
                    <li class="page-item">
                        <a class="page-link" href="{{url('/home/page='.$pageNumber+1)}}">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
    </div>

    <div class="section-break"></div>

    <section class="container">
        <div class="section-intro">
            <h3>داغ ترین مطالب</h3>
            <p>
                شما نیز این مطالب دنبال کنید!
            </p>
        </div>
        @if(count($trends) > 0)
            <div class="trending-posts">
                @foreach($trends as $trendPost)
                <a class="trending-post-wrapper" href="{{url('/post/single-post/'.$trendPost['slug'])}}">
                    <div class="trending-post">
                        <img class="trending-post-image" src="{{\Illuminate\Support\Facades\Storage::url($trendPost['thumbnail'])}}" alt="trend back image">
                        <section class="trending-image-overlay">
                            <div class="trending-post-items">
                                <button class="trending-post-button">{{$trendPost['category']}}</button>
                                <h4>{{$trendPost['title']}}</h4>
                                <img src="{{$trendPost['author-avatar']}}" style="border:2px solid #c9c9c9; aspect-ratio:1;object-fit: cover; object-position: top" alt="author-icon">
                            </div>
                        </section>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center">
                <p style=" border-radius: 10px;display: inline-block; padding: 5px 10px; background: rgb(230,230,230); width:fit-content">این صفحه موجود نیست</p>
            </div>
        @endif
    </section>

@endsection

@section('script')
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
    @include('partials.slicks-code')
    @if(!is_null($cu))
        @include('partials.chargebox_script')
    @endif
    @include('partials.toast_script')
@endsection
