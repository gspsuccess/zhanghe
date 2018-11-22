<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/23
 * Time: 10:36
 */

namespace app\index\model;


class Project extends Base
{
    protected $table = 'projects';
    protected $autoWriteTimestamp = true;

    public function massifs()
    {
        return $this->hasMany('massif');
    }
}