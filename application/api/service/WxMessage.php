<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/7/19
 * Time: 17:48
 */

namespace app\api\service;


use \Exception;

class WxMessage
{
    private $sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=%s';
    private $touser;
    private $color = 'black';

    protected $tplID;
    protected $page;
    protected $formID;
    protected $data;
    protected $emphasisKeyWord;

    function __construct()
    {
        $accessToken = new AccessToken();
        $token = $accessToken->get();

        $this->sendUrl = sprintf($this->sendUrl,$token);
    }

    protected function sendMessage($openid)
    {
        $data = [
            'touser'=>$openid,
            'template_id'=>$this->tplID,
            'page'=>$this->page,
            'form_id'=>$this->formID,
            'data'=>$this->data,
            'emphasis_keyword'=>$this->emphasisKeyWord
        ];


        $result = curl_post($this->sendUrl,$data);
        $result = json_decode($result,true);


        if($result['errcode'] == 0)
        {
            return true;
        }
        else
        {
            throw new Exception('模板消息发送失败，'.$result['errmsg']);
        }
    }
}