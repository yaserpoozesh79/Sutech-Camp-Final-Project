
<div class="modal fade upgrade-modal" id="upgrade_account" tabindex="-1" aria-labelledby="upgrade_account_label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="upgrade_account_label">ارتقا حساب کاربری</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="upgrade-form" action="{{url("/buy-sub/$userID")}}" class="upgrade-acc" method="post">
                    @csrf
                    @foreach(\App\Http\Controllers\SubscribeController::sub_types as $key=> $type)
                        <div class="plan">
                            <input type="radio" name="vip_plan" id="vip{{$loop->iteration-1}}" {{$loop->iteration==3 ? "checked" : ""}} value="{{$loop->iteration-1}}">
                            <label for="vip{{$loop->iteration-1}}" class="plan-content">
                                <div class="icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="text">
                                    <h4 class="plan-name">{{$type['title']}}</h4>
                                    <p class="plan-desc">{{round($type['time-in-days']/30)}} ماه دسترسی نامحدود</p>
                                </div>
                                <div class="price">
                                    {{$type['price']}} تومان
                                </div>
                            </label>
                        </div>
                    @endforeach

                    <div class="payment-info">
                        @if($cu->role != \App\Http\Controllers\MainUserController::Admin)
                            <div class="info">
                                <h6>
                                    موجود کیف پول شما:
                                    <span>{{$cu->wallet}}</span> تومان
                                </h6>
                                <a onclick="modalfunc(true)" style="cursor: pointer; color:dodgerblue">شارژ حساب</a>
                            </div>
                        @endif
                        <button type="submit">
                            <i class="fas fa-rocket"></i>
                            ارتقا بده!
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
