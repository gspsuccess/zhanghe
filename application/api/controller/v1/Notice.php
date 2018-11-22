<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/9
 * Time: 13:49
 */

namespace app\api\controller\v1;

use app\api\model\NoticeUser as NoticeUserModel;
use app\api\service\Token as TokenService;
use app\api\model\Notice as NoticeModel;
use app\api\model\User as UserModel;
use think\facade\Hook;


class Notice extends Base
{
    /**
     * 获取公告列表
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function getNotices()
    {
        $user_id = TokenService::getCurrentUid();
        $massif_id = UserModel::getField(['id'=>$user_id],'massif_id');

        $noticeUser = new NoticeUserModel();

        $params = input('param.');
        $result = NoticeModel::getNotices($user_id,$massif_id,$noticeUser,$params);

        return $result;
    }

    /**
     * 获取公告详情
     * @return array|null|\PDOStatement|string|\think\Model
     */
    public function getNotice()
    {
        $notice_id = input('param.id');
        $user_id = TokenService::getCurrentUid();

        $result = NoticeModel::getOne(['id'=>$notice_id],'','','project,massif');
        $result = NoticeModel::formatResult($result);

        $params = [
            'notice_id'=>$notice_id,
            'user_id'=>$user_id
        ];

        //读取公告后将用户与公告的关联信息存入公告用户关联表
        Hook::exec('app\\api\\behavior\\NoticeUserAdd',$params);

        return $result;
    }
}