<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container" style="flex-direction: row">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="منوی ناوبری">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="index" href="{{route('home')}}">صفحه اصلی</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">درباره ما</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">سوالات متداول</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">تماس با ما</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">دسته بندی‌ها</a>
                </li>
            </ul>
            @if(!is_null(session('currentUser')) && !session('currentUser')->is_admin())
                <button class="btn-upgrade" type="submit" data-bs-toggle="modal" data-bs-target="#upgrade_account">
                    <i class="fas fa-crown"></i>
                    <span>ویژه شوید!</span>
                </button>
            @endif
        </div>
    </div>
</nav>

