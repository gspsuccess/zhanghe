<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/7/14
 * Time: 11:09
 */

namespace app\api\service;

require '../extend/WxPay/WxPay.Api.php';

use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\Recharge as OrderModel;
use think\facade\Log;


class Pay
{
    private $orderID;
    private $orderNO;
    private $totalPrice;

    function __construct($orderID)
    {
        if(!$orderID)
        {
            throw new Exception('订单号不允许为空');
        }

        $this->orderID = $orderID;
    }

    public function pay()
    {
        $this->checkOrderValid();

        return $this->makeWxPreOrder($this->totalPrice);
    }

    /**
     * 生成预订单
     * @param $totalPrice
     * @return array
     * @throws TokenException
     */
    private function makeWxPreOrder($totalPrice)
    {
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid)
        {
            throw new TokenException();
        }

        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('通捷水务');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));

        return $this->getPaySignature($wxOrderData);
    }

    /**
     * 获取签名
     * @param $wxOrderData
     * @return array
     * @throws \WxPayException
     */
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS')
        {
            Log::record($wxOrder,'error');
            Log::record('微信预订单获取失败','error');
        }

        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);

        return $signature;
    }

    /**
     * 生成签名
     * @param $wxOrder
     * @return array
     */
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();

        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $jsApiPayData->SetSignType('md5');

        $noncestr = md5(time().mt_rand(1,1000));
        $jsApiPayData->SetNonceStr($noncestr);

        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $sign = $jsApiPayData->MakeSign();

        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;

        unset($rawValues['appId']);

        return $rawValues;
    }

    /**
     * 将订单表中的 prepay_id 赋值
     * @param $wxOrder
     */
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id','=',$this->orderID)->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }

    /**
     * 判断订单号是否有效，可能出现的问题如下：
     * 1. 订单号在数据库中不存在
     * 2. 订单号和对应用户不匹配
     * 3. 订单有可能已经被支付过
     * @throws OrderException
     * @throws TokenException
     * @return bool
     */
    private function checkOrderValid()
    {
        $order = OrderModel::where('id','=',$this->orderID)->find();

        if(!$order)
        {
            throw new OrderException();
        }

        if(!Token::isValidOperate($order->user_id))
        {
            throw new TokenException([
                'msg'=>'订单与用户不匹配',
                'errorCode'=>10003
            ]);
        }

        if($order->getData('status') != OrderStatusEnum::UNPAID)
        {
            throw new OrderException([
                'msg'=>'订单已经支付过了',
                'errorCode'=>80003,
                'code'=>400
            ]);
        }

        $this->orderNO = $order->out_trade_no;
        $this->totalPrice = $order->total_fee;

        return true;
    }
}