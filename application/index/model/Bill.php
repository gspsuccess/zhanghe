<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/28
 * Time: 7:06
 */

namespace app\index\model;


class Bill extends Base
{
    protected $table = 'bills';
    protected $autoWriteTimestamp = true;
    protected $observerClass = 'app\index\event\Bill';

    public function getStatusAttr($value)
    {
        $arr = ['未处理','已处理','已发出','已完成'];
        $result = $arr[$value];

        return $result;
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

    /**
     * 生成发票编号
     * @param $value
     * @return string
     */
    public static function createSn($value)
    {
        $id = $value->id;
        $result = date('YmdHis').rand(100000,999999).$id;

        return $result;
    }
}