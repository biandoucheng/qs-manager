<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/20
 * Time: 23:59
 */

namespace QSM\Help;


class ArrayHealper
{
    /**
     *@description 将标准对象转化为数组
     *
     *@author biandou
     *@date 2021/6/21 0:00
     *@param array $items 装有StdClass对象的数组
     *
     *@return array
     */
    public static function turnStdClassToArray(array $items):array
    {
        $items = (array)$items;

        $items =  array_map(function ($value) {
            if($value instanceof \stdClass) {
                return (array)$value;
            }

            if(is_array($value)) {
                return self::turnStdClassToArray($value);
            }

            return $value;
        }, $items);

        return $items;
    }
}