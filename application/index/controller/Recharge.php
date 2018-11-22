<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 15:10
 */

namespace app\index\controller;

use app\index\model\User as UserModel;
use app\index\model\Recharge as RechargeModel;


class Recharge extends Base
{
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
            $this->assign('title','充值信息管理');

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
     * 添加内容
     * @return array
     */
    public function add()
    {
        $data = input('post.');

        return RechargeModel::formatResult(RechargeModel::create($data));
    }

    /**
     * 更新内容
     * @return array|bool|null|\PDOStatement|string|\think\Model
     */
    public function edit()
    {
        if(request()->isGet())
        {
            $id = input('param.id');
            $map['id'] = $id;
            $result = RechargeModel::getOne($map);
            $result = RechargeModel::formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new RechargeModel;
            $result = $model->save($data,$map);
            $result = RechargeModel::formatResult($result);

            return $result;
        }
    }

    /**
     * 删除一条记录
     * @return array|bool
     */
    public function delete()
    {
        $id = input('param.id');
        $result = RechargeModel::destroy($id);
        $result = RechargeModel::formatResult($result);

        return $result;
    }

    /**
     * 更改状态
     * @return array|bool
     */
    public function flag()
    {
        $id = input('param.id');
        $model = RechargeModel::get($id);
        $flag = $model->getData('flag')?0:1;

        $data = ['flag'=>$flag];
        $map['id'] = $id;

        $result = $model->save($data,$map);
        $result = RechargeModel::formatResult($result);

        return $result;
    }
}