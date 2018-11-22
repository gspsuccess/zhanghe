<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/12
 * Time: 10:32
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code'=>'require|isNotEmpty'
    ];

    protected $message = [
        'code' => '没有 code 还想获取 Token 信息，做梦哦'
    ];
}