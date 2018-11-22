<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/13
 * Time: 8:24
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 80000;
}