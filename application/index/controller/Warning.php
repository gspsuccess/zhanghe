<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 16:04
 */

namespace app\index\controller;


class Warning extends Base
{
    public function index()
    {
        $this->assign('title','预警告警记录');
        return $this->fetch();
    }

    public function add()
    {
        $this->assign('title','预警告警设置');
        return $this->fetch();
    }
}