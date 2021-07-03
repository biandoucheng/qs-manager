<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/15
 * Time: 11:59
 */

namespace QSM;

use QSM\Help\StrHealper;
use QSM\Value\AllValue;
use QSM\Value\NotSetValue;
use QSM\Value\MergeValue;

class Where
{
    /*
     * @var array 支持的查询条件
     * */
    private static $opts = [
        "eq" => [
            "real" => "=",
            "adapt" => false,
        ],
        "=" => [
            "real" => "=",
            "adapt" => false,
        ],
        "not_eq" => [
            "real" => "<>",
            "adapt" => false,
        ],
        "neq" => [
            "real" => "<>",
            "adapt" => false,
        ],
        "<>" => [
            "real" => "<>",
            "adapt" => false,
        ],
        "!=" => [
            "real" => "<>",
            "adapt" => false,
        ],
        "in" => [
            "real" => "IN",
            "adapt" => false,
        ],
        "eq_or_in" => [
            "real" => "eq_or_in",
            "adapt" => true,
        ],
        "eq_or_between" => [
            "real" => "eq_or_between",
            "adapt" => true,
        ],
        "not_in" => [
            "real" => "NOT IN",
            "adapt" => false,
        ],
        "nin" => [
            "real" => "NOT IN",
            "adapt" => false,
        ],
        "neq_or_nin" => [
            "real" => "neq_or_nin",
            "adapt" => true,
        ],
        "neq_or_not_between" => [
            "real" => "neq_or_not_between",
            "adapt" => true,
        ],
        "like" => [
            "real" => "LIKE",
            "adapt" => true,
        ],
        "l_like" => [
            "real" => "LIKE",
            "adapt" => true,
        ],
        "r_like" => [
            "real" => "LIKE",
            "adapt" => true,
        ],
        "between" => [
            "real" => "BETWEEN",
            "adapt" => false,
        ],
        "not_between" => [
            "real" => "NOT BETWEEN",
            "adapt" => false,
        ],
        "nbetween" => [
            "real" => "NOT BETWEEN",
            "adapt" => false,
        ],
        "null" => [
            "real" => "ISNULL",
            "adapt" => false,
        ],
        "not_null" => [
            "real" => "IS NOT NULL",
            "adapt" => false,
        ],
        "nnull" => [
            "real" => "IS NOT NULL",
            "adapt" => false,
        ]
    ];

    /**
     *@description 判断该字段是否用于查询
     *
     *@author biandou
     *@date 2021/6/18 17:00
     *@param Cell $cell qs结构单元
     *
     *@return bool
     */
    public static function valid(Cell $cell):bool
    {
        #如果是可忽略的条件则不处理
        if (!self::isLegalOpt($cell->where) || $cell->isIgnoreVal()) {
            return false;
        }
        return true;
    }

    /**
     *@description 生成一个where查询条件
     *
     *@author biandou
     *@date 2021/6/15 13:40
     *@param Cell $cell qs结构单元
     *
     *@return Condition
     */
    public static function build(Cell $cell):Condition
    {
        return self::legalizationOfCondition($cell);
    }

    /**
     *@description 条件合法化
     *
     *@author biandou
     *@date 2021/6/15 14:17
     *
     *@return Condition
     */
    public static function legalizationOfCondition(Cell $cell):Condition
    {
        $opt = self::$opts[strtolower($cell->where)];

        $op = $opt['real'];
        $adapt = $opt['adapt'];

        if($adapt) {
            $con = self::adapterConditionOpt($op,$cell->val,$cell->commaToArray);
        }else{
            $con = self::adapterNormalConditionOpt($op,$cell->val,$cell->commaToArray);
        }

        $con->field = $cell->realField();
        return $con;
    }

    /**
     *@description 适配查询条件
     *
     *@author biandou
     *@date 2021/6/15 14:41
     *@param string $opt 查询条件
     *@param bool $comma 是否自动将逗号分隔的数据切割成数组
     *
     *@return Condition
     */
    public static function adapterConditionOpt(string $opt,$val,bool $comma=false):Condition
    {
        $con = new Condition();
        if($comma) {
            $con->val = StrHealper::splitStrByCommaOrNot($val);
        }else {
            $con->val = $val;
        }

        switch ($opt){
            case "eq_or_in":
                if(is_array($con->val)) {
                    $con->opt = "IN";
                }else {
                    $con->opt = "=";
                }
                $con->active();
                break;
            case "eq_or_between":
                if(!is_array($con->val)) {
                    $con->opt = "=";
                    break;
                }
                if(count($con->val) < 2) {
                    $con->opt = "=";
                    $con->val = $con->val[0];
                }else {
                    $con->opt = "BETWEEN";
                }
                $con->active();
                break;
            case "neq_or_nin":
                if(is_array($con->val)) {
                    $con->opt = "NOT IN";
                }else {
                    $con->opt = "<>";
                }
                $con->active();
                break;
            case "neq_or_not_between":
                if(!is_array($con->val)) {
                    $con->opt = "<>";
                    break;
                }
                if(count($con->val) < 2) {
                    $con->opt = "<>";
                    $con->val = $con->val[0];
                }else {
                    $con->opt = "NOT BETWEEN";
                }
                $con->active();
                break;
            case "LIKE":
                $con->opt = "LIKE";
                if(is_string($con->val)) {
                    $con->val = "%{$con->val}%";
                    $con->active();
                }
                break;
            case "L_LIKE":
                $con->opt = "LIKE";
                if(is_string($con->val)) {
                    $con->val = "%{$con->val}";
                    $con->active();
                }
                break;
            case "R_LIKE":
                $con->opt = "LIKE";
                if(is_string($con->val)) {
                    $con->val = "{$con->val}%";
                    $con->active();
                }
                break;
        }

        return $con;
    }

    /**
     *@description 适配普通的查询条件
     *
     *@author biandou
     *@date 2021/6/15 14:58
     *@param string $opt 查询条件
     *@param bool $comma 是否自动将逗号分隔的数据切割成数组
     *
     *@return Condition
     */
    public static function adapterNormalConditionOpt(string $opt,$val,bool $comma):Condition
    {
        $con = new Condition();
        if($comma) {
            $con->val = StrHealper::splitStrByCommaOrNot($val);
        }else {
            $con->val = $val;
        }

        switch ($opt) {
            case "=":
                if(!is_array($con->val)) {
                    $con->opt = "=";
                    $con->active();
                }
                break;
            case "<>":
                if(!is_array($con->val)) {
                    $con->opt = "=";
                    $con->active();
                }
                break;
            case "IN":
                if(is_array($con->val)) {
                    $con->opt = "IN";
                    $con->active();
                }
                break;
            case "NOT IN":
                if(is_array($con->val)) {
                    $con->opt = "NOT IN";
                    $con->active();
                }
                break;
            case "BETWEEN":
                if(is_array($con->val) && count($con->val) >= 2) {
                    $con->val = [$con->val[0],$con->val[1]];
                    $con->opt = "BETWEEN";
                    $con->active();
                }
                break;
            case "NOT BETWEEN":
                if(is_array($con->val) && count($con->val) >= 2) {
                    $con->val = [$con->val[0],$con->val[1]];
                    $con->opt = "NOT BETWEEN";
                    $con->active();
                }
                break;
            case "ISNULL":
                $con->val = new NotSetValue();
                $con->opt = "ISNULL";
                $con->active();
                break;

            case "IS NOT NULL":
                $con->val = new NotSetValue();
                $con->opt = "IS NOT NULL";
                $con->active();
                break;
        }

        return $con;
    }

    /**
     *@description 是否是合法的条件操作
     *
     *@author biandou
     *@date 2021/6/15 14:20
     *
     *@return bool
     */
    public static function isLegalOpt(string $opt):bool
    {
        $opt = strtolower($opt);
        return isset(self::$opts[$opt]);
    }
}