<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/18
 * Time: 16:21
 */

namespace QSM;

use QSM\Help\AttrHelper;
use QSM\QS;

class Output
{
    use AttrHelper;

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
     * @var bool 是否导出,默认不导出
     * */
    public $excel;

    /*
     * @var string 导出名称
     * */
    public $downloadName;

    /*
     * @var bool 是否是但字段求和,默认不是
     * */
    public $sum;

    /*
     * @var bool 是否只查询一条数据,默认不是
     * */
    public $first;

    /*
     * @var array pluck提取字段
     * */
    public $pluck;

    /*
     * @var bool 是否对数据汇总,默认不汇总
     * */
    public $summary;

    /*
     * @var bool 是否对单页数据汇总
     * */
    public $pageSummary;

    /*
     * @var array 需要提取出来,做附属信息查询的字段值
     * */
    public $attachFields;

    /*
     * @var bool 是否作为导出数据源
     * */
    public $asExportSource;

    /**
     *@description 初始化
     *
     *@author biandou
     *@date 2021/6/18 16:28
     */
    public function __construct()
    {
        $this->page             = 1;
        $this->pageName         = "page";
        $this->limit            = 15;
        $this->limitName        = "limit";
        $this->excel            = false;
        $this->asExportSource   = false;
        $this->downloadName     = "download.csv";
        $this->sum              = false;
        $this->first            = false;
        $this->pluck            = [];
        $this->summary          = false;
        $this->pageSummary      = false;
        $this->attachFields     = [];
    }

    /**
     *@description 定义输出属性
     *
     *@author biandou
     *@date 2021/6/18 16:34
     *@param array $output 传入输出定义
     */
    public function load(array $output=[])
    {
        $this->assignmentFromArray($output);
        return $this;
    }

    /**
     *@description 输出定义到QS里面
     *
     *@author biandou
     *@date 2021/6/18 16:35
     *@param QS $qs QS实例
     */
    public function write(QS &$qs)
    {
        $qs->page           = $this->page;
        $qs->limit          = $this->limit;
        $qs->excel          = $this->excel;
        $qs->asExportSource = $this->asExportSource;
        $qs->downloadName   = $this->downloadName;
        $qs->sum            = $this->sum;
        $qs->first          = $this->first;
        $qs->pluck          = $this->pluck;
        $qs->summary        = $this->summary;
        $qs->pageSummary    = $this->pageSummary;
        $qs->attachFields   = $this->attachFields;
    }
}