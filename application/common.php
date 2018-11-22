<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * @param $menu_arr   //传入的菜单数组
 * @param $url        //点击的链接
 * @return array|bool
 */
function getMenu($menu_arr,$url)
{
    //将不是菜单栏的过滤掉
    $menu_arr = array_filter($menu_arr,function($var){
        return ($var['is_menu'] == 1);
    });
    $url = strtolower($url);

    if (!is_array($menu_arr)){
        return false;
    }

    //获取顶级菜单列表
    $pList = [];
    foreach($menu_arr as $k => &$v)
    {
        if($v['pid'] == 0)
        {
            $v['active'] = false;
            $pList[] = $v;
        }
    }

    //获取子菜单列表
    foreach($menu_arr as $kitem => &$vitem)
    {
        foreach($pList as $k => &$v)
        {
            if($vitem['pid'] == $v['id'])
            {
                //将所点击的列表项以及其父列表项的active置true
                $vitem['active'] = ($vitem['name'] == $url);
                if(!isset($v['active']) || !$v['active'])
                {
                    $v['active'] = $vitem['name'] == $url;
                }
                $v['sub'][] = $vitem;
            }
        }
    }

    return $pList;
}

/**
 * 生成指定长度随机字符串
 * @param int $length
 * @param string $chars
 * @return string
 */
function random_str($length = 6,$chars = '')
{
    $result = '';
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    $chars = empty($chars)?$str:$chars;

    for($i = 0; $i < $length; $i++)
    {
        $result .= $chars[mt_rand(0,strlen($chars) - 1)];
    }

    return $result;
}

/**
 * 生成加密后的密码
 * @param $password
 * @param $salt
 * @return string
 */
function create_password($password,$salt)
{
    $result = md5(md5($password).$salt);
    return $result;
}

/**
 * 获取真实IP
 * @return string
 */
function get_proxy_ip()
{
    $arr_ip_header = array(
        'HTTP_CDN_SRC_IP',
        'HTTP_PROXY_CLIENT_IP',
        'HTTP_WL_PROXY_CLIENT_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
    );
    $client_ip = 'unknown';
    foreach ($arr_ip_header as $key) {
        if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != 'unknown') {
            $client_ip = $_SERVER[$key];
            break;
        }
    }
    return $client_ip;
}

/**
 * 生成下拉列表
 * @param $infolist
 * @param $current_id       //当前选中项的ID
 * @param string $key       //下拉列表中值对应的字段名  如  <option value="1">  这其中1这个值对应的字段名
 * @param string $val       //下拉列表中文本对应的字段名  如<option value="1">山西省</option>
 *                            这其中山西省这个地方对应的字段名
 * @return string
 */
function create_select($infolist,$current_id = 0,$key = 'id', $val = 'name')
{
    $result = '';
    foreach($infolist as $k => $v)
    {
        $selected = ($v[$key] == $current_id)?' selected':'';
        $result .= '<option value="'.$v[$key].'"'.$selected.'>'.$v[$val].'</option>';
    }

    return $result;
}

/**
 * 生成充值订单号（从后台充值的以B开头，微信的以W开头）
 * @param string $types
 * @return string
 */
function create_trade_no($types = 'W')
{
    $result = $types.date('YmdHis').rand(1000,9999);
    return $result;
}

/**
 * 获取某月的头一天和最后一天
 * @param $date
 * @return array
 */
function get_the_month($date)
{
    $firstday = date('Y-m-01', strtotime($date));
    $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));

    return [$firstday, $lastday];
}

/**
 * 格式化处理结果
 * @param $data
 * @param string $msg
 * @return array
 */
function formatResult($data,$msg = '')
{
    $result = [
        'code'=>0,
        'msg'=>'操作成功'
    ];

    if(!$data)
    {
        $result = [
            'code'=>-1,
            'msg'=>'操作失败'
        ];
    }

    if(!empty($msg))
    {
        $result['msg'] = $msg;
    }

    $result['data'] = $data;

    return $result;
}