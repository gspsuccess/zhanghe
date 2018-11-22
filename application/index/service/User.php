<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/10
 * Time: 11:47
 */

namespace app\index\service;

use app\index\model\UserDevice as UserDeviceModel;
use app\index\model\Device as DeviceModel;
use app\index\model\User as UserModel;

class User
{
    /**
     * 根据用户ID获取用户所关联的设备列表
     * @param $user_id
     * @return array|\PDOStatement|string|\think\Collection
     */
    public static function getDevices($user_id)
    {
        $user_id = 2;
        $massif_id = UserModel::getField(['id'=>$user_id],'massif_id');
        $devices = DeviceModel::getAll(['massif_id'=>$massif_id],'id,name,serialno');
        $device_checked = UserDeviceModel::getAll(['user_id'=>$user_id],'device_id');
        $device_checked_ids = array_column($device_checked->toArray(),'device_id');

        foreach($devices as $k => &$v)
        {
            $v['checked'] = (in_array($v['id'],$device_checked_ids))?' checked':'';
        }

        return $devices;
    }

    public static function setDevices($user_id,$device_ids)
    {
        $data = [];
        foreach($device_ids as $v)
        {
            $data[] = ['user_id'=>$user_id,'device_id'=>$v];
        }
        $userDeviceModel = new UserDeviceModel();
        $userDeviceModel->where('user_id','=',$user_id)->delete();
        $result = $userDeviceModel->saveAll($data);

        return $result;
    }
}