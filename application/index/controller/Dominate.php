<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 16:04
 */

namespace app\index\controller;

use app\index\model\Subcribe as SubcribeModel;
use app\index\model\Dominate as DominateModel;


class Dominate extends Base
{
    public function index()
    {
        if(request()->isGet())
        {
            $this->assign('title','配水计划记录');
            return $this->fetch();
        }
        else
        {
            $dominate = new DominateModel();
            $infolist = $dominate->select();
            $total = $dominate->count();

            $result = [
                'total'=>$total,
                'rows'=>$infolist
            ];

            return json($result);
        }
    }

    public function add()
    {
        $this->assign('title','新增配水计划');
        return $this->fetch();
    }
}