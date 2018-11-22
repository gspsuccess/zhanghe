<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/26
 * Time: 15:48
 */

namespace app\api\model;


class Recharge extends Base
{
    protected $table = 'recharges';
    protected $autoWriteTimestamp = true;
    protected $hidden = ['out_trade_no', 'certificate', 'remark', 'update_time', 'delete_time', 'handler_id', 'bill_id', 'prepay_id', 'is_verify'];

    public function getTypesAttr($value)
    {
        $result = ($value == 1) ? '后台充值' : '微信充值';
        return $result;
    }

    public function getStatusAttr($value)
    {
        $result = ($value == 1)?'未支付':'支付成功';
        return $result;
    }

    public function user()
    {
        return $this->belongsTo('user');
    }

    /**
     * 获取充值列表
     * @param $user_id
     * @param $params
     * @return array|\PDOStatement|string|\think\Collection
     */
    public static function getRecharges($user_id, $params)
    {
        $pageNum = isset($params['pageNum']) ? $params['pageNum'] : 1;
        $pageSize = isset($params['pageSize']) ? $params['pageSize'] : 10;

        if(isset($params['date']) && $params['date'])
        {
            $date = $params['date'];
            $month = get_the_month($date);

            $result = self::where('user_id', '=', $user_id)
                ->where('is_verify', '=', 1)
                ->where('status', '=', 2)
                ->where('create_time', '>=', strtotime($month[0]))
                ->where('create_time', '<=', strtotime($month[1]))
                ->order('create_time desc')
                ->page($pageNum, $pageSize)
                ->select();
        }
        else
        {
            $result = self::where('user_id', '=', $user_id)
                ->where('is_verify', '=', 1)
                ->where('status', '=', 2)
                ->order('create_time desc')
                ->page($pageNum, $pageSize)
                ->select();
        }

        $result = parent::formatResult($result);

        return $result;
    }
}