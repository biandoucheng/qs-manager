<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 15:10
 */

namespace QSM;

use QSM\QS;

class Select
{
    /*
     * @const 字段绝对不可以输出
     * */
    const FIELD_SHOW_ABS_NOT = 0;
    /*
     * @const 字段一定要输出
     * */
    const FIELD_SHOW_ABS_YES = 1;
    /*
     * @const 字段是否输出还需要依赖其它字段的出现与否
     * */
    const FIELD_SHOW_MORE_JUDGE = 2;
    /*
     * @var array 所有qs结构单元
     * */
    public static $cells = [];

    /**
     *@description 判断字段是否要输出
     *
     *@author biandou
     *@date 2021/7/3 10:28
     *@param Cell $cell qs结构单元
     *@param array $cells qs结构单元的数组 field=>cell
     *@param QS $qs qs结构
     *
     *@return bool
     */
    public static function showAble(Cell $cell,array $cells=[],QS &$qs):bool
    {
        #设置了qs->select则输出
        if(isset($qs->select[$cell->name])) {
            return true;
        }

        #该字段强制不输出
        if($cell->showNot) {
            return false;
        }

        #该字段要被合并掉，所以不输出
        if($cell->isMergeVal()) {
            return false;
        }

        #该字段指定输出
        if($cell->show) {
            return true;
        }

        #该字段选择全部输出
        if($cell->isAllVal()) {
            return true;
        }

        #show with 判断,所有关系字段输出则输出
        if($cell->showWith && self::isRelatedFieldShowAble($cell,$cells,"showWith",true,$qs)){
            return true;
        }

        #show whether 判断,所有关系字段至少输出一个则输出
        if($cell->showWhether && self::isRelatedFieldShowAble($cell,$cells,"showWhether",false,$qs)){
            return true;
        }

        #show without 判断,所有关系字段全部不输出则输出
        if($cell->showWithOut && self::isRelatedFieldShowAble($cell,$cells,"showWithout",true,$qs)){
            return true;
        }

        return false;
    }

    /**
     *@description 判断关系字段是否输出
     *
     *@author biandou
     *@date 2021/7/3 10:33
     *@param array $cells qs结构单元的数组 field=>cell
     *@param string $relation 关系类型
     *@param bool $flag 初始bool值
     *
     *@return bool
     */
    public static function isRelatedFieldShowAble(Cell $cell,array $cells,string $relation,bool $flag,QS $qs):bool
    {
        if(empty($cell->{$relation})) {
            return false;
        }

        foreach ($cell->{$relation} as $field) {
            #相关qs结构单元
            if(isset($cells[$field])) {
                $cel = $cells[$field];
            } #关系字段不存在
            if(empty($cel)) {
                return false;
            }

            #判断关系字段是否可输出
            $show = self::showAble($cel,$cells,$qs);
            if(!$show) {
                $flag = !$flag;
                break;
            }
        }

        return $flag;
    }

    /**
     *@description 字段是否输出
     *
     *@author biandou
     *@date 2021/6/15 15:18
     *@param Cell $cell qs结构单元
     *@param array $cells qs结构单元的数组 field=>cell
     *@param QS $qs qs结构
     *
     *@return bool
     */
    public static function valid(Cell $cell,array $cells=[],QS &$qs):bool
    {
        if(isset($qs->fields[$cell->realField()])) {
            return true;
        }

        $show = self::selectAble($cell,$qs);
        switch ($show){
            case self::FIELD_SHOW_ABS_NOT;
                return false;
            case self::FIELD_SHOW_ABS_YES:
                return true;
        }

        #show with 判断,所有关系字段输出则输出
        if($cell->showWith && self::isRelatedFieldselectAble($cell,$cells,"showWith",true,$qs)){
            return true;
        }

        #show whether 判断,所有关系字段至少输出一个则输出
        if($cell->showWhether && self::isRelatedFieldselectAble($cell,$cells,"showWhether",false,$qs)){
            return true;
        }

        #show without 判断,所有关系字段全部不输出则输出
        if($cell->showWithOut && self::isRelatedFieldselectAble($cell,$cells,"showWithout",true,$qs)){
            return true;
        }

        return false;
    }

    /**
     *@description 判断该字段是否应该输出
     *
     *@author biandou
     *@date 2021/6/15 15:45
     *@param Cell $cell qs结构单元
     *@param QS $qs qs结构
     *
     *@return int 字段输出选项
     */
    public static function selectAble(Cell $cell,QS &$qs):int
    {
        #已经设置了该字段
        if(isset($qs->fields[$cell->realField()])) {
            return self::FIELD_SHOW_ABS_YES;
        }

        #该字段强制不输出
        if($cell->showNot) {
            return self::FIELD_SHOW_ABS_NOT;
        }

        #附属字段不输出
        if($cell->attach || $cell->calAttach) {
            return self::FIELD_SHOW_ABS_NOT;
        }

        #该字段要被合并掉，所以不输出
        if($cell->isMergeVal()) {
            return self::FIELD_SHOW_ABS_NOT;
        }

        #默认要求输出或者where条件中有该字段则输出
        if($cell->show || ($cell->where && $cell->isShowAbleVal())) {
            return self::FIELD_SHOW_ABS_YES;
        }

        #需要去检测一下是否有
        return self::FIELD_SHOW_MORE_JUDGE;
    }

    /**
     *@description 判断关系字段是否输出
     *
     *@author biandou
     *@date 2021/6/15 16:06
     *@param Cell $cell qs结构单元
     *@param array $cells qs结构单元的数组 field=>cell
     *@param string $relation 关系类型
     *@param bool $flag 初始bool值
     *@param QS $qs qs结构
     *
     *@return bool
     */
    public static function isRelatedFieldselectAble(Cell $cell,array $cells,string $relation,bool $flag,QS $qs):bool
    {
        if(empty($cell->{$relation})) {
            return false;
        }

        foreach ($cell->{$relation} as $field) {
            #相关qs结构单元
            if(isset($cells[$field])) {
                $cel = $cells[$field];
            } #关系字段不存在
            if(empty($cel)) {
                return false;
            }

            #判断关系字段是否可输出
            $show = self::selectAble($cel,$qs);
            if($show !== self::FIELD_SHOW_ABS_YES) {
                $flag = !$flag;
                break;
            }
        }

        return $flag;
    }
}