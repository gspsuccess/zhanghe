<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/30
 * Time: 16:26
 */

namespace app\index\model;


class Notice extends Base
{
    protected $table = 'notices';
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
     * 与地块进行关联
     * @return \think\model\relation\BelongsTo
     */
    public function massif()
    {
        return $this->belongsTo('massif');
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