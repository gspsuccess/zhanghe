<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/26
 * Time: 10:54
 */

namespace app\api\model;


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
     * 获取一条记录
     * @param string $where
     * @param string $fields
     * @param string $order
     * @param string $with
     * @return array|null|\PDOStatement|string|Model
     */
    public static function getOne($where = '1', $fields = '*',$order = 'id desc', $with = '')
    {
        $result = self::where($where)
            ->field($fields)
            ->with($with)
            ->order($order)
            ->find();

        return $result;
    }

    /**
     * 根据条件获取多条记录
     * @param string $where
     * @param string $fields
     * @param string $order
     * @param string $page
     * @param string $with
     * @return array|\PDOStatement|string|\think\Collection
     */
    public static function getAll($where = ' 1 ',$fields = '*', $order = 'id desc',$page = '1,10',$with='')
    {

        $result = self::where($where)
            ->field($fields)
            ->order($order)
            ->page($page)
            ->with($with)
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

    /**
     * 获取数据量
     * @param $where
     * @param string $field
     * @return float|string
     */
    public static function countData($where,$field = '*')
    {
        $result = self::where($where)->count($field);
        return $result;
    }
}