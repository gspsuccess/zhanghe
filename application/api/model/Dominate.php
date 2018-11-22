<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/11
 * Time: 19:27
 */

namespace app\api\model;


class Dominate extends Base
{
    protected $table = 'dominates';

    public static function checkDominates($device_id)
    {
        $time = time();
        $dominates = self::where(['device_id'=>$device_id])
            ->where('starttime','<=',$time)
            ->where('endtime','>=',$time)
            ->select();

        $result = count($dominates)?true:false;

        return $result;
    }
}