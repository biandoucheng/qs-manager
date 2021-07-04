#示例项目地址
<https://github.com/biandoucheng/qs-manager-example.git>

## 特点
* 查询结构自动化成型
* 输出方式控制属性定制
* 仅支持单表,多表查询可以采用视图或者在数据查询后做附加处理

# qs-manager
一个便于快速制定查询结构的封装

## Cell qs结构单元
* 用来描述每个字段的各个属性
* 基本属性: 字段名 name,字段别名 alias ...
* 输出控制属性: 输出 show,不输出 show_not,关联输出 show_with ...
* 查询控制属性: 真实字段 field,group 分组,order 排序,where 条件 ...
* 导出控制属性: 是否是导出 excel,导出字段顺序 index ...

## Condition 查询条件
* field: 数据库字段
* opt  : 查询条件
* val  : 查询值
* enable : 是否是可用的条件

## Customize 用户自定义查询结构
* opt : 查询条件
* order : 排序规则
* index : 输出列顺序
* alias : 别名(导出使用)

## Output 输出模式控制
* page : 分页控制,0 代表不分页
* pageName : 输入分页字段名
* limit : 分页行数
* limitName : 分页行数输入字段名称
* excel : 是否导出
* downloadName : 导出文件名称
* sum : 汇总某个字段
* first : 取第一条
* pluck : 字段提取
* summary : 是否对全部数据取总
* attachFields : 需要提取的字段
* asExportSource : 是否作为导出的Query实例

## QS 查询结构
* 导出相关 : excel,excelName,asExportSource ...
* 查询相关 : select,fields,where,group,order ...
* 具体作用请查询相关示例<https://github.com/biandoucheng/qs-manager-example.git>

## QSConfig Cell配置处理器
* 将数组配置参数转化成Cell实例数组

## QSManager QS管理器
* 接受数组配置参数，传入查询数据，用户自定义配置等，将其转化从qs的属性

## QSModel 数据查询支持类
* 支持数据查询，结果存入Result实例

## Result 查询结果类
* 数据相关 : data 数据容器,summary 汇总数据,first 单条数据 ...
* 分页字段 : page,limit,total ...

## Select 输出控制
* 决定查询字段,输出字段,字段输出顺序

## Where 条件处理类
* 条件支持判断 空字段,all值处理 ...
* 条件操作转义,修复 eg:eq_or_in => eq | in ...
* 条件值修复
* 生成Condition实例