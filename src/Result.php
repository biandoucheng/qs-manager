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
     *@return mixed
     */
    public function toResponse()
    {
        #分页
        if($this->page) {
            $out = [
                'current_page' => $this->page,
                'per_page' => $this->limit,
                'total' => $this->total,
                'data' => $this->data
            ];
            if($this->summary) {
                $out['summary'] = $this->summary;
            }
        }else if($this->onlySummary){
            $out = $this->summary;
        }else {
            if($this->needSummary) {
                $out = [
                    'data' => $this->data,
                    'summary' => $this->summary
                ];
            }else {
                $out = $this->data;
            }
        }

        return $out;
    }
}