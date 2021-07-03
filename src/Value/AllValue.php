<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 13:45
 */

namespace QSM\Value;


/*
 * 等价于全部
 * */
class AllValue implements BaseValue
{
    public function value()
    {
        return "all";
    }
}