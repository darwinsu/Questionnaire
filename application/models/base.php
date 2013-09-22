<?php

class baseModel
{
	var $db;
	
	public function __construct()
	{
		$this->db = mysql::instance();
	}
	
	/*
	 | 获取数据表内容
	 +----------------------------------------
	 | @param $offset			偏移量
	 | @param $limit			获取指定数量记录
	 | @param $fields			获取指定字段
	 | @param $tableViewName	待获取的表
	 | @param $conditions		获取条件
	 | @param $orders			获取记录的排序规则
	 | @param $distinct			是否过滤重复项，默认为false不过虑
	 | @param $returnType		返回类型，默认为0对象，1为数组
	 */
	public function getDB($offset, $limit, $fields, $tableViewName, $conditions, $orders, $distinct, $returnType = 1, $groupBy = '')
	{
		if(strlen($fields)>0)
		{
			$FIELDS = $fields;
		}
		else
		{
			$FIELDS = '*';
		}
		
		$join = $tableName = '';
		if(is_array($tableViewName))
		{
			if(array_key_exists('db_join',$tableViewName))
			{
				foreach($tableViewName['db_join'] as $join_table_arr)
				{
					if(isset($join_table_arr['joinType']))
					{
						$join .= $join_table_arr['joinType'] . ' JOIN ' . $join_table_arr['tableName'] . ' ON ' . $join_table_arr['conditions'] . ' ';
					}
					else
					{
						$join .= 'JOIN ' . $join_table_arr['tableName'] . ' ON ' . $join_table_arr['conditions'] . ' ';
					}
				}
			}
			unset($tableViewName['db_join']);
			$tableName = $tableViewName['tableName'] . ' ' . $join;
		}
		elseif(strlen($tableViewName)==0)
		{
			return;
		}
		else
		{
			$tableName = $tableViewName;
		}
		$ORDER_BY = $where = $or_where = $like = $not_like = $or_like = $or_not_like = $where_in = $where_not_in = $end_where = $where_multi_table = '';
		if(is_array($conditions))
		{
			if(array_key_exists('db_where',$conditions))
			{
				foreach($conditions['db_where'] as $k => $v)
				{
					$where .= 'AND ' . $k . '="' . $v .'" ';
				}
				unset($conditions['db_where']);
			}			
			
			if(array_key_exists('db_or_where',$conditions))
			{
				foreach($conditions['db_or_where'] as $k => $v)
				{
					$or_where .= 'OR ' . $k . '="' . $v . '" ';
				}
				unset($conditions['db_or_where']);
			}
			
			if(array_key_exists('db_like',$conditions))
			{
				foreach($conditions['db_like'] as $k => $v)
				{
					$like .= 'AND ' . $k . ' LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$like = trim($like, 'AND ');
				}
				$where .= $like;
				unset($conditions['db_like']);
			}
			
			if(array_key_exists('db_not_like',$conditions))
			{
				foreach($conditions['db_not_like'] as $k => $v)
				{
					$no_like .= 'AND ' . $k . ' NOT LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$no_like = trim($no_like, 'AND ');
				}
				$where .= $no_like;
				unset($conditions['db_not_like']);
			}
			
			if(array_key_exists('db_or_like',$conditions))
			{
				foreach($conditions['db_or_like'] as $k => $v)
				{
					$or_like .= 'OR ' . $k . ' LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$or_like = trim($or_like, 'OR ');
				}
				$where .= $or_like;
				unset($conditions['db_or_like']);
			}
			
			if(array_key_exists('db_or_not_like',$conditions))
			{
				foreach($conditions['db_or_not_like'] as $k => $v)
				{
					$or_not_like .= 'OR ' . $k . ' NOT LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$or_not_like = trim($or_not_like, 'OR ');
				}
				$where .= $or_not_like;
				unset($conditions['db_or_not_like']);
			}
			
			if(array_key_exists('db_where_in',$conditions))
			{
				$where_in = $conditions['db_where_in'][0] . ' IN (' . $conditions['db_where_in'][1] . ') ';
				
				if(!empty($where))
				{
					$where_in = 'AND ' . $where_in;
				}
				$where .= $where_in;
				unset($conditions['db_where_in']);
			}
			
			if(array_key_exists('db_where_not_in', $conditions))
			{
				$where_not_in = $conditions['db_where_not_in'][0] . ' NOT IN (' . $conditions['db_where_not_in'][1] . ') ';
				
				if(!empty($where))
				{
					$where_not_in = 'AND ' . $where_not_in;
				}
				$where .= $where_not_in;
				unset($conditions['db_where_not_in']);
			}
			
			if(array_key_exists('db_where_multi_table', $conditions))
			{
				foreach($conditions['db_where_multi_table'] as $v)
				{
					$where_multi_table .= 'AND ' . $v . ' ';
				}
				
				if(empty($where))
				{
					$where_multi_table = trim($where_multi_table, 'AND ');
				}
				$where .= $where_multi_table;
				unset($conditions['db_where_multi_table']);
			}
			
			if(count($conditions)>0)
			{
				foreach($conditions as $k => $v)
				{
					$end_where .= 'AND ' . $k . '="' . $v . '" ';
				}
			}
			if(empty($where))
			{
				$end_where = trim($end_where, 'AND ');
			}
			$end_where = $end_where ? ' ' . $end_where : '';
			$where .= $end_where;
			
		}
		elseif (strlen($conditions)>0)
		{
			$where = $conditions;
		}
		$where = empty($where) ? '' : ' WHERE ' . $where;
		
		if(is_array($orders))
		{
			$ORDER_BY = ' ' . $orders[0] . ' ';
		}
		else if(strlen($orders)>0)
		{
			$ORDER_BY = ' ORDER BY ' . $orders;
		}
		$LIMIT = '';
		if(strlen($offset)>0 && is_numeric($offset))
		{
			if($offset > 0)
			{
				$LIMIT = ' LIMIT ';
				if(strlen($limit) && is_numeric($limit))
				{
					$LIMIT .= ($limit>0) ? $offset . ',' . $limit :  $offset;
				}
				else
				{
					$LIMIT .= $offset;
				}
			}
			else
			{
				if(strlen($limit) && is_numeric($limit))
				{
					if($limit > 0)
					{
						$LIMIT = ' LIMIT ';
						$LIMIT .= $limit;
					}
				}
			}
		}
		elseif(strlen($limit)>0 && is_numeric($limit))
		{
			if($limit>0)
				$LIMIT = ' LIMIT ' . $limit;
		}

		if(empty($distinct))
		{
			$distinct = false;
		}
		elseif(!is_bool($distinct))
		{
			$distinct = false;
		}

		if($distinct)
		{
			$SELECT = 'SElECT DISTINCT ';
		}
		else
		{
			$SELECT = 'SElECT ';
		}
		
		$sql = $SELECT . $FIELDS . ' FROM ' . $tableName . $where . $groupBy . $ORDER_BY . $LIMIT;error_log(print_r($sql,1)."\n", 3, __DIR__.'/sql.txt');
		$returnValue = $this->db->fetch_array($sql);
		if($returnType==0)
		{
			$returnValue = $this->Arr2Obj($returnValue);
		}
		
		/*if($returnValue){
			foreach($returnValue as $key=>$va){
				foreach($va as $k=>$v){
					 $returnValues[$key][$k]=htmlspecialchars($v);
				}
			}
		}*/
		return $returnValue;
	}
	
	/*
	 | 数组转换成对象
	 +-------------------------
	 */
	function Arr2Obj($arr)
	{
		if(gettype($arr) == 'array')
		{
			foreach($arr as $k=>$v)
			{
				if(is_array($v) || is_object($v))
				{
					$arr[$k] = (object) $this->Arr2Obj($v);
				}
			}
		}
		
		return $arr;
	}

	/*
	 | 获取数据表记录数
	 +----------------------------------------
	 | @param $tableViewName	待获取数据表
	 | @param $conditions		获取条件
	 */
	public function getDBCount($tableViewName,$conditions='', $groupBy = '')
	{
		if(is_array($conditions))
		{//判断获取条件是否为数组
			$where = $or_where = $like = $not_like = $or_like = $or_not_like = $where_in = $where_not_in = $end_where = $where_multi_table = '';
			if(array_key_exists('db_where', $conditions))
			{
				foreach($conditions['db_where'] as $k => $v)
				{
					$where .= 'AND ' . $k .'="' . $v . '" ';
				}
				$where = trim($where, 'AND ');
				unset($conditions['db_where']);
			}
			
			if(array_key_exists('db_or_where', $conditions))
			{
				foreach($conditions as $k => $v)
				{
					$or_where .= 'OR ' . $k . '="' . $v . '" ';
				}
				
				if(empty($where))
				{
					$or_where = trim($where, 'OR '); 
				}
				$where .= $or_where;
				unset($conditions['db_or_where']);
			}
			
			if(array_key_exists('db_like',$conditions))
			{
				foreach($conditions['db_like'] as $k => $v)
				{
					$like .= 'AND ' . $k . ' LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$like = trim($like, 'AND ');
				}
				$where .= $like;
				unset($conditions['db_like']);
			}
			
			if(array_key_exists('db_not_like', $conditions))
			{
				foreach($conditions['db_not_like'] as $k => $v)
				{
					$no_like .= 'AND ' . $k . ' NOT LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$no_like = trim($no_like, 'AND ');
				}
				$where .= $no_like;
				unset($conditions['db_not_like']);
			}
			
			if(array_key_exists('db_or_like',$conditions))
			{
				foreach($conditions['db_or_like'] as $k => $v)
				{
					$or_like .= 'OR ' . $k . ' LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$or_like = trim($or_like, 'OR ');
				}
				$where .= $or_like;
				unset($conditions['db_or_like']);
			}
			
			if(array_key_exists('db_or_not_like',$conditions))
			{
				foreach($conditions['db_or_not_like'] as $k => $v)
				{
					$or_not_like .= 'OR ' . $k . ' NOT LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$or_not_like = trim($or_not_like, 'OR ');
				}
				$where .= $or_not_like;
				unset($conditions['db_or_not_like']);
			}
			
			if(array_key_exists('db_where_in', $conditions))
			{
				$where_in = $conditions['db_where_in'][0] . ' IN (' . $conditions['db_where_in'][1] . ') ';
				
				if(!empty($where))
				{
					$where_in = 'AND ' . $where_in;
				}
				$where .= $where_in;
				unset($conditions['db_where_in']);
			}
			
			if(array_key_exists('db_where_not_in', $conditions))
			{
				$where_not_in = $conditions['db_where_not_in'][0] . ' NOT IN (' . $conditions['db_where_not_in'][1] . ') ';
				
				if(!empty($where))
				{
					$where_not_in = 'AND ' . $where_not_in;
				}
				$where .= $where_not_in;
				unset($conditions['db_where_not_in']);
			}
			
			if(array_key_exists('db_where_multi_table', $conditions))
			{
				foreach($conditions['db_where_multi_table'] as $v)
				{
					$where_multi_table .= 'AND ' . $v . ' ';
				}
				
				if(empty($where))
				{
					$where_multi_table = trim($where_multi_table, 'AND ');
				}
				$where .= $where_multi_table;
				unset($conditions['db_where_multi_table']);
			}
			
			
			if(count($conditions) > 0)
			{
				foreach($conditions as $k => $v)
				{
					$end_where .= 'AND ' . $k . '="' . $v . '" ';
				}
			}
			
			if(empty($where))
			{
				$end_where = trim($end_where, 'AND ');
			}
			$end_where = $end_where ? ' ' . $end_where : '';
			$where .= $end_where;
		}
		elseif(strlen($conditions)>0)
		{
			$where = $conditions;
		}
		$where = empty($where) ? '' : ' WHERE ' . $where;
		
		$tableName = '';
		if(is_array($tableViewName))
		{
			if(array_key_exists('db_join',$tableViewName))
			{
				$join = '';
				foreach($tableViewName['db_join'] as $join_table_arr)
				{
					if(isset($join_table_arr['joinType']))
					{
						$join .= $join_table_arr['joinType'] . ' JOIN ' . $join_table_arr['tableName'] . ' ON ' . $join_table_arr['conditions'] . ' ';
					}
					else
					{
						$join .= 'JOIN ' . $join_table_arr['tableName'] . ' ON ' . $join_table_arr['conditions'] . ' ';
					}
				}
			}
			unset($tableViewName['db_join']);
			$tableName = $tableViewName['tableName'] . ' ' . $join;
		}
		elseif(strlen($tableViewName)==0)
		{
			return false;
		}
		else
		{
			$tableName = $tableViewName;
		}
		if($groupBy)
		{
			$sql = 'SELECT COUNT(`cnttmp`) AS cnt FROM (SELECT COUNT(*) AS cnttmp FROM ' . $tableName . $where . $groupBy . ') AS TMP';
		}
		else
		{
			$sql = 'SELECT COUNT(*) as cnt FROM ' . $tableName . $where;
		}
		try{
			$rs = $this->db->fetch_array($sql);
		}
		catch(Database_Exception $e)
		{
			return false;
		}
		return $rs[0]['cnt'];
	}

	/**
	 | 获取字段值
	 | @param $field			获取字段
	 | @param $tableViewName	获取字段所在表
	 | @param $conditions		获取字段条件
	 | @param $order			排序方式
	 */
	public function getDBValue($field,$tableViewName,$conditions,$orders)
	{
		$fieldsList = '';	
		if(is_array($field))
		{
			if(array_key_exists('db_fields_list',$field))
			{
				$fieldsList = $field['db_fields_list'];
			}
			unset($field['db_fields_list']);
			$field = $field['field'];
		}
		else
		{
			if(strlen($field)>0)
				$fieldsList = $field;
		}	
		foreach($this->getDB(0,1,$fieldsList,$tableViewName,$conditions,$orders,false,0) as $item)
		{
			return $item->$field;
		}
	}
	
	/*
	 | 增强版 插入数据
	 +------------------------------------
	 | @param $tableViewName	待插入数据表
	 | @param $dataArray		字段及值（以数组进行传递）
	 | @param $escape			数据是否转义（取值范围：true<对数据进行转义>|false<不对数据进行转义>默认为true）
	 */
	public function replaceIntoDB($tableViewName,$dataArray,$escape = '')
	{
		if(strlen($escape)==0)
		{
			$escape = true;
		}
		elseif(!is_bool($escape))
		{
			$escape = true;
		}
		$i = 0;
		foreach($dataArray as $k => $v)
		{
			$fields[$i] = $k;
			$values[$i++] = mysql_real_escape_string($v);
		}
		$fields = implode(',',$fields);
		$values = implode("','",$values);
		$sql = 'REPLACE INTO ' . $tableViewName . '(' . $fields . ") VALUES('". $values ."')";error_log(print_r($sql, 1)."\n", 3, __DIR__.'/log.txt');
		
		$rs = $this->db->execute($sql);
		return $rs;
	}

	/*
	 | 更新数据
	 +---------------------------------
	 | @param $valuesData		数据组
	 | @param $tableViewName	待更新的数据表
	 | @param $conditions		更新条件
	 */
	public function updateDB($valuesData,$tableViewName,$conditions)
	{
		if(is_array($conditions))
		{
			$where = $or_where = $like = $not_like = $or_like = $or_not_like = $where_in = $where_not_in = $end_where = '';
			if(array_key_exists('db_where', $conditions))
			{
				foreach($conditions['db_where'] as $k => $v)
				{
					$where .= 'AND ' . $k .'="' . $v . '" ';
				}
				$where = trim($where, 'AND ');
				unset($conditions['db_where']);
			}
			
			if(array_key_exists('db_or_where', $conditions))
			{
				foreach($conditions as $k => $v)
				{
					$or_where .= 'OR ' . $k . '="' . $v . '" ';
				}
				
				if(empty($where))
				{
					$or_where = trim($where, 'OR '); 
				}
				$where .= $or_where;
				unset($conditions['db_or_where']);
			}
			
			if(array_key_exists('db_like',$conditions))
			{
				foreach($conditions['db_like'] as $k => $v)
				{
					$like .= 'AND ' . $k . ' LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$like = trim($like, 'AND ');
				}
				$where .= $like;
				unset($conditions['db_like']);
			}
			
			if(array_key_exists('db_not_like', $conditions))
			{
				foreach($conditions['db_not_like'] as $k => $v)
				{
					$no_like .= 'AND ' . $k . ' NOT LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$no_like = trim($no_like, 'AND ');
				}
				$where .= $no_like;
				unset($conditions['db_not_like']);
			}
			
			if(array_key_exists('db_or_like',$conditions))
			{
				foreach($conditions['db_or_like'] as $k => $v)
				{
					$or_like .= 'OR ' . $k . ' LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$or_like = trim($or_like, 'OR ');
				}
				$where .= $or_like;
				unset($conditions['db_or_like']);
			}
			
			if(array_key_exists('db_or_not_like',$conditions))
			{
				foreach($conditions['db_or_not_like'] as $k => $v)
				{
					$or_not_like .= 'OR ' . $k . ' NOT LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$or_not_like = trim($or_not_like, 'OR ');
				}
				$where .= $or_not_like;
				unset($conditions['db_or_not_like']);
			}
			
			if(array_key_exists('db_where_in', $conditions))
			{
				$where_in = $conditions['db_where_in'][0] . ' IN (' . $conditions['db_where_in'][1] . ') ';
				
				if(!empty($where))
				{
					$where_in = 'AND ' . $where_in;
				}
				$where .= $where_in;
				unset($conditions['db_where_in']);
			}
			
			if(array_key_exists('db_where_not_in', $conditions))
			{
				$where_not_in = $conditions['db_where_not_in'][0] . ' NOT IN (' . $conditions['db_where_not_in'][1] . ') ';
				
				if(!empty($where))
				{
					$where_not_in = 'AND ' . $where_not_in;
				}
				$where .= $where_not_in;
				unset($conditions['db_where_not_in']);
			}
			
			if(count($conditions) > 0)
			{
				foreach($conditions as $k => $v)
				{
					$end_where .= 'AND ' . $k . '="' . $v . '" ';
				}
			}
			
			if(empty($where))
			{
				$end_where = trim($end_where, 'AND ');
			}
			$end_where = $end_where ? ' ' . $end_where : '';
			$where .= $end_where;
		}
		elseif(strlen($conditions)>0)
		{
			$where = $conditions;
		}
		
		if(!empty($where))
		{
			$where = ' WHERE ' . $where; 
		}
		$SET = '';
		foreach($valuesData as $k => $v)
		{
			$SET .= $k .'="' . $v . '",';
		}
		$SET = ' SET ' . rtrim($SET, ',');
		$sql = 'UPDATE ' . $tableViewName . $SET . $where;
		$rs = $this->db->execute($sql);
		return $rs;
	}

	/*
	 | 删除数据表中的数据
	 +----------------------------------------
	 | @param $tableViewName	待删除数据的表
	 | @param $conditions		删除条件
	 */
	public  function delDB($tableViewName,$conditions)
	{
		if(is_array($conditions))
		{
			$where = $or_where = $like = $not_like = $or_like = $or_not_like = $where_in = $where_not_in = $end_where = '';
			if(array_key_exists('db_where', $conditions))
			{
				foreach($conditions['db_where'] as $k => $v)
				{
					$where .= 'AND ' . $k .'="' . $v . '" ';
				}
				$where = trim($where, 'AND ');
				unset($conditions['db_where']);
			}
			
			if(array_key_exists('db_or_where', $conditions))
			{
				foreach($conditions as $k => $v)
				{
					$or_where .= 'OR ' . $k . '="' . $v . '" ';
				}
				
				if(empty($where))
				{
					$or_where = trim($where, 'OR '); 
				}
				$where .= $or_where;
				unset($conditions['db_or_where']);
			}
			
			if(array_key_exists('db_like',$conditions))
			{
				foreach($conditions['db_like'] as $k => $v)
				{
					$like .= 'AND ' . $k . ' LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$like = trim($like, 'AND ');
				}
				$where .= $like;
				unset($conditions['db_like']);
			}
			
			if(array_key_exists('db_not_like', $conditions))
			{
				foreach($conditions['db_not_like'] as $k => $v)
				{
					$no_like .= 'AND ' . $k . ' NOT LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$no_like = trim($no_like, 'AND ');
				}
				$where .= $no_like;
				unset($conditions['db_not_like']);
			}
			
			if(array_key_exists('db_or_like',$conditions))
			{
				foreach($conditions['db_or_like'] as $k => $v)
				{
					$or_like .= 'OR ' . $k . ' LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$or_like = trim($or_like, 'OR ');
				}
				$where .= $or_like;
				unset($conditions['db_or_like']);
			}
			
			if(array_key_exists('db_or_not_like',$conditions))
			{
				foreach($conditions['db_or_not_like'] as $k => $v)
				{
					$or_not_like .= 'OR ' . $k . ' NOT LIKE "%' . $v . '%" ';
				}
				
				if(empty($where))
				{
					$or_not_like = trim($or_not_like, 'OR ');
				}
				$where .= $or_not_like;
				unset($conditions['db_or_not_like']);
			}
			
			if(array_key_exists('db_where_in', $conditions))
			{
				$where_in = $conditions['db_where_in'][0] . ' IN (' . $conditions['db_where_in'][1] . ') ';
				
				if(!empty($where))
				{
					$where_in = 'AND ' . $where_in;
				}
				$where .= $where_in;
				unset($conditions['db_where_in']);
			}
			
			if(array_key_exists('db_where_not_in', $conditions))
			{
				$where_not_in = $conditions['db_where_not_in'][0] . ' NOT IN (' . $conditions['db_where_not_in'][1] . ') ';
				
				if(!empty($where))
				{
					$where_not_in = 'AND ' . $where_not_in;
				}
				$where .= $where_not_in;
				unset($conditions['db_where_not_in']);
			}
			
			if(count($conditions) > 0)
			{
				foreach($conditions as $k => $v)
				{
					$end_where .= 'AND ' . $k . '="' . $v . '" ';
				}
			}
			
			if(empty($where))
			{
				$end_where = trim($end_where, 'AND ');
			}
			$end_where = $end_where ? ' ' . $end_where : '';
			$where .= $end_where;
		}
		elseif(strlen($conditions)>0)
		{
			$where = $conditions;
		}
		
		if(!empty($where))
		{
			$where = ' WHERE ' . $where; 
		}

		if(empty($tableViewName) || strlen($tableViewName)==0)
		{
			return;
		}
		else
		{
			$sql = 'DELETE FROM ' . $tableViewName . $where;
		}
		
		$rs = $this->db->execute($sql);
		return $rs;
	}
	public function exeDB($sql)
	{error_log(print_r($sql, 1)."\n\n", 3, __DIR__.'/log.txt');
		$rs = $this->db->execute($sql);
		return $rs;
	}
	public function inId()
	{
		return $this->db->insertID();
	}
	//过滤
	public function str_filter($str){
		if (0){
		$str = trim($str);  //清理空格	
		$str = strip_tags($str);   //过滤html标签\
		$str = htmlspecialchars($str);   //将字符内容转化为html实体	
		$str = addslashes($str);
		}
		$str=htmlspecialchars_decode($str);
		return $str;
	}
	
	/*!
	 * ubb2html support for php
	 * @requires xhEditor
	 * 
	 * @author Yanis.Wang<yanis.wang@gmail.com>
	 * @site http://xheditor.com/
	 * @licence LGPL(http://www.opensource.org/licenses/lgpl-license.php)
	 * 
	 * @Version: 0.9.10 (build 110801)
	 */
	function ubb2html($sUBB)
	{	
		$sHtml=$sUBB;
		
		global $emotPath,$cnum,$arrcode,$bUbb2htmlFunctionInit;$cnum=0;$arrcode=array();
		$emotPath='../xheditor_emot/';//表情根路径
		
		if(!$bUbb2htmlFunctionInit)
		{
			function saveCodeArea($match)
			{
				global $cnum,$arrcode;
				$cnum++;$arrcode[$cnum]=$match[0];
				return "[\tubbcodeplace_".$cnum."\t]";
			}
		}
		$sHtml=preg_replace_callback('/\[code\s*(?:=\s*((?:(?!")[\s\S])+?)(?:"[\s\S]*?)?)?\]([\s\S]*?)\[\/code\]/i','saveCodeArea',$sHtml);
		
		$sHtml=preg_replace("/&/",'&amp;',$sHtml);
		$sHtml=preg_replace("/</",'&lt;',$sHtml);
		$sHtml=preg_replace("/>/",'&gt;',$sHtml);
		$sHtml=preg_replace("/\r?\n/",'<br />',$sHtml);
		
		$sHtml=preg_replace("/\[(\/?)(b|u|i|s|sup|sub)\]/i",'<$1$2>',$sHtml);
		$sHtml=preg_replace('/\[color\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="color:$1;">',$sHtml);
		if(!$bUbb2htmlFunctionInit)
		{
			function getSizeName($match)
			{
				$arrSize=array('10px','13px','16px','18px','24px','32px','48px');
				if(preg_match("/^\d+$/",$match[1]))$match[1]=$arrSize[$match[1]-1];
				return '<span style="font-size:'.$match[1].';">';
			}
		}
		$sHtml=preg_replace_callback('/\[size\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','getSizeName',$sHtml);
		$sHtml=preg_replace('/\[font\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="font-family:$1;">',$sHtml);
		$sHtml=preg_replace('/\[back\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="background-color:$1;">',$sHtml);
		$sHtml=preg_replace("/\[\/(color|size|font|back)\]/i",'</span>',$sHtml);
		
		for($i=0;$i<3;$i++)$sHtml=preg_replace('/\[align\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\](((?!\[align(?:\s+[^\]]+)?\])[\s\S])*?)\[\/align\]/','<p align="$1">$2</p>',$sHtml);
		$sHtml=preg_replace('/\[img\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/img\]/i','<img src="$1" alt="" />',$sHtml);
		if(!$bUbb2htmlFunctionInit)
		{
			function getImg($match)
			{
				$alt=$match[1];$p1=$match[2];$p2=$match[3];$p3=$match[4];$src=$match[5];
				$a=$p3?$p3:(!is_numeric($p1)?$p1:'');
				return '<img src="'.$src.'" alt="'.$alt.'"'.(is_numeric($p1)?' width="'.$p1.'"':'').(is_numeric($p2)?' height="'.$p2.'"':'').($a?' align="'.$a.'"':'').' />';
			}
		}
		$sHtml=preg_replace_callback('/\[img\s*=([^,\]]*)(?:\s*,\s*(\d*%?)\s*,\s*(\d*%?)\s*)?(?:,?\s*(\w+))?\s*\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*)?\s*\[\/img\]/i','getImg',$sHtml);
		if(!$bUbb2htmlFunctionInit)
		{
			function getEmot($match)
			{
				global $emotPath;
				$arr=split(',',$match[1]);
				if(!isset($arr[1])){$arr[1]=$arr[0];$arr[0]='default';}
				$path=$emotPath.$arr[0].'/'.$arr[1].'.gif';
				return '<img src="'.$path.'" alt="'.$arr[1].'" />';
			}
		}
		$sHtml=preg_replace_callback('/\[emot\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\/\]/i','getEmot',$sHtml);
		$sHtml=preg_replace('/\[url\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*\[\/url\]/i','<a href="$1">$1</a>',$sHtml);
		$sHtml=preg_replace('/\[url\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]*?)\s*\[\/url\]/i','<a href="$1">$2</a>',$sHtml);
		$sHtml=preg_replace('/\[email\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/email\]/i','<a href="mailto:$1">$1</a>',$sHtml);
		$sHtml=preg_replace('/\[email\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]+?)\s*\[\/email\]/i','<a href="mailto:$1">$2</a>',$sHtml);
		$sHtml=preg_replace("/\[quote\]/i",'<blockquote>',$sHtml);
		$sHtml=preg_replace("/\[\/quote\]/i",'</blockquote>',$sHtml);
		if(!$bUbb2htmlFunctionInit)
		{
			function getFlash($match)
			{
				$w=$match[1];$h=$match[2];$url=$match[3];
				if(!$w)$w=480;if(!$h)$h=400;
				return '<embed type="application/x-shockwave-flash" src="'.$url.'" wmode="opaque" quality="high" bgcolor="#ffffff" menu="false" play="true" loop="true" width="'.$w.'" height="'.$h.'" />';
			}
		}
		$sHtml=preg_replace_callback('/\[flash\s*(?:=\s*(\d+)\s*,\s*(\d+)\s*)?\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/flash\]/i','getFlash',$sHtml);
		if(!$bUbb2htmlFunctionInit)
		{
			function getMedia($match)
			{
				$w=$match[1];$h=$match[2];$play=$match[3];$url=$match[4];
				if(!$w)$w=480;if(!$h)$h=400;
				return '<embed type="application/x-mplayer2" src="'.$url.'" enablecontextmenu="false" autostart="'.($play=='1'?'true':'false').'" width="'.$w.'" height="'.$h.'" />';
			}
		}
		$sHtml=preg_replace_callback('/\[media\s*(?:=\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*(\d+)\s*)?)?\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/media\]/i','getMedia',$sHtml);
		if(!$bUbb2htmlFunctionInit)
		{
			function getTable($match)
			{
				return '<table'.(isset($match[1])?' width="'.$match[1].'"':'').(isset($match[2])?' bgcolor="'.$match[2].'"':'').'>';
			}
		}
		$sHtml=preg_replace_callback('/\[table\s*(?:=(\d{1,4}%?)\s*(?:,\s*([^\]"]+)(?:"[^\]]*?)?)?)?\s*\]/i','getTable',$sHtml);
		if(!$bUbb2htmlFunctionInit){
		function getTR($match){return '<tr'.(isset($match[1])?' bgcolor="'.$match[1].'"':'').'>';}}
		$sHtml=preg_replace_callback('/\[tr\s*(?:=(\s*[^\]"]+))?(?:"[^\]]*?)?\s*\]/i','getTR',$sHtml);
		if(!$bUbb2htmlFunctionInit)
		{
			function getTD($match)
			{
				$col=isset($match[1])?$match[1]:0;$row=isset($match[2])?$match[2]:0;$w=isset($match[3])?$match[3]:null;
				return '<td'.($col>1?' colspan="'.$col.'"':'').($row>1?' rowspan="'.$row.'"':'').($w?' width="'.$w.'"':'').'>';
			}
		}
		$sHtml=preg_replace_callback("/\[td\s*(?:=\s*(\d{1,2})\s*,\s*(\d{1,2})\s*(?:,\s*(\d{1,4}%?))?)?\s*\]/i",'getTD',$sHtml);
		$sHtml=preg_replace("/\[\/(table|tr|td)\]/i",'</$1>',$sHtml);
		$sHtml=preg_replace("/\[\*\]((?:(?!\[\*\]|\[\/list\]|\[list\s*(?:=[^\]]+)?\])[\s\S])+)/i",'<li>$1</li>',$sHtml);
		if(!$bUbb2htmlFunctionInit)
		{
			function getUL($match)
			{
				$str='<ul';
				if(isset($match[1]))$str.=' type="'.$match[1].'"';
				return $str.'>';
			}
		}
		$sHtml=preg_replace_callback('/\[list\s*(?:=\s*([^\]"]+))?(?:"[^\]]*?)?\s*\]/i','getUL',$sHtml);
		$sHtml=preg_replace("/\[\/list\]/i",'</ul>',$sHtml);
		$sHtml=preg_replace("/\[hr\/\]/i",'<hr />',$sHtml);
	
		for($i=1;$i<=$cnum;$i++)$sHtml=str_replace("[\tubbcodeplace_".$i."\t]", $arrcode[$i],$sHtml);
	
		if(!$bUbb2htmlFunctionInit)
		{
			function fixText($match)
			{
				$text=$match[2];
				$text=preg_replace("/\t/",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$text);
				$text=preg_replace("/ /",'&nbsp;',$text);
				return $match[1].$text;
			}
		}
		$sHtml=preg_replace_callback('/(^|<\/?\w+(?:\s+[^>]*?)?>)([^<$]+)/i','fixText',$sHtml);
		
		$bUbb2htmlFunctionInit=true;
		
		return $sHtml;
	}
}