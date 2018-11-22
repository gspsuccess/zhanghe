<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 16:04
 */

namespace app\index\controller;

use app\index\model\WaterPrice as WaterPriceModel;


class Wprice extends Base
{
    public function index()
    {
        if(request()->isGet())
        {
            $this->assign('title','阶梯水价记录');
            return $this->fetch();
        }
        else
        {
            $waterPrice = new WaterPriceModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $infolist = $waterPrice
                ->limit($offset,$pagesize)
                ->order('effective_time_id desc,amount_from asc')
                ->with('effective')
                ->select();

            $total = $waterPrice->count();

            $result = [
                'total'=>$total,
                'rows'=>$infolist
            ];

            return json($result);
        }
    }

    public function add()
    {
        $this->assign('title','新增阶梯水价');
        return $this->fetch();
    }
}