<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/23
 * Time: 11:51
 */

namespace app\index\model;


class WaterUsed extends Base
{
    protected $table = 'water_used_records';
    protected $autoWriteTimestamp = true;

    public function user()
    {
        return $this->belongsTo('user');
    }

    public static function formatInfoList($infolist)
    {
        return $infolist;
    }
}