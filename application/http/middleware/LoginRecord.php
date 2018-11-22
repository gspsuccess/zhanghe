<?php

namespace app\http\middleware;

use think\facade\Log;
use app\index\model\LoginRecord as LoginRecordModel;

/**
 * 后置中间件（用于登录后给登录记录表中增加一条记录）
 * Class LoginRecord
 * @package app\http\middleware
 */
class LoginRecord
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        // 添加中间件执行代码
        $this->create(session('member_id'));

        return $response;
    }

    /**
     * 给登录记录表中增加一条记录
     * 若session中有member_id则增加，否则不做任何操作
     * @param $member_id
     * @return bool
     */
    private function create($member_id)
    {
        if(!$member_id)
        {
            return false;
        }

        $data = [
            'member_id'=>$member_id,
            'create_time'=>time(),
            'login_ip'=>get_proxy_ip()
        ];

        $loginrecordModel = new LoginRecordModel();
        $result = $loginrecordModel->save($data);

        if(!$result)
        {
            Log::error('数据并未正确保存');
            return false;
        }

        return true;
    }
}
