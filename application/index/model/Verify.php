<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/10/8
 * Time: 16:14
 */

namespace app\index\model;


class Verify extends Base
{
    protected $table = 'recharge_verifies';
    protected $autoWriteTimestamp = true;
    protected $observerClass = 'app\index\event\Verify';

    /**
     * 与充值表进行关联
     * @return \think\model\relation\BelongsTo
     */
    public function recharge()
    {
        return $this->belongsTo('recharge');
    }

    /**
     * 新增记录（单条）
     * @param $ids
     * @return array|bool|\think\Collection
     * @throws \Exception
     */
    public function createAll($ids)
    {
        $item['recharge_id'] = $ids;
        $result = $this->save($item);
        $result = self::formatResult($result);
        return $result;
    }
}