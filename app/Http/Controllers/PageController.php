<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function forgot(): View
    {
        return view('auth.forgot');
    }

    public function profile(): View
    {
        return view('profile');
    }

    public function settings(): View
    {
        return view('settings');
    }
}
