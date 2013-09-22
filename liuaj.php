<?php


$link = mysql_connect('localhost', 'root', 'anjin84');

mysql_select_db('wj', $link);

$sql = "SET character_set_connection='utf8', character_set_results='utf8', character_set_client=binary";

mysql_query($sql, $link);

$n = 10;

for($i = 0; $i < $n; $i++)
{
	$table = 'answer_'.$i;
	$sql = "CREATE TABLE `".$table."` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `fk_sytle_id` int(4) DEFAULT NULL,
	  `fk_type_id` int(4) DEFAULT NULL,
	  `q_title` varchar(255) DEFAULT NULL,
	  `q_top_desc` varchar(255) DEFAULT NULL,
	  `q_foot_desc` varchar(255) DEFAULT NULL,
	  `q_start` bigint(20) DEFAULT NULL,
	  `q_end` bigint(20) DEFAULT '0',
	  `duration` bigint(20) DEFAULT NULL,
	  `status` smallint(1) DEFAULT '1',
	  `switch` tinyint(4) NOT NULL DEFAULT '1',
	  `pass` varchar(255) DEFAULT NULL,
	  `q_repeat` smallint(1) DEFAULT '0',
	  `q_anonymous` smallint(1) DEFAULT '0',
	  `q_login` smallint(1) DEFAULT '0',
	  `q_all` smallint(1) DEFAULT NULL,
	  `c_userid` bigint(20) DEFAULT NULL,
	  `c_time` bigint(20) DEFAULT NULL,
	  `pass_type` smallint(1) DEFAULT '0',
	  `unitid` bigint(20) DEFAULT NULL,
	  `lmt` tinyint(1) DEFAULT '0',
	  PRIMARY KEY (`id`),
	  KEY `c_userid` (`c_userid`),
	  KEY `q_end` (`q_end`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$rs = mysql_query($sql);
	var_dump($rs);
	echo '<br>';
}