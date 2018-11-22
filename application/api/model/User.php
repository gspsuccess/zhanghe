<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/26
 * Time: 10:54
 */

namespace app\api\model;


class User extends Base
{
    protected $table = 'users';
    protected $autoWriteTimestamp = true;
    protected $hidden = ['name','password','flag','update_time','delete_time','handler_id','unionid','openid','money_encypt'];

    public function recharges()
    {
        return $this->hasMany('recharge');
    }

    public function project()
    {
        return $this->belongsTo('project');
    }

    public function massif()
    {
        return $this->belongsTo('massif');
    }

    /**
     * 根据OPENID获取用户信息
     * @param $openid
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getByOpenID($openid)
    {
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }
}