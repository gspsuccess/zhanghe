<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/7/14
 * Time: 17:12
 */

namespace app\api\service;

require '../extend/WxPay/WxPay.Api.php';

use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use app\api\model\Recharge as OrderModel;
use app\api\model\Account as AccountModel;
use app\api\model\User as UserModel;
use think\facade\Log;


class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data,&$msg)
    {
        if($data['result_code'] == 'SUCCESS')
        {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try
            {
                $order = OrderModel::where('out_trade_no','=',$orderNo)->lock(true)->find();

                if($order->getData('status') == 1)
                {
                    $this->updateOrderStatus($order->id);
                    $this->addAccountHistory($order->id,$data['openid'],$data['total_fee']);
                    $this->setWalletMoney($data['openid'],$data['total_fee']);
                }
                Db::commit();
                return true;
            }
            catch(Exception $e)
            {
                Log::error($e);
                Db::rollback();
                return false;
            }
        }
        else
        {
            return true;
        }
    }

    /**
     * 更改订单状态
     * @param $orderId
     */
    private function updateOrderStatus($orderId)
    {
        $data = [
            'status'=>OrderStatusEnum::PAID,
            'is_verify'=>OrderStatusEnum::VERIFIED,
            'types'=>OrderStatusEnum::WXPAY
        ];

        $order = new OrderModel();
        $map['id'] = $orderId;
        $order->where($map)->update($data);
    }

    /**
     * 给账户变动表中添加一条记录
     * @param $orderId
     * @param $openid
     * @param $total_fee
     */
    private function addAccountHistory($orderId,$openid,$total_fee)
    {
        AccountModel::saveOne($orderId,$openid,$total_fee);
    }

    /**
     * 更改钱包中的金额
     * @param $openid
     * @param $total_fee
     */
    private function setWalletMoney($openid,$total_fee)
    {
        $user = new UserModel();
        $map['openid'] = $openid;
        $user->where($map)->setInc('money',$total_fee/100);
    }
}