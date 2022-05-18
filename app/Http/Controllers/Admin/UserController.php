<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\MainUserController;
use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Comment;
use App\Models\Donate;
use App\Models\Post;
use App\Models\Subscribe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Transport\SesTransport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;

class UserController extends MainUserController
{

    public function test(){
        dd(Controller::toJalalian(Carbon::now()));
    }
    public function test2(){
        return view('test');
    }


    public static function usersList(){
        $currentUser = session('currentUser');
        if(is_null($currentUser) || $currentUser->role != self::Admin)
            return redirect()->to('/home')->withErrors([
                'msg' => 'اجازه دسترسی به این صفحه را ندارید'
            ]);
        else
            return view('panel.index')->with(['users' => User::all()]);
    }
    public static function postsList(){
        $posts = Post::all();
        $posts = Controller::processPostsToShow($posts);
        return view('panel.posts')->with([
            'posts' => $posts,
            'title' => 'پست های کاربران'
        ]);
    }

    public function show_addUserForm(){
        $currentUser = session('currentUser');
        if(is_null($currentUser) || $currentUser->role != self::Admin)
            return redirect()->to('/home')->withErrors([
                'msg' => 'اجازه دسترسی به این صفحه را ندارید'
            ]);
        return view('panel.add');
    }
    public function transactions(){
        $currentUser = session('currentUser');
        if(is_null($currentUser) || $currentUser->role != self::Admin)
            return redirect()->to('/home')->withErrors([
                'msg' => 'اجازه دسترسی به این صفحه را ندارید'
            ]);
        return view('panel.trans', ['pageData' => self::getTransactions(null)]);
    }

    public function add() {
        $currentUser = session('currentUser');
        if(is_null($currentUser) || $currentUser->role != self::Admin)
            return redirect()->to('/home')->withErrors([
                'msg' => 'اجازه دسترسی به این صفحه را ندارید'
            ]);
        $input = \request()->input();
        $data = $this->validate(request(),
            array_merge(self::prevalidationRules, ['wallet' => 'required']),
            array_merge(self::prevalidationMessages, ['wallet.required'=>'وارد کردن موجودی کیف پول الزامی است'])
        );
        $data['password'] = $this->myCrypt($data['password']);
        $data = self::avatar($data,request());
        $user = User::create($data);
        $user->wallet = $input['wallet'];
        $user->role   = $input['role'];
        $user->save();

        return redirect()->back()->with([
            'success'=>true,
            'msg' => 'کاربر با موفقیت ثبت شد'
        ]);
    }

    public function edit($id){
        $currentUser = session('currentUser');
        if(is_null($currentUser) || $currentUser->role != self::Admin)
            return redirect()->to('/home')->withErrors([
                'msg' => 'اجازه دسترسی به این صفحه را ندارید'
            ]);
        $user = User::find($id);
        if(!Storage::disk('local')->exists($user->avatar))
            $user->avatar = null;
        return view("panel.edit")->with(['theUser' => $user]);
    }

    public function update($id){
        $currentUser = session('currentUser');
        if(is_null($currentUser) || $currentUser->role != self::Admin)
            return redirect()->to('/home')->withErrors([
                'msg' => 'اجازه دسترسی به این صفحه را ندارید'
            ]);
        $data = $this->validate(request(),[
            'name'   => 'required|min:3',
            'email'  => 'required|email',
            'role'   => 'required',
            'password' => '',
            'wallet'   => 'required',
            'bio'   => 'required|max:500',
            'avatar' => ''
        ], [
            'name.required'=>'وارد کردن نام الزامی است',
            'name.min'=>'برای نام حداقل باید 3 حرف وارد کتید' ,
            'email.required'=>'وارد کردن ایمیل الزامی است',
            'email.email'=>'ایمیل را به درستی وارد کنید',
            'role.required'=>'انتخاب نقش الزامی است',
            'wallet.required'=>'وارد کردن موجودی کیف پول الزامی است',
            'bio.required'=>'وارد کردن توضیحات درباره کاربر الزامی است'
        ]);

        $user = User::find($id);

        $pss = $data['password'];
        $data['password'] = is_null($pss) ? $user->password : $this->myCrypt($pss);

        if(request()->input()['selectAvatar'] === 'yes'){
            $file = request()->file('avatar');
            if(!is_null($file)){
                $path = $file->store('avatars');
                $data['avatar'] = $path;
            }else
                $data['avatar'] = $user->avatar;
        }else {
            if (!is_null($user->avatar))
                Storage::delete($user->avatar);
        }

        $user->edit($data);
        $user->save();
        return redirect('/panel/users')->with([
            'success'=>true,
            'msg' => 'اطلاعات با موفقیت ثبت شد'
        ]);
    }

    public function delete($id){
        $currentUser = session('currentUser');
        if(is_null($currentUser) || $currentUser->role != self::Admin)
            return redirect()->to('/home')->withErrors([
                'msg' => 'اجازه دسترسی به این صفحه را ندارید'
            ]);
        $user = User::find($id);

        $success = false;
        $msg = 'کاربر با این شناسه یافت نشد';
        if(!is_null($user)){
            $user->delete();
            $success = true;
            $msg = 'حذف موفقیت آمیز بود';
        }
        return redirect('/panel/users')->with([
            'success'=>$success,
            'msg' => $msg
        ]);
    }

}


