<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/18
 * Time: 18:48
 */

namespace app\index\controller;


use app\index\model\Member;
use think\Controller;
use think\captcha\Captcha;
use think\Session;
use think\Url;

class Login extends Controller
{
    protected $middleware = [
        'LoginRecord' => ['only' => ['handle'] ],
    ];

    /**
     * 展示登录界面
     * @return mixed
     */
    public function index()
    {
        $this->view->engine->layout(false);
        return $this->fetch('login');
    }

    /**
     * 处理登录逻辑
     */
    public function handle()
    {
        $userinfo = input('post.');
        $username = $userinfo['username'];
        $password = $userinfo['password'];
        $code = $userinfo['captcha'];

        $captcha = new Captcha();
        if (!$captcha->check($code))
        {
            $this->error('验证码不正确');
        }

        if($member_info = Member::checkPassword($username,$password))
        {
            session('member_id',$member_info['id']);
            session('username',$member_info['username']);
            $this->success('登录成功','/index/index/index');
        }
        else
        {
            $this->error('用户名或密码有误');
        }
    }

    /**
     * 显示验证码
     * @return \think\Response
     */
    public function verify()
    {
        $captcha = new Captcha();
        $captcha->useZh = true;
        return $captcha->entry();
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        session(null);
        $this->redirect('/index/login/index');
    }
}