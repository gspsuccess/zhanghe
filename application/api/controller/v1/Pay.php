<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/3/9
 * Time: 10:21
 */

namespace app\api\controller\v1;

use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;
use app\api\service\Pay as PayService;

class Pay extends Base
{
    /**
     * 支付
     * @param string $id
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getPreOrder($id = '')
    {
        (new IDMustBePostiveInt())->goCheck();

        $pay = new PayService($id);
        return $pay->pay();
    }

    /**
     * 回调
     */
    public function receiveNotify()
    {
        $notify = new WxNotify();
        $notify->Handle();
    }
}