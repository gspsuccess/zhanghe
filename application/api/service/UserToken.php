<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/12
 * Time: 10:50
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;
    //小程序的时候所用
    function __construct($code)
    {
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->code = $code;
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }

    /**
     * 获取微信返回数据
     * @throws Exception
     * @throws WeChatException
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);

        if(empty($wxResult))
        {
            throw new Exception('获取 session_key 及 openid 时发生异常，微信内部错误');
        }
        else
        {
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail)
            {
                $this->processLoginError($wxResult);
            }
            else
            {
                return $this->grantToken($wxResult);
            }
        }
    }

    /**
     * 生成令牌
     * @param $wxResult
     * @return string
     * @throws Exception
     */
    private function grantToken($wxResult)
    {
        //拿到OPENID和
        $openid = $wxResult['openid'];

        //去数据库中查一下，这个OPENID是否已经存在
        $user = UserModel::getByOpenID($openid);

        //如果存在则不处理，如果不存在则新增一条USER记录
        if($user)
        {
            $uid = $user->id;
        }
        else
        {
            $uid = $this->newUser($openid);
        }

        //生成令牌，准备缓存数据，写入缓存 key：令牌 value：wxResult,uid,scope
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);

        //将令牌返回到客户端
        return $token;
    }

    /**
     * 存储在缓存中
     * @param $cachedValue
     * @return string
     * @throws Exception
     */
    private function saveToCache($cachedValue)
    {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('settings.token_expire_in');

        $result = cache($key,$value,$expire_in);
        if(!$result)
        {
            throw new Exception([
                'msg'=>'服务器缓存异常',
                'errorCode'=>10005
            ]);
        }

        return $key;
    }

    /**
     * 生成缓存的值
     * @param $wxResult
     * @param $uid
     * @return mixed
     */
    private function prepareCachedValue($wxResult,$uid)
    {
        $cachedvalue = $wxResult;
        $cachedvalue['uid'] = $uid;
        $cachedvalue['scope'] = ScopeEnum::User;

        return $cachedvalue;
    }

    /**
     * 创建一个新用户
     * @param $openid
     * @return mixed
     */
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid'=>$openid,
            'create_time'=>time()
        ]);

        return $user->id;
    }

    /**
     * 根据微信端返回的错误信息抛出异常
     * @param $wxResult
     * @throws WeChatException
     */
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'errorCode'=>$wxResult['errcode'],
            'nsg'=>$wxResult['errmsg']
        ]);
    }
}