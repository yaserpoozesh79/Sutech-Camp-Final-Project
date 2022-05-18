<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    private const categories = ['ورزشی','مذهبی','سرگرمی','طبیعت','علمی','آموزشی', 'هنری'];

    public static function getCategories(){
        return self::categories;
    }

    protected $fillable = [
        'author',
        'category_id',
        'title',
        'slug',
        'content',
        'thumbnail',
        'type'
    ];
    protected $guarded = [
        'views',
        'rate',
        'raters'
    ];

    public function getAuthor(){
        return $this->belongsTo(User::class,'author','id');
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public static function create(array $data){
        $post = new Post();
        $post->edit($data);
        return $post;
    }

    public function edit(array $data){
        $this->title = $data['title'];
        $this->slug = $data['slug'];
        $this->category_id = (int)($data['category']);
        $this->content = $data['content'];
        $this->thumbnail = $data['thumbnail'];
        $this->type = (int)($data['type']);
        if(isset($data['views'])) $this->views = (int)($data['views']);
        if(isset($data['rate'])) $this->rate = (int)($data['rate']);
        if(isset($data['raters'])) $this->raters = (int)($data['raters']);
    }

    public function addToRate(int $value){
        $raters = $this->raters;
        $rate = $this->rate;
        $sum = $raters * $rate + $value;
        $raters+=1;
        $rate = round($sum/$raters, 1);
        $this->raters = $raters;
        $this->rate = $rate;
    }
}
