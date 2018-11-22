<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/12
 * Time: 13:09
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;
use app\api\service\Token as TokenService;

class Token
{
    /**
     * 生成 Token
     * @param int $length
     * @return string
     */
    public static function generateToken($length = 32)
    {
        $randChars = getRandChars($length);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);
    }

    /**
     * 根据KEY获取相应的缓存值
     * @param $key
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentTokenVar($key)
    {
        $token = Request()->header('token');
        $vars = cache($token);

        if(!$vars)
        {
            throw new TokenException();
        }
        else
        {
            if(!is_array($vars))
            {
                $vars = json_decode($vars,true);
            }

            if(array_key_exists($key,$vars))
            {
                return $vars[$key];
            }
            else
            {
                throw new Exception('尝试获取的TOKEN变量并不存在');
            }
        }
    }

    /**
     * 获取用户ID
     * @return mixed
     * @throws TokenException
     */
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * 只可以让用户和管理员访问（未认证用户不能访问）
     * @return bool
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if($scope)
        {
            if($scope >= ScopeEnum::User)
            {
                return true;
            }
            else
            {
                throw new ForbiddenException();
            }
        }
        else
        {
            throw new TokenException();
        }
    }

    /**
     * 只能让用户访问，不能让管理员访问
     * @return bool
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needExclusiveScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if($scope)
        {
            if($scope == ScopeEnum::User)
            {
                return true;
            }
            else
            {
                throw new ForbiddenException();
            }
        }
        else
        {
            throw new TokenException();
        }
    }

    /**
     * 验证Token是否有效
     * @param $token
     * @return bool
     */
    public static function verifyToken($token)
    {
        $exist = cache($token);

        return ($exist)?true:false;
    }

    /**
     * 判断传入的UID是否有效
     * @param $checkUID
     * @return bool
     * @throws Exception
     */
    public static function isValidOperate($checkUID)
    {
        if(!$checkUID)
        {
            throw new Exception('检查UID时必须传入一个UID');
        }

        $currentOperateUID = self::getCurrentUid();
        if($checkUID == $currentOperateUID)
        {
            return true;
        }

        return false;
    }
}