<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {

		if (Auth::check()) {
   			# code...
   			return redirect()->route('dashboard');
   		}
   		else{
   			return view('welcome');
   		}
  		
})->name('home');

	Route::post('/signup',[
			'uses'=> 'UserController@postSignUp',
			'as'=> 'signup'
	]);

	Route::post('/signin',[
			'uses'=> 'UserController@postSignIn',
			'as'=> 'signin'
	]);

Route::group(['middleware'=>'auth'],function(){

	Route::get ('/dashboard',[
		'uses'=> 'PostController@getDashboard',
		'as'=> 'dashboard'
	]);

	Route::post ('/createpost',[
		'uses'=> 'PostController@postCreatePost',
		'as'=> 'post.create'
	]);

	Route::get('/delete-post/{post_id}',[
		'uses'=> 'PostController@getDeletePost',
		'as'=> 'post.delete'
	]);

	Route::get('/logout',[
		'uses' => 'UserController@getLogout',
		'as' => 'logout'
	]);

});

