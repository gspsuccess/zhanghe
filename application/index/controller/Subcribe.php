<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/22
 * Time: 20:14
 */

namespace app\index\controller;

use app\index\model\User as UserModel;
use app\index\model\Subcribe as SubcribeModel;


class Subcribe extends Base
{
    public function index()
    {
        if(request()->isGet())
        {
            $users = UserModel::getAll();
            $users_select = create_select($users, 0, 'id', 'realname');

            $this->assign('users_select', $users_select);
            $this->assign('title', '用水申报记录');
            return $this->fetch();
        }
        else
        {
            $subcribe = new SubcribeModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['title','like','%'.$post['name'].'%'];
            }

            $infolist = $subcribe
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->with('user')
                ->select();

            $total = $subcribe->where($map)->count();

            $result = [
                'total'=>$total,
                'rows'=>$infolist
            ];

            return json($result);
        }
    }

    /**
     * 添加记录
     * @return array|mixed
     */
    public function add()
    {
        if(request()->isGet())
        {
            $users = UserModel::getAll();
            $users_select = create_select($users,0,'id','realname');

            $this->assign('users_select',$users_select);
            $this->assign('title','新增用水申报');
            return $this->fetch();
        }
        else
        {
            $data = input('post.');
            $result = SubcribeModel::create($data);
            return formatResult($result);
        }
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
            $result = SubcribeModel::getOne($map);
            $result = formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new SubcribeModel;
            $result = $model->save($data,$map);
            $result = formatResult($result);

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
        $result = SubcribeModel::destroy($id);
        $result = formatResult($result);

        return $result;
    }
}