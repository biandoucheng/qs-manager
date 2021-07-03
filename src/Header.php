<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/18
 * Time: 18:11
 */

namespace QSM;


/*
 * 表头输出处理类
 * */
class Header
{
    /**
     *@description 是否是导出表头字段
     *
     *@author biandou
     *@date 2021/6/18 18:11
     *@param Cell $cell qs结构单元
     *@param QS $qs qs结构
     *
     *@return bool
     */
    public static function valid(Cell $cell,QS &$qs):bool
    {
        #配置不允许其输出
        if(!$cell->excel || $cell->showNot) {
            return false;
        }

        #该字段是查询字段
        if(isset($qs->select[$cell->name])) {
            return true;
        }

        #判断关系字段是否展示了
        #show with 判断,所有关系字段输出则输出
        if($cell->showWith && self::isRelatedFieldShowAble($cell,$qs,"showWith",true)){
            return true;
        }

        #show whether 判断,所有关系字段至少输出一个则输出
        if($cell->showWhether && self::isRelatedFieldShowAble($cell,$qs,"showWhether",false)){
            return true;
        }

        #show without 判断,所有关系字段全部不输出则输出
        if($cell->showWithOut && self::isRelatedFieldShowAble($cell,$qs,"showWithout",true)){
            return true;
        }

        return false;
    }

    /**
     *@description 判断关系字段有没有输出
     *
     *@author biandou
     *@date 2021/6/18 18:27
     *@param Cell $cell qs结构单元
     *@param QS $qs qs结构
     *@param string $relation 关系类型
     *@param bool $flag 初始bool值
     *
     *@return bool
     */
    public static function isRelatedFieldShowAble(Cell $cell,QS &$qs,string $relation,bool $flag):bool
    {
        #关系不存在
        if(empty($cell->{$relation})) {
            return false;
        }

        foreach ($cell->{$relation} as $field) {
            #判断关系字段是否可输出
            if(!isset($qs->select[$field])) {
                $flag = !$flag;
            }
        }

        return $flag;
    }
}