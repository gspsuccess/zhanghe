<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/9
 * Time: 14:09
 */

namespace app\api\model;


class Notice extends Base
{
    protected $table = 'notices';
    protected $autoWriteTimestamp = true;
    protected $hidden = ['update_time','delete_time','flag'];

    public function project()
    {
        return $this->belongsTo('project');
    }

    public function massif()
    {
        return $this->belongsTo('massif');
    }

    /**
     * 获取公告列表
     * @param $user_id
     * @param $massif_id
     * @param $noticeUser
     * @return array|\PDOStatement|string|\think\Collection
     */
    public static function getNotices($user_id,$massif_id,NoticeUser $noticeUser,$params = [])
    {
        $pageNum = isset($params['pageNum'])?$params['pageNum']:1;
        $pageSize = isset($params['pageSize'])?$params['pageSize']:10;

        $notice_ids = $noticeUser->where(['user_id'=>$user_id])
            ->field('notice_id')
            ->select()
            ->toArray();

        $notice_ids = array_column($notice_ids,'notice_id');

        $result = self::where('massif_id','=',$massif_id)
            ->page($pageNum, $pageSize)
            ->with('project,massif')
            ->select();

        foreach($result as &$v)
        {
            $v['is_read'] = in_array($v['id'],$notice_ids)?true:false;
        }

        $result = formatResult($result);

        return $result;
    }
}