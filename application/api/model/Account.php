<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/26
 * Time: 16:44
 */

namespace app\api\model;

use app\api\model\User as UserModel;


class Account extends Base
{
    protected $table = 'account_money_changes';
    protected $autoWriteTimestamp = true;

    /**
     * 新增一条账户变动记录
     * @param $changeId
     * @param $openid
     * @param $totle_fee
     * @param int $types
     * @return static
     */
    public static function saveOne($changeId,$openid,$totle_fee,$types = 1)
    {
        $map['openid'] = $openid;
        $userinfo = UserModel::getOne($map,'id,money');
        $money = $totle_fee / 100;

        $data = [
            'relation_id'=>$changeId,
            'acc_id'=>$userinfo['id'],
            'money_before'=>$userinfo['money'],
            'money'=>$money,
            'money_after'=>$userinfo['money'] + $money,
            'create_time'=>time(),
            'types'=>$types,
        ];

        return self::create($data);
    }
}