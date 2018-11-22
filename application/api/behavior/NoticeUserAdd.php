<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/9
 * Time: 15:13
 */

namespace app\api\behavior;


use app\api\model\NoticeUser as NoticeUserModel;

class NoticeUserAdd
{
    /**
     * 如果用户已读该公告，则将信息存入关联表中
     * @param $params
     * @return bool|static
     */
    public function run($params)
    {
        $result = false;
        if(!NoticeUserModel::countData($params))
        {
            $result = NoticeUserModel::create($params);
        }

        return $result;
    }
}