<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/19
 * Time: 9:00
 */

namespace QSM;


class Result
{
    /*
     * @var collection 查询结果
     * */
    public $data = [];

    /*
     * @var Model|DB 查询实例
     * */
    public $instance;

    /*
     * @var int 页码
     * */
    public $page = 0;

    /*
     * @var int 分页行数
     * */
    public $limit = 15;

    /*
     * @var int total 数据总条数
     * */
    public $total = 0;

    /*
     * @var int 但字段汇总值
     * */
    public $count = 0;

    /*
     * @var int 求和
     * */
    public $sum = 0;

    /*
     * @var array 数据汇总
     * */
    public $summary = [];

    /*
     * @var bool 是否需要汇总计算
     * */
    public $needSummary = false;

    /*
     * @var bool 是否只取汇总
     * */
    public $onlySummary = false;

    /*
     * @var array 附属信息查询需要的字段值
     * */
    public $attach = [];


    /**
     *@description 导出
     *
     *@author biandou
     *@date 2021/7/2 14:00
     *
     *@return array
     */
    public function toResponse():array
    {
        #分页
        $pager = [];
        if(!empty($this->page)) {
            $pager["current_page"] = $this->page;
            $pager["per_page"] = $this->limit;
            $pager["total"] = $this->total;
        }

        #汇总及数据行
        $data = [];
        if($this->onlySummary) {
            $data = $this->summary;
        }else {
            #携带汇总行
            if($this->needSummary) {
                $data['summary'] = $this->summary;
                $data['data'] = $this->data;
            }else {
                $data = $this->data;
            }
        }

        #输出合并
        if(!empty($pager)) {
            $pager['data'] = $data;
        }else {
            $pager = $data;
        }

        return $pager;
    }
}