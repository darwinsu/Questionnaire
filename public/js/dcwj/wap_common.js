// JavaScript Document
function do_ajax(fn,val,htmlID){
	val=val.replace(/\+/g,"%2B");
	var test='';
	x$('body').xhr(
	fn+val, 
	{
    async: true,
	dataType:'json',
	type:'POST',
    callback: function() {
		eval(htmlID+'(this.responseText)');
    },
   headers:{
       'Mobile':'true'
   }
	});
	return ajax_str;
}
//------单选------//
 function radioChecks(id)
    {
        var radios=document.getElementsByName(id);
        for(var i=0;i<radios.length;i++)
        {
            if(radios[i].checked==true)
            {
                return radios[i].value;
            }
        }
    }
//复选
function CheckboxChecks(checkbox)
{if(!checkbox.length&&checkbox.type.toLowerCase()=='checkbox')
{return(checkbox.checked)?checkbox.value:'';}
if(checkbox[0].tagName.toLowerCase()!='input'||checkbox[0].type.toLowerCase()!='checkbox')
{return'';}
var val=[];var len=checkbox.length;for(i=0;i<len;i++)
{if(checkbox[i].checked)
{val[val.length]=checkbox[i].value;}}
return(val.length)?val:'';
}