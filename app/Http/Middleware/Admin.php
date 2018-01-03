<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class Admin
{
    public function handle($request, Closure $next)
    {
        if (Session::get('username') == env("LOGIN_USERNAME") && Session::get('isLoggedIn')) {
            return $next($request);
        } else {
            echo trans('messages.You must be logged in');
            die();
        }
    }
}