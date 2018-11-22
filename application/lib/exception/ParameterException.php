<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/8
 * Time: 14:47
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}