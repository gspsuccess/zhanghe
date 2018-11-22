<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/7/19
 * Time: 17:34
 */

namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;

class DeliveryMessage extends WxMessage
{
    const DELIVERY_MSG_ID = 'OERDGAIFnPY-T4lUMVu4f3dNeLEeh2faRWbHkfdzQyM';

    /**
     * 发送模板消息
     * @param $order
     * @param string $tplJumpPage
     * @return bool
     * @throws OrderException
     * @throws \Exception
     */
    public function sendDeliveryMessage($order,$tplJumpPage = '')
    {
        if(!$order)
        {
            throw new OrderException();
        }

        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        $this->emphasisKeyWord = 'keyword2.DATA';

        return parent::sendMessage($this->getUserOpenId($order->user_id));
    }

    /**
     * 初始化模板消息内容
     * @param $order
     */
    private function prepareMessageData($order)
    {
        $data = [
            'keyword1'=>[
                'value'=>$order->order_no
            ],
            'keyword2'=>[
                'value'=>$order->total_price,
                'color'=>'#27408B'
            ],
            'keyword3'=>[
                'value'=>$order->create_time
            ],
            'keyword4'=>[
                'value'=>$order->snap_name
            ]
        ];

        $this->data = $data;
    }

    /**
     * 获取用户 openid
     * @param $user_id
     * @return mixed
     */
    private function getUserOpenId($user_id)
    {
        $user = UserModel::get($user_id);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }
}