//全选复选框
function allchead(key){
	var a = document.getElementsByTagName("input");
   for (var i=0; i<a.length; i++){
         if (a[i].type == "checkbox" && a[i].id =='Sid') a[i].checked = key;
   	  }
}
//分页
(function($){
	$.fn.artDialog=function(opt){
		//settings
		var settings=jQuery.extend(
			{
				title:'',//标题
				content:'',//内容
				id:'',//当前artDialog的标题
				pageContentBox:"#pageContent",//内容显示处
				left:"",//当前页css
				top:"",
				width:"",
				height:"",
				fixed:"",
				icon:'',//定义消息图标
				mask:'',//定义透明层
				append:'',//定义是否为append
				art_class:'',//用来限制点击生成artDialo的个数为
				target_class:'',//用做追踪数据
				limit:'0',
				time:''//判断自动关闭...时间
			},
			opt
		);
		var content='';
		if(settings.append){
			content=$.trim($(settings.content).html());
			$(settings.content).html('');
		}else{
			content=settings.content;
		}
		var html='';
	html+='<div id="art_dialog" style="position: absolute; z-index: 1996; " class="aui_state_focus ';
	html+=(settings.art_class)?'art_'+settings.art_class+'"':'"';
	html+='">'+
	      '	<div class="aui_outer">'+
			'	<table class="aui_border">'+
			'	<tbody ';
	html+=(settings.target_class)?'class="'+settings.target_class+'"':'';
	html+='>'+
			'	<tr>'+
			'		<td class="aui_nw"/>'+
			'		<td class="aui_n"/>'+
			'		<td class="aui_ne"/>'+
			'	</tr>'+
			'	<tr>'+
			'		<td class="aui_w"/>'+
			'		<td class="aui_c">'+
			'	<div class="aui_inner">'+
			'	<table class="aui_dialog">'+
			'		<tbody>'+
			'			<tr>'+
			'				<td colspan="2" class="aui_header">'+
			'				<div class="aui_titleBar">'+
			'				<div class="aui_title" style="cursor: move">'+settings.title+'</div>'+
			'				<a  href="##"><font class="aui_close">×</font></a>'+
			'				</div>'+
			'				</td>'+
			'			</tr>'+
			'			<tr>'+
			'				<td class="aui_icon" style="display: none">'+
			'				<div class="aui_iconBg" style="background-attachment: scroll; background-repeat: repeat; background-image: none; background-position: 0% 0%; background-size: auto; background-origin: padding-box; background-clip: border-box; background-color: transparent"/>'+
			'				</td>'+
			'				<td class="aui_main" style=""><div style="padding-top: 20px; padding-right: 25px; padding-bottom: 20px; padding-left: 25px">'+
							content+
			'				</div></td>'+
			'			</tr>'+
			'			<tr>'+
			'				<td colspan="2" class="aui_footer">'+
			'				<div class="aui_buttons" style="display: none"/>'+
			'				</td>'+
			'			</tr>'+
			'		</tbody>'+
			'	</table>'+
			'	</div>'+
			'	</td>'+
			'	<td class="aui_e"/>'+
			'	</tr>'+
			'		<tr>'+
			'			<td class="aui_sw"/>'+
			'			<td class="aui_s"/>'+
			'			<td class="aui_se" style="cursor: se-resize"/>'+
			'		</tr>'+
			'	</tbody>'+
			'	</table>'+
			'	</div>'+
			'	</div>';
			//
		$(this).find('.aui_state_focus').addClass('aui_outer').removeClass('aui_state_focus');
			if(settings.art_class){//如果已经存在则返回
				if($(this).find('.art_'+settings.art_class).length>settings.limit) return;
			}
			$(this).append(html);
		//初始化定义
		var  $this=$(this);
		  target=$('.aui_state_focus'),
		   width_css=(settings.width)?settings.width:target.width(),
		  height_css=(settings.height)?settings.height:'auto',
			left_css=(settings.left)?settings.left:($(window).width()-target.width())/2,
			 top_css=(settings.top)?settings.top:$(document).scrollTop()+($(window).height()-target.height())/2;
		
		//css设置	
		target.css({
			'left'  :left_css,
			'top'   :top_css,
			'width' :width_css,
			'height':height_css
		});
		//end
		//透明层
		if(settings.mask){
			 var temp='<div id="mask" style="';
			    temp+='position: absolute;top: 0;left: 0;width: 100%;height:800px;background-color:#000;filter:alpha(opacity=60);opacity: 0.6;';
				temp+='-moz-opacity: 0.6;z-index:5;"></div>';
				$this.append(temp);
				$("#mask").click(function(e){
					$(e.target).remove();
					target.find('.aui_close').trigger('click');
				})
		}
		//end
		
		
		$(".aui_close").click(function(e){
			$(e.target).closest('#art_dialog').remove();
		
			if(settings.append){
				$(settings.content).html(content);
			}
			$("#mask").remove();
		});
		
		$("#art_dialog").live('mousedown',function(e){
			var target=$(this);
			var class_val=target.attr('class');
			var temp=class_val.split(" ");
			if(temp[2]!='aui_state_focus'){
				$this.find('.aui_state_focus').addClass('aui_outer').removeClass('aui_state_focus');
				target.removeClass("aui_outer").addClass("aui_state_focus");
			}
		});
		target.draggable( {handle: ".aui_title"} );
		if(settings.time){
			setTimeout(function(){
				target.find('.aui_close').trigger('click');
			},settings.time);
		}
		
    };
})(jQuery);