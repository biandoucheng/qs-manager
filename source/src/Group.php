<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 16:33
 */

namespace QSM;


class Group
{
    /**
     *@description 判断是否参与分组
     *
     *@author biandou
     *@date 2021/6/15 16:37
     *@param Cell $cell qs结构单元
     *@param QS $qs qs输出查询结构
     *
     *@return bool
     */
    public static function valid(Cell $cell,QS $qs):bool
    {
        #附属字段不能分组
        if($cell->attach || $cell->calAttach) {
            return false;
        }

        #指定分组字段要参与分组
        if($cell->group) {
            return true;
        }

        #关系字段判断
        #所有关系字段都输出，则分组
        if($cell->groupWith) {
            foreach ($cell->groupWith as $field) {
                if(!isset($qs->select[$field])) {
                    return false;
                }
            }
            return true;
        }

        #至少有一个关系字段输出，则分组
        if($cell->groupWhether) {
            foreach ($cell->groupWith as $field) {
                if(isset($qs->select[$field])) {
                    return true;
                }
            }
            return false;
        }

        #全部关系字段都不输出，则分组
        if($cell->showWithOut) {
            foreach ($cell->groupWith as $field) {
                if(isset($qs->select[$field])) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }
}