<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 11:45
 */

namespace QSM\Help;


class StrHealper
{
    /**
     *@description 下划线分隔转驼峰
     *
     *@author biandou
     *@date 2021/6/15 11:46
     *@param string $str 需要转换的字符串
     *@param bool $low 是否是小驼峰
     *
     *@return string
     */
    public static function underToHump(string $str,bool $low = true)
    {
        $strs = explode('_',$str);

        $items = [];

        foreach ($strs as $index=>$sp) {
            if($index == 0 && $low) {
                $it = lcfirst($sp);
            }else {
                $it = ucfirst($sp);
            }
            $items[] = $it;
        }

        return implode("",$items);
    }

    /**
     *@description 判断一个值是否可以用字符串是分割，是则分割，否则返回原值
     *
     *@author biandou
     *@date 2021/6/15 14:47
     *@param string $str 需要检测的字符串
     *
     *@return mixed
     */
    public static function splitStrByCommaOrNot(string $val)
    {
        if(!is_string($val)) {
            return $val;
        }

        $strs = explode(",",$val);
        if(count($strs) > 1) {
            return $strs;
        }

        return $val;
    }
}