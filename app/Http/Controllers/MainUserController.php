<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Donate;
use App\Models\Post;
use App\Models\Subscribe;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use MongoDB\Driver\Session;
use Morilog\Jalali\Jalalian;
use function PHPUnit\Framework\isEmpty;

class MainUserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public const Admin = 3;
    public const Writer = 2;
    public const NormalUser = 1;

    Protected const prevalidationRules = [
        'name' => ['required', 'string', 'min:3'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string'],
        'role'   => ['required', 'integer'],
        'bio'   => ['required', 'max:500'],
        'avatar' => ['nullable']
    ];

    Protected const prevalidationMessages = [
            'name.required'=>'وارد کردن نام الزامی است',
            'name.min'=>'برای نام حداقل باید 3 حرف وارد کنید' ,
            'email.required'=>'وارد کردن ایمیل الزامی است',
            'email.email'=>'ایمیل را به درستی وارد کنید',
            'email.unique' => 'این ایمیل قابل استفاده نیست',
            'password.required'=>'وارد کردن رمز عبور الزامی است',
            'role.required'=>'انتخاب نقش الزامی است',
            'bio.required'=>'وارد کردن توضیحات درباره کاربر الزامی است',
            'bio.max' => 'طول توضیحات کاربر بیشتر از حد مجاز است'
    ];

    protected function myCrypt(string $s){
        return md5($s);
    }
    protected function showRegisterForm(){
        return view('form.register');
    }

    protected static function avatar($data,$request){
        $file = $request->file('avatar');
        if(!is_null($file)){
            $path = $file->store('avatars');
            $data['avatar'] = $path;
        }else
            $data['avatar'] = null;
        return $data;
    }

    private static function deleteLoginSession(){
        session()->pull('currentUser');
        session()->pull('roleDcb');
    }
    public static function createLoginSession(User $user){
        $roleDcb = self::describeRole($user);
        self::deleteLoginSession();
        session([
            'currentUser' => $user,
            'roleDcb' => $roleDcb
        ]);
    }


    protected function register(){
        $data = $this->validate(request(),
            array_merge(self::prevalidationRules, ['repass' => 'required']),
            array_merge(self::prevalidationMessages, ['repass.required' => 'تکرار رمز الزامی است'])
        );
        if($data['role'] != self::NormalUser && $data['role'] != self::Writer)
            $data['role'] = null;
        if($data['password'] != $data['repass'])
            return redirect()->back()->withErrors(['msg' => 'رمز و با تکرار آن مطابقت ندارد']);
        $data['password'] = $this->myCrypt($data['password']);
        unset($data['repass']);
        $data = self::avatar($data,request());
        $data['wallet'] = null;
        $user = User::create($data);
        $user->role = (int)($data['role']);
        $user->save();

        self::createLoginSession($user);

        return redirect()->to('/home')->with([
            'success'=>true,
            'msg' => 'خوش آمدید'
        ]);
    }

    private static function describeRole(User $user){
        switch ($user->role){
            case self::Admin:
                return 'مدیر سایت';
            case self::Writer:
                return 'نویسنده';
            case self::NormalUser:
                return 'کاربر';
            default:
                return null;
        }
    }

    protected function showLoginForm(){
        return view('form.login');
    }
    protected function login(){
        $data = $this->validate(request(),[
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ],[
            'email.required'=>'وارد کردن ایمیل الزامی است',
            'email.email'=>'ایمیل را به درستی وارد کنید',
            'password.required'=>'وارد کردن رمز عبور الزامی است',
        ]);
        $user = User::query()->where('email','=',$data['email'])->get('*');
        if($user->isEmpty())
            return redirect()->back()->withErrors([
                'msg' => 'کاربری با این ایمیل یافت نشد'
            ]);
        $user = $user[0];
        if($user->password != $this->myCrypt($data['password']))
            return redirect()->back()->withErrors([
                'msg' => 'رمز وارد شده صحیح نیست'
            ]);

        self::createLoginSession($user);
        return redirect()->to('/home')->with([
            'success'=>true,
            'msg' => 'خوش آمدید'
        ]);
    }

    public function edit(int $userID){
        $cu = session('currentUser');
        if(is_null($cu) || $cu->id != $userID)
            return redirect()->back()->withErrors([
                'msg' => 'شما به این صفحه دسترسی ندارید'
            ]);
        session([
            'afterEditURL' => url()->previous()
        ]);
        return view('form.edit',[
            'theUser' =>  User::find($userID)
        ]);
    }
    public function update(int $userID){
        if(is_null(session('afterEditURL')))
            return redirect()->back()->withErrors([
                'msg'=>'در بارگذاری صفحه اشکالی رخ داده است'
            ]);
        $cu = session('currentUser');
        if(is_null($cu) || $cu->id != $userID)
            return redirect()->to(session()->pull('afterEditURL'))->withErrors([
                'msg' => 'درخواست غیر مجاز'
            ]);
        $user = User::find($userID);
        $vr = self::prevalidationRules;
        $vr['password'] = ['nullable', 'string'];
        $vr['repass'] = ['nullable', 'string'];
        $vr['email'] = ['required', 'string', 'email', 'max:255'];
        $data = $this->validate(request(), $vr, self::prevalidationMessages);
        if($data['role'] != self::NormalUser && $data['role'] != self::Writer)
            $data['role'] = null;
        if($data['password'] == null){
            $data['password'] = $user->password;
        }else{
            if($data['password'] != $data['repass'])
                return redirect()->back()->withErrors(['msg' => 'رمز با تکرار آن مطابقت ندارد']);
            $data['password'] = $this->myCrypt($data['password']);
        }
        unset($data['repass']);

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

        $data['wallet'] = $user->wallet;
        $user->edit($data);
        $user->role = (int)($data['role']);
        $user->save();
        session('currentUser')->name = $data['name'];

        return redirect()->to(session()->pull('afterEditURL'))->with([
            'success'=>true,
            'msg' => 'تغییرات با موفقیت ثبت شد'
        ]);
    }

    protected function exitAccount(){
        self::deleteLoginSession();
        return redirect()->back()->with([
            'success'=>true,
            'msg' => 'روز خوبی داشته باشید'
        ]);
    }

    public static function getUserRate(User $user){
        if($user->posts->count() == 0)
            return 0;
        $posts = $user->posts;
        $sum = 0;
        foreach($posts as $post){
            $sum += $post->rate;
        }
        $result = ((double)$sum)/$user->posts->count();
        $result = round($result, 1);
        return $result;
    }

    public static function hasSubscribtion($user){
        if(is_null($user) || get_class($user) != User::class || $user->subscribes->isEmpty())
            return false;
        if($user->role == self::Admin)
            return true;
        $last_subscribe = $user->subscribes->last();
        return $last_subscribe->expire_date > Controller::getCurrentTime();
    }

    public function showProfile(int $userID)
    {
        $user = User::find($userID);
        if(is_null($user))
            return redirect()->to('/home')->withErrors([
                'msg'=>'صفحه مورد نظر یافت نشد'
            ]);
        if ($user->role == self::Admin) {
            $user->role = self::Writer;
            $data = $user->getFullInfo();
            $user->role = self::Admin;
        } else
            $data = $user->getFullInfo();
        return view("profile", ['pageData' => $data]);
    }
    public static function getTransactions(User $user = null){
        if(is_null($user)){
            $donates = Donate::query()->get()->all();
            $subs = Subscribe::query()->get()->all();
            $charges = Charge::query()->get()->all();
        }else{
            $donates = Donate::query()->where('donator', '=', $user->id)
                ->orWhere('donatee', '=', $user->id)->get()->all();
            $subs = Subscribe::query()->where('user_id', '=', $user->id)->get()->all();
            $charges = Charge::query()->where('user_id', '=', $user->id)->get()->all();
        }
        usort($donates, function ($a, $b){
            return (strtotime($b['created_at']) - strtotime($a['created_at']));
        });
        $donates = Controller::processDonates($donates);
        usort($subs, function ($a, $b){
            return (strtotime($b['created_at']) - strtotime($a['created_at']));
        });
        $subs = Controller::processSubscribes($subs);
        usort($charges, function ($a, $b){
            return (strtotime($b['created_at']) - strtotime($a['created_at']));
        });
        $charges = Controller::processCharges($charges);
        $data = [
            'donates' => $donates,
            'subscribes' => $subs,
            'charges' => $charges,
        ];
        return $data;
    }
    public function mytransactions(){
        if(is_null(session('currentUser')))
            return redirect()->to('/home')->withErrors([
                'msg' => 'برای مشاهده این صفحه باید وارد شوید'
            ]);
        $cu = session('currentUser');
        if(is_null($cu))
            return redirect()->back()->withErrors([
                'msg' => 'برای مشاهده این قسمت ابتدا باید وارد شوید'
            ]);
        return view('panel.trans', ['pageData' => self::getTransactions($cu)]);
    }

    public function userPosts(int $userID){
        $user = User::find($userID);
        if (is_null($user))
            return redirect()->back()->withErrors([
                'msg' => 'کاربر مورد نظر یافت نشد'
            ]);
        if ($user->role == self::NormalUser)
            return redirect()->back()->withErrors([
                'msg' => 'این کاربر نویسنده نیست'
            ]);
        $posts = $user->posts;
        $posts = Controller::processPostsToShow($posts);
        return view('panel.posts')->with([
            'posts' => $posts,
            'title' => 'پست های من'
        ]);
    }
}
