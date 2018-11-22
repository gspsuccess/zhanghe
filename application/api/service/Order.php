<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/13
 * Time: 7:35
 */

namespace app\api\service;


use app\api\model\User as UsersModel;
use think\Exception;
use app\api\model\Recharge as RechargeModel;

class Order
{
    //从客户端提交过来的商品列表
    protected $oProduct;

    //真实商品信息（数据库中查询出来的）
    protected $product;

    protected $user_id;

    /**
     * 下单方法
     * @param $user_id
     * @param $orderInfo
     * @return array
     */
    public function place($user_id,$orderInfo)
    {
        //金额,用户ID,用户名,钱包ID,type 1
        $orderInfo['user_id'] = $user_id;
        $orderInfo['type'] = 1;

        //开始创建订单
        $order = $this->createOrder($orderInfo);
        $order['pass'] = true;

        return $order;
    }

    /**
     * 创建订单
     * @param $orderInfo
     * @return array
     * @throws Exception
     */
    private function createOrder($orderInfo)
    {
        try
        {
            $tradeNo = $this->makeOrderNo();
            $payNo = create_trade_no();

            $rechargeModel = new RechargeModel();
            $rechargeModel->user_id = $orderInfo['user_id'];
            $rechargeModel->total_fee = $orderInfo['money'];
            $rechargeModel->out_trade_no = $tradeNo;
            $rechargeModel->pay_no = $payNo;
            $rechargeModel->create_time = time();

            $rechargeModel->save();

            $rechargeID = $rechargeModel->id;

            return [
                'recharge_id' => $rechargeID,
                'tradeNo' => $tradeNo,
                'create_time' => date('Y-m-d H:i:s')
            ];
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * 生成订单号
     * @return string
     */
    public static function makeOrderNo()
    {
        $yCode = array('A','B','C','D','E','F','G','H','I','J');
        $orderSn = date('Ymd').$yCode[intval(date("Y"))-2017].strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5);

        return $orderSn;
    }
}