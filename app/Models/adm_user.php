<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class adm_user extends Model
{
    public $table = 'adm_user';

    public $timestamps = true;

    protected $fillable = ['id', 'phone', 'pass', 'nickname', 'user_type', 'is_valid', 'created_at', 'updated_at'];

    public $primaryKey = "id";

    /**
     * 添加数据
     * @param $data
     * @return mixed
     */
    protected function add($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        return $this->insertGetId($data);
    }

    /**
     * 获取信息
     * @param $where
     * @return mixed
     */
    protected function getWhere($where)
    {
        return $this->where($where)->paginate(6);
    }

    /**
     * 获取一条数据
     * @param $where
     * @return string
     */
    protected function getOne($where)
    {
        $res = $this->where($where)->first();
        return $res ? $res->toArray() : '';
    }


    /**
     * 根据条件修改
     * @param $data
     * @param $where
     * @return mixed
     */
    protected function edit($where, $data )
    {
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        return $this->where($where)->update($data);
    }
}
