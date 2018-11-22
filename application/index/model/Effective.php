<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/23
 * Time: 10:47
 */

namespace app\index\model;


class Effective extends Base
{
    protected $table = 'effective_times';
    protected $autoWriteTimestamp = true;

    public function wprices()
    {
        return $this->hasMany('water_price');
    }
}