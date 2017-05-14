<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;
use Sentinel;
class UserController extends Controller
{
    public function render_sign_up()
    {
      return view('sign-up');
    }

    public function create(Request $req)
    {
      $this->validate($req,User::$sign_up_rules);
      $user = Sentinel::registerAndActivate($req->all());
      Session::flash('message', "Please Sign-in with Student id");
      return redirect('/');
    }
    public function sign_in(Request $req)
    {
      $this->validate($req,User::$sign_in_rules);
      $login = Sentinel::authenticate($req->all());
      if(Sentinel::check()){
        return redirect('/welcome');
      }else{

        return redirect('/');
      }
    }
    public function sign_out()
    {
      Sentinel::logout();
      return redirect('/');
    }
}
