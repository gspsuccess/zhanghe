<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/8
 * Time: 13:11
 */

namespace app\index\event;


use think\facade\Log;

class Recharge
{
    /**
     * 在充值前填充未填充的数据
     * @param $recharge
     * @return mixed
     */
    public function beforeInsert($recharge)
    {
        $recharge->pay_no = create_trade_no('B');
        $recharge->handler_id = session('member_id');
        $recharge->status = 2;

        return $recharge;
    }

    /**
     * 充值后操作：
     * 1.提醒审核人员有人充值
     * @param $recharge
     */
    public function afterInsert($recharge)
    {
        Log::record(json_encode($recharge));
    }
}