<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/29
 * Time: 16:14
 */

namespace app\api\model;

class Project extends Base
{
    protected $table = 'projects';
    protected $autoWriteTimestamp = true;
    protected $hidden = ['region_id','update_time','delete_time'];
}