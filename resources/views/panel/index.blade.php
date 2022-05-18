@extends('layouts.panel', [
    'title' => 'کاربران سایت'
])

@section('body')

    @include('partials.upgrade',[
        'userID' => "",
        'cu' => session('currentUser')
        ])
    <div class="d-flex flex-row justify-content-center" style="background-color:transparent;padding: 0px">
        @include('partials.toast')
        @if(!is_null($users) && !$users->isEmpty())
            <table class="table table-hover bg-gray p-5 rounded" style="border-radius:20px;width:800px">
                <thead>
                <tr>
                    <th scope="col" class="text-center">ردیف</th>
                    <th scope="col" class="text-center">نام و نام خانوادگی</th>
                    <th scope="col" class="text-center">ایمیل</th>
                    <th scope="col" class="text-center">عملیات</th>
                </tr>
                </thead>
            @foreach($users as $user)
                <tbody>
                <tr>
                    <th scope="row" class="text-center">{{$loop->iteration}}</th>
                    <td class="text-center">{{$user->name}}</td>
                    <td class="text-center"><a title="پروفایل" href="{{url('/profile/'.$user->id)}}">{{$user->email}}</a></td>
                    <td class="text-center">
                        <a href="{{url("/panel/edit/$user->id")}}">ویرایش</a> | <a href="{{url("/panel/delete/$user->id")}}">حذف</a>
                        @if($user->role != \App\Http\Controllers\MainUserController::Admin)
                            |
                            <button class="btn-upgrade" onclick="setID({{$user->id}})" type="submit" data-bs-toggle="modal" data-bs-target="#upgrade_account">
                                <i class="fas fa-crown"></i>
                                <span>ثبت اشتراک!</span>
                            </button>
                        @else
                            |
                            <button class="btn btn-secondary rounded" >
                                <i class="fas fa-crown"></i>
                                <span>کاربر مدیر</span>
                            </button>
                        @endif
                    </td>
                </tr>
                </tbody>
            @endforeach
            </table>
        @else
            <div class="bg-dark text-white p-md-2 rounded text-center" style="width:800px;height:fit-content;font-size:15px">کاربری در سیستم وجود ندارد</div>
        @endif
    </div>

@endsection

@section('script')
    @include('partials.toast_script')
    <script>
        var addressForSub = '{{url('/buy-sub/')}}' + '/';
        function setID(id){
            $('#upgrade-form').attr('action',addressForSub+id)
        }
    </script>
@endsection

