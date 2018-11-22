<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/11
 * Time: 12:22
 */

namespace app\api\model;


class WaterUsed extends Base
{
    protected $table = 'water_used_records';
    protected $autoWriteTimestamp = true;

    /**
     * 获取用水量
     * @param $user_id
     * @return float
     */
    public static function getWaterUsed($user_id)
    {
        $result = self::where(['user_id'=>$user_id])
            ->sum('amount');

        return $result;
    }

    /**
     * 根据设备ID获取最后一条取水记录（非退费）
     * @param $device_id
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public static function getLastRecord($device_id)
    {
        $result = self::where('device_id','=',$device_id)
            ->where('money','>',0)
            ->order('create_time desc')
            ->find();

        return $result;
    }
}