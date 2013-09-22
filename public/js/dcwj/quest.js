//autoComplete	 contains
$.expr[":"].econtains=function(obj,index,meta,stack){
	return (obj.textContent||obj.innerText||$(obj).text()||'').toLowerCase()==meta[3].toLowerCase();
}

function getdom(){
	return $(window.parent.document);
}

function showInfo(val,del){
	var tbody='';
	var tfoot='';
	var tfoot_num=1;
	$("#tthead").show();
	if(val.result==false){
		$('#tbody').empty();
		switch(page)
		{
			case 'index':
			case 'draft':
			case 'recycle':
			case 'sytle':
				tfoot_num=3;break;
			case 'subType':
				tfoot_num=3;break;
			case 'subject':
				tfoot_num=6;break;
		}
		$("#tbody").empty().formhtml('<tr><td colspan="'+tfoot_num+'">暂无数据</td></tr>');
		$("#tfoot").empty().formhtml('<tr><td colspan="'+tfoot_num+'">'+val.menu+'</td></tr>');
		return false;
	}
	
	switch(page){
		case 'index':
            tfoot_num=7;
            for(var i=0;i<val.data.length;i++){
                tbody+= '<tr class="pm_'+val.data[i]['id']+'"><td>';
                tbody+= '<div class="quest_title">'
                    +'<span class="left">'+'<input type="checkbox" name="select['+val.data[i]['id']+']" id="Sid" value="'+val.data[i]['id']+'">'+val.data[i]['q_title']+'</span>'
                    +'<span class="right"><span class="status">'+((val.data[i]['nowtime']>(Number(val.data[i]['q_start'])))?((val.data[i]['nowtime']<=(Number(val.data[i]['q_end'])+(3600*24)))?'运行中':'结束'):'未开始')+' </span> <span class="creator"> &nbsp;创建者：'+val.data[i]['c_user']+'</span>'+'<span class="num">&nbsp;答卷数：0  </span> <span class="time">&nbsp;截止时间：'+getLocalTime(val.data[i]['q_end'])+'</span>'+' </span>'
                    +'</div>'
                    +'<div class="quest_control">'
                    +'<span class="left"><span class="pz" onclick="edit(\'id='+val.data[i]['id']+'\',\''+val.data[i]['fk_sytle_id']+'\',\'.pm_'+val.data[i]['id']+'\',\'t=1\',\'id='+val.data[i]['id']+'&t=1\',\'pageno='+val.pageno+'\');pass_show();">编辑问卷</span> <a href="./Quest/subject?questid='+val.data[i]['id']+'">设计问卷</a> <span class="wjkk" onclick="survey(\''+val.data[i]['id']+'\');">问卷概况</span> <span class="wjyl" onclick="preview(\''+val.data[i]['id']+'\');">问卷预览</span> <a href="./report/index/wjid/'+val.data[i]['id']+'/" class="fx">分析</a></span>'
                    +'<span class="right"><a href="./Dj/answers/wjid/'+val.data[i]['id']+'/" class="dj">答卷</a> <span class="copydj" onclick="copy(\''+val.data[i]['id']+'\');">复制</span> <span class="deletedj" onclick="del(\'t=1\',\''+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');">删除</span></span>'
                    +'</div></td></tr>';
            }
			break;
		case 'draft':
            tfoot_num=7;
            for(var i=0;i<val.data.length;i++){
                tbody+= '<tr class="pm_'+val.data[i]['id']+'"><td>';
                tbody+= '<div class="quest_title">'
                    +'<span class="left">'+val.data[i]['q_title']+'</span>'
                    +'<span class="right"><span class="status">'+((val.data[i]['nowtime']>(Number(val.data[i]['q_start'])))?((val.data[i]['nowtime']<=(Number(val.data[i]['q_end'])+(3600*24)))?'运行中':'结束'):'未开始')+' </span> <span class="creator"> &nbsp;创建者：'+val.data[i]['c_user']+'</span>'+'<span class="num">&nbsp;答卷数：0  </span> <span class="time">&nbsp;截止时间：'+getLocalTime(val.data[i]['q_end'])+'</span>'+' </span>'
                    +'</div>'
                    +'<div class="quest_control">'
                    +'<span class="left"><span onclick="edit(\'id='+val.data[i]['id']+'\',\''+val.data[i]['fk_sytle_id']+'\',\'.pm_'+val.data[i]['id']+'\',\'t=1\',\'id='+val.data[i]['id']+'&t=1\',\'pageno='+val.pageno+'\');pass_show();">编辑问卷</span> <a href="./Quest/subject?questid='+val.data[i]['id']+'">设计问卷</a> <span onclick="survey(\''+val.data[i]['id']+'\');">问卷概况</span> <span onclick="preview(\''+val.data[i]['id']+'\');">问卷预览</span> <a href="./report/index/wjid/'+val.data[i]['id']+'/" class="fx">分析</a></span>'
                    +'<span class="right">停止 <a href="./Dj/answers/wjid/'+val.data[i]['id']+'/" class="dj">答卷</a> <span onclick="copy(\''+val.data[i]['id']+'\');">复制</span> <span onclick="del(\'t=1\',\''+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');">删除</span></span>'
                    +'</div></td></tr>';

            }
            break;
		case 'recycle':
            tfoot_num=7;
            for(var i=0;i<val.data.length;i++){
                tbody+= '<tr class="pm_'+val.data[i]['id']+'"><td>';
                tbody+= '<div class="quest_title">'
                    +'<span class="left">'+'<input type="checkbox" name="select['+val.data[i]['id']+']" id="Sid" value="'+val.data[i]['id']+'">'+val.data[i]['q_title']+'</span>'
                    +'<span class="right"><span class="status">'+((val.data[i]['nowtime']>(Number(val.data[i]['q_start'])))?((val.data[i]['nowtime']<=(Number(val.data[i]['q_end'])+(3600*24)))?'运行中':'结束'):'未开始')+' </span> <span class="creator"> &nbsp;创建者：'+val.data[i]['c_user']+'</span>'+'<span class="num">&nbsp;答卷数：0  </span> <span class="time">&nbsp;截止时间：'+getLocalTime(val.data[i]['q_end'])+'</span>'+' </span>'
                    +'</div>'
                    +'<div class="quest_control">'
                    +'<span class="left"><span class="pz" onclick="edit(\'id='+val.data[i]['id']+'\',\''+val.data[i]['fk_sytle_id']+'\',\'.pm_'+val.data[i]['id']+'\',\'t=1\',\'id='+val.data[i]['id']+'&t=1\',\'pageno='+val.pageno+'\');pass_show();">配置问卷</span> <a href="./Quest/subject?questid='+val.data[i]['id']+'">设计问卷</a> <span class="wjkk" onclick="survey(\''+val.data[i]['id']+'\');">问卷概况</span> <span class="wjyl" onclick="preview(\''+val.data[i]['id']+'\');">问卷预览</span> <a href="./report/index/wjid/'+val.data[i]['id']+'/" class="fx">分析</a></span>'
                    +'<span class="right"><a href="./Dj/answers/wjid/'+val.data[i]['id']+'/" class="dj">答卷</a> <span class="copydj" onclick="copy(\''+val.data[i]['id']+'\');">复制</span> <span class="deletedj" onclick="del(\'t=1\',\''+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');">删除</span></span>'
                    +'</div></td></tr>';
            }
            break;
		case 'sytle':	
			tfoot_num=3;
			for(var i=0;i<val.data.length;i++){
			tbody+='<tr class="pm_'+val.data[i]['id']+'">'
			+'<td>'+val.data[i]['id']+'</td>'
			+'<td>'+val.data[i]['name']+'</td>';
			if(rights==2||rights==1){
			tbody+='<td>&nbsp;</td>';	
			}else{
			tbody+='<td><div><a class="edit" title="编辑" href="#"  onclick="edit(\'id='+val.data[i]['id']+'\',\''+val.data[i]['name']+'\',\'.pm_'+val.data[i]['id']+'\',\'t=1\',\'id='+val.data[i]['id']+'&t=1\',\'pageno='+val.pageno+'\');"></a><a class="del" title="删除" href="#"  onclick="delSytle(\'t=1\',\'id='+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');sytle_show();"></a></div></td>'
			+'</tr>';
			}
			}
			break;
		case 'type':	
			for(var i=0;i<val.data.length;i++){
			tbody+='<tr class="pm_'+val.data[i]['id']+'">'
			+'<td>'+val.data[i]['id']+'</td>'
			+'<td>'+val.data[i]['name']+'</td>'
			+'<td><a title="编辑" href="#"  onclick="edit(\'id='+val.data[i]['id']+'\',\''+val.data[i]['name']+'\',\'.pm_'+val.data[i]['id']+'\',\'t=2\',\'id='+val.data[i]['id']+'&t=2\',\'pageno='+val.pageno+'\');">编辑</a> | <a title="删除"  href="#"  onclick="delSytle(\'t=2\',\'id='+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');sytle_show();">删除</a></td>'
			+'</tr>';
			}
			break;
		case 'subType':
			for(var i=0;i<val.data.length;i++){
				tbody+='<tr class="pm_'+val.data[i]['id']+'">'
					+'<td>'+val.data[i]['id']+'</td>'
					+'<td>'+val.data[i]['name']+'</td>'
					+'<td><a href="#"  onclick="edit(\'id='+val.data[i]['id']+'\',\''+val.data[i]['name']+'\',\'.pm_'+val.data[i]['id']+'\',\'t=3\',\'id='+val.data[i]['id']+'&t=3\',\'pageno='+val.pageno+'\');">编辑</a> | <a href="#"  onclick="delSytle(\'t=3\',\'id='+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');sytle_show();">删除</a></td>'
					+'</tr>';
			}
			break;
		case 'subject':	
			tfoot_num=6;
			for(var i=0;i<val.data.length;i++){
				tbody+='<tr class="pm_'+val.data[i]['id']+'">'
					+'<td><input type="checkbox" name="select['+val.data[i]['id']+']" id="Sid" value="'+val.data[i]['id']+'"></td>'
					+'<td>'+val.data[i]['title_id']+'</td>'
					+'<td><div class="subject_t">'+val.data[i]['s_title']+'</div></td>'
					+'<td>'+eval("ss_type_array["+val.data[i]['fk_type_id']+"]")+'</td>'
					+'<td>'+val.data[i]['s_order']+'</td>';
				if(rights==2||rights==1){
					tbody+='<td>&nbsp;</td>';
				}else{
					tbody+='<td><div><a href="##" class="choose" onclick="setings(\''+val.data[i]['id']+'\',\''+eval("ss_type_array["+val.data[i]['fk_type_id']+"]")+'\');">选项</a><a href="#" class="edit" onclick="edit(\'id='+val.data[i]['id']+'\',\''+val.data[i]['fk_type_id']+'\',\'.pm_'+val.data[i]['id']+'\',\'t=2\',\'id='+val.data[i]['id']+'&t=2\',\'pageno='+val.pageno+'\');">编辑</a><a class="del" href="#"  onclick="del(\'t=2\',\'id='+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');sytle_show();">删除</a></div></td>';
				}
				tbody+='</tr>';
			}
			break;
	}
	tfoot+='<tr><td colspan="'+tfoot_num+'">'+val.menu;
	tfoot+=(val.conditions_item)?'<input id="search_item_hide" type="hidden" name="search_item_hide" value="'+val.conditions_item[0]+'" /><input id="search_val_hide" type="hidden" name="search_val" value="'+val.conditions_val[0]+'" />':'';
	tfoot+='</td></tr>';
	$("#tfoot").empty().formhtml(tfoot);
	$("#tbody").empty().formhtml(tbody);
}

//刷页面
function getPage(isajax,url,state)
{
    pageno = parseParam(url, 'pageno')
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

function subjectShow(val, subject_id){
	var the_q_title = $('.pm_'+subject_id+' .subject_t').html(),
	 tbody = '<tr class="tr_nav"><td style="background:#ddd">问卷题目</td><td colspan="4" id="the_q_title">'+the_q_title+'</td></tr>'
		+'<tr class="tr_nav" style="style="background:#C6E1FD""><td>编号</td><td>选项标题</td><td>选项分值</td><td>排序号</td><td>操作</td></tr>',
	tfoot='';
	getdom().find("#sbodys #querybox_sub").show();
	if(val.result==false){
		getdom().find('#sbody').empty();
		getdom().find('#sbodys #sbody').empty();
		getdom().find("#sbody").empty().formhtml(tbody);		
		getdom().find("#sbodys #sbody").empty().formhtml(tbody);	
	}else{
		for(var i=0;i<val.data.length;i++){
			tbody+='<tr class="pm_'+val.data[i]['id']+'">'
				+'<td>'+val.data[i]['id']+'</td>'
				+'<td>'+val.data[i]['s_answer']+'</td>'
				+'<td>'+val.data[i]['s_value']+'</td>'
				+'<td>'+val.data[i]['s_order']+'</td>'
				+'<td><span style="cursor:pointer"  onclick="mainFrame.sub_edit(\'id='+val.data[i]['id']+'&t=3\');">编辑</span> | <span style="cursor:pointer" onclick="mainFrame.del(\'t=3\',\'id='+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');">删除</span></td>'
			+'</tr>';
		}
		getdom().find("#sfoot").empty().formhtml(tfoot);
		getdom().find("#sbody").empty().formhtml(tbody);		  
		getdom().find("#sbodys #sfoot").empty().formhtml(tfoot);
		getdom().find("#sbodys #sbody").empty().formhtml(tbody);
	}
}
//概述编辑
function desc_edit(id){
	$('[name="descs"]:radio').each(function() { if (this.value == '1') { this.checked = true;} });
	var val=do_ajax('getData','t=1&id='+id);
	$('#q_top_desc').val(val.data[0]['q_top_desc']);
	$('#q_foot_desc').val(val.data[0]['q_foot_desc']);
	$('#desc_edits #quest_id').val(id);
	$('#desc_edits #desc_top').show();
	$('#desc_edits #desc_foot').hide();
	$('#desc_edits').show();
	$('#out_context').hide();
}
//选项数据
function setings(id,set_type,conditions){
	//设置当前题目ID
	$("#span_sub #fk_subject_id").val(id);
	$("#querybox_sub").show();
	$("#sbodys #querybox_sub").show();
	if(set_type=='简答题'){
		$("#querybox_sub").hide();
		$("#sbodys #querybox_sub").hide();
		var  val=do_ajax('getOneData','t=3&fk_subject_id='+id);
		var tsbody='';
		var checked1='';var checked2='';var checked3='';
		if(val.data[0]['s_type']==1){checked1='checked';}
		if(val.data[0]['s_type']==2){checked2='checked';}
		if(val.data[0]['s_type']==3){checked3='checked';}
		tsbody+='<tr><td>标题<span class="fontred">*</span></td><td><textarea name="s_answer" id="s_answer" cols="60" rows="5">'+val.data[0]['s_answer']+'</textarea><br>(限制250字)</td></tr>';
		tsbody+='</tr><tr><td>排序号<span class="fontred">*</span></td><td><input type="text" size="3" id="s_order" name="s_order" value="'+val.data[0]['s_order']+'"  onblur="mainFrame.noZ($(\'#sbodys #s_order\'))" />(非负数)&nbsp;在同一页中号越小越靠前</td></tr>';
		tsbody+='<tr><td>值类型<span class="fontred">*</span></td><td><input type="radio" id="s_type" name="s_type" value="1" '+checked1+'>字符&nbsp;<input type="radio" id="s_type" name="s_type" value="2" '+checked2+'>数字&nbsp;<input type="radio" id="s_type" name="s_type" value="3" '+checked3+'>日期&nbsp;</td></tr>';
		if(!val.data[0]['s_len']) val.data[0]['s_len']=0;
		tsbody+='<tr><td>数据长度</td><td><input type="text" size="3" id="s_len" name="s_len" value="'+val.data[0]['s_len']+'"></td></tr>';
		if(!val.data[0]['s_value']) val.data[0]['s_value']=0;
		tsbody+='<tr><td>分值<span class="fontred">*</span></td>	<td><input type="text" size="3" id="s_value" name="s_value" value="'+val.data[0]['s_value']+'">&nbsp;</td></tr>';
		tsbody+='<tr><td colspan="2" id="save_tag"><input id="fk_subject_id" type="hidden"  name="fk_subject_id" value="'+val.data[0]['id']+'" /><input id="short_edit_save" class="isearcher_submit_button" type="button" value="保 存"/>&nbsp;<input id="short_edit_back" class="isearcher_submit_button" type="button" value="返 回"/></td></tr>';
		$("#sbody").empty().formhtml(tsbody);
		$("#sbodys #sbody").empty().formhtml(tsbody);
	}else{
		var val = do_ajax('getData','t=3&fk_subject_id='+id),
		tfoot = '',
		the_q_title = $('.pm_'+id+' .subject_t').html(),
		tbody = '<tr class="tr_nav"><td style="backface:#ddd">问卷题目</td><td colspan="4" id="the_q_title">'+the_q_title+'</td></tr>'
			+'<tr class="tr_nav" style="background:#C6E1FD"><td>编号</td><td>选项</td><td>分值</td><td>排序号</td><td>操作</td></tr>';
		$("#sbodys #querybox_sub").show();
		$('#querybox_sub #subject_add').attr('data', the_q_title);
		if(val.result==false){
			$('#sbody').empty();
			$('#sbodys #sbody').empty();
			$("#sbody").empty().formhtml(tbody);		
			$("#sbodys #sbody").empty().formhtml(tbody);	
		}else{
			for(var i=0;i<val.data.length;i++){
				tbody+='<tr class="pm_'+val.data[i]['id']+'">'
				+'<td>'+val.data[i]['id']+'</td>'
				+'<td>'+val.data[i]['s_answer']+'</td>'
				+'<td>'+val.data[i]['s_value']+'</td>'
				+'<td>'+val.data[i]['s_order']+'</td>'
				+'<td><span style="cursor:pointer"  onclick="mainFrame.sub_edit(\'id='+val.data[i]['id']+'&t=3\');">编辑</span> | <span  onclick="mainFrame.del(\'t=3\',\'id='+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');"  style="cursor:pointer">删除</span></td>'
				+'</tr>';
			}
			$("#sfoot").empty().formhtml(tfoot);
			$("#sbody").empty().formhtml(tbody);		  
			$("#sbodys #sfoot").empty().formhtml(tfoot);
			$("#sbodys #sbody").empty().formhtml(tbody);
		}

	}
	parent.asyncbox.open({
		title  : '选项编辑',
		id:'sbodys',
		html:$("#subject_show").formhtml()
	});		
}

//简答题
function edit_Set_Short(conditions){
	getdom().find("#sbodys #querybox_sub").hide();
	var  val=do_ajax('getOneData',conditions);
	var tsbody='';
	var checked1='';var checked2='';var checked3='';
	if(val.data[0]['s_type']==1){checked1='checked';}
	if(val.data[0]['s_type']==2){checked2='checked';}
	if(val.data[0]['s_type']==3){checked3='checked';}
	tsbody+='<tr><td>标题<span class="fontred">*</span></td><td><textarea name="s_answer" id="s_answer" cols="60" rows="5">'+val.data[0]['s_answer']+'</textarea><br>(限制250字)</td></tr>';
	tsbody+='</tr><tr><td>排序号<span class="fontred">*</span></td><td><input type="text" size="3" id="s_order" name="s_order" value="'+val.data[0]['s_order']+'"  onblur="mainFrame.noZ($(\'#sbodys #s_order\'))" />(非负数)&nbsp;在同一页中号越小越靠前</td></tr>';
	tsbody+='<tr><td>值类型<span class="fontred">*</span></td><td><input type="radio" id="s_type" name="s_type" value="1" '+checked1+'>字符&nbsp;<input type="radio" id="s_type" name="s_type" value="2" '+checked2+'>数字&nbsp;<input type="radio" id="s_type" name="s_type" value="3" '+checked3+'>日期&nbsp;</td></tr>';
	if(!val.data[0]['s_len']) val.data[0]['s_len']=0;
	tsbody+='<tr><td>数据长度</td><td><input type="text" size="3" id="s_len" name="s_len" value="'+val.data[0]['s_len']+'"></td></tr>';
	if(!val.data[0]['s_value']) val.data[0]['s_value']=0;
	tsbody+='<tr><td>选项分值<span class="fontred">*</span></td>	<td><input type="text" size="3" id="s_value" name="s_value" value="'+val.data[0]['s_value']+'">&nbsp;</td></tr>';
	tsbody+='<tr><td colspan="2" id="save_tag"><input id="fk_subject_id" type="hidden"  name="fk_subject_id" value="'+val.data[0]['id']+'" /><input id="short_edit_save" class="isearcher_submit_button" type="button" value="保 存"/>&nbsp;<input id="short_edit_back" class="isearcher_submit_button" type="button" value="返 回"/></td></tr>';
	getdom().find("#sbody").empty().formhtml(tsbody);
	getdom().find("#sbodys #sbody").empty().formhtml(tsbody);
}

//概况

function survey(id){
    var ttitle='概况';
    var tcontent='#sedit';
    var tart_class='edit';
    var  val=do_ajax('getData','t=1&id='+id);
    var tsbody='<tr><th class="pop_title" colspan="2"><div id="pop_title">问卷概况</div></th></tr>';
    tsbody+='<tr><td>答卷地址</td><td><input type="text" size="28" id="dj_url" name="dj_url" value="'+val.data[0]['djurl']+'/">&nbsp;&nbsp;<input id="myfuzhi" class="isearcher_submit_button" type="button" data-clipboard-target="dj_url" name="fzx" value="复 制"/></td></tr>';
    tsbody+='<tr><td>问卷标题</td><td>'+val.data[0]['q_title']+'</td></tr>';
    tsbody+='<tr><td>问卷类型</td><td>'+eval("type_array["+val.data[0]['fk_type_id']+"]")+'</td></tr><tr><td>添加人</td><td>'+val.data[0]['c_user']+'</td></tr>';
    tsbody+='<tr><td>添加时间</td><td>'+getLocalTime(val.data[0]['c_time'])+'</td></tr>';
    tsbody+='<tr><td>起止时间</td><td>'+getLocalTime(val.data[0]['q_start'])+'至'+getLocalTime(val.data[0]['q_end'])+'</td></tr>';
    tsbody+='<tr><td>收集样本数</td><td>'+val.data[0]['dj_num']+'</td></tr>';
    $("#sbody").empty().html(tsbody);
    $("#sbodys #sbody").empty().html(tsbody);
    parent.asyncbox.open({
        title  : ttitle,
        id: 'sbodys',
        html:$(tcontent).formhtml()
    });
}


//选项数据编辑
function sub_edit(conditions){	
	var  val=do_ajax('getOneData',conditions),
	s_title = getdom().find("#sbodys #sbody #the_q_title").html();
	edit_Set(val, s_title);
}
function edit_Set(val, the_q_title){
	getdom().find("#sbodys #querybox_sub").hide();
	getdom().find('#sbodys #sbody').empty();
	var tsbody='<tr><td style="background:#ddd">问卷题目</td><td>'+the_q_title+'</td></tr>',
	checked='';
	if(val)
	{
		if(val.data[0]['s_replenish']==1) checked='checked';
		tsbody +='<tr><td width="100">选项<span class="fontred">*</span></td><td><textarea name="s_answer" id="s_answer" cols="60" rows="2">'+val.data[0]['s_answer']+'</textarea><br>(限制500字)</td></tr><tr><td>上传图片<input type="hidden" id="s_url" name="s_url" value="" ></td><td id="cc_url">&nbsp;</td></tr><tr><td>排序号<span class="fontred">*</span></td><td><input type="text" size="3" id="s_order" name="s_order" value="'+val.data[0]['s_order']+'" onblur="mainFrame.noZ($(\'#sbodys #s_order\'))" />(非负数)&nbsp;在同一页中号越小越靠前</td></tr><tr><td>分值<span class="fontred">*</span></td>	<td><input type="text" size="3" id="s_value" name="s_value" value="'+val.data[0]['s_value']+'" >&nbsp;</td></tr><tr><td>补充</td><td><input type="checkbox" size="3" id="s_replenish" name="s_replenish" value="1" '+checked+'> 说明：答卷用户选中该项，出现文本框让答卷用户补充填写</td></tr><tr><td colspan="2" id="save_tag"><input id="subjesct_id" type="hidden"  name="subjesct_id" value="'+val.data[0]['id']+'" /><input id="sub_edit_save" onclick="mainFrame.sub_edit_save();" class="isearcher_submit_button" type="button" value="保 存"/>&nbsp;<input id="sub_edit_back" onclick="mainFrame.subject_show();" class="isearcher_submit_button" type="button" value="返 回"/></td></tr>';
	}else{
		tsbody +='<tr><td>选项<span class="fontred">*</span></td><td><textarea name="s_answer" id="s_answer" cols="60" rows="2"> </textarea><br>(限制500字)</td></tr><tr><td>上传图片<input type="hidden" id="s_url" name="s_url" value="" ></td><td id="cc_url">&nbsp;</td></tr><tr><td>排序号<span class="fontred">*</span></td><td><input type="text" size="3" id="s_order" name="s_order" value=""  onblur="mainFrame.noZ($(\'#sbodys #s_order\'))" />(非负数)&nbsp;在同一页中号越小越靠前</td></tr><tr><td>分值<span class="fontred">*</span></td>	<td><input type="text" size="3" id="s_value" name="s_value" value="" >&nbsp;</td></tr><tr><td>补充</td><td><input type="checkbox" size="3" id="s_replenish" name="s_replenish" value="1"> 说明：答卷用户选中该项，出现文本框让答卷用户补充填写</td></tr><tr><td colspan="2" id="save_tag"><input onclick="mainFrame.sub_add_saves()" id="sub_add_saves" class="isearcher_submit_button" type="button" value="保存并继续">&nbsp;<input onclick="mainFrame.sub_add_save();" id="sub_add_save" class="isearcher_submit_button" type="button" value="保存并关闭"/>&nbsp;<input id="sub_edit_back" onclick="mainFrame.subject_show();" class="isearcher_submit_button" type="button" value="返 回"/></td></tr>';
	}
	getdom().find("#sbodys #sbody").empty().formhtml(tsbody);
	try{parent.upfile("#sbodys #cc_url","#sbodys #s_url_name","#sbodys #s_url",'#sbodys #s_url_name');}catch(e){}
	if(val&&val.data[0]['s_url']) getdom().find('#sbodys #fileQueue').formhtml('<img  width="80" src="../public/'+val.data[0]['s_url']+'">');
}

//复制
function copy(id){
	var tsbody='';
	var ttitle='复制问卷';
	var tcontent='#sedit';
	var tart_class='edit';
	tsbody='<tr><th class="pop_title" colspan="2"><div id="pop_title">复制问卷</div></th></tr>';
	tsbody+='<tr><td>请输入新问卷标题<span class="fontred">*</span></td><td><input type="text" id="new_title" name="new_title" value=""><input type="hidden" id="copy_id" name="copy_id" value="'+id+'" ></td></tr>';
	tsbody+='<tr><td class="ctl_btn" colspan="2"><input onclick="mainFrame.copy_save();" class="isearcher_submit_button" type="button" value="复制并关闭"/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="q_back" class="isearcher_submit_button" type="button" value="取消"/</td></tr>';
	
	$('#sbody').empty().formhtml(tsbody);
	$("#sbodys #sbody").empty().formhtml(tsbody);
	parent.asyncbox.open({
		title  : ttitle,
		id: 'sbodys',
		html:$(tcontent).formhtml()
	});		
}
//编辑数据
function edit(id,name,target,t,conditions,pageno){
	var tbody='';
  	var tfoot='';
	var ttitle='';
	var tcontent='';
	var tart_class='';
	switch(page){
		case 'index':
		case 'draft':
			ttitle='编辑问卷';
			tcontent='#edit';
			tart_class='edit';
           var tempId = id.substring(3);
            //然后调用Participants/getData?qid=XXXXX  接口  获得该问卷已参与问卷的人
            $.ajax({
                type:"post",
                url:"../Participants/getData",
                data:{
                    //从参数中获取问卷id
                    qid:tempId
                },
                dataType: 'json',
                success:function(data){
                    //保存已参与人员的全局变量，先清空后放入
                    selector.personel=[];
                    if(data.arr_2&&data.arr_2!=null){
                        var obj = {};
                        obj.userName = data.arr_2.username||[];
                        obj.deptId = data.arr_2.deptid||[];
                        obj.uId = data.arr_2.uid||[];
                        for(var i= 0;i<obj.userName.length;i++){
                            var oo = {"username":obj.userName[i],"deptid":obj.deptId[i],"uid":obj.uId[i]};
                            selector.personel.push(oo);
                        }
                    }
                }
            });
            var  val=do_ajax('getOneData',conditions);
			$('#ebody #pop_title').html(ttitle);
			$('#ebody #q_title').val(val.data[0]['q_title']);
			$('#ebody #q_start').val(formatDate(val.data[0]['q_start']));
			$('#ebody #q_end').val(formatDate(val.data[0]['q_end']));
			$('#ebody #duration').val(val.data[0]['duration']); 	 
			$('#ebody #fk_sytle_id').val(val.data[0]['fk_sytle_id']);
			$('input[name=fk_type_id][value='+val.data[0]['fk_type_id']+']').attr("checked",true); 
			$('input[name=pass_type][value='+val.data[0]['pass_type']+']').attr("checked",true);
			$('#ebody #pass').val(val.data[0]['pass']); 	 
			$('input[name=q_login][value='+val.data[0]['q_login']+']').attr("checked",true);
			$('input[name=q_anonymous][value='+val.data[0]['q_anonymous']+']').attr("checked",true);
			$('input[name=q_repeat][value='+val.data[0]['q_repeat']+']').attr("checked",true);
			$('#ebody #quest_id').val(val.data[0]['id']);
			$("#save_tag").empty().formhtml('<input id="q_edit_save" class="isearcher_submit_button" type="button" value="保 存"/>');
			parent.asyncbox.open({
				title  : ttitle,
				id: 'ebodys',
				html:$(tcontent).formhtml()
			});
            $('#selectParticp', window.parent.document).bind('click',function() {
                parent.asyncbox.open({
                    title:'人员选择器',
                    id: 'test',
                    html:$("#selectParcpDiv").formhtml()
                });
                $("#particpUl").html("");
               //初始化右边列表(如果有已选人员),如果是第一次弹出人员选择器，则从全局变量中取数据
                liNodeAdd("particpUl",selector.personel);
                selector.personel=[];
                //变量btreedata,防止重复的ajax
                var btreedata=[];
                var treeData=[];
                var mid=[];
                function onClick(event, treeId, treeNode, clickFlag){
                    if(btreedata[treeNode.id]){
                        //去调用ajax，然后增加节点
                        $.ajax({
                            url:'/Rolemanagement/getDeptMem',
                            type:'get',
                            dataType:'json',
                            data:{deptid:treeNode.id},
                            success:function(data){
                                //console.log(data.rs);
                                if(data.rs===true){
                                    var zTree = $.fn.zTree.getZTreeObj(treeId);
                                    for(var i= 0,l=data.data.length;i<l;i++){
                                        if($.inArray(data.data[i].uid,mid)==-1){
                                            zTree.addNodes(treeNode,{id:data.data[i].uid,pId:treeNode.id,isParent:false,name:data.data[i].username});
                                        }
                                        //zTree.addNodes(treeNode,{id:data.data[i].uid,pId:treeNode.id,isParent:false,name:data.data[i].username});
                                    }
                                }
                            }
                        })
                        btreedata[treeNode.id]=false;
                    }
                }
                function onExpand(event, treeId, treeNode){
                    onClick(event, treeId, treeNode);
                }
                function showIconForTree(treeId, treeNode) {
                    return !treeNode.isParent;
                }
                var setting = {
                    edit: {
                        enable: true
                    },
                    data: {
                        simpleData: {
                            enable: true
                        }
                    },
                    view: {
                        showIcon: showIconForTree,
                        selectedMulti: false
                    },
                    callback: {
                        onClick: onClick,
                        onExpand: onExpand
                    }
                };
                $.ajax({
                    type:'get',
                    url:'../Participants/getData',
                    dataType:'json',
                    data:{
                        qid:tempId		//这儿写传进来的id
                    },
                    success:function(data){
                        for(var i= 0,l=data.dept_1.length;i<l;i++){
                            treeData[i]={};
                            treeData[i].id = data.dept_1[i];
                            treeData[i].name = data.dept_3[i];
                            treeData[i].pId = data.dept_2[i];
                            treeData[i].isParent=true;
                            //防止第二次点击时还往里面加数据。
                            btreedata[data.dept_1[i]]=true;
                        }
                        //单位根节点成员
                        for(var i=0,l=data.arr_1.length;i<l;i++){
                            treeData[treeData.length]={};
                            treeData[treeData.length-1].id=data.arr_1["uid"];
                            treeData[treeData.length-1].name=data.arr_1["username"];
                            treeData[treeData.length-1].isParent=false;
                        }
                        mid=data.arr_2.uid;
                        //初始化数据
                        var zNodes=[];
                        //排除相同的数据。
                        for(var i= 0,l=treeData.length;i<l;i++){
                            if($.inArray(treeData[i].id,mid)==-1){
                                zNodes.push(treeData[i]);
                            }
                        }
                        $.fn.zTree.init($("#mytree",window.parent.document), setting, zNodes);
                    }
                });

                //右边ul的li被点击事件处理，添加或去除选中样式
                $(".ul-no-style",window.parent.document).on("click","li",function(){
                    var $this= $(this);
                    if(!$this.hasClass("li-node-select")){
                        $(".ul-no-style",window.parent.document).find(".li-node-select").removeClass("li-node-select");
                        $this.addClass("li-node-select");
                    }
                });
                //添加或取消按钮的事件绑定
                $(".tree-btn-div",window.parent.document).on("click",".tree-btn",function(event){
                    var x = event.target;
                    var id = $(x).attr("id");
                    switch(id){
                        case "particpAdd":
                            //左边树被选中的节点数据保存到selector.addInfo
                            var treeObj= $.fn.zTree.getZTreeObj('mytree',window.parent.document);
                            var node=treeObj.getSelectedNodes()[0]||{};
                            if(!node.isParent){
                                //获得id,pId,name
                                var tempid=node['id'];
                                var temppId=node['pId'];
                                var tempname=node['name'];
                                treeObj.removeNode(node);
                                selector.addInfo.push({'username':tempname,'deptid':temppId,'uid':tempid});
                                //调用liNodeAdd方法把左边树节点移到右边列表
                                liNodeAdd("particpUl",selector.addInfo);
                                //清空selector.addInfo
                                selector.addInfo = [];
                            }
                            break;
                        case "particpCancel":
                            //调用移除右边节点的方法
                            liNodeCancel("particpUl");
                            var treeObj= $.fn.zTree.getZTreeObj('mytree');
                            var tempid=selector.remInfo[0].uId;
                            var temppid=selector.remInfo[0].deptId;
                            var tempname=selector.remInfo[0].userName;
                            var parentNode=treeObj.getNodesByParam("id",temppid)[0]||null;
                            treeObj.addNodes(parentNode,{id:tempid,pId:temppid,isParent:false,name:tempname});
                            //清空保存被删除右边节点的数组
                            selector.remInfo = [];
                            break;
                    }
                });
                //保存已选人员并关闭窗口
                $("#selectorSave",window.parent.document).bind('click',function(){
                    //selector.lastInfo是用于保存最终要提交到后台的数组变量
                    selector.editLastInfo = [];
                    var uids = $("#particpUl",window.parent.document).find(".width-0");
                    for(var i=0;i<uids.length;i++){
                        selector.editLastInfo.push($(uids[i]).text());
                    }
                    //关闭窗口
                    //parent.box_close();
                    $("#test",parent.document).remove();
                    $("#asyncbox_cover",parent.document).remove();
                });
                //取消按钮事件绑定
                $("#selectorCancel",window.parent.document).bind('click',function(){
                    //关闭窗口
                   $("#test",parent.document).remove();
                   $("#asyncbox_cover",parent.document).remove();
                   //parent.box_close();

                });

            });
			break;
		case 'sytle':
		case 'type':
		case 'subType':
			ttitle='信息编辑';
			tcontent='#edit';
			tart_class='edit';
			var  val=do_ajax('getOneSytleData',conditions); 
			tbody+='<tr><th class="pop_title" colspan="2"><div id="pop_title">编辑分类</div></th></tr><tr>'
			+'<td>请输入分类名称<span class="fontred">*</span></td>'
			+'<td><input id="name" type="text" name="name" value="'+val.data[0]['name']+'" /></td>'
			+'</tr>';
			tbody+='<tr>'
			+'<td  class="ctl_btn" colspan="2"><input id="q_edit_save" class="isearcher_submit_button" type="button" value="保 存"/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="q_back" class="isearcher_submit_button" type="button" value="取 消"/><input id="qid" type="hidden" name="qid" value="'+val.data[0]['id']+'" /></td>'
			+'</tr>';
			
			parent.asyncbox.open({
				title  : ttitle,
				id: 'ebodys',
				html:$(tcontent).formhtml()
			});
			$(window.parent.document).find("#ebody").empty().formhtml(tbody);
			//$("#ebodys #ebody").empty().formhtml(tbody);
		break;
		case 'subject':
			ttitle='编辑题目';
			tcontent='#edit';
			tart_class='edit';
			var  val=do_ajax('getOneData',conditions);
			$('#ebody #pop_title').html(ttitle);
			$('#ebody #s_title').val(val.data[0]['s_title']);
			$('#ebody #title_id').val(val.data[0]['title_id']);
			$('#ebody #s_order').val(val.data[0]['s_order']);
			$('#ebody #q_end').val(formatDate(val.data[0]['q_end']));
			$('#ebody #s_url').val(val.data[0]['s_url']);
			$('#ebody #chk_limit').val(val.data[0]['chk_limit']);
            $('input[name=fk_type_id][value='+val.data[0]['fk_type_id']+']').attr("checked",true); 
			$('input[name=s_type][value='+val.data[0]['s_type']+']').attr("checked",true); 
			$('#ebody #q_remark').val(val.data[0]['q_remark']); 	 
			$('#ebody #q_subjesct_id').val(val.data[0]['id']);
			$("#save_tag").empty().formhtml('<input id="q_edit_save" class="isearcher_submit_button" type="button" value="保 存"/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="short_edit_back" class="isearcher_submit_button" type="button" value="取 消"/>'); 
			parent.asyncbox.open({
				title  : ttitle,
				id: 'ebodys',
				html:$(tcontent).formhtml()
			});		
			try{parent.upfile('#ebodys #c_url','#ebodys #s_url_name','#ebodys #s_url','#ebodys #s_url_name');}catch(e){}
			if(val&&val.data[0]['s_url']) $(window.parent.document).find('#ebodys #fileQueue').formhtml('<img width="80" src="../public/'+val.data[0]['s_url']+'">');
		break;
	}
	
}

function add(){	 
	var tbody='';
  	var tfoot='';
	var ttitle='';
	var tcontent='';
	var tart_class='';
	switch(page){	
		case 'sytle':	
		case 'type':	
		case 'subType':
			ttitle='信息编辑';
			tcontent='#edit';
			tart_class='edit';
			tbody+='<tr><th class="pop_title" colspan="2"><div id="pop_title">添加分类</div></th></tr><tr>'
				+'<td>请输入问卷分类<span class="fontred">*</span></td>'
				+'<td><input id="name" type="text" name="name" value="" /></td>'
				+'</tr>';
			tbody+='<tr>'
				+'<td class="ctl_btn" colspan="2"><input id="q_add_save" class="isearcher_submit_button" type="button" value="保 存"/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="q_back" class="isearcher_submit_button" type="button" value="取 消"/></td>'
				+'</tr>';	
								
			$("#ebody").empty().formhtml(tbody);
		break;
	}
	
	parent.asyncbox.open({
		title  : ttitle,
		id: 'ebodys',
		html:$(tcontent).formhtml()
	});	
}

function m_updata(val){
	do_ajax('m_updata',val);	
}

//检测新增数据是否存在
function check_val(temp,container){
	if($.inArray(temp,container)>-1){
		return true;
	}else{
		return false;
	}
	
}

//上传文件
function initthumselect(src,parent_id,file_name,file_size) {
	up_temp_num++;
	temp_up_name=file_name;
	file_size=Math.round(file_size*1000/(1024*1024))/1000;
	var pre_img='<li id="local_up">'
					+'<input type="hidden" name="up_itemSize" value="'+(file_size)+'" id="up_itemSize" />'
					+'<input type="hidden" name="up_url" value="'+src+'" id="up_src" />'
					+'<input type="hidden" name="file_name" value="'+file_name+'" />'
					+'<span><a id="up_del" class="progressCancel" ></a>'+file_name+'</span>'
					+'<span class="up_com_desc">上传完成</span>'
					+'<span class="up_com_desc">'+file_size+'MB</span>'
				+'</li>';
	$("#"+parent_id).hide().formhtml(pre_img).show();
}

//点击上传
function do_upload(){
	if($('.progressWrapper').filter(':visible').length<1){
			alert('请您先选择要上传的文档!');
		}else{
			if(up_temp_num>0){
				alert('您已经存在上传完成的文件，请先删除，再选择上传!');
			}else{
				swfu.startUpload();
			}
		}	
}

