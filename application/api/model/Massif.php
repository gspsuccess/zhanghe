<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/9
 * Time: 14:14
 */

namespace app\api\model;


class Massif extends Base
{
    protected $table = 'massifs';
    protected $autoWriteTimestamp = true;
    protected $hidden = ['update_time','delete_time'];
}