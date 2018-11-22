<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 16:04
 */

namespace app\index\controller;

use app\index\model\WaterUsed as WaterUsedModel;
use app\index\model\Recharge as RechargeModel;
use app\lib\Report;


class Census extends Base
{
    /**
     * 用水记录统计
     * @return mixed|\think\response\Json
     */
    public function waters()
    {
        if(request()->isGet())
        {
            $this->assign('title','用水记录统计');
            return $this->fetch();
        }
        else
        {
            $waterUsed = new WaterUsedModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['name','like','%'.$post['name'].'%'];
            }

            $infolist = $waterUsed
                ->where('amount','>',0)
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->with('user')
                ->select();

            $total = $waterUsed->where('amount','>',0)->where($map)->count();

            $result = [
                'total'=>$total,
                'rows'=>$infolist
            ];

            return json($result);
        }
    }

    /**
     * 充值记录统计
     * @return mixed
     */
    public function recharges()
    {
        if(request()->isGet())
        {
            $this->assign('title','充值记录统计');

            return $this->fetch();
        }
        else
        {
            $recharge = new RechargeModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['title','like','%'.$post['name'].'%'];
            }

            $infolist = $recharge
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->with('user,verify')
                ->select();

            $total = $recharge->where($map)->count();

            $result = [
                'total'=>$total,
                'rows'=>$infolist
            ];

            return json($result);
        }
    }

    /**
     * 导出用水记录
     */
    public function expWaterReport()
    {
        $columninfo = config('statistics.water');

        $waterUsed = new WaterUsedModel();
        $infolist = $waterUsed
            ->where('amount','>',0)
            ->order('id desc')
            ->with('user')
            ->select();

        $infolist = WaterUsedModel::formatInfoList($infolist);
        $title = '用水记录报表信息';

        Report::expReport($title,$columninfo,$infolist,'water');
    }

    /**
     * 导出充值记录
     */
    public function expRechargeReport()
    {

    }
}