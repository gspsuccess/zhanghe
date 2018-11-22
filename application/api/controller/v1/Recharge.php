<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/3/8
 * Time: 20:10
 */

namespace app\api\controller\v1;


use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Recharge as RechargeModel;

class Recharge extends Base
{
    /**
     * 下单
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function placeOrder()
    {
        $products = input('post.product/a');
        $user_id = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($user_id,$products);

        return $status;
    }

    /**
     * 获取充值列表
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function getRecharges()
    {
        $user_id = TokenService::getCurrentUid();

        $params = input('param.');
        $result = RechargeModel::getRecharges($user_id,$params);

        return $result;
    }

    /**
     * 获取充值详情
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function getRecharge()
    {
        $user_id = TokenService::getCurrentUid();

        $id = input('param.id');
        $map = ['user_id'=>$user_id,'id'=>$id];
        $result = RechargeModel::getOne($map);
        $result = RechargeModel::formatResult($result);

        return $result;
    }
}