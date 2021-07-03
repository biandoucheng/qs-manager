<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/19
 * Time: 9:33
 */

namespace QSM;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use QSM\Help\ArrayHealper;

class QSModel
{
    /*
     * @object QS 查询结构
     * */
    public $qs;

    /*
     * @object Result 查询结果
     * */
    public $result;


    /**
     *@description 查询数据
     *
     *@author biandou
     *@date 2021/6/19 10:06
     *@param string $table 表名
     *@param Model $model 模型
     *@param QS $qs 查询结构
     *@param bool $toArray 查询结果是否转数组
     *
     *@return object
     */
    public function search(string $table="",?Model $model,QS $qs,bool $toArray=true):object
    {
        #初始化结果
        $this->result = new Result();

        #实例化数据库实例
        if(!empty($table)) {
            $instance = DB::table($table);
        }else {
            $instance = $model;
        }

        #where
        foreach ($qs->where as $field=>$cond) {
            switch ($cond->opt) {
                case "BETWEEN":
                    $instance = $instance->whereBetween($cond->field,$cond->val);
                    break;
                case "NOT BETWEEN":
                    $instance = $instance->whereNotBetween($cond->field,$cond->val);
                    break;
                case "IN":
                    $instance = $instance->whereIn($cond->field,$cond->val);
                    break;
                case "NOT IN":
                    $instance = $instance->whereNotIn($cond->field,$cond->val);
                    break;
                case "ISNULL":
                    $instance = $instance->whereNull($cond->field);
                    break;
                case "IS NOT NULL":
                    $instance = $instance->whereNotNull($cond->field);
                    break;
                default:
                    $instance = $instance->where($cond->field,$cond->opt,$cond->val);
            }
        }

        #pluck
        if(!empty($qs->pluck)) {
            $this->result->data = $instance->pluck(...$qs->pluck);

        #sum
        }else if(!empty($qs->sum)) {
            $this->result->sum = $instance->sum($qs->sum);

        #count
        }else if(!empty($qs->count)) {
            $this->result->count = $instance->count($qs->count);

        #first
        }else if($qs->first) {
            $this->result->data = $instance->select(array_values($qs->fields))->first();

        #only_summary
        }else if($qs->onlySummary) {
            $this->result->summary = $instance->select($qs->summaryFields)->first();
            $this->result->onlySummary = true;

        #查询
        }else {
            #全量汇总
            if($qs->summary) {
                $summaryInstance = clone $instance;
                $this->result->summary = $summaryInstance->select($qs->summaryFields)->first();
                $this->result->needSummary = true;
            }

            #select
            if(!empty($qs->fields)) {
                $instance = $instance->select(array_values($qs->fields));
            }

            #group
            if(!empty($qs->group)) {
                $instance = $instance->groupBy(...$qs->group);
            }

            #orde
            foreach ($qs->order as $field=>$ord) {
                $instance = $instance->orderBy($field,$ord);
            }

            #page
            if(!empty($qs->page)) {
                $pager = $instance->paginate($qs->limit,["*"],"page",$qs->page);
                $this->result->data = $pager->items();
                $this->result->page = $pager->currentPage();
                $this->result->limit = $qs->limit;
                $this->result->total = $pager->total();
            }else {
                #仅作为数据源
                if(!$qs->asExportSource) {
                    $this->result->data = $instance->get();
                }else {
                    $this->result->instance = $instance;
                }
            }
        }

        #字段提取
        if(!$qs->asExportSource && !empty($qs->attachFields) && !$qs->isOnlyOneLineResult()) {
            foreach ($qs->attachFields as $field) {
                if(!isset($this->result->attach[$field])) {
                    $this->result->attach[$field] = [];
                }
            }
            foreach ($this->result->data as $item) {
                foreach ($qs->attachFields as $field) {
                    if(isset($item[$field])) {
                        $this->result->attach[$field][] = $item[$field];
                    }
                }
            }
        }

        #输出为数组
        if(!$qs->asExportSource && $toArray) {
            #data
            $this->toArray(empty($table),"data");
            #summary
            $this->toArray(empty($table),"summary");
        }

        return $this;
    }

    /**
     *@description 转数组
     *
     *@author biandou
     *@date 2021/6/21 0:11
     *@param bool $isModel 是否是通过模型查询
     *@param string $name 数据名称
     */
    protected function toArray(bool $isModel,string $name)
    {
        if(empty($this->result->{$name})) {
            $this->result->{$name} = [];
            return;
        }

        if($isModel) {
            $this->result->{$name} = collect($this->result->{$name})->toArray();
            return;
        }

        if($this->result->{$name} instanceof \stdClass) {
            $this->result->{$name} = (array)$this->result->{$name};
            return;
        }

        if(is_array($this->result->{$name})) {
            $this->result->{$name} = ArrayHealper::turnStdClassToArray($this->result->{$name});
            return;
        }
    }
}