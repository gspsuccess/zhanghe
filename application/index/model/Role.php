<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/5
 * Time: 18:15
 */

namespace app\index\model;


class Role extends Base
{
    protected $table = 'think_auth_group';

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
     * 获取节点信息
     * @param Privilege $privilege
     * @param $rules
     * @return array
     */
    public static function getAccessList(Privilege $privilege, $rules)
    {
        $result = $privilege->field('id,title,pid')
            ->order('id asc')
            ->select()
            ->toArray();

        $arr = [];

        if (!empty($rules)) {
            $rules = explode(',', $rules);
        }

        foreach ($result as $k => $v) {
            $item = [
                'id'=>$v['id'],
                'pId'=>$v['pid'],
                'name'=>$v['title']
            ];

            if (!empty($rules) && in_array($v['id'], $rules)) {
                $item['checked'] = 1;
            }

            $arr[] = $item;
        }

        return $arr;
    }
}