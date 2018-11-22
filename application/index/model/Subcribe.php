<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/21
 * Time: 16:50
 */

namespace app\index\model;


class Subcribe extends Base
{
    protected $table = 'subcribes';
    protected $autoWriteTimestamp = true;

    /**
     * 与用户进行关联
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('user');
    }

    public function getStarttimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    public function getEndtimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    public function setStarttimeAttr($value)
    {
        return strtotime($value);
    }

    public function setEndtimeAttr($value)
    {
        return strtotime($value);
    }

    public function getStatusAttr($value)
    {
        $status_img = $value?'ico_ok.png':'ico_no.png';
        $status_url = '<img class="flag-size" src = "/assets/img/'.$status_img.'">';

        return $status_url;
    }
}