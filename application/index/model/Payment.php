<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/5
 * Time: 10:09
 */

namespace app\index\model;


class Payment extends Base
{
    protected $table = 'payment_channels';
    protected $autoWriteTimestamp = true;
}