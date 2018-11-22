<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 14:51
 */

namespace app\index\controller;

use app\index\model\Privilege as PrivilegeModel;
use app\index\model\Role as RoleModel;


class Role extends Base
{
    /**
     * 显示角色列表
     * @return mixed|\think\response\Json
     */
    public function index()
    {
        if(request()->isGet())
        {
            $this->assign('title','角色管理');
            return $this->fetch();
        }
        else
        {
            $role = new RoleModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['title','like','%'.$post['name'].'%'];
            }

            $infolist = $role
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->select();
            $total = $role->where($map)->count();

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
        return RoleModel::formatResult(RoleModel::create($data));
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
            $result = RoleModel::getOne($map);
            $result = RoleModel::formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new RoleModel;
            $result = $model->save($data,$map);
            $result = RoleModel::formatResult($result);

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

        $result = RoleModel::destroy($id);
        $result = RoleModel::formatResult($result);

        return $result;
    }

    /**
     * 更改状态
     * @return array|bool
     */
    public function flag()
    {
        $id = input('param.id');
        $model = RoleModel::get($id);
        $flag = $model->getData('status')?0:1;

        $data = ['status'=>$flag];
        $map['id'] = $id;

        $result = $model->save($data,$map);
        $result = NoticeModel::formatResult($result);

        return $result;
    }

    /**
     * 获取权限列表
     * @return string
     */
    public function access()
    {
        if(Request()->isGet())
        {
            $id = input('param.id');
            $privilege = new PrivilegeModel();
            $map = ['id' => $id];
            $rules = RoleModel::getField($map, 'rules');
            $result = RoleModel::getAccessList($privilege, $rules);
            $result = RoleModel::formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new RoleModel;
            $result = $model->save($data,$map);
            $result = RoleModel::formatResult($result);

            return $result;
        }
    }
}