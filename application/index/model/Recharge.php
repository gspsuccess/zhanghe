<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/8
 * Time: 13:12
 */

namespace app\index\model;


class Recharge extends Base
{
    protected $table = 'recharges';
    protected $autoWriteTimestamp = true;
    protected $observerClass = 'app\index\event\Recharge';

    /**
     * 将相应的状态值变为图标
     * @param $value
     * @return string
     */
    public function getStatusAttr($value)
    {
        $status_img = $value == 2 ? 'ico_ok.png' : 'ico_no.png';
        $status_url = '<img class="flag-size" src = "/assets/img/' . $status_img . '">';

        return $status_url;
    }

    /**
     * 将相应的状态值变为图标
     * @param $value
     * @return string
     */
    public function getIsVerifyAttr($value)
    {
        $status_img = $value == 1 ? 'ico_ok.png' : 'ico_no.png';
        $status_url = '<img class="flag-size" src = "/assets/img/' . $status_img . '">';

        return $status_url;
    }

    /**
     * 将相应的充值方式状态变为文字
     * @param $value
     * @return string
     */
    public function getTypesAttr($value)
    {
        $types_txt = $value == 1 ? '<b class="recharge-behind">后台充值</b>':'<b class="recharge-wxpay">微信支付</b>';

        return $types_txt;
    }

    /**
     * 与用户进行关联
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('user');
    }

    /**
     * 与充值审核记录表进行关联
     * @return \think\model\relation\HasMany
     */
    public function verify()
    {
        return $this->hasMany('verify');
    }
}