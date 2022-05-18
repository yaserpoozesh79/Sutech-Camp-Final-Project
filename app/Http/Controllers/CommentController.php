<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function add(int $postID){
        $currentUser = session('currentUser');
        if(is_null($currentUser))
            return redirect()->back()->withErrors(['msg' => 'برای ثبت دیدگاه باید وارد شوید']);
        if(!Post::query()->find($postID)->exists())
            return redirect()->back()->withErrors(['msg' => 'اشکال در ارسال اطلاعات (پست یافت نشد)']);
        $comment_text = \request()->input()['comment'];
        $newComment = Comment::create([
            'user_id' => $currentUser->id,
            'post_id' => $postID,
            'content' => trim($comment_text),
            'date' => self::getCurrentTime()
        ]);
        $newComment->save();
        return redirect()->back()->with([
            'success' => true,
            'msg' => 'دیدگاه با موفقیت ثبت شد'
            ]);
    }
}
