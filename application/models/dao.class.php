<?php
class dao
{
    public $pdoe;

    public $crud;

    public function __construct()
    {
        if(empty($this->pdoe))
        { 
			$conf = Yaf_Application::app()->getConfig();	
			$myconf=$conf[database][mysql];
            $_db['dsn'] = 'mysql:host='.$myconf['hostname'].';port='.$myconf['port'].';dbname='.$myconf['database'];
            $_db['user'] = $myconf['username'];
            $_db['pass'] = $myconf['password'];
            $_db['option'] = array(
                                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET character_set_connection="UTF8",character_set_results="UTF8",character_set_client=binary',
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_AUTOCOMMIT => false,
                                PDO::ATTR_TIMEOUT => 30,
                                PDO::ATTR_PERSISTENT => false,
                                );
            $_db['pageMode'] = 'limit';

            include_once(APP_PATH . '/application/library/db/PDOExtend.class.php');
            include_once(APP_PATH . '/application/library/db/CRUD.class.php');

            $this->pdoe = new PDOExtend($_db['dsn'], $_db['user'], $_db['pass'], $_db['option']);
            $this->pdoe->SetPageMode($_db['pageMode']); //设置分页模式
            $this->crud = new CRUD($this->pdoe);
        }

    }

    public function __destruct()
    {
        $this->crud = null;
        $this->pdoe = null;
    }
}
?>