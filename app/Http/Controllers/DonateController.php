<?php

namespace App\Http\Controllers;

use App\Models\Donate;
use App\Models\User;
use Carbon\Carbon;

class DonateController extends Controller
{
    const DisappointedFace = "<i class='fas fa-meh'></i>";
    public function add(){
        $donatee_id = request()->input()['author_id'];
        $currentUser = session('currentUser');
        if(is_null($currentUser))
            return response()->json([
                'success' => false,
                'bg' => 'red',
                'sticker' => self::DisappointedFace,
                'header' => 'شرمنده!',
                'msg' => 'به مشکل خوردیم(کاربر وارد نشده)'
            ]);
        $donatee = User::find($donatee_id);
        if(is_null($donatee))
            return response()->json([
                'success' => false,
                'bg' => 'yellow',
                'sticker' => self::DisappointedFace,
                'header' => 'شرمنده!',
                'msg' => 'به مشکل خوردیم(کاربر پیدا نشد)'
            ]);
        $amount = request()->input()['amount'];
        if($amount < 1000)
            return response()->json([
                'success' => false,
                'bg' => 'grey',
                'sticker' => self::DisappointedFace,
                'header' => 'دوست عزیز!',
                'msg' => 'کمتر از 1000 تومن نمیشه زد'
            ]);
        if($amount > $currentUser->wallet)
            return response()->json([
                'success' => false,
                'bg' => 'grey',
                'sticker' => "<i class='fas fa-kiss-wink-heart'></i>",
                'header' => 'اینقد نداری رفیق!',
                'msg' => 'جیبت خالیه اما دلت یه دریاست'
            ]);

        $donatee->wallet += $amount;
        $currentUser->wallet -= $amount;
        $donatee->save();
        $currentUser->save();
        Donate::create([
            'donatee' => $donatee->id,
            'donator' => $currentUser->id,
            'amount' => $amount,
            'donate_date' => Carbon::now()
        ])->save();
        return response()->json([
            'success' => true,
            'bg' => '#50ac23',
            'sticker' => "<i class='fas fa-smile-wink'></i>",
            'header' => 'مرسی!',
            'msg' => 'براش فرستادیم!'
        ]);

    }
}
