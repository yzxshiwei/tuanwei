<?php

namespace Common\Model;

class NewsModel extends \Common\Helper\Model{
    protected $tableName = 'news';
    const COL_1 = 1;
    const COL_2 = 2;
    const COL_3 = 3;
    const COL_4 = 4;
    const COL_5 = 5;
	const COL_6 = 6;
    
    const SUB_COL_1 = 1;
    const SUB_COL_2 = 2;
    const SUB_COL_3 = 3;
	
    /**
     * 新闻栏目
     * @var unknown
     */
    public $col = array(
        self::COL_1 => '新闻主页',
        self::COL_2 => '创业政策',
        self::COL_3 => '讲座培训',
        self::COL_4 => '创业动态',
        self::COL_5 => '资料库',
        self::COL_6 => '场地类别',
    );
    /**
     * 新闻副栏目
     * @var unknown
     */
    public $sub_col = array(
        self::SUB_COL_1 => '学校',
        self::SUB_COL_2 => '地方',
        self::SUB_COL_3 => '国家'
    );
}