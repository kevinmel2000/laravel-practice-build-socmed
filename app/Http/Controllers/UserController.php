<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\User;
use Session;


class UserController extends Controller
{
   public function postSignUp(Request $request){

   	//untuk sign up 
   	if (Auth::check()) {
   		# code...
   		return view('check');
   	}

   	else{

	   	     $this->validate($request, [
	   	     	'email' => 'required|email|unique:users',
	   	     	'first_name' => 'required|max:120',
	   	     	'password' => 'required|min:4'
	   	     ]);

	   		 $email = $request['email'];
	   		 $first_name = $request['first_name'];
	   		 $password = bcrypt($request['password']);


	   		 $user = new User;
	   		 $user->email = $email;
	   		 $user->first_name = $first_name;
	   		 $user->password = $password;

	   		 $user->save();

	   		 Auth::login($user);
	   		  if (Session::has('oldUrl')){
	           $oldUrl = Session::get('oldUrl');
	           Session::forget('oldUrl');
	           return redirect()->to($oldUrl);
	       }

	   		 return view('dashboard');
	   	}
   }

   public function postSignIn(Request $request)
   {

   	//untuk  sign in

   	 if (Auth::check()) {
   		# code...
   		return view('check');
   	}
   	else{

		   	$this->validate($request, [
		   	     	'email' => 'required|email',
		   	     	'password' => 'required'
		   	     ]);


		   	if (Auth::attempt([
		   		'email'=> $request['email'],
		   		'password'=> $request['password']])) {

		   			if (Session::has('oldUrl')){
		                 $oldUrl = Session::get('oldUrl');
		                 Session::forget('oldUrl');
		                 return redirect()->to($oldUrl);
		             }
		   		return view('dashboard'); // kalau berhasil login
		   	}
		   	return redirect()->back();
		}
   	
   }


   public function getDashboard(){
   	 return view('dashboard');
   }
}
