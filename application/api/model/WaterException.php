<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/19
 * Time: 19:08
 */

namespace app\api\model;


class WaterException extends Base
{
    protected $table = 'water_used_exceptions';
    protected $autoWriteTimestamp = true;
}