<?php

class role
{

    public $longRID;
    public $strRoleName;

    private $arrAuth;
    private $_arrAuth = array();


    public function setLongRID($longRID)
    {
        $this->longRID = $longRID;
    }

    public function getLongRID()
    {
        return $this->longRID;
    }


    public function setArrAuth($arrAuth)
    {
        $this->arrAuth = $arrAuth;
        if(!empty($arrAuth))
        {
            foreach ($arrAuth as $auth)
            {
                $this->_arrAuth[$auth['auth_code']] = $auth['auth_name'];
            }
        }
        else
        {
            $this->_arrAuth = array();
        }
    }

    public function getArrAuth()
    {
        return $this->arrAuth;
    }

    public function setStrRoleName($strRoleName)
    {
        $this->strRoleName = $strRoleName;
    }

    public function getStrRoleName()
    {
        return $this->strRoleName;
    }


    public function isAuth($strAuthCode)
    {
        return array_key_exists($strAuthCode,$this->_arrAuth);
    }

}
