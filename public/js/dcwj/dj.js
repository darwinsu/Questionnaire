//autoComplete	 contains
$.expr[":"].econtains=function(obj,index,meta,stack){
	return (obj.textContent||obj.innerText||$(obj).text()||'').toLowerCase()==meta[3].toLowerCase();
}

function showInfo(val,del){
	var tbody='';
	var tfoot='';
	var tfoot_num=1;
	if(val.result==false){
		$('#tbody').empty();
		if(page=='index'){
			$("#tfoot").empty().html('<td colspan="7">'+val.menu+'</td>');
		}else{
			$("#tfoot").empty().html('<td colspan="7">'+val.menu+'</td>');
		}
		return false;
	}
	
	switch(page){
		case 'my':	
			tfoot_num=7;
			for(var i=0;i<val.data.length;i++){
				tbody+='<tr>';
				if(rights==3&&val.data[i]['uid']!=userID&&val.data[i]['wuid']!=userID){
					tbody+='<td><span class="wj_t">'+val.data[i]['q_title']+'</span></td>';			
				}else{
					tbody+='<td><span class="wj_t"><a title="'+val.data[i]['q_title']+'" href="'+urk+'Dj/djinfo/djid/'+val.data[i]['djid']+'/">'+val.data[i]['q_title']+'</a></span></td>';
				}
				
				tbody+='<td>'+((val.data[i]['is_anonymous']>0)?'匿名[guest]':val.data[i]['c_user'])+'</td>'
				+'<td>'+val.data[i]['dj_start_time']+'</td>'
				+'<td>'+time_To_hhmmss(val.data[i]['dj_time_consuming'])+'</td>'
				+'<td>'+((val.data[i]['dj_zf']>=0)?val.data[i]['dj_zf']:'未评分')+'</td>'
				+'<td>'+((val.data[i]['dj_pm'])?val.data[i]['dj_pm']:'无排名')+'</td>';
				if(rights=='1'||rights=='2'){
					tfoot_num=6;
				}else{
					if(rights==3&&val.data[i]['uid']==userID&&val.data[i]['wuid']!=userID){
						tbody+='<td>&nbsp;</td>';			
					}else{
						tbody+='<td><div><a class="del" href="#"  onclick="del(\'t=1\',\'id='+val.data[i]['djid']+'\',\'pageno='+val.pageno+'\');">删除</a></div></td>';
					}
				}
				+'</tr>';
			}
		break;

	}
	tfoot+='<tr><td colspan="'+tfoot_num+'">'+val.menu;
	tfoot+=(val.conditions_item)?'<input id="search_item_hide" type="hidden" name="search_item_hide" value="'+val.conditions_item[0]+'" /><input id="search_val_hide" type="hidden" name="search_val" value="'+val.conditions_val[0]+'" />':'';
	tfoot+='</td></tr>';

	$("#tfoot").empty().html(tfoot);
	$("#tbody").empty().html(tbody);
}


//刷页面
function getPage(isajax,url,state)
{
	if(isajax=='1')
	{
		var now=new Date().getTime();
		//搜索条件
		var conditions=($('#search_item_hide').length>0&&$('#search_item_hide').val())?'&'+$('#search_item_hide').val()+'='+$('#search_val_hide').val():'';
		var state=(state)?'&state=del':'';
		$.get(url+"&v="+now+conditions+state,function(data){
			var temp=eval('('+data+')');
			(state)?showInfo(temp,'del'):showInfo(temp,'');
		});
	}
	else
	{
		window.location.href=url;
	}
}

//分页调整
function set_page(){
	var temp_page;
	temp_page=parseInt($('#page_count').text());
	if(temp_page>9){
		var temp_pre;
		temp_pre=Math.ceil(temp_page/9)-1;
		if((temp_page%9)==0){//往前一页面
			var str;
			str='#pageno_'+temp_pre;
			$(str).trigger('click');
		}
	}else if(temp_page==9){
		$('.page_first').trigger('click');
	}
}
//分秒转换
function time_To_hhmmss(seconds){
    var hh;
    var mm;
    var ss;
    //传入的时间为空或小于0
    if(seconds==null||seconds<0){
        return;
    }
    //得到小时
    hh=seconds/3600|0;
    seconds=parseInt(seconds)-hh*3600;
    if(parseInt(hh)<10){
           hh="0"+hh;
    }
    //得到分
    mm=seconds/60|0;
    //得到秒
    ss=parseInt(seconds)-mm*60;
    if(parseInt(mm)<10){
          mm="0"+mm;    
   }
    if(ss<10){
        ss="0"+ss;      
   }
   if(parseInt(hh)>0) {hh=hh+'小时';}else{hh='';}
   if(parseInt(mm)>0) {mm=mm+'分';}else{mm='';}
   if(parseInt(ss)>0) {ss=ss+'秒';}else{ss='';}
    return hh+mm+ss;
    
}