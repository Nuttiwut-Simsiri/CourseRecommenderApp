<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','student_ID', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $sign_up_rules = [
      'first_name' => 'required',
      'last_name' => 'required',
      'student_ID' => 'required|digits_between:13,13|unique:users|numeric',
      'password' =>  'required|min:6|max:13|confirmed',
      'email' => 'required|email|unique:users',
    ];
    public static $sign_in_rules =[
      'student_ID' => 'required|digits_between:13,13|numeric',
      'password' =>  'required',

    ];
    public static $course_rules =[
        'course*' => 'required|unique:users_rating',

    ];



}
