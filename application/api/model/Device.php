<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/9
 * Time: 15:26
 */

namespace app\api\model;


class Device extends Base
{
    protected $table = 'devices';
    protected $autoWriteTimestamp = true;
    protected $hidden = ['update_time','delete_time','handler_id'];

    public function massif()
    {
        return $this->belongsTo('massif');
    }

    public function project()
    {
        return $this->belongsTo('project');
    }
}