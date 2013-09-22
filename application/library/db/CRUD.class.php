<?php
/**
 * 文件名： CRUD.class.php
 *
 * Copyright (c) 2004-2010 by LZD Engineering Co.,Ltd
 * All rights reserved.
 * 功能：通用数据库单表CRUD操作类
 * 该类可快速对单表数据进行插入、修改、删除、列表（带分页）操作。
 *
 * @author dengyong <249449033@qq.com>
 * @version : CRUD.class.php,v 0.1 2010-4-14 16:58:17 dengyong Exp $
 * @access public
 * @see
 * @package lib.db
 */

class CRUD
{
    /**
     * 数据库PDOExtend连接实例
     * @var PDOExtend
     * @access public
     */
    public $mPDOE;

    /**
     * 生成的sql语句方便调试
     * @var string
     * @access private
     */
    private $mSql;

    /**
     * 方法名：__construct
     * 功能：初始化数据库连接句柄。
     * @access public
     */
    public function __construct($pdoe)
    {
        $this->mPDOE = $pdoe;
    }

    public function GetSql()
    {
        return $this->mSql;
    }

    /**
     * 方法名：C
     * 功能：插入一条表数据
     * 通用功能，针对单表。
     *
     * @param string $tName 表名
     * @param array $arrKeyValue 列名对值的数组。如：array('name'=>'xxx','password'=> 'xxx')
     * @return boolean 成功返回true,失败返回false
     */
    public function C($tName, $arrKeyValue)
    {
        //生成数据名列名
        $strColumnName = implode(',', array_keys($arrKeyValue));
        //生成占位符
        $strPlaceholder = implode(',', array_fill(0, count($arrKeyValue), '?'));
        //组合sql语句
        $this->mSql = 'INSERT INTO ' . $tName . '(' . addslashes($strColumnName) . ') VALUES(' . $strPlaceholder . ')';

        //生成占位符对应的值数组
        $arrValue = array_values($arrKeyValue);

        //执行，并返回结果
        $sth = $this->mPDOE->prepare($this->mSql);
        if($sth->execute($arrValue))
        {
            return $this->mPDOE->lastInsertId();
        }
        return false;
    }

    /**
     * 方法名：R
     * 功能：读取一条表数据
     * 通用功能，针对单表。
     *
     * @param string $tName 表名
     * @param string $pk 表主键名、或唯一标识列名
     * @param string $value 主键或唯一标识名的值
     * @return array 返回数组
     */
    public function R($tName, $pk, $value)
    {
        $this->mSql = 'SELECT * FROM ' . addslashes($tName) . ' where ' . addslashes($pk) . ' = ?';
        $sth = $this->mPDOE->prepare($this->mSql);
        $sth->execute(array($value));
        return $sth->fetch();
    }

    /**
     * 方法名：U
     * 功能：修改一条表数据
     * 通用功能，针对单表。
     *
     * @param string $tName 表名
     * @param array $arrKeyValue 列名对值的数组。如：array('name'=>'xxx','password'=> 'xxx')
     * @param string $pk 表主键名、或唯一标识列名
     * @param string $value 主键或唯一标识名的值
     * @return boolean 成功返回true,失败返回false
     */
    public function U($tName, $arrKeyValue, $pk, $value)
    {
        //生成列名和占位符号
        $strColumnName = implode('=?,', array_keys($arrKeyValue)) . '=?';
        //组合sql
        $this->mSql = 'UPDATE ' . addslashes($tName) . ' SET ' . addslashes($strColumnName) . ' WHERE ' . addslashes($pk) . '= ?';
        //生成占位符对应的值数组
        $arrValue = array_values($arrKeyValue);
        array_push($arrValue, $value);
        //执行
        $sth = $this->mPDOE->prepare($this->mSql);
        return $sth->execute($arrValue);
    }

    /**
     * 方法名：D
     * 功能：删除一条表数据
     * 通用功能，针对单表。
     *
     * @param string $tName 表名
     * @param string $pk 表主键名、或唯一标识列名
     * @param string $value 主键或唯一标识名的值
     * @return boolean 成功返回true,失败返回false
     */
    public function D($tName, $pk, $value)
    {
        $this->mSql = 'DELETE FROM ' . addslashes($tName) . ' WHERE ' . addslashes($pk) . '= ?';
        $sth = $this->mPDOE->prepare($this->mSql);
        return $sth->execute(array($value));
    }

    /**
     * 方法名：L
     * 功能：取得指定带占位符参数SQL的数据
     * @param string $sql 带占位符的SQL
     * @param array $arrSqlParam 占位符参数数组
     * @return array 返回数组
     */
    public function L($sql, $arrSqlParam = NULL)
    {
        $sth = $this->mPDOE->prepare($sql);
        $sth->execute($arrSqlParam);
        return $sth->fetchAll();
    }

    /**
     * 方法名：LP
     * 功能：分页取得指定带占位符参数SQL的数据
     * @param string $sql 带占位符的SQL
     * @param array $arrSqlParam 占位符参数数组
     * @param int $page 当前页数
     * @param int $pageSize 每页显示条数
     * @return array 返回数组
     */
    public function LP($sql, $arrSqlParam = NULL, $page = 1, $pageSize = 20)
    {
        //设置参数
        $this->mPDOE->SetPageSize($pageSize);
        $this->mPDOE->SetCurrentPage($page);
        //读取数据
        return $this->mPDOE->fetchPage($sql, $arrSqlParam);
    }

    /**
     * 方法名：E
     * 功能：执行指定的sql语句
     * @param string $sql 带占位符的SQL
     * @param array $arrSqlParam 占位符参数数组
     * @return boolean 是否执行成功
     */
    public function E($sql, $arrSqlParam)
    {
        $sth = $this->mPDOE->prepare($sql);
        return $sth->execute($arrSqlParam);
    }
}
?>
