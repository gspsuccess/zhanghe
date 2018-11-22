<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 16:03
 */

namespace app\index\controller;

use app\index\model\User as UserModel;
use app\index\model\Project as ProjectModel;
use app\index\model\Massif as MassifModel;

use app\index\model\UserDevice as UserDeviceModel;
use app\index\service\User as UserService;


class User extends Base
{
    /**
     * 显示用户列表
     * @return mixed|\think\response\Json
     */
    public function index()
    {
        if(request()->isGet())
        {
            $projects = ProjectModel::getAll();
            $projects_select = create_select($projects);

            $map['project_id'] = $projects[0]['id'];
            $massifs = MassifModel::getAll($map);
            $massifs_select = create_select($massifs);

            $this->assign('projects_select',$projects_select);
            $this->assign('massifs_select',$massifs_select);
            $this->assign('title','用户管理');

            return $this->fetch();
        }
        else
        {
            $user = new UserModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['realname','like','%'.$post['name'].'%'];
            }

            $infolist = $user
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->with('project,massif')
                ->select();

            $total = $user->where($map)->count();

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

        return UserModel::formatResult(UserModel::create($data));
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
            $result = UserModel::getOne($map);
            $result = UserModel::formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new UserModel;
            $result = $model->save($data,$map);
            $result = UserModel::formatResult($result);

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
        $result = UserModel::destroy($id);
        $result = UserModel::formatResult($result);

        return $result;
    }

    /**
     * 更改状态
     * @return array|bool
     */
    public function flag()
    {
        $id = input('param.id');
        $model = UserModel::get($id);
        $flag = $model->getData('flag')?0:1;

        $data = ['flag'=>$flag];
        $map['id'] = $id;

        $result = $model->save($data,$map);
        $result = UserModel::formatResult($result);

        return $result;
    }

    /**
     * 设置关联设备
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function devices()
    {
        if(request()->isGet())
        {
            $id = input('param.id');
            $devices = UserService::getDevices($id);

            return $devices;
        }
        else
        {
            $ids = input('post.ids');
            $user_id = input('post.user_id');

            $result = UserService::setDevices($user_id,$ids);
            $result = UserDeviceModel::formatResult($result);

            return $result;
        }
    }
}