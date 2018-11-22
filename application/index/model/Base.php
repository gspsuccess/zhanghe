<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/21
 * Time: 16:51
 */

namespace app\index\model;


use think\Model;

class Base extends Model
{
    /**
     * 格式化返回的数据（JSON）
     * @param $data
     * @return array
     */
    public static function formatResult($data)
    {
        $result = [
            'code'=>0,
            'msg'=>'操作成功'
        ];

        if(!$data)
        {
            $result = [
                'code'=>-1,
                'msg'=>'操作失败'
            ];
        }

        $result['data'] = $data;

        return $result;
    }

    /**
     * 根据条件获取一条记录
     * @param $where
     * @param string $fields
     * @param string $with
     * @return array|null|\PDOStatement|string|Model
     */
    public static function getOne($where,$fields = '*',$with='')
    {
        $result = self::where($where)
            ->field($fields)
            ->with($with)
            ->find();

        return $result;
    }

    /**
     * 根据条件获取多条记录
     * @param string $where
     * @param string $fields
     * @param string $order
     * @return array|\PDOStatement|string|\think\Collection
     */
    public static function getAll($where = ' 1 ',$fields = '*', $order = 'id desc')
    {

        $result = self::where($where)
            ->field($fields)
            ->order($order)
            ->select();

        return $result;
    }

    /**
     * 获取某个字段值
     * @param int $where
     * @param string $field
     * @param string $order
     * @return array|false|mixed|\PDOStatement|string|Model
     */
    public static function getField($where = 1,$field = '',$order = 'id desc')
    {
        $result = self::where($where)
            ->field($field)
            ->order($order)
            ->find();

        $result = $result[$field];

        return $result;
    }

    /**
     * 设置某个字段值
     * @param $where
     * @param $field
     * @param $value
     * @return int
     */
    public static function setFields($where,$field,$value)
    {
        $result = self::where($where)->setField($field,$value);
        return $result;
    }
}