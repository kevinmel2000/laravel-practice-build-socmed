<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
		public function postCreatePost(Request $request){
			//validatioon

			$this->validate($request,[
				'body'=> 'required | max:1000'
			]);

			$post = new Post();
			$post->body = $request['body'];
			$message = 'there was an errror';
			if($request -> user()->posts()->save($post)){
					$message= 'post sucessfully created';
			}
			return redirect()->route('dashboard')->with(['message'=> $message]);
		}
}