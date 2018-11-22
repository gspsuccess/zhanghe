<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/19
 * Time: 15:01
 */

namespace app\index\controller;

use app\index\model\Bill as BillModel;

class Bill extends Base
{
    /**
     * 显示项目列表
     * @return mixed|\think\response\Json
     */
    public function index()
    {
        if(request()->isGet())
        {
            $this->assign('title','发票管理');

            return $this->fetch();
        }
        else
        {
            $notice = new BillModel();
            $post = input('post.');
            $offset = empty($post['offset'])?0:$post['offset'];
            $pagesize = empty($post['pagesize'])?10:$post['pagesize'];

            $map = [];
            if(!empty($post['name']))
            {
                $map[] = ['title','like','%'.$post['name'].'%'];
            }

            $infolist = $notice
                ->where($map)
                ->limit($offset,$pagesize)
                ->order('id desc')
                ->select();

            $total = $notice->where($map)->count();

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
        $result = BillModel::create($data);

        return formatResult($result);
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
            $result = BillModel::getOne($map);
            $result = formatResult($result);

            return $result;
        }
        else
        {
            $data = input('post.');
            $map['id'] = $data['id'];
            unset($data['id']);
            $model = new BillModel;
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
        $result = BillModel::destroy($id);
        $result = formatResult($result);

        return $result;
    }

    /**
     * 更改状态
     * @return array|bool
     */
    public function flag()
    {
        $id = input('param.id');
        $model = BillModel::get($id);
        $flag = $model->getData('flag')?0:1;

        $data = ['flag'=>$flag];
        $map['id'] = $id;

        $result = $model->save($data,$map);
        $result = formatResult($result);

        return $result;
    }
}