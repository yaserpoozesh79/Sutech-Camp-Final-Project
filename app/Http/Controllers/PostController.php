<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainUserController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    const MaxPostsInHomePage = 3;

    public function choose(array $array,int $pageNum,int $count){
        $temp = array();
        for($i = ($pageNum-1)*self::MaxPostsInHomePage ; $i<$pageNum*self::MaxPostsInHomePage && $i<count($array); $i++){
            array_push($temp,$array[$i]);
        }
        return $temp;
    }
    public function homePage(int $pageNum){
        $temp = Post::all();
        $posts = array();
        foreach ($temp as $index => $value){
            $posts[$index] = $temp[$index];
        }
        usort($posts, function ($a, $b){
            return (strtotime($b->created_at) - strtotime($a->created_at));
        });
        $numberOfPages = (int)ceil(count($posts)/self::MaxPostsInHomePage);
        if($numberOfPages < 1)
            $numberOfPages = 1;
        if($pageNum < 1)
            $pageNum = 1;
        if($pageNum > $numberOfPages)
            $pageNum = $numberOfPages;
        $newestPosts = $this->choose($posts,$pageNum,self::MaxPostsInHomePage);
        usort($posts, function ($a, $b){
            return (strtotime($b->rate) - strtotime($a->rate));
        });
        if (count($posts)>self::MaxPostsInHomePage)
            for ($i = self::MaxPostsInHomePage ; $i<count($posts) ; $i++)
                unset($posts[$i]);

        return view('home',[
            'news' => Controller::processPostsToShow($newestPosts),
            'trends' => Controller::processPostsToShow($posts),
            'pageNumber' => $pageNum,
            'lastPageNumber' => $numberOfPages
        ]);
    }
    public function homePage1(){
        return redirect()->to('/home/page=1');
    }

    public function showPostForm(){
        $currentUser = session('currentUser');
        if(is_null($currentUser)) {
            return redirect()->to('/home')->withErrors([
                'msg' => 'باید ابتدا وارد حساب خود شوید'
            ]);
        }else{
            if($currentUser->role == MainUserController::NormalUser)
                return redirect()->to('/home')->withErrors([
                    'msg' => 'شما به این قسمت دسترسی ندارید'
                ]);
            else
                return view('form.newPost')->with([
                    'categories' => Post::getCategories()
                ]);
        }
    }

    public function add(){
        if(is_null(session('currentUser'))) {
            return redirect()->to('/home')->withErrors([
                'msg' => 'باید ابتدا وارد حساب خود شوید'
            ]);
        }
        $cat_len = count(Post::getCategories());
        $data = $this->validate(request(), [
            'title'    => 'required|min:5|max:255',
            'category' => "required|integer|between:0,$cat_len",
            'slug'     => 'required|min:3|unique:posts',
            'content'  => 'required|min:50',
            'thumbnail'=> '',
            'type'=>'required|integer|boolean'
        ], [
            'title.required' => 'وارد کردن عنوان الزامی است.',
            'title.min' => 'عنوان حداقل باید ۵ کاراکتر باشد.',
            'title.max' => 'عنوان بیش از حد طولانی است',
            'category.required' =>'وارد کردن دسته بندی الزامی است',
            'category.integer' => 'دسته بندی به درستی وارد نشده',
            'category.between' => 'دسته بندی وارد شده غیر مجاز است',
            'slug.required' => 'وارد کردن نامک الزامی است',
            'slug.min' => 'حداقل طول مجاز برای نامک رعایت نشده است',
            'slug.unique' => 'این نامک قبلا استفاده شده است',
            'content.required' => 'وارد کردن محتوای مقاله الزامی است',
            'content.min' => 'مارکاپ محتوا حداقل باید 50 حرف باشد',
            'type.required' => 'وارد کردن نوع مقاله الزامی است',
            'type.integer' => 'در وارد کردن نوع اشکالی رخ داده است',
            'type.boolean' => 'نوع وارد شده غیر مجاز است'
        ]);

        if($data['type'] == 1 && strlen($data['content']) < 300)
            return redirect()->back()->withErrors([
                'msg' => 'مارکاپ محتوای ویژه حداقل باید 300 حرف باشد',
            ]);
        $file = \request()->file('thumbnail');
        if(!is_null($file)){
            $path = $file->store('thumbnails');
            $data['thumbnail'] = $path;
        }else
            $data['thumbnail'] = 'thumbnails/default-thumbnail.jpg';

        $post = Post::create($data);
        $post->author = session('currentUser')->id;
        $post->save();

        return redirect()->to('/post/single-post/'.$data['slug'])->with([
            'success' => true,
            'msg' => 'مطلب جدید منتشر شد',
        ]);
    }

    public function showSinglePost(string $slug){
        $thePost = Post::query()->where('slug','=',$slug)->first();
        $author = $thePost->getAuthor;
        $currentUser = session('currentUser');
        $isVIP = false;
        if(($thePost->type) == 1)
            if(is_null($currentUser) ||
                (($currentUser->role != MainUserController::Admin) &&
                    !MainUserController::hasSubscribtion($currentUser) &&
                    ($author->id != $currentUser->id))) {
                $isVIP = true;
                $thePost->content = self::getNonVIPPart($thePost->content);
            }
        $post_comments = self::processCommentsForOutput($thePost->comments);
        $pageData = [
            'post-title' => $thePost->title,
            'post-content' => $thePost->content,
            'post-isVIP' => $isVIP,
            'post-thumbnail' => Storage::url($thePost->thumbnail),
            'post-comments' => $post_comments,
            'post-rate' => $thePost->rate,
            'author-avatar' =>  Storage::url($author->avatar),
            'author-name' => $author->name,
            'author-bio' => $author->bio,
            'author-number-of-posts' => $author->posts->count(),
            'author-rate' => MainUserController::getUserRate($author),
            'author-hasSubscribtion' => MainUserController::hasSubscribtion(session('currentUser'))
        ];

        $temp = Post::query()->where('category_id' ,'=', $thePost->category_id)->get()->all();
        $suggestions = array();
        foreach ($temp as $index => $value)
            if($value->id != $thePost->id)
                array_push($suggestions, $temp[$index]);

        unset($temp);
        usort($suggestions, function ($a, $b){
            return (strtotime($b->rate) - strtotime($a->rate));
        });
        $c = count($suggestions);
        if($c > 5)
            for($i=0 ; $i<$c ; $i++)
                unset($suggestions[$i]);
        unset($c);

        $this->addToView($thePost->id);
        return view('single')->with([
            'post_id' => (int)$thePost->id,
            'pageData'=>$pageData,
            'author_id' => $thePost->author,
            'suggestions' => Controller::processPostsToShow($suggestions)
        ]);

    }

    const VIPPercentage = 30;
    const KeyTag = '</p>';
    public static function getNonVIPPart(string $postContent){ // picking a part of the vip content based on the tag type and percentage set.
        $len = strlen($postContent);
        if($len < 300 || substr_count($postContent,self::KeyTag) < 3)
            return true;
        $vipBorder = (int)($len * self::VIPPercentage / 100);
        $KeyTag_positions_in_content = self::strpos_all($postContent,self::KeyTag);
        $positions_distances_from_border = array();
        foreach($KeyTag_positions_in_content as $key => $value){$positions_distances_from_border[$key] = abs($value-$vipBorder);}
        $min_index = array_search(min($positions_distances_from_border), $positions_distances_from_border);
        return substr($postContent, 0, $KeyTag_positions_in_content[$min_index]+strlen(self::KeyTag));
    }

    public static function strpos_all($haystack, $needle) {
        $offset = 0;
        $allpos = array();
        while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
            $offset   = $pos + 1;
            $allpos[] = $pos;
        }
        return $allpos;
    }

    public function addToView(int $postID){
        $thePost = Post::query()->find($postID)->increment('views');
    }

    public function rate(){
        $post = Post::find(request()->input('postID'));
        $post->addToRate(\request()->input('rate'));
        $post->save();
        return response()->json([
            'new-author-rate' => MainUserController::getUserRate($post->getAuthor),
            'post-rate' => $post->rate,
            'message'=>'بازخورد ثبت شد'
        ]);
    }

    public function edit(int $postID){
        $cu = session('currentUser');
        $post = Post::find($postID);
        if(is_null($post))
            return redirect()->to('/home')->withErrors([
                'msg' => 'محتوای مورد نظر یافت نشد'
            ]);
        if(is_null($cu) || ($cu->id != $post->author && $cu->role != MainUserController::Admin))
            return redirect()->to('/home')->withErrors([
                'msg'=> 'شما اجازه دسترسی به این صفحه را ندارید'
            ]);
        return view('form.editPost')->with(['post' => $post]);
    }
    public function update(){
        if(!isset(\request()->input()['id']))
            return redirect()->back()->withErrors([
                'msg'=> 'متاسفانه اشکالی رخ داده است'
            ]);
        $post = Post::find(\request()->input()['id']);
        if(is_null($post))
            return redirect()->back()->withErrors([
                'msg'=> 'متاسفانه اشکالی رخ داده است'
            ]);
        $cu = session('currentUser');
        if($post->author != $cu->id && $cu->role != MainUserController::Admin)
            return redirect()->back()->withErrors([
                'msg'=> 'درخواست دسترسی غیر مجاز'
            ]);
        $cat_len = count(Post::getCategories());

        $data = $this->validate(request(), [
            'title'    => 'required|min:5|max:255',
            'category' => "required|integer|between:0,$cat_len",
            'slug'     => 'required|min:3',
            'content'  => 'required|min:50',
            'thumbnail'=> '',
            'type'=>'required|integer|boolean',
            'select-thumbnail' => 'required|between:0,1',
        ], [
            'title.required' => 'وارد کردن عنوان الزامی است.',
            'title.min' => 'عنوان حداقل باید ۵ کاراکتر باشد.',
            'title.max' => 'عنوان بیش از حد طولانی است',
            'category.required' =>'وارد کردن دسته بندی الزامی است',
            'category.integer' => 'دسته بندی به درستی وارد نشده',
            'category.between' => 'دسته بندی وارد شده غیر مجاز است',
            'slug.required' => 'وارد کردن نامک الزامی است',
            'slug.min' => 'حداقل طول مجاز برای نامک رعایت نشده است',
            'content.required' => 'وارد کردن محتوای مقاله الزامی است',
            'content.min' => 'مارکاپ محتوا حداقل باید 50 حرف باشد',
            'type.required' => 'وارد کردن نوع مقاله الزامی است',
            'type.integer' => 'در وارد کردن نوع اشکالی رخ داده است',
            'type.boolean' => 'نوع وارد شده غیر مجاز است',
            'select-thumbnail.required' => 'متاسفانه اشکالی رخ داده است',
            'select-thumbnail.between' =>'متاسفانه اشکالی رخ داده است' ,
        ]);
        if($data['type'] == 1 && strlen($data['content']) < 300)
            return redirect()->back()->withErrors([
                'msg' => 'مارکاپ محتوای ویژه حداقل باید 300 حرف باشد',
            ]);

        if($data['slug'] != $post->slug){
            $possibleDuplicate = Post::query()->where('slug' , '=', $data['slug'])->get()->all();
            if (!($possibleDuplicate->esEmpty()))
                return redirect()->back()->withErrors([
                    'msg'=> 'این نامک قبلا استفاده شده است'
                ]);
        }

        if($data['select-thumbnail'] == '1'){
            $file = \request()->file('thumbnail');
            if(!is_null($file)) {
                Storage::delete($post->thumbnail);
                $data['thumbnail'] = $file->store('thumbnails');
            }
        }else{
            if($post->thumbnail != 'thumbnails/default-thumbnail.jpg')
                Storage::delete($post->thumbnail);
            $data['thumbnail'] = "thumbnails/default-thumbnail.jpg";
        }
        $post->edit($data);
        $post->save();
        return redirect()->to('/post/single-post/'.$data['slug'])->with([
            'success' => true,
            'msg' => 'تغییرات با موفقیت ثبت شد',
        ]);
    }
    public function delete(int $postID){
        $post = Post::find($postID);
        $cu = session('currentUser');
        if($post->author != $cu->id && $cu->role != MainUserController::Admin)
            return redirect()->back()->withErrors([
                'msg'=> 'درخواست دسترسی غیر مجاز'
            ]);
        $post->delete();
        return redirect()->back()->with([
            'success' => true,
            'msg' => 'تغییرات با موفقیت ثبت شد',
        ]);
    }

}
