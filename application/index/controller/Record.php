<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/20
 * Time: 16:47
 */

namespace app\index\controller;


class Record extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}