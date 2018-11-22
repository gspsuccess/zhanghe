<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/11
 * Time: 12:21
 */

namespace app\api\service;

use app\api\model\Recharge;
use app\api\model\User as UserModel;
use app\api\model\WaterPrice as WaterPriceModel;
use app\api\model\WaterUsed as WaterUsedModel;


class User
{
    /**
     * 获取当前账户信息
     * 1.先获取用户信息
     * 2.获取用户对应的用水信息
     * 3.根据用水量获取对应水价
     * 4.获取当前用户充值列表
     * @param $user_id
     * @param Recharge $recharge
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public static function getWallet($user_id,Recharge $recharge)
    {
        $userinfo = UserModel::getOne(['id'=>$user_id],'id,realname,money,water_limit,project_id');
        $water_used = WaterUsedModel::getWaterUsed($user_id);
        $water_price = WaterPriceModel::getCurrentPrice($water_used,$userinfo['project_id']);
        $recharges = $recharge->where(['status'=>2,'is_verify'=>1,'user_id'=>$user_id])
            ->order('create_time desc')
            ->field('id,user_id,total_fee,create_time,types')
            ->limit('0,3')
            ->select();

        $userinfo['water_used'] = number_format($water_used,2);
        $userinfo['water_price'] = $water_price;
        $userinfo['recharges'] = $recharges;

        unset($userinfo['project_id']);

        return $userinfo;
    }
}