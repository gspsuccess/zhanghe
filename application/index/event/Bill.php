<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/8
 * Time: 13:11
 */

namespace app\index\event;


use think\facade\Log;
use app\index\model\Bill as BillModel;

class Bill
{
    /**
     * 在充值前填充未填充的数据
     * @param $bill
     * @return mixed
     */
    public function beforeInsert($bill)
    {
        $bill->create_user = session('member_id');

        return $bill;
    }

    /**
     * 充值后操作：
     * 1.提醒审核人员有人充值
     * @param $bill
     */
    public function afterInsert($bill)
    {
        $bill->bill_sn = BillModel::createSn($bill);
        $bill->save();

        return $bill;
    }
}