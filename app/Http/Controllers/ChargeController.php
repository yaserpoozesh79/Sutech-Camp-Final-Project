<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\User;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    public function charge(){
        $currentUser = session('currentUser');
        if(is_null($currentUser))
            return redirect()->back()->withErrors([
                'msg'=>'برای شارژ حساب ابتدا باید وارد شوید'
            ]);
        $amount = \request()->input()['amount'];
        if($amount < 1000)
            return redirect()->back()->withErrors([
                'msg'=>'مبلغ شارژ حداقل باید ۱۰۰۰ تومان باشد.'
            ]);
        $charge = Charge::create([
            'user_id'=>$currentUser->id,
            'amount'=>$amount,
            'charge_date'=>Controller::getCurrentTime()
        ]);
        $currentUser->wallet += $amount;
        MainUserController::createLoginSession($currentUser);
        $currentUser->save();
        $charge->save();
        return redirect()->to(url()->previous())->with([
            'success' => true,
            'msg'=>'شارژ با موفقیت انجام شد'
        ]);
    }
}
