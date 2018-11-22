<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/20
 * Time: 16:49
 */

namespace app\index\controller;


class Systemlog extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}