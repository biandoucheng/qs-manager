<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 12:42
 */
namespace QSM\Help;


trait AttrHelper
{
    /**
     *@description 属性赋值
     *
     *@author biandou
     *@date 2021/6/15 11:42
     *@param string $key 属性名
     *@param mixed $val 属性值
     */
    public function assignment(string $key,$val)
    {
        //驼峰转换
        $attr = StrHealper::underToHump($key);

        //设置属性值
        if(property_exists($this,$attr)) {
            $this->{$attr} = $val;
        }
    }

    /**
     *@description 批量属性赋值
     *
     *@author biandou
     *@date 2021/6/15 12:38
     *@param array $vals 属性=>属性值数组
     */
    public function assignmentFromArray(array $vals)
    {
        foreach ($vals as $key=>$val) {
            $this->assignment((string)$key,$val);
        }
    }
}