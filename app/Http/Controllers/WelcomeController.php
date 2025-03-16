<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Selamat Datang',
            'list' => ['Home', 'welcome']
        ];
        $activeMenu = 'dashboard';

        return view('welcome1', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}
