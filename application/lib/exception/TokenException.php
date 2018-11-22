<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/12
 * Time: 13:26
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}