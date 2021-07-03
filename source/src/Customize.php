<?php
/**
 * Created by PhpStorm.
 * User: 86182
 * Date: 2021/6/16
 * Time: 11:30
 */

namespace QSM;

use QSM\Help\AttrHelper;
use QSM\Cell;
use QSM\Where;
use QSM\Order;

class Customize
{
    use AttrHelper;

    /*
     * @var string 查询条件,eg:=,in,<>,not in
     * */
    public $opt;

    /*
     * @var string 排序,eg:ASC,DESC
     * */
    public $order;

    /*
     * @var int 导出列索引,指excel导出时某个列所在位置,从左到右0->+
     * */
    public $index;

    /*
     * @var string 输出别名
     * */
    public $alias;


    public function __construct(array $customizes = [])
    {
        $this->assignmentFromArray($customizes);
    }

    /**
     *@description 将自定义的信息写入到Cell单元
     *
     *@author biandou
     *@date 2021/6/18 14:00
     *@param Cell $cell qs结构单元
     */
    public function write(Cell &$cell)
    {
        #where 查询条件自定义
        if(!empty($this->opt) && Where::isLegalOpt($this->opt)) {
            $cell->where = $this->opt;
        }

        #排序自定义
        if(!empty($this->order) && Order::isLegalOrd($this->order)) {
            $cell->order = $this->order;
        }

        #列导出顺序自定义
        if(!empty($this->index) && is_numeric($this->index)) {
            $cell->index = (int)$this->index;
        }

        #取别名
        if(!empty($this->alias)) {
            $cell->alias = $this->alias;
        }
    }
}