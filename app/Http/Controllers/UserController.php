<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use App\User;
use Session;

class UserController extends Controller
{
   public function postSignUp(Request $request){

   	//untuk sign up 

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

   public function postSignIn(Request $request)
   {

   	//untuk  sign in


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
		   		return redirect()-> route('dashboard');  // kalau berhasil login
		   	}
		   	return redirect()->back();
	
   	
   }

   public function getLogout(){
   	 Auth::logout();
   	 return redirect()->route('home');
   }

   public function getAccount(){
   	return view('account',['user' => Auth::user()]);
   }

    public function postSaveAccount( Request $request){
       $this->validate($request,[
         'first_name'=> 'required| max:120'
       ]);


       $user = Auth::user();
       $user-> first_name = $request['first_name'];
       $user->update();
       $file= $request->file('image');
       $filename= $request['first_name'] .'-'. $user->id . '.jpg';
       if ($file ) {
       	# code...
       	Storage::disk ('local')-> put($filename, File::get($file));

       }
       return redirect()->route('account');
   }

   public function getUserimage($filename){
   		$file = Storage::disk('local')->get($filename);
   		return new Response($file,200);
   }
  
}
