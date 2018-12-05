<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/11/22
 * Time: 16:42
 */

namespace app\index\controller;

use app\index\model\ApplyVerify as ApplyVerifyModel;
use app\index\enum\StepEnum;
use app\index\service\Member as MemberService;


class ApplyVerify extends Base
{
    /**
     * 待审核信息列表
     * @return array|mixed
     */
    public function index()
    {
        if(request()->isGet())
        {
            $this->assign('title','信息审核');
            return $this->fetch();
        }
        else
        {
            $step_id = MemberService::getStepId(session('member_id'));

            $map = [
                'status'=>['=',StepEnum::UNTREATED],
                'step_id'=>['=',$step_id]
            ];

            $applyVerifyModel = new ApplyVerifyModel();
            $infolist = $applyVerifyModel
                ->where($map)
                ->with('subcribe,member')
                ->select();

            $total = $applyVerifyModel->where($map)->count();

            $result = [
                'total'=>$total,
                'rows'=>$infolist
            ];

            return $result;
        }
    }

    /**
     * 审核操作
     * @return array|bool|null|\PDOStatement|string|\think\Model
     */
    public function edit()
    {
        if(request()->isGet())
        {
            $id = input('param.id');
            $map['id'] = $id;
            $result = ApplyVerifyModel::getOne($map,'','member,subcribe');
            $result = formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');

            $map['id'] = $data['id'];
            $model = new ApplyVerifyModel;
            $result = $model->save($data,$map);
            $result = formatResult($result);

            return $result;
        }
    }
}