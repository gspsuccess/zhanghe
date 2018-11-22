<?php
namespace app\index\controller;


class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function settings()
    {
        return $this->fetch();
    }
}
