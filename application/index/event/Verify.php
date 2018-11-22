<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/8
 * Time: 20:16
 */

namespace app\index\event;

use app\index\model\Account as AccountModel;
use app\index\model\Recharge as RechargeModel;
use app\index\model\User as UserModel;
use think\facade\Log;


class Verify
{
    /**
     * 插入之前将管理员ID插入到表中
     * @param $verify
     * @return mixed
     */
    public function beforeInsert($verify)
    {
        $verify->member_id = session('member_id');

        return $verify;
    }

    /**
     * 数据插入成功之后操作
     * 1.将recharge表中的相应记录更改为已审核
     * 2.给资金变动表中加入一条记录
     * 3.给相应的账户中加入资金
     * @param $verify
     */
    public function afterInsert($verify)
    {
        $recharge_id = $verify->recharge_id;
        $map['id'] = $recharge_id;
        RechargeModel::setFields($map,'is_verify',1);

        $recharge_data = RechargeModel::getOne($map,'','user');
        $user_data = $recharge_data->user;
        $account_data = [
            'acc_id'=>$user_data['id'],
            'money_before'=>$user_data['money'],
            'money_after'=>$user_data['money'] + $recharge_data['total_fee'],
            'money'=>$recharge_data['total_fee'],
            'relation_id'=>$recharge_id,
            'member_id'=>session('member_id')
        ];
        AccountModel::create($account_data);

        UserModel::setFields(['id'=>$user_data['id']],'money',$account_data['money_after']);
    }
}