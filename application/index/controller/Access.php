<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/20
 * Time: 14:54
 */

namespace app\index\controller;

class Access extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}