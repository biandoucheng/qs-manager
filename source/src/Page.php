<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 16:33
 */

namespace QSM;


class Page
{
    /**
     *@description 判断是否需要分页
     *
     *@author biandou
     *@date 2021/6/15 16:57
     *@param Cell $cell qs结构单元
     *@param QS $qs qs输出查询结构
     *
     *@return bool
     */
    public static function page(Cell $cell,QS $qs):bool
    {
        if($cell->order && isset($qs->select[$cell->name]) && in_array(strtoupper($cell->order),["ASC","DESC"])) {
            return true;
        }
        return false;
    }
}