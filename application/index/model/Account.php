<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/8
 * Time: 20:41
 */

namespace app\index\model;


class Account extends Base
{
    protected $table = 'account_money_changes';
    protected $autoWriteTimestamp = true;
}