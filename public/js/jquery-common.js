 /**
 * @fileOverview Template engine in jquery
 * @name   jquery-common.js
 * @author ray
 * @date $Date: 2013-01-06
 */
var	START_EVENT = 'mousedown',
	MOVE_EVENT = 'mousemove',
	END_EVENT = 'mouseup',
	MOVE_CLICK = 'click',
	MOVE_DBCLICK = 'dbclick',
	MOVE_BLUR = 'blur',
	MOVE_FOCUS = 'focus',
	MOVE_CHANGE = 'change',
	MOVE_RESIZE = 'resize',
	UNSET_EVENT = 'DOMNodeRemovedFromDocument';
var nd = {};
var nd = {
	"debug":function( args ){
		var args = args || null;
		if( window.console ){
			console.log( args );
		}
		else{
			return
		}
	},
	"isArray":function( args ){
		return $.isArray( args );
	},
	"set_tpl":function( tpl_name, data, domNode ){
		var url = "tpl/";
		$( domNode ).setTemplateURL( url+tpl_name );
		$( domNode ).processTemplate( data );
		nd.debug("--------"+tpl_name + "模版加载完成------------");
	},
	"type":function( variable ){
		return typeof variable;
	},
	"unset":function( obj ){
		for ( p in obj )
		{
			obj[ p ] = null;
			delete( obj[ p ] );
		}
	},
	"trim":function( str ){
		return $.trim( str );
	},
	"html":function( domNode, html_str ){	
			nd.dom.appendHtml( domNode, html_str );
	},
	"device":function(){
		
	},
	"ajaxRequest":function( tpl_name, domNode, args ){
		$.ajax({
			url:args.url,
			type:args.type,
			data:args.data,
			dataType:args.dataType,
			success:args.success
		})
	},
	"ajax":(function(){
	    return{
				ajaxstart:function( domNode_1,domNode_2,args ){
					$( domNode_1 ).ajaxstart( function(){
						nd.dom.setCss( domNode_2,args );
					})
				},
				ajaxComplete:function( domNode, callback ){
					$( domNode_1 ).ajaxComplete( function(){
						nd.dom.setCss( domNode_2,args );
					})
				},
				post:function( domNode, args, tpl_name){
					$.post( args.url, args.data,function( data ){
						var data = eval("("+data+")");
						var html_data = nd.set_tpl( tpl_name, domNode, data );
					});
				},
				get:function( domNode, args, tpl_name ){
					$.get( args.url, args.data,function( data ){
						var data = eval("("+data+")");
						nd.set_tpl( tpl_name, data, domNode );
					});
				},
				load:function( domNode, tpl_name ){				   
					$( domNode ).load( tpl_name );
				}
			}						
})(),
	"dom":(function(){
		return{
			
			show:function( domNode ){
				$( domNode ).show();
			},
			hide:function( domNode ){
				$( domNode ).hide();
			},
			empty:function( domNode ){
				$( domNode ).empty();
			},
			addClass:function( domNode, cssName ){
				$(domNode).addClass( cssName );
			},
			removeClass:function( domNode, cssName ){
				$(domNode).removeClass( cssName );
			},
			hasClass:function( domNode, cssName ){
				return $(domNode).hasClass( cssName );
			},
			getClass:function( domNode ){
				return $(domNode).attr("class");
			},
			setCss:function( domNode, args ){
				$(domNode).css( args )
			},
			removeNode:function( domNode ){
				$( domNode ).remove();
			},
			addEve:function( domNode, eve_type, handle ){
				$( domNode ).bind( eve_type, handle );
			},
			removeEve:function( domNode, eve_type, handle ){
				$( domNode ).unbind( eve_type, handle );
			},
			getAttr: function( domNode, nodeName ){
				return $( domNode ).attr( nodeName );
			},
			setAttr:function( domNode, args ){
				$( domNode ).attr( args );
			},
			addUnset: function( domNode,eve_type ){
				nd.dom.addEve( domNode,UNSET_EVENT,handle );
			},
			delUnset: function(){
				nd.dom.removeEve( domNode,UNSET_EVENT,handle );
			},
			makeHtml:function( c_node ){
				return c_node;
			},
			appendHtml:function( domNode, c_node ){
				$( domNode ).append( c_node );
			}
		}
	})(),
	"form":(function(){
		return{
			getValue:function( domNode ){
				return $( domNode ).val(); 
			},
			isCheck:function( domNode ){
				
			},
			getSize:function( domNode ){
				var nodeName = $( domNode ).get(0).tagName;
				if( "SELECT" == nodeName ){
					return $( domNode ).get(0).options.length;
				}
				else{
					return false;
				}
			},
			clearAll:function( domNode ){
				var nodeName = $( domNode ).get(0).tagName;
				if( "SELECT" == nodeName ){
					$( domNode ).get(0).options.length = 0;
				}
				else{
					return false;
				}
			},
			addOption:function( domNode, text, value ){
				
			},
			removeSelected:function( domNode ){
				
			}
		}
	})(),
	"table":(function(){
		  return{
			 getTrlength:function( domNode ){
				return $( domNode ).find("tr").length;
			 },
			 getTdlength:function( domNode ){
				return $( domNode ).find("td").length;
			 },
			 addtr:function( domNode ){
				
			 },
			 removetr:function( domNode, rowNum ){
				
			 }
			 
		  }
	})(),
	"cookie":(function(){
		return{
				getCookie:function( str ){
					$.cookie( str ); 
				},
				setCookie:function( key, value ){
					$.cookie( key, value ); 
				}
			}
	})(),
	"animation":(function(){
		 return{
			fadeIn:function( domNode, speed, callback ){
				$( domNode ).fadeIn( speed,  callback );
			},
			fadeOut:function( domNode, speed, callback ){
				$( domNode ).fadeOut( speed,  callback );
			}
		 }
	})(),
	"position":(function(){
		return{
			left:function( domNode ){
				return $( domNode ).offset().left;
			},
			top:function( domNode ){
				return $( domNode ).offset().top;
			}
		}
	})()
	
	
}
//判断是否JSON串
String.prototype.is_json = function()
{
	var str = this.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"/g, '');
	return (/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(str);
};