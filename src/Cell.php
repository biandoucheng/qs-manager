<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 10:51
 */

namespace QSM;

use QSM\Help\AttrHelper;
use QSM\Value\NotSetValue;
use QSM\Value\AllValue;
use QSM\Value\MergeValue;

class Cell
{
    use AttrHelper;

    /*
     * @var string 字段名
     * */
    public $name;

    /*
     * @var string 字段输出别名 eg:os=>系统
     * */
    public $alias;

    /*
     * @var bool 默认情况下是否输出该字段
     * true 字段默认展示
     * false 字段参与where条件时且传入有效值时输出
     * */
    public $show;

    /*
     * @var bool 该字段是否禁止输出
     * */
    public $showNot;

    /*
     * @var array 指定字段都输出时才输出
     * */
    public $showWith;

    /*
     * @var array 指定字段至少都输出一个时才输出
     * */
    public $showWhether;

    /*
     * @var array 指定字段列表中的字段一个都不输出时才输出
     * */
    public $showWithOut;

    /*
     * @var string 字段在数据库中的实际名称 eg:system_plat=>os
     * */
    public $field;

    /*
     * @var bool 是否是汇总字段
     * */
    public $isSummaryField;

    /*
     * @var mixed 默认值
     * */
    public $def;

    /*
     * @var bool 默认情况下是否参与分组
     * true  默认参与分组
     * false 当where条件中包含该该字段的有效值时参与分组
     * */
    public $group;

    /*
     * @var string 排序类型,空代表不参与排序
     * */
    public $order;

    /*
     * @var array 当指定字段全部输出时才参与分组
     * */
    public $groupWith;

    /*
     * @var array 当指定字段至少有一个出现时才参与分组
     * */
    public $groupWhether;

    /*
     * @var array 当指定字段一个都不出现时才参与分组
     * */
    public $groupWithout;

    /*
     * @var string 是否参与查询条件,空代表不参与
     * '' => 不参与查询
     * 'eq_or_in' => 等于或者IN查询
     * */
    public $where;

    /*
     * @var string 该字段是否需要计算,空代表不参与计算
     * '' => 不需要计算
     * 'sum' => 需要聚合计算
     * */
    public $cal;

    /*
     * @var bool 该字段是否需要参与excel导出
     * */
    public $excel;

    /*
     * @var bool 是否是附加字段,该字段不在查询列表，需要额外手动添加
     * */
    public $attach;

    /*
     * @var bool 是否是计算型附加字段,该字段不在查询列表，需要额外手动计算得出
     * */
    public $calAttach;

    /*
     * @var mixed 这个字段的值
     * */
    public $val;

    /*
     * @var mixed 特殊值all
     * */
    public $allVal;

    /*
     * @var mixed 特殊值not set
     * */
    public $noSetVal;

    /*
     * @var mixed 特殊值merge
     * */
    public $mergeVal;

    /*
     * @var bool 是否在类似in查询条件下将逗号分隔变成数组
     * */
    public $commaToArray;

    /*
     * @var string 自定义查询条件,空代表不采用自定义
     * */
    public $customize;

    /*
     * @var int 导出列顺序
     * */
    public $index;


    public function __construct()
    {
        $this->reload();
    }

    /**
     *@description 重置属性值
     *
     *@author biandou
     *@date 2021/6/15 11:29
     */
    public function reload()
    {
        $this->name = "";
        $this->alias = "";
        $this->show = false;
        $this->showNot = false;
        $this->showWith = [];
        $this->showWhether = [];
        $this->showWithOut = [];
        $this->field = "";
        $this->isSummaryField = false;
        $this->group = false;
        $this->order = "";
        $this->groupWith = [];
        $this->groupWhether = [];
        $this->groupWithout = [];
        $this->where = "";
        $this->cal = "";
        $this->excel = true;
        $this->attach = false;
        $this->calAttach = false;
        $this->val = new NotSetValue();
        $this->allVal = "all";
        $this->commaToArray = false;
        $this->noSetVal = "";
        $this->mergeVal = "merge";
        $this->customize = "";
        $this->index = 0;
    }

    /**
     *@description 获取真实字段名
     *
     *@author biandou
     *@date 2021/6/15 14:01
     *
     *@return string
     */
    public function realField():string
    {
        return $this->field ?: $this->name;
    }

    /**
     *@description 检测是否是可忽略的条件
     *
     *@author biandou
     *@date 2021/6/15 13:54
     *
     *@return bool
     */
    public function isIgnoreVal():bool
    {
        if ($this->val instanceof AllValue || $this->val instanceof MergeValue || $this->val instanceof NotSetValue) {
            return true;
        }
        return false;
    }

    /**
     *@description 判断是否是Merge值类型
     *
     *@author biandou
     *@date 2021/6/15 15:51
     *
     *@return bool
     */
    public function isMergeVal()
    {
        return $this->val instanceof MergeValue;
    }

    /**
     *@description 根据值判断在show=>false时候,该字段是否要输出
     *
     *@author biandou
     *@date 2021/6/15 15:59
     *
     *@return bool
     */
    public function isShowAbleVal()
    {
        if($this->val instanceof NotSetValue || $this->val instanceof MergeValue) {
            return false;
        }
        return true;
    }

    /**
     *@description 判断是否是All值类型
     *
     *@author biandou
     *@date 2021/6/15 15:57
     *
     *@return bool
     */
    public function isAllVal()
    {
        return $this->val instanceof AllValue;
    }

    /**
     *@description 判断是否是附属字段
     *
     *@author biandou
     *@date 2021/6/16 11:47
     *@param
     *
     *@return bool
     */
    public function isAttach():bool
    {
        return !empty($this->attach) || !empty($this->calAttach);
    }
}