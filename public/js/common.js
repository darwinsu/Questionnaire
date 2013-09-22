
function myPostForm(fact,selid,sys_action,msg){
	if(msg!=undefined){
		if(!confirm(msg)){
			return;
		}
	}
	document.MyForm.action=fact;
	if(sys_action!=undefined){
		document.MyForm.sys_action.value=sys_action;
	}
	if(selid!=undefined){
		document.MyForm.selid.value=selid;
	}
	document.MyForm.submit();
}
function mySelAllCheckbox(form,chk,findname){
	var _len = form.elements.length;
	var i;
	if(_len){
		for(i=0; i<_len; i++){
			if(form.elements[i].type == 'checkbox'){
				if(findname!=undefined){
					if(form.elements[i].name.indexOf(findname)==-1){
						continue;
					}
				}
				form.elements[i].checked = chk;
			}
		}
	}
}

function ys(ys){
	document.MyForm.ys.value=ys;
	document.MyForm.submit();
}
function Menuys(selObj){
	var ys;
	ys=selObj.options[selObj.selectedIndex].value;
	document.MyForm.rowidid.value="";
	document.MyForm.ys.value=ys;
	document.MyForm.action.value=document.MyForm.action_old.value;
	document.MyForm.submit();
}
function Menuys_text(){
	var ys;
	var obj = document.MyForm.elements['yslist'];
	ys=document.MyForm.ys.value;
	if(ys == "")
	{
		ys=1;
	}
	var i,len = obj.length;
	if(len)
	{
		for(i=0; i<len; i++)
		{
			if(obj[i].value != ys)
			{
				ys = obj[i].value;
				break;
			}
		}
	}
	else
	{
		ys = obj.value;
	}
	document.MyForm.ys.value=ys;
	document.MyForm.submit();
}

function px(id,value){
	var order_value;
	order_value=document.MyForm.order.value;
	if(order_value==id){
		document.MyForm.order.value=id+" desc";
		document.MyForm.order1.value="按"+value+"反排序";
		document.MyForm.action.value="sel";
		document.MyForm.ys.value="";
		document.MyForm.submit();
	}
	else{
		document.MyForm.order.value=id;
		document.MyForm.order1.value="按"+value+"排序";
		document.MyForm.action.value="sel";
		document.MyForm.ys.value="";
		document.MyForm.submit();
	}
}

function onColor(td)
{
	td.style.backgroundColor='#EBEBD6';
	td.style.color='#0600FF';
}

function offColor(td)
{
	td.style.backgroundColor='';
	td.style.color='';
}

var  highlightcolor='#c1ebff';
var  clickcolor='#51b2f6';
function  changeto(){
	source=event.srcElement;
	if  (source.tagName=="TR"||source.tagName=="TABLE")
	return;
	while(source.tagName!="TD")
	source=source.parentElement;
	source=source.parentElement;
	cs  =  source.children;
	//alert(cs.length);
	if  (cs[1].style.backgroundColor!=highlightcolor&&source.id!="nc"&&cs[1].style.backgroundColor!=clickcolor)
	for(i=0;i<cs.length;i++){
		cs[i].style.backgroundColor=highlightcolor;
	}
}

function  changeback(){
	if  (event.fromElement.contains(event.toElement)||source.contains(event.toElement)||source.id=="nc")
	return
	if  (event.toElement!=source&&cs[1].style.backgroundColor!=clickcolor)
	//source.style.backgroundColor=originalcolor
	for(i=0;i<cs.length;i++){
		cs[i].style.backgroundColor="";
	}
}

function  clickto(){
	source=event.srcElement;
	if  (source.tagName=="TR"||source.tagName=="TABLE")
	return;
	while(source.tagName!="TD")
	source=source.parentElement;
	source=source.parentElement;
	cs  =  source.children;
	//alert(cs.length);
	if  (cs[1].style.backgroundColor!=clickcolor&&source.id!="nc")
	for(i=0;i<cs.length;i++){
		cs[i].style.backgroundColor=clickcolor;
	}
	else
	for(i=0;i<cs.length;i++){
		cs[i].style.backgroundColor="";
	}
}

function showHideSearch(){
	if (document.getElementById('searchM').style.display=='block')
	{
		document.getElementById('searchM').style.display='none';
		document.getElementById('showText').value ='显示查询条件';
		//document.getElementById('key').style.display='block';
	}else {
		document.getElementById('searchM').style.display='block';
		document.getElementById('showText').value ='隐藏查询条件';
		//document.getElementById('key').style.display='none';
	}
}
function checkAll(){
	var cbx = document.forms["myForm"].elements["cbx"];
	var chk = document.forms["myForm"].elements["chkAll"];
	for(var i = 0;i < cbx.length;i ++){
		cbx[i].checked = chk.checked;
	}
}

function getFullFileName(str){
	var   p=str.lastIndexOf('/');
	return   str.substr(++p,str.length-p);
}
function db_Click(obj){
	var s = document.getElementById(obj);
	if(s.style.display=='none'){
		s.style.display='';
		img=document.getElementById("img_"+obj);
	}
	else{
		s.style.display='none'
		img=document.getElementById("img_"+obj);
	}
}

function isIp(val){
	var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
	var reg = val.match(exp);
	if(reg==null)
	{
		return false;
	}
	return true;
}

function check_num(val,cName,msg){
     var str=val;
	 var i;
	 i=0;

     var retStr = "";
	 for(i=0;i<str.length;i++){
	     //alert(str.substr(i,1));
	     if(str.substr(i,1)!='0' & str.substr(i,1)!='1' & str.substr(i,1)!='2' & str.substr(i,1)!='3' & str.substr(i,1)!='4' & str.substr(i,1)!='5' & str.substr(i,1)!='6' & str.substr(i,1)!='7' & str.substr(i,1)!='8' & str.substr(i,1)!='9'){
			 document.MyForm[""+cName+""].value='0';
             document.MyForm[""+cName+""].focus();
             if(msg)
             {
                alert(msg);
             }
             else
             {
		        alert("请输入整数！");
             }
			 return false;
		 }
		 else
		 {
		     retStr += str.substr(i,1);
		 }
	 }
	 return true;
 }

function checkdata_num(val,name)
{
     if (isNaN(val))
     {
         str = val.substring(0,val.length-1);
         document.all[""+name+""].value = '0';
		 alert("请输入数字！");
         return false;
     }
	 return true;
}

function DateHour(cname,cval,isnull)
{
	var now = new Date();
	var _html = new Array();

	if(cval=='' && isnull!=1)
	{
		cval = now.getHours();
	}

	_html.push('<select name="'+cname+'">');
	if(isnull==1)
	{
		_html.push('<option value=""></option>');
	}
	var y;
	for(i=0;i<=24;i++)
	{
		if(i<10)
		{
			y = '0'+i;
		}
		else
		{
			y = i;
		}
		if(y==cval)
		{
			_html.push('<option value="'+y+'" selected>'+y+'</option>');
		}
		else
		{
			_html.push('<option value="'+y+'">'+y+'</option>');
		}
	}
	_html.push('</select>');
	return _html.join('');
}

function DateMinute(cname,cval,isnull)
{
	var now = new Date();
	var _html = new Array();

	if(cval=='' && isnull!=1)
	{
		cval = now.getMinutes();
	}

	_html.push('<select name="'+cname+'">');
	if(isnull==1)
	{
		_html.push('<option value=""></option>');
	}
	var y;
	for(i=0;i<60;i++)
	{
		if(i<10)
		{
			y = '0'+i;
		}
		else
		{
			y = i;
		}
		if(y==cval)
		{
			_html.push('<option value="'+y+'" selected>'+y+'</option>');
		}
		else
		{
			_html.push('<option value="'+y+'">'+y+'</option>');
		}
	}
	_html.push('</select>');
	return _html.join('');
}

function DateYear(cname,cval,isnull)
{
	var now = new Date();
	var _html = new Array();

	if(cval=='' && isnull!=1)
	{
		cval = now.getFullYear();
	}

	_html.push('<select name="'+cname+'">');
	if(isnull==1)
	{
		_html.push('<option value=""></option>');
	}
	var y;
	for(i=2008;i<2050;i++)
	{
		if(i<10)
		{
			y = '0'+i;
		}
		else
		{
			y = i;
		}
		if(y==cval)
		{
			_html.push('<option value="'+y+'" selected>'+y+'</option>');
		}
		else
		{
			_html.push('<option value="'+y+'">'+y+'</option>');
		}
	}
	_html.push('</select>');
	return _html.join('');
}

function DateMonth(cname,cval,isnull)
{
	var now = new Date();
	var _html = new Array();

	if(cval=='' && isnull!=1)
	{
		cval = now.getMonth()+1;
	}

	_html.push('<select name="'+cname+'">');
	if(isnull==1)
	{
		_html.push('<option value=""></option>');
	}
	var y;
	for(i=1;i<13;i++)
	{
		if(i<10)
		{
			y = '0'+i;
		}
		else
		{
			y = i;
		}
		if(y==cval)
		{
			_html.push('<option value="'+y+'" selected>'+y+'</option>');
		}
		else
		{
			_html.push('<option value="'+y+'">'+y+'</option>');
		}
	}
	_html.push('</select>');
	return _html.join('');
}

function DateDay(cname,cval,isnull)
{
	var now = new Date();
	var _html = new Array();

	if(cval=='' && isnull!=1)
	{
		cval = now.getDate();
	}

	_html.push('<select name="'+cname+'">');
	if(isnull==1)
	{
		_html.push('<option value=""></option>');
	}
	var y;
	for(i=1;i<32;i++)
	{
		if(i<10)
		{
			y = '0'+i;
		}
		else
		{
			y = i;
		}
		if(y==cval)
		{
			_html.push('<option value="'+y+'" selected>'+y+'</option>');
		}
		else
		{
			_html.push('<option value="'+y+'">'+y+'</option>');
		}
	}
	_html.push('</select>');
	return _html.join('');
}

function onClickEqu(equ_code)
{
	//var str = window.showModalDialog('./jk.php?mainaction=ywjk&action=sbgjxx&equcode='+equ_code,"�澯��ϸ��Ϣ","dialogHeight: 520px; dialogWidth: 660px; center: yes; help: no; resizable:no; status:no");
	window.open('./jk.php?mainaction=ywjk&action=sbgjxx&equcode='+equ_code, '�澯��ϸ��Ϣ','menubar=no,toolbar=no,location=no,directories=no,status=no,scrollbars=1,resizable=1,top=0,left=0,width=660px,height=520px');
}

window.onload=function(){
    try
    {
        if(navigator.userAgent.indexOf("MSIE 6.0")==-1)
        {
            var obj=document.getElementsByTagName('div');
            if(obj)
            {
                var len=obj.length;
                for(i=0;i<len;i++)
                {
                    //alert(obj[i].className);
                    if(obj[i].className=='list')
                    {
                        obj[i].style.height=document.documentElement.clientHeight-130;
                    }
                }
            }
        }
    }
    catch(e)
    {}
}

function IsEmail(val){
 var reyx= /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
 return(reyx.test(val));
}

function initTable()
{
	var Ptr=document.getElementById("tab").getElementsByTagName("tr");
	for (i=1;i<Ptr.length+1;i++) { 
		Ptr[i-1].className = (i%2>0)?"t1":"t2";
    }

	for(var i=0;i<Ptr.length;i++) {
		Ptr[i].onmouseover=function(){
			this.tmpClass=this.className;
			this.className = "t3";
		};
		Ptr[i].onmouseout=function(){
			this.className=this.tmpClass;
		};
	}
}

window.onload=function()
{
	try
    {
        if(navigator.userAgent.indexOf("MSIE 6.0")==-1)
        {
            var obj=document.getElementById('man_zone');
            if(obj)
            {                
               obj.style.height=document.documentElement.clientHeight-140;
            }
        }
    }
    catch(e)
    {}
}

//if (window.Event) 
//document.captureEvents(Event.MOUSEUP); 

function nocontextmenu() 
{
	event.cancelBubble = true
	event.returnValue = false;

	return false;
}

function norightclick(e) 
{
	if (window.Event) 
	{
		if (e.which == 2 || e.which == 3)
		return false;
	}
	else if (event.button == 2 || event.button == 3)
	{
		event.cancelBubble = true
		event.returnValue = false;
		return false;
	}

}

//document.oncontextmenu = nocontextmenu;
//document.onmousedown = norightclick;