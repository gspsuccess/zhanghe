<?php
/**
 * Created by PhpStorm.
 * User: gongs
 * Date: 2017/6/8
 * Time: 10:20
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        //1.获取HTTP传入的参数
        //2.对参数做校验
        $params = Request()->param();

        $result = $this->batch()->check($params);
        if(!$result)
        {
            $e = new ParameterException([
                'msg'=>$this->error,
                'errorCode'=>10002
            ]);

            throw $e;
        }
        else
        {
            return true;
        }
    }

    /**
     * 自定义判断传入参数是否为正整数
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool
     */
    protected function isPostiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0)>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 判断空值
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool
     */
    protected function isNotEmpty($value,$rule = '',$data = '',$field = '')
    {
        if(empty($value))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * 手机号检验
     * @param $value
     * @return bool
     */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public function getDataByRule($arrays)
    {
        if(array_key_exists('user_id',$arrays) || array_key_exists('uid',$arrays))
        {
            throw new Exception('参数中含有非法参数');
        }

        $newArray = [];
        foreach($arrays as $k => $v)
        {
            $newArray[$k] = $arrays[$k];
        }

        return $newArray;
    }
}