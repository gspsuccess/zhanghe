<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/9
 * Time: 19:01
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\service\User as UserService;
use app\api\model\User as UserModel;
use app\api\model\Recharge as RechargeModel;


class User extends Base
{
    /**
     * 获取用户基本信息
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function getUser()
    {
        $user_id = TokenService::getCurrentUid();
        $result = UserModel::getOne(['id'=>$user_id],'','','project,massif');
        $result = formatResult($result);

        return $result;
    }

    /**
     * 获取钱包信息
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function getWallet()
    {
        $user_id = TokenService::getCurrentUid();
        $recharge = new RechargeModel();
        $result = UserService::getWallet($user_id,$recharge);
        $result = formatResult($result);

        return $result;
    }
}