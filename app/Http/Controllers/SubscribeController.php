<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    const sub_types = [
        [
            'title' => 'اشتراک ویژه طرح عادی',
            'price' => 15000,
            'time-in-days' => 30
        ],
        [
            'title' => 'اشتراک ویژه طرح طلایی',
            'price' => 40000,
            'time-in-days' => 90
        ],
        [
            'title' => 'اشتراک ویژه طرح لاکچری',
            'price' => 75000,
            'time-in-days' => 180
        ]
    ];

    public function add(int $userID){
        $user = User::find($userID);
        if(is_null($user))
            return redirect()->back()->withErrors([
                'msg' => 'ابتدا باید وارد شوید'
            ]);
        if(MainUserController::hasSubscribtion($user))
            return redirect()->back()->withErrors([
                'msg' => 'امکان تمدید اشتراک وجود ندارد'
            ]);
        $sub_index = \request()->input('vip_plan');
        $amount = self::sub_types[$sub_index]['price'];
        $startTime = $user->nextSubStartTime();
        $expireTime = strtotime("+".self::sub_types[$sub_index]['time-in-days']." days",$startTime->timestamp);
        $expireDate = Carbon::createFromTimestamp($expireTime);

        $cu = session('currentUser');
        $is_currenUser_admin = $cu->role == MainUserController::Admin;
        if (is_null($cu))
            return redirect()->back()->withErrors([
                'msg' => 'برای خرید اشتراک باید ابتدا وارد شوید'
            ]);
        if(!$is_currenUser_admin && $amount > $cu->wallet)
            return redirect()->back()->withErrors([
                'msg' => 'موجودی شما برای خرید این بسته کافی نیست'

            ]);
        $newSub = Subscribe::create([
            'user_id' => $userID,
            'buy_date' => $startTime,
            'expire_date' => $expireDate,
            'amount' => $amount
        ]);
        $newSub->expire_date = $expireDate;
        $newSub->amount = $amount;
        if(!$is_currenUser_admin)
            $cu->decrement('wallet',$amount);
        $newSub->save();
        $returnBackMessage = $is_currenUser_admin? "اشتراک با موفقیت برای $user->name ثبت شد" : "اشتراک با موفقیت برای $user->name خریداری شد";
        if(!$is_currenUser_admin)
            MainUserController::createLoginSession(User::find($userID));
        return redirect()->back()->with([
            'success' => true,
            'msg' => $returnBackMessage
        ]);
    }

}
