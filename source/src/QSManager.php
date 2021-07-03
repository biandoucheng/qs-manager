<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 16:34
 */

namespace QSM;

use Illuminate\Support\Facades\DB;
use QSM\CellFactory;

/*
 * qs结构管理器
 * */
class QSManager
{
    /*
     * @var qs输出结构
     * */
    protected $qs;

    /*
     * @var array qs输出数据
     * */
    protected $receive;

    /*
     * @var array qs配置参数
     * */
    protected $conf;

    /**
     *@description 初始化
     *
     *@author biandou
     *@date 2021/6/15 17:26
     */
    public function init()
    {
        $this->qs = new QS();
        $this->receive = [];
        $this->conf = [];
    }

    /**
     *@description 加载配置和数据
     *
     *@author biandou
     *@date 2021/6/15 17:27
     *@param CellFactory $cf qs配置
     *@param array $receive 传入参数
     *@param array $output 输出定义,eg:分页,导出,汇总 ...
     *@param array $columns 强制指定输出字段
     *@param array $customize 用户自定义的查询结构,在接受的参数中包含了自定义的where操作,排序操作等
     *
     * @return QS
     */
    public function load(CellFactory $cf,array $receive=[],array $customizes=[],array $columns = []):QS
    {
        #初始化
        $this->init();

        #生成field=>Cell格式的关联数组
        $cellMap = QSConfig::load($cf->get(),$receive,$customizes,$columns);

        #QS结构化
        $qs = new QS();

        #输出定义
        $cf->writeOutPutToQs($qs);

        #分页处理
        if(is_numeric($receive[$qs->pageName] ?? null)) {
            $qs->page = (int)$receive[$qs->pageName];
        }

        if(is_numeric($receive[$qs->limitName] ?? null)) {
            $qs->limit = (int)$receive[$qs->limitName];
        }

        #导出处理
        if(isset($receive[$qs->excelName])) {
            $qs->excel = (bool)$receive[$qs->excelName];
        }

        #输出参数处理
        foreach ($cellMap as $field=>$cell) {
            #获取真实字段
            $realField = $cell->realField();

            #字段输出控制
            if(Select::showAble($cell,$cellMap,$qs)) {
                #输出字段
                $qs->select[$cell->name] = true;

                #字段输出顺序
                $qs->index[$realField]  = $cell->index;
            }

            #字段查询控制
            if (Select::valid($cell,$cellMap,$qs)) {
                #记录真实参与sql的字段形式
                if($cell->cal) {
                    $qs->fields[$realField] = DB::raw($cell->cal);
                }else {
                    $qs->fields[$realField] = $realField;
                }

                #汇总字段收集
                if($cell->isSummaryField) {
                    $qs->summaryFields[] = DB::raw($cell->cal);
                }
            }

            #where条件查询
            if(Where::valid($cell)) {
                $qs->where[$realField] = Where::build($cell);
            }

            #where原始输入字段记录
            if(!empty($cell->where)) {
                $qs->whereOriginal[] = $realField;
            }
        }

        #分组排序处理
        foreach ($cellMap as $field=>$cell) {
            #获取真实字段
            $realField = $cell->realField();

            #group分组处理
            if(Group::valid($cell,$qs) && isset($qs->fields[$realField])) {
                $qs->group[] = $realField;
            }

            #order排序处理
            if(Order::valid($cell,$qs)) {
                $qs->order[$realField] = $cell->order;
            }

            #header头导出
            if(Header::valid($cell,$qs)) {
                $qs->header[$realField] = $cell->alias;
            }
        }

        #字段输出顺序做最后的调整
        $headers = [];
        asort($qs->index);
        foreach (array_keys($qs->index) as $idx=>$field) {
            $qs->index[$field] = $idx;
            if(isset($qs->header[$field])) {
                $headers[$field] = $qs->header[$field];
            }
        }

        #表头做最后的顺序调整
        $qs->header = array_merge($headers,$qs->header);

        #分页重新整理
        if($qs->page > 0 && $qs->limit <= 0) {
            $qs->limit = 15;
        }

        return $qs;
    }
}