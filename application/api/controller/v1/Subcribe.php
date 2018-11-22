<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/11
 * Time: 15:20
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\service\Subcribe as SubcribeService;

class Subcribe extends Base
{
    /**
     * 新生成一条取水记录
     * @return array
     */
    public function create()
    {
        $user_id = TokenService::getCurrentUid();
        $info = input('post.');

        $serialno = $info['serialno'];
        $amount = $info['amount'];
        $result = SubcribeService::checkPermission($user_id,$serialno,$amount);

        if($result['code'] == 0)
        {
            $result = SubcribeService::createWaterUsedRecord($user_id,$info);
            $result = formatResult($result);

            return $result;
        }

        return $result;
    }
}