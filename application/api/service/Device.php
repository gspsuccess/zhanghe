<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/11
 * Time: 12:05
 */

namespace app\api\service;

use app\api\model\UserDevice as UserDeviceModel;
use app\api\model\Device as DeviceModel;
use app\api\model\WaterUsed as WaterUsedModel;
use app\api\model\DeviceLog as DeviceLogModel;
use app\api\enum\DeviceEnum;
use app\api\service\Log as LogService;
use app\api\service\Subcribe as SubcribeService;


class Device
{
    /**
     * 获取设备列表（关联的项目和地块详情同步获取）
     * @param $user_id
     * @return array|\PDOStatement|string|\think\Collection
     */
    public static function getDevices($user_id)
    {
        $device_ids_arr = UserDeviceModel::getAll(['user_id' => $user_id], 'device_id')->toArray();
        $device_ids = array_column($device_ids_arr, 'device_id');
        $devices = DeviceModel::where('id', 'in', implode(',', $device_ids))
            ->with('project,massif')
            ->select();

        return $devices;
    }

    /**
     * 格式化提交来的信息（给其中加入设备ID信息和用户信息）
     * @param $infos
     * @param string $user_id
     * @return mixed
     */
    public static function formatDeviceInfos($infos, $user_id = '')
    {
        $device_id = DeviceModel::getField(['serialno' => $infos['serialno']], 'id');
        $infos['device_id'] = $device_id;
        $infos['user_id'] = $user_id;

        return $infos;
    }

    /**
     * 获取当前设备状态
     *
     * 1.获取该设备的最后一次的取水记录，
     * 如果当前设备是开着的，并且记录中的用户ID与现在的用户ID不一致，则返回 false
     * 2.如果当前设备是关着的，
     * 将当前获取的设备信息传到设备日志文件中，并且返回设备取水时的初始信息
     *
     * @param array $infos
     * @return array|bool|null|\PDOStatement|string|\think\Model
     */
    public static function getCurrentState($infos = [])
    {
        $result = WaterUsedModel::getLastRecord($infos['device_id']);
        $user_id = $infos['user_id'];

        if ($infos['state'] == DeviceEnum::START && count($result) && $result['user_id'] != $user_id)
        {
            $result = false;
        }
        else
        {
            LogService::createDeviceLog($infos);
        }

        $result = count($result) ? $result : '首次取水';

        return $result;
    }

    /**
     * 设备控制
     *
     * 1. 获取设备日志的最后一条信息
     * 2. 用户操作日志生成一条记录，设备操作日志生成一条记录
     * 3. 如果当前设备状态为开且最后一条设备日志记录中的设备状态为关，则生成一条取水记录
     *
     * @param array $infos
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public static function controlDevice($infos = [])
    {
        $device_id = $infos['device_id'];
        $last_device_log = DeviceLogModel::getOne(['device_id'=>$device_id],'','create_time desc');

        LogService::createUserLog($infos);
        LogService::createDeviceLog($infos);

        $last_water_record = WaterUsedModel::getLastRecord($device_id);

        if(($infos['state'] == DeviceEnum::START && $last_device_log['state'] == DeviceEnum::STOP) || !count($last_water_record))
        {
            SubcribeService::createWaterUsedRecord($infos['user_id'],$infos,$last_water_record);
        }

        $result = WaterUsedModel::getLastRecord($device_id);

        return $result;
    }
}