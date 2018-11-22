<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/20
 * Time: 16:49
 */

namespace app\index\controller;

use app\index\model\Member as MemberModel;


class Member extends Base
{
    /**
     * 显示管理员列表
     * @return mixed|\think\response\Json
     */
    public function index()
    {
        if(request()->isGet())
        {
            $this->assign('title','管理员管理');
            return $this->fetch();
        }
        else
        {
            $member = new MemberModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['username','like','%'.$post['name'].'%'];
            }

            $infolist = $member
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->select();
            $total = $member->where($map)->count();

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
        $data = MemberModel::formatData($data);

        return MemberModel::formatResult(MemberModel::create($data));
    }

    /**
     * 更新内容
     * @return array|bool|null|\PDOStatement|string|\think\Model
     */
    public function edit()
    {
        if(Request()->isGet())
        {
            $id = input('param.id');
            $map['id'] = $id;
            $result = MemberModel::getOne($map);
            $result = MemberModel::formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $data = MemberModel::formatData($data);
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new MemberModel;
            $result = $model->save($data,$map);
            $result = MemberModel::formatResult($result);

            return $result;
        }
    }

    /**
     * 删除一条或多条记录
     * @return array|bool
     */
    public function delete()
    {
        $id = input('param.id');

        $result = MemberModel::destroy($id);
        $result = MemberModel::formatResult($result);

        return $result;
    }

    /**
     * 更改状态
     * @return array|bool
     */
    public function status()
    {
        $id = input('param.id');
        $model = MemberModel::get($id);
        $flag = $model->getData('status')?0:1;

        $data = ['status'=>$flag];
        $map['id'] = $id;

        $result = $model->save($data,$map);
        $result = MemberModel::formatResult($result);

        return $result;
    }
}