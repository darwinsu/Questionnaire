<?php
/**
 * 文件名： PDOExtend.class.php
 *
 * Copyright (c) 2004-2010 by LZD Engineering Co.,Ltd
 * All rights reserved.
 * 功能：PDO的功能扩展类，所有PDO的连接由此类接管。
 *
 * @author dengyong <249449033@qq.com>
 * @version : PDOExtend.class.php,v 0.1 2010-3-29 9:35:57 dengyong Exp $
 * @access public
 * @see
 * @package lib.core.db
 */

class PDOExtend extends PDO
{
    /**
     * 系统所支持的分页模式。
     *
     * @var array
     * @access private static
     */
    private static $mModes = array('limit', 'cursor', 'none');
    /**
     * 当前数据库的分页模式。
     *
     * @var string
     * @access private static
     */
    private static $mPageMode;
    /**
     * 每页显示记录数。默认20条
     *
     * @var int
     * @access public
     */
    public $mPageSize = 20;
    /**
     * 当前页数。默认第一页
     *
     * @var int
     * @access public
     */
    public $mCurrentPage = 1; //
    /**
     * 总记录数
     *
     * @var int
     * @access public
     */
    public $mTotalItem;
    /**
     * 总页数
     *
     * @var int
     * @access public
     */
    public $mTotalPage;

    /**
     * 方法名：SetPageMode
     * 功能：设置分页模式
     *
     * @param string $mode 可选'limit','cursor','none'
     */
    public function SetPageMode($mode)
    {
        if (!in_array($mode, self::$mModes))
        {
            die('不支持的分页模式：' . $mode);
            exit;
        }
        self::$mPageMode = $mode;
    }

    /**
     * 方法名：SetPageSize
     * 功能：设置每页数据数
     *
     * @param int $size 每页数据数，强制转换成int类型，防止非法字符
     */
    public function SetPageSize($size)
    {
        $this->mPageSize = (int) $size;
    }

    /**
     * 方法名：SetCurrentPage
     * 功能：设置当前页数，强制转换成int类型，防止非法字符
     *
     * @param int $page 当前页数
     */
    public function SetCurrentPage($page)
    {
        $this->mCurrentPage = (int) $page;
    }

    /**
     * 方法名：fetchPage
     * 功能：根据sql语句，读取分页数据并返回
     * 分页模式由SetPageMode方法设定，数据集由SetPageSize和SetCurrentPage方法控制
     *
     * @param string $sql 带占位符的sql预处理语句
     * @param array $sqlParams pdo的占位符参数，在execute时使用。默认NULL
     * @return array 分页数据
     */
    public function fetchPage($sql, $sqlParams = null)
    {
        //重写sql语句以便计算记录总数
        $resql = self::rewriteCountQuery($sql);

        $sth = $this->prepare($resql);
        $sth->execute($sqlParams);
        $this->mTotalItem = $sth->fetchColumn(); //取得总记录数
        $this->mTotalPage = ceil(($this->mTotalItem / $this->mPageSize)); //计算总页数。
        //检查当前页
        if ($this->mCurrentPage < 1 or $this->mCurrentPage > $this->mTotalPage)
        {
            $this->mCurrentPage = 1; //超出范围的页数，直接转换成第1页。
        }
        //计算起始数
        $start = ($this->mCurrentPage - 1) * $this->mPageSize;
        //根据模式读取分页信息
        $modname = self::$mPageMode;
        return $this->$modname($sql, $sqlParams, $start, $this->mPageSize);
    }

    /**
     * 方法名：limit
     * 功能：limit分页方法，被fetchPage所调用。
     *
     * @param string $sql 带占位符的sql预处理语句
     * @param array $params pdo的占位符参数，在execute时使用。默认NULL
     * @param int $start limit时的起始位置
     * @param int $offset 读取的条数
     * @return array 分页数据
     */
    public function limit($sql, $params = null, $start, $offset)
    {
        $sth = $this->prepare($sql . ' limit ' . $start . ',' . $offset);
        $sth->execute($params);
        return $sth->fetchAll();
    }

    /**
     * 方法名：cursor
     * 功能：游标分页方法，被fetchPage所调用。
     *
     * @param string $sql 带占位符的sql预处理语句
     * @param array $params pdo的占位符参数，在execute时使用。默认NULL
     * @param int $start limit时的起始位置
     * @param int $offset 读取的条数
     * @return array 分页数据
     */
    public function cursor($sql, $params = null, $start, $offset)
    {
        $sth = $this->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $sth->execute($params);
        for ($toFetch = $offset, $row = $sth->fetch(NULL, PDO::FETCH_ORI_REL, ++$start); $toFetch-- > 0 && $row !== false; $row = $sth->fetch())
        {
            $rows[] = $row;
        }
        $sth->closeCursor();
        return $rows;
    }

    /**
     * 方法名：none
     * 功能：对不支持分页模的一种空处理
     */
    public function none()
    {
        return null;
    }

    /**
     * 方法名：rewriteCountQuery
     * 功能：解析原始sql，转换成count(*)的形式供fetchPage使用
     *
     * @param string $sql 原始sql语句，带占位符。
     * @return string 转换后的sql语句
     * @access private
     */
    private static function rewriteCountQuery($sql)
    {
        return 'select count(*) from (' . $sql . ') as _count_';
    }

    /**
     * 方法名：GetPageInfo
     * 功能：取得分页详细信息
     *
     * @return array 分页详细信息 array['PageSize'] 每页显示数
     *                             array['CurrentPage'] 当前页数
     *                             array['TotalItem'] 总条数
     *                             array['TotalPage'] 总页数
     * @access public
     */
    public function GetPageInfo()
    {
        return array('PageSize' => $this->mPageSize,
            'CurrentPage' => $this->mCurrentPage,
            'TotalItem' => $this->mTotalItem,
            'TotalPage' => $this->mTotalPage
        );
    }

}

?>