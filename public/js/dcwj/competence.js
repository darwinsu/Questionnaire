//autoComplete	 contains
			$.expr[":"].econtains=function(obj,index,meta,stack){
			return (obj.textContent||obj.innerText||$(obj).text()||'').toLowerCase()==meta[3].toLowerCase();
		}
function getdom(){
			return $(window.parent.document);
		}   
//查看数据未删除列表
function show_info(t){
  var temp=do_ajax('getData',t); 
  showInfo(temp,'');
}


var w1Name="";
var w2Name="";
var w3Name="";
var tdleft=0;


function showInfo(val,del){
    var thead='';
	var tbody='';
	var tfoot='';
    var table_box = '';
	var colspans=6;

	if(val.result==false){
		$('#tbody').empty();
		if(page=='index'){
			colspans = 5;
		}
		$("#tbody").empty().formhtml('<tr><td colspan="'+tfoot_num+'">暂无数据</td></tr>');
		$("#tfoot").empty().formhtml('<tr><td colspan="'+tfoot_num+'">'+val.menu+'</td></tr>');
		return false;
	}
	switch(page){
		case 'user':
            colspans=6;

            w1Name=val.weight.w1Name;
            w2Name=val.weight.w2Name;
            w3Name=val.weight.w3Name


				thead+='<tr>'
	                +'<td style="width: 10%">编号</td>'
	                +'<td style="width: 15%">姓名</td>'
	                +'<td style="width: 15%">联系方式</td>';
		            if(val.weight.w1Name!=""){
		            	thead+='<td style="width: 15%" >'+val.weight.w1Name+'<img class="first_setname" src="/public/images/edit_button.png" style="float: right;margin-top: 20px;">'+'</td>';
		            	if(val.weight.w2Name!=""){
		            		thead+='<td style="width: 15%" >'+val.weight.w2Name+'<img class="second_setname" src="/public/images/edit_button.png" style="float: right;margin-top: 20px;">'+'</td>';
		            		if(val.weight.w3Name!=""){
				            	thead+='<td style="width: 15%" >'+val.weight.w3Name+'<img class="third_setname" src="/public/images/edit_button.png" style="float: right;margin-top: 20px;">'+'</td>';
				            }
		            	}

		            }
		            
		            if(val.weight.w1Name==""||val.weight.w2Name==""||val.weight.w3Name==""){
		            	thead+='<td style="width:15%" id="forplus"><img src="/public/images/plus.png" style="margin-top:15px;"/></td>';
		            }

		            thead+='</tr>';

				for(var i=0;i<val.data.length;i++){
	                tbody+='<tr class="pm_'+val.data[i]['id']+'">'
	                    +'<td>'+val.data[i]['uid']+'</td>'
	                    +'<td>'+val.data[i]['username']+'</td>'
	                    +'<td>'+val.data[i]['mobilephone']+'</td>';

	                    if(val.weight.w1Name!=""){
		            		tbody+='<td style="width: 15%" >'+val.data[i]['w1Val']+'<img class="w1val" src="/public/images/edit_button.png" style="float: right;margin-top: 20px;">'+'</td>';
				            if(val.weight.w2Name!=""){
				            	tbody+='<td style="width: 15%" >'+val.data[i]['w2Val']+'<img class="w2val" src="/public/images/edit_button.png" style="float: right;margin-top: 20px;">'+'</td>';
				            	if(val.weight.w3Name!=""){
					            	tbody+='<td style="width: 15%">'+val.data[i]['w3Val']+'<img class="w1val" src="/public/images/edit_button.png" style="float: right;margin-top: 20px;">'+'</td>';
					            }
				            }
			            }
	                tbody+='</tr>';
	            }

		break;
		case 'part':
			colspans=5;
			for(var i=0;i<val.data.length;i++){
				tbody+='<tr class="pm_'+val.data[i]['id']+'">'
					+'<td>'+val.data[i]['id']+'</td>'
					+'<td>'+val.data[i]['jsbh']+'</td>'
					+'<td>'+val.data[i]['pname']+'</td>'
					+'<td>'+val.data[i]['remark']+'</td>'
					+'<td><a href="#"  onclick="edit_auth(\''+val.data[i]['id']+'\');">分配</a> |<a href="#"  onclick="edit(\'id='+val.data[i]['id']+'\',\''+val.data[i]['pname']+'\',\'.pm_'+val.data[i]['id']+'\',\'t=1\',\'id='+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');">编辑</a> | <a href="#"  onclick="del(\'t=1\',\'id='+val.data[i]['id']+'\',\'pageno='+val.pageno+'\');part_show();">删除</a></td>'
					+'</tr>';
			}
		break;
	}
	tfoot+='<tr><td colspan="'+colspans+'">'+val.menu;
	tfoot+=(val.conditions_item)?'<input id="search_item_hide" type="hidden" name="search_item_hide" value="'+val.conditions_item[0]+'" /><input id="search_val_hide" type="hidden" name="search_val" value="'+val.conditions_val[0]+'" />':'';
	tfoot+='</td></tr>';
	$("#tfoot").empty().html(tfoot);
	$("#tbody").empty().html(tbody);
    $("#thead").empty().html(thead);
   
}

//刷页面
function getPage(isajax,url,state)
{
	//alert(1)
    if(isajax=='1')
	{
		var now=new Date().getTime();
		//搜索条件
		var conditions=($('#search_item_hide').length>0&&$('#search_item_hide').val())?'&'+$('#search_item_hide').val()+'='+$('#search_val_hide').val():'';
		var state=(state)?'&state=del':'';
		$.get(url+"&v="+now+conditions+state,function(data){
			var temp=eval('('+data+')');
            //alert(temp);
			(state)?showInfo(temp,'del'):showInfo(temp,'');
		});
	}
	else
	{
        //alert(url);
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

function edit_auth(id){
	var AllCheck = document.getElementsByTagName("input");//获取所有input对象
	for (var i = 0; i < AllCheck.length; i++){
		if (AllCheck[i].type == "checkbox" ) AllCheck[i].checked = false;
	}
	var conditions="t=2&part_id="+id+"&page_num=50";
	var  val=do_ajax('getData',conditions); 
	if(val.data){
		for(var i=0;i<val.data.length;i++){
			eval("$('input[id="+val.data[i]['auth_code']+"][value="+val.data[i]['auth_code']+"]').attr('checked',true);");
		}
	}
	$('#rights_edit #partid').val(id);

	$('#rights_edit').show();
	$('#outcontext').hide();
}

//编辑数据
function edit(id,name,target,t,conditions,pageno){
	var tbody='';
  	var tfoot='';
	var ttitle='';
	var tcontent='';
	var tart_class='';
	switch(page){
		case 'user':	
			ttitle='用户信息编辑';
			tcontent='#edit';
			tart_class='edit';
			var  val=do_ajax('getOneData',conditions); 
			var val_sex1=(val.data[0]['sex']==1)?'selected':'';
			var val_sex2=(val.data[0]['sex']==2)?'selected':'';
			tbody+='<tr>'
				+'<td>账户：</td>'
				+'<td>'+val.data[0]['username']+'</td>'
				+'</tr>';
			tbody+='<tr>'
				+'<td>姓名：</td>'
				+'<td><input id="realname" type="text" name="realname" style="240px;" value="'+val.data[0]['realname']+'" /></td>'
				+'</tr>';
			tbody+='<tr>'
				+'<td>性别：</td>'
				+'<td><select id="sex" name="sex" class="isearcher_select_list"><option value="1" '+val_sex1+'>男</option><option value="2" '+val_sex2+'>女</option></select></td>'
				+'</tr>';
			tbody+='<tr>'
				+'<td colspan="2" align="right" style="border:none"><input id="user_save" class="isearcher_submit_button" type="button" value="保 存"/>  <input id="userid" type="hidden" name="userid" value="'+val.data[0]['id']+'" /></td>'
				+'</tr>';	
								
			$("#ebody").empty().formhtml(tbody);
		break;
		
		case 'part':	
			ttitle='角色信息编辑';
			tcontent='#edit';
			tart_class='edit';
			var  val=do_ajax('getOnePartData',conditions); 
			tbody+='<tr>'
				+'<td>角色编号：</td>'
				+'<td><input id="jsbh" type="text" name="jsbh" style="240px;" value="'+val.data[0]['jsbh']+'" /></td>'
				+'</tr>';
			tbody+='<tr>'
				+'<td>角色名称：</td>'
				+'<td><input id="pname" type="text" name="pname" style="240px;" value="'+val.data[0]['pname']+'" /></td>'
				+'</tr>';
			tbody+='<tr>'
				+'<td>说明：</td>'
				+'<td><textarea name="remark" id="remark" style="240px;">'+val.data[0]['remark']+'</textarea></td>'
				+'</tr>';		
			tbody+='<tr>'
				+'<td colspan="2" align="right" style="border:none"><input id="part_save" class="isearcher_submit_button" type="button" value="保 存"/>  <input id="partid" type="hidden" name="partid" value="'+val.data[0]['id']+'" /></td>'
				+'</tr>';
			$("#ebody").empty().formhtml(tbody);
		break;
	}
	parent.asyncbox.open({
		title  : ttitle,
		id:'ebodys',
		html:$("#edit").formhtml()
	});		
}

function add(){	 
	var tbody='';
  	var tfoot='';
	var ttitle='';
	var tcontent='';
	var tart_class='';
	switch(page){	
		case 'part':	
			ttitle='角色信息添加';
			tcontent='#edit';
			tart_class='edit';
			tbody+='<tr>'
				+'<td>角色编号：</td>'
				+'<td><input id="jsbh" type="text" name="jsbh" style="240px;" value="" /></td>'
				+'</tr>';
			tbody+='<tr>'
				+'<td>角色名称：</td>'
				+'<td><input id="pname" type="text" name="pname" style="240px;" value="" /></td>'
				+'</tr>';
			tbody+='<tr>'
				+'<td>说明：</td>'
				+'<td><textarea name="remark" id="remark" style="240px;" rows="4"></textarea></td>'
				+'</tr>';		
			tbody+='<tr>'
				+'<td colspan="2" align="right" style="border:none"><input id="part_adds" class="isearcher_submit_button" type="button" value="保 存"/> &nbsp</td>'
				+'</tr>';	
			$("#ebody").empty().formhtml(tbody);
		break;
	}
	parent.asyncbox.open({
		title  : ttitle,
		id:'ebodys',
		html:$("#edit").formhtml()
	});	
}

function m_updata(val){
	do_ajax('m_updata',val);	
}


//删除数据
function del(t,conditions,pageno){
	var val=t+'&'+conditions+'&'+pageno;
	do_ajax('del',val);
}
//end



//其它数据----------------------------------------------------------------------
//数据列表
function m_lists(val,id,container){
	var li='';
	for(var i=0;i<val.length;i++){
		li+='<li name="';
		li+=(id[i]=='')?'':id[i];
		li+='">'+val[i]+'</li>';
	}
	$(container+" ul").empty().append(li);
	li_bg(container+' ul li');
};
//初始化显示数据
function show_info_s(name,id,container){
	if(name){
		var tbody='';
		if(id){//判断是否带有id
			var len=Math.ceil(name.length/7)*7;
			for(var i=0;i<len;i++){
				if(i==0){
					tbody+='<tr>';
				}
				tbody+='<td name="'
				tbody+=(id[i])?id[i]:"";
				tbody+='">';
				tbody+=(name[i])?name[i]:"";
				tbody+='</td>';
				if(i>0&&i%7==0&&i<len){
					tbody+='</tr><tr>'
				}
				if(i==len){
					tbody+='</tr>'
				}
			}
		}else{
				var len=Math.ceil(name.length/7)*7;
				for(var i=0;i<len;i++){
				if(i==0){
					tbody+='<tr>';
				}
				tbody+='<td name="">';
				tbody+=(name[i])?name[i]:"";
				tbody+='</td>';
				if(i>0&&i%7==0&&i<len){
					tbody+='</tr><tr>'
				}
				if(i==len){
					tbody+='</tr>'
				}	
			}			
		}//end id
		$(container+" tbody").empty().append(tbody);
	}
}
//检测新增数据是否存在
function check_val(temp,container){
	if($.inArray(temp,container)>-1){
		return true;
	}else{
		return false;
	}
	
}
function editpart(id){
	var val=do_ajax('getData','t=4&user_id='+id);
	var str_u=",,";
	if(val){
		for(var j=0;j<val.data.length;j++){
			str_u+=","+val.data[j]['part_id'];
		}
	}
	var temp=do_ajax('getData','t=3');
	var tbody='';
	tbody+='<tr>'
		+'<td>角色</td>'
		+'</tr>';
	
	for(var i=0;i<temp.data.length;i++){
		if(str_u.indexOf(','+temp.data[i]['id'])>0){checked='checked';}else{checked='';} 
		tbody+='<tr><td><input type="radio" name="parids" id="p'+temp.data[i]['id']+'" '+checked+' value="'+temp.data[i]['id']+'"   />'+temp.data[i]['pname']+'</td></tr>';
	}
	tbody+='<tr>'
		+'<td align="right" style="border:none"><input id="part_save" class="isearcher_submit_button" type="button" value="保 存"/> &nbsp <input id="user_id" type="hidden" name="partid" value="'+id+'" /></td>'
		+'</tr>';
	$("#ebody").empty().formhtml(tbody);
	parent.asyncbox.open({
		title  : '权限分配',
		id:'ebodys',
		html:$("#edit").formhtml()
	});
}


var clicknum=0;
$(document).ready(function(){

    //这儿需要判断。clicknum刚开始是几？
    setTimeout(function(){
    	var thead=$("#thead").find("td");
    	if(thead.length==4){
    		tdleft=3;
    	}else if(thead.length==5){
    		tdleft=2;
    	}else if(thead.length==6){
    		tdleft=1;
    	}
    },1000);

    //这儿加一个判断，看到底修改权重几。



    $('#addWname').live('click',function(){
        document.getElementById('MyDiv').style.display='none';
        var wName =document.getElementById('wname').value;
        //alert(wName);
        //发送ajax

        if(w1Name==""){
    		$.ajax({
                type:"post",
                url:"../Competence/setWeightName",
                data:{
                    w1Name:wName
                },
                dataType:"json",
                success:function(data){
                    $.ajax({
                        type:"post",
                        url:"../Competence/getData",
                        data:{
                            t:1,
                            pageno:1
                        },
                        dataType:"json",
                        success:function(data){
                            $("<td style='width:15%'>"+wName+"<img class='first_setname'src='/public/images/edit_button.png' style='float: right;margin-top: 20px;'>"+"</td>").insertBefore("#forplus") ;
                            $("#tbody").find("tr").each(function(i){
                                $(this).append("<td style='width:15%'>"+data.data[i].w1Val+"<img class='w1val'src='/public/images/edit_button.png' style='float: right;margin-top: 20px;'>"+"</td>");
                            });
                        }
                    });
                    w1Name=wName;
                    w2Name="";
                    w3Name="";
                }
            });
        }else if(w2Name==""){
        	$.ajax({
                    type:"post",
                    url:"../Competence/setWeightName",
                    data:{
                    	w1Name:w1Name,
                        w2Name:wName
                    },
                    dataType:"json",
                    success:function(data){
                        $("<td style='width:15%'>"+wName+"<img class='second_setname'src='/public/images/edit_button.png' style='float: right;margin-top: 20px;'>"+"</td>").insertBefore("#forplus") ;
                        $.ajax({
                            type:"post",
                            url:"../Competence/getData",
                            data:{
                                t:1,
                                pageno:1
                            },
                            dataType:"json",
                            success:function(data){
                                $("#tbody").find("tr").each(function(i){
                                    $(this).append("<td style='width:15%'>"+data.data[i].w2Val+"<img class='w2val'src='/public/images/edit_button.png' style='float: right;margin-top: 20px;'>"+"</td>");
                                });

                            }
                        });
                        w2Name=wName;
	                    w3Name="";
                    }
                });
        }else if(w3Name==""){
        	$.ajax({
                type:"post",
                url:"../Competence/setWeightName",
                data:{
                	w1Name:w1Name,
                	w2Name:w2Name,
                    w3Name:wName
                },
                dataType:"json",
                success:function(data){
                    $("<td style='width:15%'>"+wName+"<img class='third_setname'src='/public/images/edit_button.png' style='float: right;margin-top: 20px;'>"+"</th>").insertBefore("#forplus") ;
                    $.ajax({
                        type:"post",
                        url:"../Competence/getData",
                        data:{
                            t:1,
                            pageno:1
                        },
                        dataType:"json",
                        success:function(data){
                            $("#tbody").find("tr").each(function(i){
                                $(this).append("<td style='width:15%'>"+data.data[i].w3Val+"<img class='w3val'src='/public/images/edit_button.png' style='float: right;margin-top: 20px;'>"+"</td>");
                            });

                        }
                    });
                    $("#forplus").remove();
                    
                }
            });
        }

    });

    //修改第一个权重名称
    $(".first_setname").live("click",  function(){
        var currenttd = $(this.parentNode);
        //取出当前th中的文本内容保存起来
        var text = currenttd.text();
        currenttd.html("");
        //建立一个文本框，也就是input的元素节点
        var input = $("<input>");
        //设置文本框的值是保存起来的文本内容
        input.attr("value", text);
        //响应鼠标离开文本框
        input.mouseleave(function(){
            var inputnode = $(this);
            //保存当前文本框的内容
            var intputext = inputnode.val();

            //发送ajax
            $.ajax({
                type:"post",
                url:"../Competence/setWeightName",
                data:{
                    w1Name:intputext,
                    w2Name:w2Name,
                    w3Name:w3Name
                },
                success:function(data){
                	w1Name=intputext;
                }
            });
            //清空td里面的内容
            var tdNode = inputnode.parent();
            //将保存的文本框的内容填充到th中
            tdNode.html(intputext);
            //新建img标签
            var img=$("<img src='/public/images/edit_button.png'  class='third_setname' style='float: right;margin-top: 20px;'>");
            tdNode.append(img);

        })
        //将文本框加入到th中
        currenttd.append(input);
        //让文本框里面的文字被高亮选中
        //需要将jquery的对象转换成dom对象
        var inputdom = input.get(0);
        inputdom.select();
    })
    //修改第二个权重名称
    $(".second_setname").live("click",  function(){
        var currenttd = $(this.parentNode);
        //取出当前th中的文本内容保存起来
        var text = currenttd.text();
        currenttd.html("");
        //建立一个文本框，也就是input的元素节点
        var input = $("<input>");
        //设置文本框的值是保存起来的文本内容
        input.attr("value", text);
        //响应鼠标离开文本框
        input.mouseleave(function(){
            var inputnode = $(this);
            //保存当前文本框的内容
            var intputext = inputnode.val();
            $.ajax({
                type:"post",
                url:"../Competence/setWeightName",
                data:{
                	w1Name:w1Name,
                    w2Name:intputext,
                    w3Name:w3Name
                },
                success:function(data){
                	w2Name=intputext;
                }
            });
            //清空th里面的内容
            var tdNode = inputnode.parent();
            //将保存的文本框的内容填充到th中
            tdNode.html(intputext);
            //新建img标签
            var img=$("<img src='/public/images/edit_button.png'  class='third_setname' style='float: right;margin-top: 20px;'>");
            tdNode.append(img);
        })
        //将文本框加入到th中
        currenttd.append(input);
        //让文本框里面的文字被高亮选中
        //需要将jquery的对象转换成dom对象
        var inputdom = input.get(0);
        inputdom.select();
    })
    //修改第三个权重名称
    $(".third_setname").live("click",  function(){
        var currenttd = $(this.parentNode);
        //取出当前th中的文本内容保存起来
        var text = currenttd.text();
        currenttd.html("");
        //建立一个文本框，也就是input的元素节点
        var input = $("<input>");
        //设置文本框的值是保存起来的文本内容
        input.attr("value", text);
        //响应鼠标离开文本框
        input.mouseleave(function(){
            var inputnode = $(this);
            //保存当前文本框的内容
            var intputext = inputnode.val();
            $.ajax({
                type:"post",
                url:"../Competence/setWeightName",
                data:{
                	w1Name:w1Name,
                	w2Name:w2Name,
                    w3Name:intputext
                },
                success:function(data){
                	w3Name=intputext;
                }
            });
            //清空td里面的内容
            var tdNode = inputnode.parent();
            //将保存的文本框的内容填充到th中
            tdNode.html(intputext);
            //新建img标签
            var img=$("<img src='/public/images/edit_button.png'  class='third_setname' style='float: right;margin-top: 20px;'>");
            tdNode.append(img);
        })
        //将文本框加入到th中
        currenttd.append(input);
        //让文本框里面的文字被高亮选中
        //需要将jquery的对象转换成dom对象
        var inputdom = input.get(0);
        inputdom.select();
    })
    //双击单元格变成可编辑文本框
    //修改第一个权重值
    $(".w1val").live("click",  function(){
        //保存当前的td节点
        var currenttd = $(this.parentNode);
        //获取对应的用户id
        var userIdVal=currenttd.parent().find("td").eq(0).text();
        //取出当前td中的文本内容保存起来
        var text = currenttd.text();
        //清空td里面的内容
        currenttd.html("");
        //建立一个文本框，也就是input的元素节点
        var input = $("<input>");
        //设置文本框的值是保存起来的文本内容
        input.attr("value", text);
        //响应鼠标离开文本框
        input.mouseleave(function(){
            var inputnode = $(this);
            //保存当前文本框的内容
            var intputext = inputnode.val();
            $.ajax({
                type:"post",
                url:"../Competence/setWeightVal",
                data:{
                    uid:userIdVal,
                    w1Val:intputext
                },
                success:function(data){
                }
            });
            //清空td里面的内容
            var tdNode = inputnode.parent();
            //将保存的文本框的内容填充到td中
            tdNode.html(intputext);
            //新建img标签
            var img=$("<img src='/public/images/edit_button.png'  class='third_setname' style='float: right;margin-top: 20px;'>");
            tdNode.append(img);
        })
        //将文本框加入到td中
        currenttd.append(input);
        //让文本框里面的文字被高亮选中
        //需要将jquery的对象转换成dom对象
        var inputdom = input.get(0);
        inputdom.select();
    })

//修改第二个权重值
    $(".w2val").live("click",  function(){
        //保存当前的td节点
        var currenttd = $(this.parentNode);
        //获取对应的用户id
        var userIdVal=currenttd.parent().find("td").eq(0).text();
        //取出当前td中的文本内容保存起来
        var text = currenttd.text();
        //清空td里面的内容
        currenttd.html("");
        //建立一个文本框，也就是input的元素节点
        var input = $("<input>");
        //设置文本框的值是保存起来的文本内容
        input.attr("value", text);
        //响应鼠标离开文本框
        input.mouseleave(function(){
            var inputnode = $(this);
            //保存当前文本框的内容
            var intputext = inputnode.val();
            $.ajax({
                type:"post",
                url:"../Competence/setWeightVal",
                data:{
                    uid:userIdVal,
                    w2Val:intputext
                },
                success:function(data){

                }
            });
            //清空td里面的内容
            var tdNode = inputnode.parent();
            //将保存的文本框的内容填充到td中
            tdNode.html(intputext);
            //新建img标签
            var img=$("<img src='/public/images/edit_button.png'  class='third_setname' style='float: right;margin-top: 20px;'>");
            tdNode.append(img);
        })
        //将文本框加入到td中
        currenttd.append(input);
        //让文本框里面的文字被高亮选中
        //需要将jquery的对象转换成dom对象
        var inputdom = input.get(0);
        inputdom.select();
    })


//修改第三个权重值
    $(".w3val").live("click",  function(){
        //保存当前的td节点
        var currenttd = $(this.parentNode);
        //获取对应的用户id
        var userIdVal=currenttd.parent().find("td").eq(0).text();
        //取出当前td中的文本内容保存起来
        var text = currenttd.text();
        //清空td里面的内容
        currenttd.html("");
        //建立一个文本框，也就是input的元素节点
        var input = $("<input>");
        //设置文本框的值是保存起来的文本内容
        input.attr("value", text);
        //响应鼠标离开文本框
        input.mouseleave(function(){
            var inputnode = $(this);
            //保存当前文本框的内容
            var intputext = inputnode.val();
            $.ajax({
                type:"post",
                url:"../Competence/setWeightVal",
                data:{
                    uid:userIdVal,
                    w3Val:intputext
                },
                success:function(data){

                }
            });
            //清空td里面的内容
            var tdNode = inputnode.parent();
            //将保存的文本框的内容填充到td中
            tdNode.html(intputext);
            //新建img标签
            var img=$("<img src='/public/images/edit_button.png'  class='third_setname' style='float: right;margin-top: 20px;'>");
            tdNode.append(img);
        })
        //将文本框加入到td中
        currenttd.append(input);
        //让文本框里面的文字被高亮选中
        //需要将jquery的对象转换成dom对象
        var inputdom = input.get(0);
        inputdom.select();
    })
})