<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/11
 * Time: 15:29
 */

namespace app\api\service;

use app\api\model\Device as DeviceModel;
use app\api\model\Dominate;
use app\api\model\WaterPrice as WaterPriceModel;
use app\api\model\WaterUsed as WaterUsedModel;
use app\api\model\User as UserModel;
use app\api\model\Account as AccountModel;
use app\api\model\UserDevice as UserDeviceModel;
use app\api\model\WaterException as WaterExceptionModel;

class Subcribe
{
    /**
     * 1.生成一条与上次比对的取水记录（用作退款或者异常处理，如果之前该设备有过取水记录则进行与上一次的对比进行退款）
     * 2.生成一条取水记录
     * 3.账户扣费
     * 4.生成一条资金变动记录
     *
     * @param $user_id
     * @param $info
     * @param $last_water_record
     * @return bool
     */
    public static function createWaterUsedRecord($user_id,$info,$last_water_record)
    {
        $water_used = WaterUsedModel::getWaterUsed($user_id);
        $project_id = UserModel::getField(['id'=>$user_id],'project_id');

        $data['device_id'] = $info['device_id'];
        $data['value_start'] = $info['water_meter'];
        $data['amount'] = $info['amount'];
        $data['value_end'] = $data['value_start'] + $data['amount'];
        $data['user_id'] = $user_id;
        $data['money'] = WaterPriceModel::getMoney($water_used,$data['amount'],$project_id);

        if(!is_null($last_water_record))
        {
            self::refund($data['device_id'],$data['value_start'],$last_water_record);
        }

        //生成一条取水记录
        $waterUsed = new WaterUsedModel();
        $result = $waterUsed->save($data);

        //进行扣款、生成资金变动记录
        $userinfo = UserModel::getOne(['id'=>$user_id]);
        $account_data = [
            'relation_id'=>$waterUsed->id,
            'acc_id'=>$user_id,
            'money_before'=>$userinfo['money'],
            'money'=>-$data['money'],
            'money_after'=>$userinfo['money'] - $data['money'],
            'create_time'=>time(),
            'types'=>2
        ];

        AccountModel::create($account_data);
        $user_data = [
            'money'=>$userinfo['money'] - $data['money'],
            'water_limit'=>$userinfo['water_limit'] - $data['amount']
        ];
        UserModel::update($user_data,['id'=>$user_id]);

        return $result;
    }

    /**
     * 账户退款
     *
     * 1.获取设备最后一次取水记录
     * 2.将最后一次的结束表底与当前记录的开始表底进行对比
     * 3.若当前的开始表底小于最后一次的结束表底，则证明需要退款
     * 4.生成一条取水记录，并执行退款、生成一条账户资金变动记录
     *
     * @param $device_id
     * @param $water_meter
     * @param $last_water_record
     * @return bool
     */
    private static function refund($device_id,$water_meter,$last_water_record)
    {
        //获取当前取水设备最后一次取水记录
        $user_id = $last_water_record['user_id'];
        $water_used = WaterUsedModel::getWaterUsed($user_id);
        $project_id = UserModel::getField(['id'=>$user_id],'project_id');

        $water_meter_prev = $last_water_record['value_end'];
        $extra = $water_meter - $water_meter_prev;

        //上次实际取水量（当前表底 - 上次开始表底）
        $water_meter_fact = $water_meter - $last_water_record['value_start'];

        //如果算出来的实际取水量小于0，则说明有异常情况
        if($water_meter_fact < 0)
        {
            $water_meter_fact = $last_water_record['amount'];

            $data = [
                'device_id'=>$device_id,
                'user_id'=>$user_id,
                'water_meter'=>$water_meter,
                'water_start'=>$last_water_record['value_start'],
                'water_end'=>$last_water_record['value_end'],
                'record_id'=>$last_water_record['id']
            ];

            WaterExceptionModel::create($data);
        }

        //获取要退款的金额
        $money = WaterPriceModel::getMoney($water_used,$water_meter_fact,$project_id) - $last_water_record['money'];

        //如果发现当前表底比上次记录的结束表底小，则给账户退款；
        //如果发现当前表底比上次记录的结束表底大，则将异常情况记录表异常取水记录表中
        if($extra < 0)
        {
            $data = [
                'value_start' => $water_meter_prev,
                'value_end'=>$water_meter,
                'amount'=>$extra,
                'user_id'=>$user_id,
                'device_id'=>$device_id,
                'money'=>$money
            ];

            //获取用户原账户金额
            $money_user = UserModel::getField(['id'=>$user_id],'money');

            //生成取水记录
            $waterUsed = new WaterUsedModel();
            $waterUsed->save($data);

            //扣款并生成资金变动记录
            $account_data = [
                'relation_id'=>$waterUsed->id,
                'acc_id'=>$user_id,
                'money_before'=>$money_user,
                'money'=>abs($money),
                'money_after'=>$money_user + abs($money),
                'create_time'=>time(),
                'types'=>2
            ];

            AccountModel::create($account_data);
            $userinfo = UserModel::getOne(['id'=>$user_id]);
            $user_data = [
                'money'=>$userinfo['money'] + abs($money),
                'water_limit'=>$userinfo['water_limit'] - $extra
            ];
            UserModel::update($user_data,['id'=>$user_id]);
        }
        else if($extra > 0)
        {
            $data = [
                'device_id'=>$device_id,
                'user_id'=>$user_id,
                'water_meter'=>$water_meter,
                'water_start'=>$last_water_record['value_start'],
                'water_end'=>$last_water_record['value_end'],
                'record_id'=>$last_water_record['id']
            ];

            WaterExceptionModel::create($data);
        }

        return true;
    }

    /**
     * 取水前验证
     * @param $user_id
     * @param $serialno
     * @return array
     */
    public static function checkPermission($user_id,$serialno)
    {
        /**
         * 1.关联关系是否正常
         * 2.是否在配水时间段内
         * 3.水额度是否够
         * 4.账户余额是否够
         */

        $device_id = DeviceModel::getField(['serialno'=>$serialno],'id');
        $device_ids_arr = UserDeviceModel::getAll(['user_id'=>$user_id],'device_id')->toArray();
        $device_ids = array_column($device_ids_arr,'device_id');

        $result = formatResult(true,'验证通过');
        if(empty($device_id) || !in_array($device_id,$device_ids))
        {
            $msg = '您没有操作此设备的权限';
            $result = formatResult('',$msg);
            return $result;
        }

        if(!Dominate::checkDominates($device_id))
        {
            $result = formatResult('','未在允许的取水时间段');
            return $result;
        }

        $userinfo = UserModel::getOne(['id'=>$user_id]);
        if($userinfo['water_limit'] <= 0)
        {
            $msg = '您的购水额度不足';
            $result = formatResult('',$msg);
            return $result;
        }

        if($userinfo['money'] <= 0)
        {
            $msg = '您的账户余额不足';
            $result = formatResult('',$msg);
            return $result;
        }

        return $result;
    }
}