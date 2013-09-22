(function($){
	var oldHTML = $.fn.html;
	$.fn.formhtml = function() {        
		if (arguments.length) return oldHTML.apply(this,arguments);
		$("input,button", this).each(function(){
			this.setAttribute('value',this.value);
		});

		$("textarea", this).each(function(){
			this.innerHTML=this.value;
		});
		$(":radio,:checkbox", this).each(function(){
			if (this.checked)
				this.setAttribute('checked', 'checked');
			else
				this.removeAttribute('checked');
		});

		$("option", this).each(function(){
			if(this.selected) this.setAttribute('selected', 'selected');
			else this.removeAttribute('selected');
		});
		return oldHTML.apply(this);

	};
})(jQuery);

function do_ajax(fn,val){
	val=val.replace(/\+/g,"%2B");
	var test='';
	$.ajax({
		url:actionPhp+fn,
		data:val,
		async:false,
		dataType:'json',
		type:'POST',
		success: function(data){
			test=data;
		},
		error:function(e){
			//alert(e);
		}
	}); 
	if(test){return test};
}
//时间截取
function unixtime(datestr){	
	var arr = datestr.split("-");
	return ux = Date.UTC(arr[0],arr[1]-1,arr[2],0,0,0)/1000;		
}
//时间转换
function getLocalTime(nS) {     
	var dd= new Date(parseInt(nS) * 1000).toLocaleString().replace(/星|期/, " ").replace(/星/, " ");
	dd = 18 < dd.length ? dd.substr(0,10):dd.substr(0,11);
	dd = dd.replace(/年/, "-").replace(/月/,"-").replace(/日/,"");
	return dd;
}
	  
function formatDate(xtume){     
	var d=new Date(parseInt(xtume) * 1000);
	var year=d.getFullYear();   
	var month=d.getMonth()+1;     
	var date=d.getDate();     
	var hour=d.getHours();     
	var minute=d.getMinutes();     
	var second=d.getSeconds();  
	if(date<10) date="0"+date;
	if(month<10) month="0"+month;
	
	return year+"-"+month+"-"+date;     
} 	
		  

function js_strto_time(str_time){
    var arr = str_time.split("-");
    var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
    return strtotime = datum.getTime()/1000;
}

function js_date_time(unixtime) {
    var timestr = new Date(parseInt(unixtime) * 1000);
    var datetime = timestr.toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
    return datetime;
}
function a(){}
function isNum(v){
	// var reg = /^[0-9]+\.?[0-9]{0,9}$/;
	var reg = /^-?[1-9]+(\.\d+)?$|^-?0(\.\d+)?$|^-?[1-9]+[0-9]*(\.\d+)?$/; 
	 if(v.val()!=""){
		if( reg.test(v.val()))
	{
	return true;
	}else{
	v.val('');
	return false;
	}
	 }
}
function noZ(v){
	 var reg = /^[1-9]d*|0$/;
	 if(v.val()!=""){
		if(reg.test(v.val()))
	{
	return true;
	}else{
	v.val('');
	return false;
	}
	}
}
function noF(v){
	 var reg = /^-([0-9]+\.?[0-9]{0,9})$/;
	//var reg = /^-([1-9]d*.d*|0.d*[1-9]d*)$/; 
	 if(v.val()!=""){
		if(!reg.test(v.val()))
	{
	return true;
	}else{
	v.val('');
	return false;
	}
	}
}
function inputValue(obj){
	var s='';  
			 for(var i=0; i<obj.length; i++){  
						if(obj[i].checked) s+=','+obj[i].value;  //如果选中，将value添加到变量s中     
					  } 
	return s.substr(1);				  
}
function strlen(str){  
   var len = 0;  
    for (var i=0; i<str.length; i++) {   
     var c = str.charCodeAt(i);   
     if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {   
       len++;   
    }   
     else {   
      len+=2;   
     }   
    }   
    return len;  
}
 
function parseParam(url, k){
	var loc = String(url);
	var pieces = loc.substr(loc.indexOf('?') + 1).split('&');
	var params = {};
    
    params.keys=[];
	for (var i = 0; i < pieces.length; i+=1){
	    var keyVal = pieces[i].split('=');
	    params[keyVal[0]] = decodeURIComponent(keyVal[1]);
	    params.keys.push(keyVal[0]);
	}
    return params[k];
}