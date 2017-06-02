<?php

namespace App\Http\Controllers\Admin;

use App\Models\action;
use App\Models\role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PowerController extends Controller
{
    public function rolelist()
    {
        $list = role::getWhere([]);
        return view('admin/power/rolelist', ['list'=>$list]);
    }

    public function addrole()
    {
        return view('admin/power/addrole');
    }

    public function doaddrole(Request $request)
    {
        $role_name = trim($request->role_name);
        $role_desc = trim($request->role_desc);

        $data = [
            'role_name' => $role_name,
            'role_desc' => $role_desc
        ];

        $res = role::add($data);
        if (!$res) {
            return fun_error_view(0, '添加失败', 'addrole');
        }
        return fun_error_view(1, '添加成功', 'rolelist');
    }

    public function actionlist()
    {
        $list = action::getWhere([]);
        return view('admin/power/actionlist', ['list'=>$list]);
    }

    public function addaction()
    {
        return view('admin/power/addaction');
    }
}
