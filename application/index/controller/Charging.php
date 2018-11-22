<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 16:04
 */

namespace app\index\controller;


class Charging extends Base
{
    public function index()
    {
        $this->assign('title','计费信息列表');
        return $this->fetch();
    }

    public function add()
    {
        $this->assign('title','新增计费记录');
        return $this->fetch();
    }
}