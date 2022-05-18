<header>
    <div class="container">
        <div class="row">
            <div class="col-6 header-right">
                <a href="#">
                    <img src="{{'/assets/img/SUTCAMPLogo.png'}}" alt="لوگو سوتک کمپ">
                </a>
                <div class="search-bar">
                    <form>
                        <input type="search" class="form-control" name="" id=""
                               placeholder="عبارتی برای جستجو وارد کنید...">
                    </form>
                </div>
            </div>
            <div class="col-6 header-left">
                @include('partials.UserInfo')
            </div>
        </div>
    </div>
</header>
