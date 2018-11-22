<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/21
 * Time: 16:50
 */

namespace app\index\model;


class User extends Base
{
    protected $table = 'users';
    protected $autoWriteTimestamp = true;

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

    /**
     * 与项目进行关联
     * @return \think\model\relation\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('project');
    }

    /**
     * 与地块进行关联
     * @return \think\model\relation\BelongsTo
     */
    public function massif()
    {
        return $this->belongsTo('massif');
    }
}