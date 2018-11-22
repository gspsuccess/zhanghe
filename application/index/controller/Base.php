<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/18
 * Time: 18:43
 */

namespace app\index\controller;


use think\Controller;
use app\extend\Auth\Auth;

class Base extends Controller
{
    protected $current_action;

    public function initialize()
    {
        if(session('member_id') && session('username'))
        {
            $auth = new Auth();
            $this->current_action = request()->module() . '/' . request()->controller() . '/' . lcfirst(request()->action());

            $result = $auth->check($this->current_action, session('member_id'));

            //根据权限获取可以操作的菜单列表
            $menuList = $auth->getMenuList();
            $menu = getMenu($menuList,$this->current_action);

            $this->assign('menu',$menu);
            $this->assign('username',session('username'));
            $this->assign('title','水费征收管理系统');

            //如果无权限，则提醒
            if (!$result) {
                $this->error('您暂无权限操作此类目');
            }
        }
        else
        {
            $this->redirect('/index/login/index');
        }
    }
}