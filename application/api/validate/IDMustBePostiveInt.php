<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/8
 * Time: 9:51
 */

namespace app\api\validate;


class IDMustBePostiveInt extends BaseValidate
{
    protected $rule = [
        'id'=>'require|isPostiveInteger'
    ];

    protected $message = [
        'id'=>'id必须为正整数'
    ];
}