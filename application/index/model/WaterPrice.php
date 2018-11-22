<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/23
 * Time: 10:48
 */

namespace app\index\model;


class WaterPrice extends Base
{
    protected $table = 'water_prices';
    protected $autoWriteTimestamp = true;

    public function effective()
    {
        return $this->belongsTo('effective','effective_time_id');
    }
}