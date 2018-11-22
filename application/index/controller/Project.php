<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 14:51
 */

namespace app\index\controller;

use app\index\model\Project as ProjectModel;
use think\Request;


class Project extends Base
{
    /**
     * 显示项目列表
     * @return mixed|\think\response\Json
     */
    public function index()
    {
        if(request()->isGet())
        {
            $this->assign('title','项目管理');
            return $this->fetch();
        }
        else
        {
            $project = new ProjectModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['name','like','%'.$post['name'].'%'];
            }

            $infolist = $project
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->select();
            $total = $project->where($map)->count();

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
        return formatResult(ProjectModel::create($data));
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
            $result = ProjectModel::getOne($map);
            $result = ProjectModel::formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new ProjectModel;
            $result = $model->save($data,$map);
            $result = ProjectModel::formatResult($result);

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

        $result = ProjectModel::destroy($id);
        $result = ProjectModel::formatResult($result);

        return $result;
    }
}