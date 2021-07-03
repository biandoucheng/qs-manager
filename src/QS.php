<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 11:38
 */

namespace QSM;

use QSM\Help\AttrHelper;
use QSM\Cell;

/*
 * qs 结构类
 * */
class QS
{
    use AttrHelper;

    /*
     * @var bool 是否是导出操作
     * */
    public $excel;

    /*
     * @var bool 导出控制字段名称
     * */
    public $excelName;

    /*
     * @var bool 是否作为导出数据源
     * */
    public $asExportSource;

    /*
     * @var string 导出文件名称
     * */
    public $downloadName;

    /*
     * @var array 导出表头
     * */
    public $header;

    /*
     * @var array index 输出字段的顺序
     * */
    public $index;

    /*
     * @var array 参与输出字段的实际数据表字段,一定是真实字段  eg:['ss'=>'SUM(ss) AS ss','bb'=>bb ...]
     * */
    public $fields;

    /*
     * @var array 参与输出字段的实际数据表字段,不一定是真实字段 eg:['ss'=>true]
     * 键的值是无意义的
     * */
    public $select;

    /*
     * @var int 导出分页,0代表不分页
     * */
    public $page;

    /*
     * @var string 页码名称
     * */
    public $pageName;

    /*
     * @var int 分页行数,0则默认15
     * */
    public $limit;

    /*
     * @var string 分页行数名称
     * */
    public $limitName;

    /*
     * @var array where条件,字段=>Condition对象
     * */
    public $where;

    /*
     * @var array 输入where条件字段。不管是否是真实参与查询都记录
     * */
    public $whereOriginal;

    /*
     * @var array 分组字段
     * */
    public $group;

    /*
     * @var array 汇总需要查询的字段
     * */
    public $summaryFields;

    /*
     * @var array 排序 field=>ASC
     * */
    public $order;

    /*
     * @var array laravel Model|Db 的pluck
     * */
    public $pluck;

    /*
     * @var string 汇总某个字段,空代表不汇总 eg:sum=>income 对收益汇总
     * */
    public $sum;

    /*
     * @var string 计数,空代表不汇总
     * */
    public $count;

    /*
     * @var bool 是否只查询一条数据
     * */
    public $first;

    /*
     * @var bool 是否对数据汇总,默认不汇总
     * */
    public $summary;

    /*
     * @var bool 是否只进行汇总,不查询详细数据
     * */
    public $onlySummary;

    /*
     * @var array 需要提取出来,做附属信息查询的字段值
     * */
    public $attachFields;


    public function __construct()
    {
        $this->reload();
    }

    /**
     *@description 重置属性值
     *
     *@author biandou
     *@date 2021/6/15 12:35
     */
    public function reload()
    {
        $this->excel = false;
        $this->asExportSource = false;
        $this->downloadName = "download.csv";
        $this->header = [];
        $this->fields = [];
        $this->select = [];
        $this->page = 0;
        $this->pageName = "page";
        $this->limit = 30;
        $this->limitName = "limit";
        $this->where = [];
        $this->whereOriginal = [];
        $this->group = [];
        $this->summaryFields = [];
        $this->group = [];
        $this->order = [];
        $this->pluck = [];
        $this->sum = "";
        $this->count = "";
        $this->first = false;
        $this->summary      = false;
        $this->attachFields = [];
    }

    /**
     *@description 判断查询结果是否是只有一行的
     *
     *@author biandou
     *@date 2021/6/21 0:28
     *
     *@return bool
     */
    public function isOnlyOneLineResult():bool
    {
        if($this->isOneColumnPluck() || $this->isOnlyOneValueResult()) {
            return true;
        }

        return $this->first || $this->sum || $this->count;
    }

    /**
     *@description 判断查询结果是否是只有一个值的
     *
     *@author biandou
     *@date 2021/6/21 0:29
     *
     *@return bool
     */
    public function isOnlyOneValueResult():bool
    {
        return $this->sum || $this->count;
    }

    /**
     *@description 判断查询结果是否是只有一个字段值的pluck列表
     *
     *@author biandou
     *@date 2021/6/21 0:29
     *
     *@return bool
     */
    public function isOneColumnPluck()
    {
        return count($this->pluck) == 1;
    }

    /**
     *@description 添加一个Where条件
     *
     *@author biandou
     *@date 2021/7/3 11:02
     *@param array $cell qs结构单元配置数组
     */
    public function addWhere(array $cell)
    {
        $newCell = new Cell();
        $newCell->assignmentFromArray($cell);
        if(Where::valid($newCell)) {
            $this->where[$newCell->realField()] = Where::build($newCell);
        }
    }
}