<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/7/2
 * Time: 9:32
 */

namespace QSM;

use QSM\Output;
use QSM\QS;

/*
 * 查询结构单元辅助工具
 * */
class CellFactory
{
    /*
     * @var array 查询规则结构
     * */
    protected $struct = [];

    /*
     * @object \App\Assist\QSM\Output 输出规则实例
     * */
    protected $outPut;

    /*
     * 初始化
     * */
    public function __construct()
    {
        $this->outPut = new Output();
    }

    /**
     *@description 获取
     *
     *@author biandou
     *@date 2021/7/2 11:16
     *@param string $field 字段名
     *
     *@return
     */
    public function get(string $field = ""):array
    {
        #去点
        $field = trim($field,".");

        #空代表获取完整的结构
        if(empty($field)) {
            return $this->struct;
        }

        return $this->struct[$field] ?? [];
    }

    /**
     *@description 设置一个字段结构进去
     *
     *@author biandou
     *@date 2021/7/2 11:20
     *@param string $field 字段
     *@param array $struct 字段结构
     */
    public function set(string $field,array $struct)
    {
        #去点
        $field = trim($field,".");

        #写入
        $this->struct[$field] = $struct;
    }

    /**
     *@description 重载一个新的结构进来
     *
     *@author biandou
     *@date 2021/7/2 11:21
     *@param array $struct 结构
     */
    public function reload(array $struct)
    {
        $this->struct = $struct;
    }

    /**
     *@description 方法作用
     *
     *@author biandou
     *@date 2021/7/2 11:48
     *@param array $output 输出属性设置数组
     */
    public function outputReset(array $output)
    {
        $this->outPut->assignmentFromArray($output);
    }

    /**
     *@description 方法作用
     *
     *@author biandou
     *@date 2021/7/2 11:53
     *@param QS $qs 查询结构实例
     */
    public function writeOutPutToQs(QS &$qs)
    {
        $this->outPut->write($qs);
    }
}