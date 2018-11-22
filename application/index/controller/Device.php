<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 15:01
 */

namespace app\index\controller;

use app\index\model\Device as DeviceModel;
use app\index\model\Project as ProjectModel;
use app\index\model\Massif as MassifModel;

class Device extends Base
{
    /**
     * 显示项目列表
     * @return mixed|\think\response\Json
     */
    public function index()
    {
        if(Request()->isGet())
        {
            $projects = ProjectModel::getAll();
            $projects_select = create_select($projects);

            $map['project_id'] = $projects[0]['id'];
            $massifs = MassifModel::getAll($map);
            $massifs_select = create_select($massifs);

            $this->assign('projects_select',$projects_select);
            $this->assign('massifs_select',$massifs_select);
            $this->assign('title','设备管理');

            return $this->fetch();
        }
        else
        {
            $device = new DeviceModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['name','like','%'.$post['name'].'%'];
            }

            $infolist = $device
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->with('project,massif')
                ->select();

            $total = $device->where($map)->count();

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
        $data = DeviceModel::formatData($data);

        return DeviceModel::formatResult(DeviceModel::create($data));
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
            $result = DeviceModel::getOne($map);
            $result = DeviceModel::formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new DeviceModel;
            $result = $model->save($data,$map);
            $result = DeviceModel::formatResult($result);

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
        $result = DeviceModel::destroy($id);
        $result = DeviceModel::formatResult($result);

        return $result;
    }

    /**
     * 更改状态
     * @return array|bool
     */
    public function flag()
    {
        $id = input('param.id');
        $model = DeviceModel::get($id);
        $flag = $model->getData('flag')?0:1;

        $data = ['flag'=>$flag];
        $map['id'] = $id;

        $result = $model->save($data,$map);
        $result = DeviceModel::formatResult($result);

        return $result;
    }
}