<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $info = LoginController::decryptToken();
        return view('admin/users/index', ['info'=>$info]);
    }
}
