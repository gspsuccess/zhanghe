<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/12/4
 * Time: 14:54
 */

namespace app\index\event;

use app\index\enum\ApplyVerifyEnum;
use app\index\model\ApplyVerify as ApplyVerifyModel;
use app\index\model\Member as MemberModel;
use app\index\model\Dominate as DominateModel;

use think\facade\Log;

class ApplyVerify
{
    public function afterUpdate($apply_verify)
    {
        //如果通过审核，
        //1. step_id 不等于 3，则新生成一条审核记录
        //2. step_id 等于3，则生成配水计划
        //如果未通过审核，则发邮件通知给用水协会原因
        $info = ApplyVerifyModel::getOne(['id'=>$apply_verify->id]);
        if($apply_verify->status == ApplyVerifyEnum::DENYED)
        {
            $member_info = MemberModel::getOne(['id'=>$info['apply_member_id']]);
            send_mail($member_info['email'],$member_info['realname'],'审核结果通知','审核未通过，失败原因为：'.$apply_verify->status_result);
        }
        else
        {
            if($info['step_id'] == 3)
            {
                Log::record('我是局级领导，我通过了');
            }
            else
            {
                $data = [
                    'apply_member_id'=>$info['apply_member_id'],
                    'step_id'=>$info['step_id'] + 1,
                    'relation_id'=>$info['id']
                ];

                ApplyVerifyModel::create($data);
            }
        }
    }
}