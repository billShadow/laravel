<?php

namespace App\Http\Controllers\Admin;

use App\Models\adm_user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $info = LoginController::decryptToken();
        return view('admin/users/index', ['info'=>$info]);
    }

    public function userlist()
    {
        $list = adm_user::getWhere(['is_valid'=>1]);
        return view('admin/users/userlist', ['list'=>$list]);
    }

    public function deluser(Request $request)
    {
        $id = (int) $request->id;
        if (empty($id)) {
            ajax_respon(0, '缺少参数', 11005);
        }
        ajax_respon(1, '删除用户成功', 11006);
    }
}
