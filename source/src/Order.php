<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 16:33
 */

namespace QSM;


class Order
{
    public static $ords = [
        "asc" => "ASC",
        "desc" => "DESC"
    ];

    /**
     *@description 输出排序
     *
     *@author biandou
     *@date 2021/6/15 16:51
     *@param Cell $cell qs结构单元
     *@param QS $qs qs输出查询结构
     *
     *@return bool
     */
    public static function valid(Cell $cell,QS $qs):bool
    {
        if($cell->order && isset($qs->select[$cell->name]) && self::isLegalOrd($cell->order)) {
            return true;
        }
        return false;
    }

    /**
     *@description 判断是否是一个合法的排序值
     *
     *@author biandou
     *@date 2021/6/18 14:08
     *@param string $ord 排序值
     *
     *@return bool
     */
    public static function isLegalOrd(string $ord):bool
    {
        $ord = strtolower($ord);
        return isset(self::$ords[$ord]);
    }
}