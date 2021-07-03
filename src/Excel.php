<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 16:34
 */

namespace QSM;


class Excel
{
    /**
     *@description 方法作用
     *
     *@author biandou
     *@date 2021/6/15 17:00
     *@param Cell $cell qs结构单元
     *@param QS $qs qs输出查询结构
     *
     *@return
     */
    public function excel(Cell $cell,QS $qs)
    {
        if($cell->excel && isset($qs->select[$cell->name])) {
            return true;
        }
        return false;
    }
}