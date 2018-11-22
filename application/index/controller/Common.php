<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/18
 * Time: 19:15
 */

namespace app\index\controller;


class Common extends Base
{
    public function menu()
    {
        return $this->fetch('menu');
    }
}