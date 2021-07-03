<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 13:43
 */

namespace QSM\Value;


/*
 * 等价于没有赋值
 * */
class NotSetValue implements BaseValue
{
    public function value()
    {
        return null;
    }
}