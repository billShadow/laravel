<?php

namespace App\Http\Controllers\Admin;

use App\Models\order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function orderlist()
    {
        $list = order::getWhere(['is_valid'=>1]);
        return view('admin/orders/orderlist', ['list'=>$list]);
    }

    public function addorder()
    {
        for ($i=0;$i<=10;$i++) {
            $data['order_no'] = 'no_'.time().str_random(20);
            $data['user_id'] = rand(11,99);
            $data['order_status'] = rand(1,3);
            $data['card_id'] = '123';
            $data['integral'] = '79';
            $data['is_valid'] = 1;
            order::add($data);
        }
        $list = order::getWhere(['is_valid'=>1]);
        return view('admin/orders/orderlist', ['list'=>$list]);
    }

    /**
     * 导出订单CSV文件
     */
    public function exportorder()
    {
        $list = order::getlist(['is_valid'=>1]);
        $str = "编号,用户ID,订单状态\n";
        $str = iconv('utf-8','gb2312',$str);
        foreach ($list as $k=>$v) {
            $order_no = iconv('utf-8','gb2312',$v['order_no']); //中文转码
            $user_id = iconv('utf-8','gb2312',$v['user_id']);
            $str .= $order_no.",".$user_id.",".$v['order_status']."\n"; //用引文逗号分开
        }
        $filename = date('Ymd').'.csv'; //设置文件名
        self::export_csv($filename,$str); //导出
    }

    /**
     * 导出下载到本地
     * @param $filename
     * @param $data
     */
    public static function export_csv($filename,$data)
    {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }

    /**
     * 导入csv订单文件
     */
    public function importorder(Request $request)
    {
        $filename = $_FILES['file']['tmp_name'];
        if (empty ($filename)) {
            echo '请选择要导入的CSV文件！';
            exit;
        }
        $handle = fopen($filename, 'r');
        $result = self::input_csv($handle); //解析csv
        $len_result = count($result);
        if($len_result==0){
            echo '没有任何数据！';
            exit;
        }
        for ($i = 1; $i < $len_result; $i++) { //循环获取各字段值
            $data['order_no'] = iconv('gb2312', 'utf-8', $result[$i][0]); //中文转码
            $data['user_id'] = iconv('gb2312', 'utf-8', $result[$i][1]);
            $data['order_status'] = $result[$i][2];
            order::add($data);
        }
        ajax_respon(1, '导入成功');
    }

    public static function input_csv($handle) {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = $data[$i];
            }
            $n++;
        }
        return $out;
    }
}
