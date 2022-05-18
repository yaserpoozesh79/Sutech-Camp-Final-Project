<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public static function getCurrentTime(){
        return Carbon::now()->toDateTimeString();
    }
    public static function getDifferenceInDays($date1,$date2){
        if(is_string($date1)) $date1 = strtotime($date1);
        else                  $date1 = strtotime($date1->toDateString());

        if(is_string($date2)) $date2 = strtotime($date2);
        else                  $date2 = strtotime($date2->toDateString());

        $diff = abs($date2 - $date1);
        return (int)floor($diff / (60*60*24));
    }
    public static function substr_words(string $text, int $words_count){
        $counter = 0;
        for($i=0 ; $i<strlen($text) ; $i++){
            if($text[$i] == ' ')
                $counter++;
            if($counter == $words_count)
                return substr($text, 0, $i);
        }
        return trim($text);
    }
    public static function processCommentsForOutput($comments){  // must be iterable and it's elements must be model
        $temp = array();
        foreach ($comments as $index => $comment){
            $comment_author = $comment->user;
            $comment_receiver = $comment->post->getAuthor;
            $temp[$index] = [
                'date' => Controller::toJalalian($comment->created_at),
                'user-avatar' => Storage::url($comment_author->avatar),
                'user-name' => $comment_author->name,
                'age' => self::getDifferenceInDays($comment->date,self::getCurrentTime()),
                'body' => $comment->content,
                'receiver-name' => $comment_receiver->name
            ];
        }
        return $temp;
    }

    public static function processCharges($charges){ // must be iterable and it's elements must be model
        $temp = array();
        foreach ($charges as $key => $charge) {
            $temp[$key] = [
                'user' => User::find($charge->user_id)->email,
                'amount' => $charge->amount,
                'date' => Controller::toJalalian($charge->charge_date),
            ];
        }
        return $temp;
    }

    public static function processSubscribes($subs){ // must be iterable and it's elements must be model
        $temp = array();
        foreach ($subs as $key => $sub) {
            $type = Controller::getDifferenceInDays($sub->buy_date, $sub->expire_date);
            $type = round($type / 30);
            $temp[$key] = [
                'user' => User::find($sub->user_id)->email,
                'amount' => $sub->amount,
                'buy_date' => Controller::toJalalian($sub->buy_date),
                'expire_date' => Controller::toJalalian($sub->expire_date),
                'type' => (int)$type
            ];
        }
        return $temp;
    }

    public static function processDonates($donates){ // must be iterable and it's elements must be model
        $temp = array();
        foreach ($donates as $key => $donate) {
            $temp[$key] = [
                'donator' => User::find($donate->donator)->email,
                'donatee' => User::find($donate->donatee)->email,
                'amount' => $donate->amount,
                'date' => Controller::toJalalian($donate->donate_date),
            ];
        }
        return $temp;
    }

    public static function processPostsToShow($posts){ // must be iterable and it's elements must be model
        $temp = array();
        foreach ($posts as $index => $post) {
            $author = $post->getAuthor;
            $temp[$index] = [
                'author-id' => $author->id,
                'author-name' => $author->name,
                'author-avatar' => Storage::url($author->avatar),
                'id'=>$post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'category' => Post::getCategories()[$post->category_id],
                'thumbnail' => $post->thumbnail,
                'content' => Controller::substr_words(strip_tags($post->content), 40),
                'date' => Controller::toJalalian($post->created_at),
                'rate' => $post->rate,
                'views' => $post->views,
                'type' => $post->type,
                'age' => Controller::getDifferenceInDays($post->created_at, Carbon::now()),
                'comments-count' => $post->comments->count()
            ];
        }
        return $temp;
    }
    public static function toJalalian(Carbon $date){
        return Jalalian::fromCarbon($date)->format('%d %B %Y');
    }
}
