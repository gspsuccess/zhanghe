<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/30
 * Time: 16:50
 */

namespace app\index\controller;


use think\Controller;
use app\index\model\Massif as MassifModel;

class Ajax extends Controller
{
    public function massifs()
    {
        $post = input('post.');
        $massifs = MassifModel::getAll($post);
        $massifs_select = create_select($massifs);

        $result = MassifModel::formatResult($massifs_select);

        return $result;
    }
}