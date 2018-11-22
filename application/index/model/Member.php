<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/21
 * Time: 16:51
 */

namespace app\index\model;


class Member extends Base
{
    protected $table = 'members';
    protected $autoWriteTimestamp = true;

    /**
     * 将相应的状态值变为状态文字
     * @param $value
     * @return string
     */
    public function getStatusAttr($value)
    {
        $status_img = $value ? 'ico_ok.png' : 'ico_no.png';
        $status_url = '<img class="flag-size" src = "/assets/img/' . $status_img . '">';

        return $status_url;
    }

    /**
     * 验证用户名密码
     * @param $username
     * @param $password
     * @return array|bool|null|\PDOStatement|string|\think\Model
     */
    public static function checkPassword($username,$password)
    {
        $map['username'] = $username;
        $member_info = self::where($map)->field('id,username,salt,status')->find();

        $result = false;
        if($member_info)
        {
            $map['password'] = create_password($password,$member_info['salt']);
            $result = self::where($map)->field('id,username,status')->find();
        }

        return $result;
    }

    /**
     * 格式化输入数据（主要针对密码和盐）
     * @param $data
     * @return mixed
     */
    public static function formatData($data)
    {
        if(!isset($data['id']) || empty($data['id']))
        {
            $data['salt'] = random_str();
            $data['password'] = create_password($data['password'],$data['salt']);
        }
        else
        {
            if(!isset($data['password']) || empty($data['password']))
            {
                unset($data['password']);
            }
            else
            {
                $map['id'] = $data['id'];
                $salt = self::getField($map,'salt');
                $data['password'] = create_password($data['password'],$salt);
            }
        }

        return $data;
    }
}