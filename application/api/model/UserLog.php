<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/18
 * Time: 12:25
 */

namespace app\api\model;


class UserLog extends Base
{
    protected $table = 'user_logs';
    protected $autoWriteTimestamp = true;
}