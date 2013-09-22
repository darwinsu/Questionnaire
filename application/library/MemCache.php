<?php  
/**   
* Afx Framework   
* A Light Framework Provider Basic Communication With    
* Databases Like Mysql Memcache Mongo and more   
* LICENSE   
* This source file is part of the Afx Framework   
* You can copy or distibute this file but don't delete the LICENSE content here   
* @copyright  Copyright (c) 2011 Banggo Technologies China Inc. (http://www.banggo.com)   
* @license Free   */  

/**   
* @package Afx_Db   
* @version $Id Memcache.php   
* The Memcache Class Wrapper Provider Seperator Read And Write   
* @author Afx team && firedtoad@gmail.com &&dietoad@gmail.com   *   
*/  

class MemCacheHelper 
{         
/**           
* @var Afx_Db_Memcache           
*/         
protected static $instance;          

/**           
* @var array store the configurations            
*/          
protected static $options = array ();  

/**           
* @var Memcache The Master Link           
*/          
protected static $write_cache;          

/**           
* Notice! This is really protected             
* so this class was prevented be instance            
* by call global new Method           
*/          
protected function __construct() 
{         
    $this->_initConnection ();          
}          

/**           
* set the configuration           
* @param array $options           
* @return Boolean           
*/          
public static function setOptions($options = array()) 
{                  
    self::$options = $options;                  
    return TRUE;          
}          

/**   
* init The Configuration           
* same as setOptions()           
* @param array $options           
* @return Boolean           
*/          
public static function initOption($options = array()) 
{                  
    self::$options = $options;                  
    return TRUE;          
}          

public static function reInitConnection() 
{                  
    if (self::$instance) 
    {                          
        self::$instance->_initConnection ();                  
    }          
}          

/**           *            
* get the Configuration           
* @return Array           
*/          
public static function getOptions() 
{                  
    return self::$options;          
}          

/**           *            
* Initialize the Read and Write Link           
* If No Memcache extension loaded Throw Afx_Db_Exception           
* @throws Afx_Db_Exception           
* @return Boolean           
*/          
private function _initConnection() 
{        
	if (class_exists ( 'Memcache' )) 
	{                                  
	    self::$write_cache = new Memcache ();                                  
		self::$write_cache->addServer ( "192.168.94.30", 11211 );                                  
		self::$write_cache->pconnect ( "192.168.94.30", 11211 ); 
	}
	else 
	{                                  
	    //throw new Afx_Db_Exception ( "no Memcache Class Found Please check the memcache installtion", '404' );                          
	}                           
}          

/**           
* The Memcache add Wrapper           
* Write To The Master           
* @param string $key           
* @param mixed $value           
* @param int $timeout           
* @param int $flag           
* @return Boolean           
*/         
public function add($key, $value, $timeout = 60, $flag = MEMCACHE_COMPRESSED) 
{                  
    if (self::$write_cache) 
    {                          
        return self::$write_cache->add ( $key, $value, $flag, $timeout );                  
    }          
}          

/**           *            
* The Memcache delete Wrapper           
* Delete  The Master            
* @param string $key           
* @param int $timeout  default=0 means no expired           
* @return Boolean           
*/          
public function delete($key, $timeout = 0) 
{                  
    if (self::$write_cache) 
    {                          
        return self::$write_cache->delete ( $key, $timeout );                  
    }          
}          
/**           *            
* The Memcache get Wrapper           
* Read from the Slave           
* @param string $key           
* @param Boolean $master can be true or false           
* @return mixed           
*/          
public function get($key) 
{                  
    if (self::$write_cache) 
	{
	    return self::$write_cache->get ( $key );
	}
	return NULL;
}          

/**           
* The Memcache set Wrapper           
* Write To The Master           
* @param string $key           
* @param mixed $value           
* @param int $timeout           
* @param int $flag           
* @return Boolean           
*/          
public function set($key, $value, $timeout = 1800, $flag = MEMCACHE_COMPRESSED) 
{                  
    if (self::$write_cache) 
    {
        return self::$write_cache->set ( $key, $value, $flag, $timeout );                  
    }          
}          

/**           
* please Don't use this method            
* It will delete all the items on the master server           
* if you do really want to clean the master server uncomment this function body           
* @deprecated           
* @return Boolean           
*/          
public function flush() 
{                                    
    if (self::$write_cache) 
    {                          
        return self::$write_cache->flush ();                  
    }          
}          

/**           *            
* The Memcache replace Wrapper           
* replace the master            
* @param string $key           
* @param mixed $value           
* @param int $timeout           
* @param int $flag           
* @return Boolean           
*/          
public function replace($key, $value, $timeout = 60, $flag = MEMCACHE_COMPRESSED) 
{                  
    if (self::$write_cache) 
    {                          
        return self::$write_cache->replace ( $key, $value, $flag, $timeout );                  
    }          
}          

/**           
* The Memcache increment Wrapper           
* @param string $key           
* @param int $value           
* @return Boolean           
*/         
public function increment($key, $value = 1) 
{                  
    if (self::$write_cache) 
    {                          
        return self::$write_cache->increment ( $key, $value );                  
    }          
}          

/**           
* The Memcache decrement Wrapper           
* @param string $key           
* @param int $value           
* @return Boolean           
*/          
public function decrement($key, $value = 1) 
{                  
    if (self::$write_cache) 
    {                         
        return self::$write_cache->decrement ( $key, $value );                  
    }          
}          

/**           
* The Memcache getStatus Wrapper           
* @param string $which can be master or slave or null means all           
* @return array           
*/          
public function getStatus() 
{                  
    if (self::$write_cache) 
	{                                  
	    return self::$write_cache->getStats ();                          
	}
	return NULL;
}         

/**           
* Get the Instance           
* @return Afx_Db_Memcache           
*/          
public static function Instance()
{                  
    if (NULL === self::$instance) 
    {                          
        self::$instance = new self ();                  
    }                  
    return self::$instance;          
}
}
?>