@extends('layouts.panel',[
    'title' => 'تراکنش ها'
])

@section('body')
    @if(!is_null($cu = session('currentUser')) &&  $cu->role != \App\Http\Controllers\MainUserController::Admin)
        @include('partials.upgrade',[
            'userID' => $cu->id,
            'cu' => $cu
            ])
        @include('partials.chargebox')
    @endif
    @if(!is_null(session('currentUser')))
        @include('partials.drawer')
    @endif
    @include('partials.toast')
    <div class="container">
        <div class="d-flex flex-row gap-5">
            <h3>گزارش تراکنش ها</h3>
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    دسته بندی گزارشات
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><button class="dropdown-item" onclick="showTrans(1)">شارژ</button></li>
                    <li><button class="dropdown-item" onclick="showTrans(2)">خریداشتراک</button></li>
                    <li><button class="dropdown-item" onclick="showTrans(3)">حمایت مالی</button></li>
                </ul>
            </div>
        </div>


        <div id="charges" class="row m-2 bgg">
            <h5 class="m-3">شارژ کیف پول</h5>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">حساب کاربری</th>
                    <th scope="col" class="text-center">مبلغ شارژ</th>
                    <th scope="col" class="text-center">تاریخ</th>
                </tr>
                </thead>
                <tbody>

                @foreach($pageData['charges'] as $charge)
                    <tr>
                        <th scope="row" class="text-center">{{$loop->iteration}}</th>
                        <td class="text-center">{{$charge['user']}}</td>
                        <td class="text-center">{{$charge['amount']}} تومان</td>
                        <td class="text-center">{{$charge['date']}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            @if($pageData['charges'] == [])
                <p class="text-center" style="margin: auto">
                    گزارشی در این دسته ثبت نشده است
                </p>
            @endif
        </div>
        <div id="subscribes" style="display: none" class="row m-2 bgg">
            <h5 class="m-3">خرید اشتراک</h5>
            <table class="table table-striped tables">
                <thead>
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">حساب کاربری</th>
                    <th scope="col" class="text-center">هزینه اشتراک</th>
                    <th scope="col" class="text-center">تاریخ خرید</th>
                    <th scope="col" class="text-center">نوع اشتراک</th>
                    <th scope="col" class="text-center">تاریخ اتمام</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pageData['subscribes'] as $sub)
                    <tr>
                        <th scope="row" class="text-center">{{$loop->iteration}}</th>
                        <td class="text-center">{{$sub['user']}}</td>
                        <td class="text-center">{{$sub['amount']}} تومان</td>
                        <td class="text-center">{{$sub['buy_date']}}</td>
                        <td class="text-center">{{$sub['type']}} ماهه</td>
                        <td class="text-center">{{$sub['expire_date']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if($pageData['subscribes'] == [])
                <p class="text-center" style="margin: auto">
                    گزارشی در این دسته ثبت نشده است
                </p>
            @endif
        </div>
        <div id="donates" style="display: none" class="row m-2 bgg">
            <h5 class="m-3">حمایت مالی</h5>
            <table class="table table-striped tables">
                <thead>
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">حمایت کننده</th>
                    <th scope="col" class="text-center">حمایت شده</th>
                    <th scope="col" class="text-center">مقدار</th>
                    <th scope="col" class="text-center">تاریخ</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pageData['donates'] as $don)
                    <tr>
                        <th scope="row" class="text-center">1</th>
                        <td class="text-center">{{$don['donator']}}</td>
                        <td class="text-center">{{$don['donatee']}}</td>
                        <td class="text-center">{{$don['amount']}} تومان</td>
                        <td class="text-center">{{$don['date']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if($pageData['donates'] == [])
                <p class="text-center" style="margin: auto">
                    گزارشی در این دسته ثبت نشده است
                </p>
            @endif
        </div>

    </div>
@endsection

@section('script')
    <script>
        var charges = document.getElementById('charges');
        var subscribes = document.getElementById('subscribes');
        var donates = document.getElementById('donates');
        function fadeOutAll(){
            $(charges).hide();
            $(subscribes).hide();
            $(donates).hide();

        }
        function showTrans(num){
            fadeOutAll();
            if (num == 1){
                $(charges).fadeIn(500);
            } else if (num == 2){
                $(subscribes).fadeIn(500);
            } else {
                $(donates).fadeIn(500);
            }
        }
    </script>
    @include('partials.toast_script')
    @if(!is_null($cu))
        @include('partials.chargebox_script')
    @endif
@endsection
