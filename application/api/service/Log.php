<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/18
 * Time: 12:29
 */

namespace app\api\service;


use app\api\model\UserLog as UserLogModel;
use app\api\model\DeviceLog as DeviceLogModel;

class Log
{
    /**
     * 生成用户操作记录
     * @param $infos
     * @return array|static
     */
    public static function createUserLog($infos)
    {
        $fields = ['user_id', 'device_id', 'state'];
        $data = self::generateData($fields, $infos);

        $result = UserLogModel::create($data);
        $result = formatResult($result);

        return $result;
    }

    /**
     * 生成设备信息记录
     * @param $infos
     * @return array|static
     */
    public static function createDeviceLog($infos)
    {
        $fields = ['device_id', 'voltage', 'water_pressure', 'water_meter', 'instantaneous', 'state'];
        $data = self::generateData($fields, $infos);

        $result = DeviceLogModel::create($data);
        $result = formatResult($result);

        return $result;
    }

    /**
     * 生成
     * @param $fields
     * @param $infos
     * @return array
     */
    private static function generateData($fields, $infos)
    {
        $data = [];
        foreach ($fields as $v) {
            $data[$v] = !empty($infos[$v]) ? $infos[$v] : 0;
        }

        return $data;
    }
}