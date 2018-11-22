<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/7/14
 * Time: 11:39
 */
namespace app\lib\enum;


class OrderStatusEnum
{
    //未支付
    const UNPAID = 1;
    //已支付
    const PAID = 2;

    //未通过审核
    const UNVERIFIED = 0;
    //已通过审核
    const VERIFIED = 1;

    //支付方式为微信支付
    const WXPAY = 2;
}