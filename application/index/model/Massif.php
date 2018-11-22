<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/23
 * Time: 10:36
 */

namespace app\index\model;


class Massif extends Base
{
    protected $table = 'massifs';
    protected $autoWriteTimestamp = true;

    /**
     * 与项目进行关联
     * @return \think\model\relation\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('project');
    }

    /**
     * 将相应的状态值变为状态文字
     * @param $value
     * @return string
     */
    public function getFlagAttr($value)
    {
        $flag_img = $value?'ico_ok.png':'ico_no.png';
        $flag_url = '<img class="flag-size" src = "/assets/img/'.$flag_img.'">';

        return $flag_url;
    }
}