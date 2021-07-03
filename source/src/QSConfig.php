<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 17:50
 */

namespace QSM;

use QSM\Value\NotSetValue;
use QSM\Value\MergeValue;
use QSM\Value\AllValue;
use QSM\Cell;
use QSM\Customize;

class QSConfig
{
    /**
     *@description 加载qs配置并绑定参数值
     *
     *@author biandou
     *@date 2021/6/16 11:06
     *@param array $conf qs配置
     *@param array $receive 传入参数
     *@param array $columns 强制指定输出字段
     *@param array $customize 用户自定义的查询结构,在接受的参数中包含了自定义的where操作,排序操作等
     *
     *@return array
     */
    public static function load(array $conf,array $receive=[],array $customizes=[],array $columns = []):array
    {
        #如果强制指定输出字段,则键值反转用来进行对其他字段强制不输出
        #强制输出时附加字段不受影响
        $abs = false;
        if($columns) {
            $columns = array_flip($columns);
            $abs = true;
        }

        $cellMap = [];
        foreach ($conf as $field=>$item) {
            #qs配置转Cell对象
            $cell = new Cell();
            $cell->name = $field;
            $cell->assignmentFromArray($item);

            #自定义处理
            if(isset($customizes[$field])) {
                (new Customize($customizes[$field]))->write($cell);
            }

            #参数值写入
            if(isset($receive[$cell->name])) {
                $cell->val = self::correctVal($cell,$receive[$cell->name]);
            }

            #没有允许输出的非附属字段强制不输出
            if($abs && !$cell->isAttach() && !isset($columns[$cell->name])) {
                $cell->showNot = true;
                $cell->show = false;
            }

            #装箱
            $cellMap[$field] = $cell;
        }

        return $cellMap;
    }

    /**
     *@description 矫正参数值
     *
     *@author biandou
     *@date 2021/6/16 11:25
     *@param Cell $cell qs结构单元
     *@param mixed $val 参数值
     *
     *@return mixed
     */
    public static function correctVal(Cell $cell,$val)
    {
        switch ($val) {
            case $cell->allVal:
                return new AllValue();
            case $cell->mergeVal:
                return new MergeValue();
            case $cell->noSetVal:
                return new NotSetValue();
        }

        return $val;
    }
}