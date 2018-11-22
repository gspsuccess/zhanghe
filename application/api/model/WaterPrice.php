<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/11
 * Time: 12:21
 */

namespace app\api\model;


class WaterPrice extends Base
{
    protected $table = 'water_prices';

    /**
     * 获取当前水价（如果未设置则为0）
     * @param $water_used
     * @param $project_id
     * @return array|int|mixed|null|\PDOStatement|string|\think\Model
     */
    public static function getCurrentPrice($water_used,$project_id)
    {
        $result = self::where('project_id','=',$project_id)
            ->where('amount_from','<=',$water_used)
            ->where('amount_to','>',$water_used)
            ->field('price')
            ->find();

        if(!isset($result['price']))
        {
            $result = self::where('project_id','=',$project_id)
                ->field('price')
                ->order('amount_to desc')
                ->find();
        }

        $result = isset($result['price'])?$result['price']:0;

        return $result;
    }

    /**
     * 获取一定取水量所需的金额
     * @param $water_used
     * @param $amount
     * @param $project_id
     * @return int
     */
    public static function getMoney($water_used,$amount,$project_id)
    {
        $water_used_end = $water_used + $amount;
        $water_price_start = self::getCurrentPrice($water_used,$project_id);
        $water_price_end = self::getCurrentPrice($water_used_end,$project_id);

        if($water_price_start == $water_price_end)
        {
            $result = $amount * $water_price_start;
        }
        else
        {
            $result = 1000;
        }

        return $result;
    }
}