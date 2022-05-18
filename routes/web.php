<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/home/page={pageNum}', 'PostController@homePage');
Route::get('/home', 'PostController@homePage1')->name('home');


Route::get('/test', 'Admin\UserController@test');
//Route::get('/test2', 'Admin\UserController@test2');

Route::prefix('/panel')->group( function () {
    Route::get('/users','Admin\UserController@userslist')->name('users-list');
    Route::get('/posts','Admin\UserController@postslist')->name('posts-list');
    Route::get('/transactions','Admin\UserController@transactions')->name('transactions');

    Route::get('/add', 'Admin\UserController@show_addUserForm')->name('add-user');
    Route::post('/add', 'Admin\UserController@add');

    Route::get('/edit/{id}', 'Admin\UserController@edit');
    Route::post('/update/{id}', 'Admin\UserController@update');

    Route::get('/delete/{id}', 'Admin\UserController@delete');
});
Route::get('/profile/{id}', 'MainUserController@showProfile');
Route::get('/my-transactions','MainUserController@myTransactions')->name('my-transactions');
Route::get('/user-posts/{id}', 'MainUserController@userPosts');

Route::prefix('/form')->group(function (){
    Route::get('/register', 'MainUserController@showRegisterForm')->name('registerForm');
    Route::post('/register', 'MainUserController@register')->name('register');

    Route::get('/login', 'MainUserController@showLoginForm')->name('loginForm');
    Route::post('/login', 'MainUserController@login')->name('login');

    Route::get('/edit-user/{id}', 'MainUserController@edit');
    Route::post('/update/{id}', 'MainUserController@update');

    Route::get('/newPost', 'PostController@showPostForm')->name('newPostForm');
    Route::post('/newPost', 'PostController@add')->name('newPost');

    Route::get('/edit-post/{id}', 'PostController@edit');
    Route::post('/update-post', 'PostController@update')->name('editPost');
});

Route::get('/delete-post/{id}', 'PostController@delete');

Route::prefix('/post')->group(function (){
    Route::get('/single-post/{slug}', 'PostController@showSinglePost');
});

Route::post('/new-comment/{postID}','CommentController@add');
Route::get('/donate','DonateController@add');

Route::get('/rate', 'PostController@rate');

Route::post('/charge','ChargeController@charge')->name('charge');

Route::post('/buy-sub/{id}', 'SubscribeController@add');

Route::get('/exit', 'MainUserController@exitAccount')->name('exitAccount');





