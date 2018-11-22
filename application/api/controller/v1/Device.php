<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/9
 * Time: 15:25
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\model\Device as DeviceModel;
use app\api\service\Device as DeviceService;
use app\api\service\Subcribe as SubcribeService;
use app\api\service\Log as LogService;


class Device extends Base
{
    /**
     * 获取设备列表
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function getDevices()
    {
        $user_id = TokenService::getCurrentUid();
        $result = DeviceService::getDevices($user_id);
        $result = formatResult($result);

        return $result;
    }

    /**
     * 获取设备详情
     * 需要判断用户与设备是否有关联关系
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function getDevice()
    {
        $user_id = TokenService::getCurrentUid();
        $id = input('param.id');

        $result = DeviceModel::getOne(['id'=>$id],'','','project,massif');
        $result = formatResult($result);

        return $result;
    }

    /**
     * 取水前设备验证
     * @return array
     */
    public function checkPermission()
    {
        $user_id = TokenService::getCurrentUid();
        $serialno = input('param.serialno');

        $result = SubcribeService::checkPermission($user_id,$serialno);

        return $result;
    }

    /**
     * 实时获取设备的信息，并根据响应结果返回设备数据
     * @return array|bool|null|\PDOStatement|string|\think\Model
     */
    public function getCurrentState()
    {
        $user_id = TokenService::getCurrentUid();
        $infos = DeviceService::formatDeviceInfos(input('post.'),$user_id);

        $result = DeviceService::getCurrentState($infos);
        $msg = '该设备正在被其他用户使用，您暂时无操作权限';
        $result = $result ? formatResult($result):formatResult('',$msg);

        return $result;
    }

    /**
     * 设备控制
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function control()
    {
        $user_id = TokenService::getCurrentUid();
        $infos = DeviceService::formatDeviceInfos(input('post.'),$user_id);

        $result = DeviceService::controlDevice($infos);
        $result = formatResult($result);

        return $result;
    }

    /**
     * 实时上传设备状态（数据上传至设备日志中）
     * @return array|static
     */
    public function createDeviceLogs()
    {
        $infos = DeviceService::formatDeviceInfos(input('post.'));
        $result = LogService::createDeviceLog($infos);

        return $result;
    }
}