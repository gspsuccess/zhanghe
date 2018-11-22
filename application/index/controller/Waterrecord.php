<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 16:05
 */

namespace app\index\controller;


class Waterrecord extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}