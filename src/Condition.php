<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 13:34
 */
namespace QSM;

use QSM\Help\AttrHelper;
use QSM\Value\NotSetValue;

class Condition
{
    use AttrHelper;

    /*
     * @var string 数据库字段
     * */
    public $field;

    /*
     * @var 查询条件 eg:>,<,= ...
     * */
    public $opt;

    /*
     * @var mixed 字段值
     * */
    public $val;

    /*
     * @var bool 是否可用
     * */
    private $enable;


    public function __construct()
    {
        $this->reload();
    }

    /**
     *@description 重置属性
     *
     *@author biandou
     *@date 2021/6/15 13:37
     */
    public function reload()
    {
        $this->field = "";
        $this->opt = "";
        $this->val = null;
        $this->enable = new NotSetValue;
    }

    /**
     *@description 激活该条件
     *
     *@author biandou
     *@date 2021/6/15 13:56
     */
    public function active()
    {
        $this->enable = true;
    }

    /**
     *@description 判断该条件是否有效
     *
     *@author biandou
     *@date 2021/6/15 13:57
     *
     *@return bool
     */
    public function isActive():bool
    {
        return $this->enable;
    }
}