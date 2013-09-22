<?php

class func
{
	static function ajax($rs)
	{
		header("Access-Control-Allow-Origin:*");
		echo json_encode($rs);
	}
	
	/*
	 | 从缘分playerid(用户ID)转换成UAP uid(伪造的)
	 +------------------------------------
	 */
	static function cmbnInt2Long($playerid, $appid)
	{
		$bin_appid = decbin($appid);
	
		$bin64_appid = $bin_appid . str_repeat(0,32);
	
		$appid64 = bindec($bin64_appid);
	
		$rs = $playerid + $appid64;
	
		return $rs;
	}
	
	/*
	 | UAP 统一登录
	 +---------------------------
	 */
	static function uapcookie($key, $value, $expire=0)
	{
        return setrawcookie($key, $value, $expire, '/|.91.com');
    }
	
	// 拆分中文字符串为数组
	static function str_split_utf8($str)
	{
		$split=1;
		$array=array();
	
		for($i=0;$i<strlen($str);){
			$value=ord($str[$i]);
			if($value>127){
				if($value>=192&&$value<=223){
					$split=2;
				}
				else if($value>=224 && $value<=239){
					$split=3;
				}
				else if($value>=240 && $value<=247){
					$split=4;
				}
			}else{
				$split=1;
			}
			$key=NULL;
			for($j=0;$j<$split;$j++,$i++){
				$key.=$str[$i];
			}
			array_push($array,$key);
		}
		return $array;
	}
	
	
    static function getUapObj()
    {
    	$uapMdl=Yaf_Registry::get('uapMdl');
    	if(!$uapMdl)
    	{
    		$uapMdl=new uapModel();
    		Yaf_Registry::set('uapMdl',$uapMdl);
    	}
    	return $uapMdl;
    }
    
    static function getLyObj()
    {
    	$lyMdl=Yaf_Registry::get('lyMdl');
    	if(!$lyMdl)
    	{
    		$lyMdl=new lyModel();
    		Yaf_Registry::set('lyMdl',$lyMdl);
    	}
    	return $lyMdl;
    }
    
    /**
     * 分页显示
     *
     * @param $pageno：当前页数
     * @param $perpage：每页显示数
     * @param $count：总记录数
     * @param $theurl：基础连接
     */
    static function fy($pageno,$perpage,$count,$theurl,$isajax='0',$updateId='')
    {
    	if(strstr($theurl,"?"))
    	{
    		$key="&";
    	}
    	else 
    	{
    		$key="?";
    	}
    	
    	$totalpage=ceil($count/$perpage);
    	
    	$str='<div class="page"><a href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno=1","'.$updateId.'");\'>首页</a>';
    	if($pageno>1)
    	{
    		$str.='<a href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno='.($pageno-1).'","'.$updateId.'");\'>上一页</a>';
    	}
    	for($i=0;$i<$totalpage;$i++)
    	{
    		$str.='<a '.((($i+1)==$pageno)?'class="current"':'').' href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno='.($i+1).'","'.$updateId.'");\'>'.($i+1).'</a>';
    	}
    	
    	if($pageno<$totalpage)
    	{
    		$str.='<a href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno='.($pageno+1).'","'.$updateId.'");\'>下一页</a>';
    	}   	
    	$str.='<a href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno='.($totalpage).'","'.$updateId.'");\'>最后一页</a>';
 
    	$str.='</div>';
    	return $str;
    }
    
	
	
    static function get_page($pageno,$perpage,$count,$theurl,$isajax='0',$updateId='')
    {
		if($count==0){
			$temp=true;
			$count=1;
		}
    	if(strstr($theurl,"?"))
    	{
    		$key="&";
    	}
    	else 
    	{
    		$key="?";
    	}
    	
    	$totalpage=ceil($count/$perpage);
    	
    	$str='<div class="page"><a href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno=1","'.$updateId.'");\'><font class="page_first">首页</font></a>';
    	if($pageno>1)
    	{
    		$str.='<a href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno='.($pageno-1).'","'.$updateId.'");\'><font class="page_up">上一页</font></a>';
    	}
    	for($i=0;$i<$totalpage;$i++)
    	{
    		$str.='<a '.((($i+1)==$pageno)?'class="page_current"':'').' href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno='.($i+1).'","'.$updateId.'");\'><font id="pageno_'.$i.'">'.((($i+1)==$pageno)?($i+1):'['.($i+1).']').'</font></a>';
    	}
    	
    	if($pageno<$totalpage)
    	{
    		$str.='<a href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno='.($pageno+1).'","'.$updateId.'");\'><font class="page_down">下一页</font></a>';
    	}   	
    	$str.='<a href=\'javascript:getPage("'.$isajax.'","'.$theurl.$key.'pageno='.($totalpage).'","'.$updateId.'");\'><font class="page_last">尾页</font></a>';
    	
		$str.="<font style='color: #808080;margin-left: 10px;'>&nbsp;共<span id='page_count'>".(($temp)?'0':$count)."</span>条记录,分".$totalpage."页,每页".$perpage."条</font>";
		$str.='</div>';
    	return $str;
    }
	
	
	
	static function getPage($count,$perpage,&$start,&$limit)
    {
    	if($_GET['page']=='')
    	{
    		$_GET['page']=1;
    	}
    	
    	$totalpage=ceil($count/$perpage);
    	$str = "排序：<input type='text' name='order1' size='15' value='".$_POST['order1']."'>显示：<input type='text' name='sys_tempfy' size='4' value='".$_POST['sys_tempfy']."'>&nbsp;行 &nbsp;<a href=\"javascript:ys('1');\">首页</a>&nbsp;<a href=\"javascript:ys('".$totalpage."');\">尾页</a>&nbsp;&nbsp;第&nbsp;<input type='text' size='3' maxlength='6' value='".$_GET['page']."' name='yslist' id='yslist'>&nbsp;页&nbsp;<a href=\"javascript:Menuys_text();\">转到</a>";
    	if($_GET['page'] > 1)
    	{
    		$str.="&nbsp;<a href=\'javascript:prepage();\'>上一页</a>";
    	}
    	if($_GET['page']!=$totalpage)
    	{
    		$str.="&nbsp;<a href=\'javascript:nextPage();\'>下一页</a>";
    	}
    	$str.="&nbsp;共<font color='#FF0000'>".$count."</font>条记录&nbsp;&nbsp;<font color='#FF0000'>".$_GET['page']."</font>/".$totalpage."页";
    	return $str;
    }
	
	
	static function hdl_switch($endtime, &$switch)
	{
		if($_SERVER['REQUEST_TIME'] - 86400 > $endtime)
		{
			$switch = 0;
		}
	}
	
	
    /**
     * 获取年龄
     *
     * @param $birthday：出生年月日，格式：yyyy-mm-dd
     */
    static function getAge($birthday)
    {
    	$arr=explode('-',$birthday);
    	if(is_numeric($arr[0]) && is_numeric($arr[1]) && is_numeric($arr[2]))
    	{
			$u=date('U',mktime(0,0,0,$arr[1],$arr[2],$arr[0]));
			$l=time()-$u; //相差的秒数
			$year=floor($l/(60*60*24*365));
			return $year;
    	}
    	else 
    	{
    		return -1;
    	}
    }
    
    /**
     * 阿拉伯数字转中文
     *
     * @param string $alabo阿拉伯数字
     */
    static function alaboToHanzi($alabo)
    {
    	$arrHanzi[0]='零';
    	$arrHanzi[1]='一';
    	$arrHanzi[2]='二';
    	$arrHanzi[3]='三';
    	$arrHanzi[4]='四';
    	$arrHanzi[5]='五';
    	$arrHanzi[6]='六';
    	$arrHanzi[7]='七';
    	$arrHanzi[8]='八';
    	$arrHanzi[9]='九';
    	
    	$len=strlen($alabo);
    	$hanzi='';
    	for($i=0;$i<$len;$i++)
    	{
//    		echo substr($alabo,$i,1);
    		$hanzi.=$arrHanzi[substr($alabo,$i,1)];	
    	}
    	
    	return $hanzi;
    }
    
	static function cutstr($string, $length) {
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);  
        for($i=0; $i<count($info[0]); $i++) {
                $wordscut .= $info[0][$i];
                $j = ord($info[0][$i]) > 127 ? $j + 2 : $j + 1;
                if ($j > $length - 3) {
                        return $wordscut." ...";
                }
        }
        return join('', $info[0]);
	}
	static function checkmobile() {
	$mobile = array();
	static $mobilebrowser_list ="/iphone|android|phone|mobile|wap|netfront|java|opera mobi|opera mini',
				'ucweb|windows ce|symbian|series|webos|sony|blackberry|dopod|nokia|samsung',
				'palmsource|xda|pieplus|meizu|midp|cldc|motorola|foma|docomo|up.browser',
				'up.link|blazer|helio|hosin|huawei|novarra|coolpad|webos|techfaith|palmsource',
				'alcatel|amoi|ktouch|nexian|ericsson|philips|sagem|wellcom|bunjalloo|maui|smartphone',
				'iemobile|spice|bird|zte-|longcos|pantech|gionee|portalmmm|jig browser|hiptop',
				'benq|haier|^lct|320x320|240x320|176x220/i";
	static $wmlbrowser_list = "/cect|compal|ctl|lg|nec|tcl|alcatel|ericsson|bird|daxian|dbtel|eastcom|pantech|dopod|philips|haier|konka|kejian|lenovo|benq|mot|soutec|nokia|sagem|sgh|sed|capitel|panasonic|sonyericsson|sharp|amoi|panda|zte/i";

	$pad_list = "/pad|gt-p1000/i";

	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

	if(preg_match($pad_list,$useragent)) {
		return false;
	}
	if(($v = preg_match($mobilebrowser_list,$useragent))){
		return '2';
	}
	if(($v = preg_match($wmlbrowser_list,$useragent))) {
		return '3';
	}
	$brower = "/mozilla|chrome|safari|opera|m3gate|winwap|openwave|myop/i";
	if(preg_match($brower,$useragent)) return false;
		return true;
	}
}