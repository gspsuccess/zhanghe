<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/12
 * Time: 13:26
 */

namespace app\lib\exception;


class TemplateException extends BaseException
{
    public $code = 405;
    public $msg = '模板消息发送失败';
    public $errorCode = 10001;
}