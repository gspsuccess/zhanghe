<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 15:12
 */

namespace app\index\controller;

use app\index\model\Recharge as RechargeModel;
use app\index\model\Verify as VerifyModel;
use app\index\model\User as UserModel;


class Verify extends Base
{
    /**
     * 充值审核信息新增
     * @return array|bool|\think\Collection
     */
    public function add()
    {
        $id = input('param.id');
        $verifyModel = new VerifyModel();
        $result = $verifyModel->createAll($id);

        return $result;
    }

    /**
     * 显示充值信息列表
     * @return mixed|\think\response\Json
     */
    public function index()
    {
        if(request()->isGet())
        {
            $users = UserModel::getAll();
            $users_select = create_select($users,0,'id','realname');

            $this->assign('users_select',$users_select);
            $this->assign('title','充值信息审核');

            return $this->fetch();
        }
        else
        {
            $recharge = new RechargeModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = ['types'=>1,'is_verify'=>0];
            if(!empty($post['name']))
            {
                $map[] = ['title','like','%'.$post['name'].'%'];
            }

            $infolist = $recharge
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc,is_verify asc')
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
}