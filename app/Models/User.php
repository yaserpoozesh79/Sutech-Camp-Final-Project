<?php

namespace App\Models;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainUserController;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Morilog\Jalali\Jalalian;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $guarded = [
        'Wallet',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function posts(){
        return $this->hasMany(Post::class, 'author');
    }

    public function subscribes(){
        return $this->hasMany(Subscribe::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function charges(){
        return $this->hasMany(Charge::class);
    }

    public function receivedDonates(){
        return $this->hasMany(Donate::class, 'donatee');
    }

    public function gaveDonates(){
        return $this->hasMany(Donate::class, 'donator');
    }

    public function nextSubStartTime():Carbon{
        if($this->subscribes->isEmpty())
            return Carbon::now();
        $lastSub = $this->subscribes->last();
        if ($lastSub->expire_date->timestamp < Carbon::now()->timestamp)
            return Carbon::now();
        else
            return $lastSub->expire_date;
    }

    public static function create(array $data)
    {
        return new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => (int)($data['role']),
            'password' => $data['password'],
            'wallet' => $data['wallet'] ?? null,
            'bio' => $data['bio'],
            'avatar' => $data['avatar'] ?? "avatars/default-avatar.png"
        ]);
    }

    public function edit(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->role = $data['role'];
        $this->password = $data['password'];
        $this->wallet = $data['wallet'];
        $this->bio = $data['bio'];
        $this->avatar = $data['avatar']?? "avatars/default-avatar.png";
    }

    public function is_admin()
    {
        return $this->role == MainUserController::Admin;
    }

    private function processUsersForProfile(Collection $users){
        $temp = array();
        foreach($users as $key => $user){
            $temp[$key] = [
                'id' => $user->id,
                'email' => $user->email,
                'join_date' => Controller::toJalalian($user->created_at),
            ];
        }
        return $temp;
    }

    const MaxActivitiesToShow = 3;
    private function getBasicInfo_forPanel()
    {
        $donates = Donate::query()->where('donator', '=', $this->id)
                    ->orWhere('donatee', '=', $this->id)->get()->all();
        $donates = Controller::processDonates($donates);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'join-date' => Controller::toJalalian($this->created_at),
            'avatar' => Storage::url($this->avatar),
            'last-visit' => 'نامعلوم',
            'wallet' => $this->wallet,
            'bio' => $this->bio,
            'activities' => Controller::processCommentsForOutput($this->comments->take(self::MaxActivitiesToShow)),
            'subscribes' => Controller::processSubscribes($this->subscribes),
            'charges' => Controller::processCharges($this->charges),
            'donates' => $donates,
        ];
    }
    private function getWriterInfo_forPanel(array $data_to_merge = null){
        $data = array();

        $data['rate'] = MainUserController::getUserRate($this);

        $posts_ids = array();
        $posts = $this->posts;
        foreach($posts as $index => $post){ $posts_ids[$index] = $post->id; }
        $data['received-comments'] = Controller::processCommentsForOutput(
            Comment::query()->whereIn('post_id', $posts_ids)->limit(3)->get()
        );
        $data['feedbacks-count'] = Comment::query()->whereIn('post_id', $posts_ids)->count();

        $self_posts = $this->posts()->take(self::MaxActivitiesToShow)->get(['id', 'author', 'title', 'created_at', 'category_id', 'content']);
        $self_posts = Controller::processPostsToShow($self_posts);
        $data['posts-count'] = $this->posts->count();
        $data_to_merge['activities'] = array_merge($data_to_merge['activities'],$self_posts);

        usort($data_to_merge['activities'], function ($a, $b){
            return strtotime($b['date']) - strtotime($a['date']);
        });

        for($i=3 ; $i<2*self::MaxActivitiesToShow ; $i++)
            unset($data_to_merge['activities'][$i]);

        if(is_null($data_to_merge))
            return $data;
        else
            return array_merge($data_to_merge,$data);
    }

    private function getUsersInfo(){
        $users = $this->processUsersForProfile(User::all(['id', 'email', 'created_at']));
        $donates = Controller::processDonates(Donate::all());
        $subs = Controller::processSubscribes(
            Subscribe::all(['user_id', 'amount', 'buy_date', 'expire_date'])
        );
        $charges = Controller::processCharges(Charge::all());

        return [
            'users' => $users,
            'donates' => $donates,
            'dubscribes' => $subs,
            'chareges' => $charges,
        ];
    }

    public function getFullInfo(){
        $data = $this->getBasicInfo_forPanel();
        switch($this->role){
            case UserController::Admin:
                $data = $this->getWriterInfo_forPanel($data);
                $data = array_merge($data,$this->getUsersInfo());
                break;
            case UserController::Writer:
                $data = $this->getWriterInfo_forPanel($data);
                break;
            case UserController::NormalUser:
                break;
        }
        return $data;
    }
}
