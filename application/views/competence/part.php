<?php
$target_name='用户管理';
include(TPL_DIR.'common/header.php');
include(APP_PATH.'/application/helper/rights.php');
?>
<link href="<?php echo VIEW_CSS_URL;?>lab.css?v=<?php echo SYS_VERSION;?>" rel="stylesheet" type="text/css" />  
<!--内容-->


<style type="text/css">
table.table{margin-top:10px;}table.table .tr_nav th{background:#C6E1FD repeat;border:1px solid #B6B6B6;height:40px;line-height:40px;}table.table td{height:40px;line-height:40px;}table.table .tr_nav .main_menu{width:122px;}table.table .tr_nav .sub_menu{width:122px;}table.table .s_m_cnt{height:40px;line-height:40px;border-bottom:1px solid #B6B6B6;border-right:1px solid #B6B6B6;}table.table .r_p_cnt{height:40px;line-height:40px;border-bottom:1px solid #B6B6B6;border-right:1px solid #B6B6B6;}table.table .isearcher_submit_button{height:27px;width:87px;border:none;}table.table .isearcher_submit_button#q_back_out{background-color:#939393;}

#divTab {
    width: 70%;
    height: 900px;
    margin: 0 auto;
    margin-left: 40px;;
}

#tabs {
    width: 450px;
    height: 40px;
    margin: 5px auto;
    padding-top: 10px;
    display: inline-block;
    *display: inline;
    *zoom: 1;
}

#tabs ul {
    height: 33px;
    list-style-type: none;
    position: relative;
    left: 2px;
    top: 18px;
}

#tabs ul li {
    cursor: pointer;
    border: 1px solid #E4E4E4;
    float: left;
    height: 25px;
    width: 110px;
    color: #000000;
    font-family: '宋体', arial, georgia;
    border-bottom: 0;
    margin-right: 20px;
    text-align: center;
    padding-top: 5px;
}

    /* #tabs ul li:last-child{border-right:none;} */
.tabSelect {
    background: rgb(235, 235, 235);
}

.tabContentspace {
    background: #ffffff;
}

.selectContent {
    display: block;
}

.unSelectContent {
    display: none;
}

#tabContent {
    over-flow: hidden;
    border-radius: 2px;
    width: 800px;
    border: 1px solid #E4E4E4;
    margin: 0 auto;
    padding-top: 50px;
    padding-bottom: 30px;
    background: #F3F3F3;
    text-align:center;
}
#tabs ul li span {
    width: 100px;
    height: 40px;
}

.inline-block {
    display: inline-block;
    border: 1px solid #E4E4E4;
    vertical-align: middle;
    *display:inline;
    *zoom:1;
}

.tree-frame {
    overflow: auto;
    width: 310px;
    height: 500px;
    padding-top: 10px;
    background: #ffffff;
    text-align: left;
}

.tree-btn-div {
    width: 120px;
    height: 90px;
    text-align: center;
    vertical-align: middle;
}

.tree-btn {
    width: 70px;
    margin-top: 15px;
}

.ul-no-style {
    list-style-type: none;
}

.ul-no-style li {
    cursor: pointer;
    margin-top: 5px;
}

    /*.span-head{display:inline-block;width:20px;height:21px;background: url(../images/head.png);vertical-align: top;}*/
.span-info {
    display: inline-block;
    height: 30px;
    font-size: 16px;
    font-family: "宋体" "微软雅黑";
    vertical-align: middle;
    text-align: center;
    margin-left: 5px;
    margin-top:3px;
}

.li-base {
    width: 100%;
    height: 30px;
    margin: 10px auto;
}

.li-node-select {
    background-color: #518FD3;
    color:#ffffff;
}

.width-40 {
    width: 40px;
}
.width-70{
    width:70px;
}

.width-100 {
    width: 100px;
}

.width-400 {
    width: 400px;
}

.width-0 {
    width: 0;
}

.height-40 {
    height: 40px;
}

.display-none {
    display: none;
}

.span-relt {
    position: relative;
    top: -3px;
    font-size: 18px;
    left: 8px;
}
</style>
<div id="divTab">
        <span class="span-relt">角色名：</span>
        <div id="tabs">
            <ul>
                <li id="tab1" class="tabSelect">管理员</li>
                <li id="tab2" class="tabContentspace">行政职员</li>
                <li id="tab3" class="tabContentspace">职员</li>
            </ul>
        </div>

    <div id="tabContent">
        <div id="tab1Content" class="selectContent">
            <div class="inline-block tree-frame"><ul id="mytree1" class="ztree"></ul></div>
            <div class="inline-block tree-btn-div" style="border-width: 0;"><input id="manageSelect" type="button" value=">>添加" class="tree-btn"/><input id="manageCancel" type="button" value="<<取消" class="tree-btn"/></div>
            <div class="inline-block tree-frame">
                <ul id="manageUl" class="ul-no-style">
                    <!-- 管理员权限人员 -->
                </ul>
            </div>
        </div>
        <div id="tab2Content" class="unSelectContent">
            <div class="inline-block tree-frame"><ul id="mytree2" class="ztree"></ul></div>
            <div class="inline-block tree-btn-div" style="border-width: 0;"><input id="executeSelect" type="button" value=">>添加" class="tree-btn"/><input id="executeCancel" type="button" value="<<取消" class="tree-btn"/></div>
            <div class="inline-block tree-frame">
                <ul id="executeUl" class="ul-no-style">
                    <!-- 行政权限人员 -->
                </ul>
            </div>
        </div>
        <div id="tab3Content" class="unSelectContent">
            <!--
            <div class="inline-block tree-frame"><ul id="mytree3" class="ztree"></ul></div>
            <div class="inline-block tree-btn-div" style="border-width: 0;"><input id="empSelect" type="button" value=">>添加" class="tree-btn"/><input id="empCancel" type="button" value="<<取消" class="tree-btn"/></div>
            <div class="inline-block tree-frame">-->
<!--                <ul id="empUl" class="ul-no-style">-->
<!--                    <!-- 员工 -->
<!--                </ul>-->
            </div>
        </div>
    </div>
</div>





<?php include(TPL_DIR.'common/common_js.php');?>
<script src="<?php echo VIEW_JS_URL;?>dcwj/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript">
(function(w){
    //用jquery委托绑定事件
    $("#divTab #tabs").on("click","li",function(event){
        var x = event.target;
        var id = $(x).attr("id");
        switchTab(id);
    });
    //切换tab页的方法
    var switchTab = function(tabId){
        $("#tabContent").find(".selectContent").removeClass("selectContent").addClass("unSelectContent");
        $("#tabs").find(".tabSelect").addClass("tabContentspace");
        $("#divTab #tabs #"+tabId).removeClass("tabContentspace").addClass("tabSelect");
        $("#tabContent #"+tabId+"Content").removeClass("unSelectContent").addClass("selectContent");


        $(".ul-no-style").each(function(){
            $(this).html("");
        })
        //重新生成树
        var zNodes=[];
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
        if(tabId=="tab1"){
            //筛选
            var mid=[]
            for(var i=0;i<manage.length;i++){
                mid.push(manage[i].uid);
            }
            //排除相同的数据。
            for(var i= 0,l=treeData.length;i<l;i++){
                if($.inArray(treeData[i].id,mid==-1)){
                    zNodes.push(treeData[i]);
                }
            }
            namespace.manageInfo=manage;
            liNodeAdd("manageUl",namespace.manageInfo);

            //每次添加完以后就清空
            namespace.manageInfo=[];
        }else if(tabId=="tab2"){
            //筛选
            var mid=[]
            for(var i=0;i<clerical.length;i++){
                mid.push(clerical[i].uid);
            }
            //排除相同的数据。
            for(var i= 0,l=treeData.length;i<l;i++){
                if($.inArray(treeData[i].id,mid==-1)){
                    zNodes.push(treeData[i]);
                }
            }
            namespace.executeInfo=clerical;
            liNodeAdd("executeUl",namespace.executeInfo);
            //每次添加完以后就清空
            namespace.executeInfo=[];
//        }else{
//            //筛选
//            var mid=[]
//            for(var i=0;i<normal.length;i++){
//                mid.push(normal[i].uid);
//            }
//            //排除相同的数据。
//            for(var i= 0,l=treeData.length;i<l;i++){
//                if($.inArray(treeData[i].id,mid)==-1){
//                    zNodes.push(treeData[i]);
//                }
//            }
//            namespace.empInfo=normal;
//            liNodeAdd("empUl",namespace.empInfo);
//            //每次添加完以后就清空
//            namespace.empInfo=[];
        }
        var temp=window.parseInt(tabId.slice(3));
        if(temp!==3){
            $.fn.zTree.init($("#mytree"+temp+""), setting, zNodes);
        }
    };

    //右边列表的操作方法
    //添加列表节点,传入参数：右边ul的id,人员信息对象数组
    function liNodeAdd(ulId,info){
        if(info.length&&info.length >= 1){
            var arrHtml = [];
            if(info.length >= 1){
                for(var i = 0;i < info.length;i++){
                    arrHtml.push('<li class="li-base">');
                    arrHtml.push('<span class="span-info width-70">'+info[i].username+'</span><span class="display-none width-100">'+info[i].deptid+'</span><span class="display-none width-0">'+info[i].uid+'</span></li>');
                }
            }
            $("#"+ulId).append(arrHtml.join(""));
        }
    }
    //全局命名空间
    window.namespace = {};
    //将全局变量挂到命名空间下
    namespace.manageInfo = [];
    namespace.executeInfo = [];
    namespace.empInfo = [];


    //右边ul的li被点击事件处理，添加或去除选中样式
    $(".ul-no-style").on("click","li",function(){
        var $this= $(this);
        if(!$this.hasClass("li-node-select")){
            $(".ul-no-style").find(".li-node-select").removeClass("li-node-select");
            $this.addClass("li-node-select");
        }
    });


    //定义数组，存放被删除的li节点信息，以便右边删除后左边树添加这些节点
    window.namespace.remManagInfo = [];
    window.namespace.remExecInfo = [];
    window.namespace.remEmpInfo = [];
    //移除选中的列表节点
    function liNodeCancel(ulId){
        var nodeArray = $("#"+ulId).find(".li-node-select");//被选中节点的选择器
        if(nodeArray.length&&nodeArray.length >=1){
            for(var i = 0;i<nodeArray.length;i++){
                var userName = $(nodeArray[i]).find(".width-70").text();
                var deptId = $(nodeArray[i]).find(".width-100").text();
                var uId = $(nodeArray[i]).find(".width-0").text();
                var obj = {userName:userName,deptId:deptId,uId:uId};
                switch(ulId){
                    case "manageUl":
                        namespace.remManagInfo.push(obj);
                        break;
                    case "executeUl":
                        namespace.remExecInfo.push(obj);
                        break;
                    case "empUl":
                        namespace.remEmpInfo.push(obj);
                        break;
                }
                $(nodeArray[i]).remove();
            }
        }
    }

    //添加或取消按钮的事件绑定
    $(".tree-btn-div").on("click",".tree-btn",function(event){
        var x = event.target;
        var id = $(x).attr("id");

        switch(id){
            case "manageCancel":
                liNodeCancel("manageUl");
                //添加结点
                var treeObj= $.fn.zTree.getZTreeObj('mytree1');
                //zTree.addNodes(treeNode,{id:data.data[i].uid,pId:treeNode.id,isParent:false,name:data.data[i].username});
                //先要获取父节点。这儿要写获得到的部门id；
                var parentNode=treeObj.getNodesByParam("id",namespace.remManagInfo[0].deptId,null)[0]||null;
                treeObj.addNodes(parentNode,{id:namespace.remManagInfo[0].uId,pId:namespace.remManagInfo[0].deptId,isParent:false,name:namespace.remManagInfo[0].userName});
                //发送ajax
                $.ajax({
                    type:"post",
                    url:"../Rolemanagement/setData",
                    data:{
                        m_3:namespace.remManagInfo[0].uId
                    },
                    success:function(){
                    }
                })
                //本地保存

                //normal增加
                normal.push({"username":namespace.remManagInfo[0].userName,"uid":namespace.remManagInfo[0].uId,"deptid":namespace.remManagInfo[0].uId});
                //manage删除
                for(var i=0;i<manage.length;i++){
                    if(manage[i].uid==namespace.remManagInfo[0].uId){
                        manage.splice(i,1);
                        break;
                    }
                }
                //清空保存右边节点的数组
                namespace.remManagInfo = [];
                break;
            case "manageSelect":

                //获得树
                //获得选中的结点。
                var treeObj= $.fn.zTree.getZTreeObj('mytree1');
                var node=treeObj.getSelectedNodes()[0];
                if(!node.isParent){
                    //获得id,pId,name
                    var tempid=node['id'];
                    var temppId=node['pId'];
                    var tempname=node['name'];
                    treeObj.removeNode(node);
                    //发送ajax
                    $.ajax({
                        type:"post",
                        url:"../Rolemanagement/setData",
                        data:{
                            m_1:tempid
                        },
                        success:function(){
                        }
                    });
                    //剩下的就是往数组中添加数据。
                    namespace.manageInfo.push({"username":tempname,"uid":tempid,"deptid":temppId});
                    liNodeAdd("manageUl",namespace.manageInfo);
                    //本地保存。
                    manage.push({"username":tempname,"uid":tempid,"deptid":temppId});
                    //添加后清空数组
                    namespace.manageInfo = [];
                }
                break;
            case "executeCancel":
                liNodeCancel("executeUl");

                //添加结点
                var treeObj= $.fn.zTree.getZTreeObj('mytree2');
                //zTree.addNodes(treeNode,{id:data.data[i].uid,pId:treeNode.id,isParent:false,name:data.data[i].username});
                //先要获取父节点。这儿要写获得到的部门id；
                var parentNode=treeObj.getNodesByParam("id",namespace.remExecInfo[0].deptId,null)[0]||null;
                treeObj.addNodes(parentNode,{id:namespace.remExecInfo[0].uId,pId:namespace.remExecInfo[0].deptId,isParent:false,name:namespace.remExecInfo[0].userName});
                //发送ajax
                $.ajax({
                    type:"post",
                    url:"../Rolemanagement/setData",
                    data:{
                        m_3:namespace.remExecInfo[0].uId
                    },
                    success:function(){
                    }
                })
                //本地保存
                //normal增加
                normal.push({"username":namespace.remExecInfo[0].userName,"uid":namespace.remExecInfo[0].uId,"deptid":namespace.remExecInfo[0].uId});
                //manage删除
                for(var i=0;i<clerical.length;i++){
                    if(clerical[i].uid==namespace.remExecInfo[0].uId){
                        clerical.splice(i,1);
                        break;
                    }
                }
                //清空保存右边节点的数组
                namespace.remExecInfo = [];
                break;
            case "executeSelect":
                //获得树
                //获得选中的结点。
                var treeObj= $.fn.zTree.getZTreeObj('mytree2');
                var node=treeObj.getSelectedNodes()[0];
                //获得id,pId,name
                var tempid=node['id'];
                var temppId=node['pId'];
                var tempname=node['name'];
                treeObj.removeNode(node);
                //剩下的就是往数组中添加数据。
                //发送ajax
                $.ajax({
                    type:"post",
                    url:"../Rolemanagement/setData",
                    data:{
                        m_2:tempid
                    },
                    success:function(){
                    }
                });

                namespace.executeInfo.push({"username":tempname,"uid":tempid,"deptid":temppId});

                liNodeAdd("executeUl",namespace.executeInfo);
                //本地保存
                clerical.push({"username":tempname,"uid":tempid,"deptid":temppId});
                //添加后清空数组
                namespace.executeInfo = [];
                break;

        }
    });




    /*******************************************************************************************************/
    //我的代码
    //第一次的时候就已经把所有数据都取回来了。还需要保留的是，管理员，行政职员，职员等的相关数据。
    //每一次按选项卡上面的按钮，根据本地数据重新生成树和右边的数据。重绘的时候要注意根据
    //树必须只能是单选。
    //添加，取消需要绑定事件是：左边树中减少和删除。右边列表中添加和删除。并且发送响应的ajax
    //总之，要维护的数组就四个。  三个数据成员，一个btreedata。


    //树的所有成员
    var treeData=[];
    //控制不进行第二次ajax
    var btreedata=[];

    //普通职员
    var normal=[];
    //行政人员
    var clerical=[];
    //管理员
    var manage=[];

    function showIconForTree(treeId, treeNode) {
        return !treeNode.isParent;
    };
    function onClick(event, treeId, treeNode, clickFlag){

        if(btreedata[treeNode.id]){
            //去调用ajax，然后增加节点
            $.ajax({
                url:'/Rolemanagement/getDeptMem',
                type:'get',
                dataType:'json',
                data:{deptid:treeNode.id},
                success:function(data){
                    if(data.rs===true){
                        var zTree = $.fn.zTree.getZTreeObj(treeId);
                        var mid=[]
                        for(var i=0;i<manage.length;i++){
                            mid.push(manage[i].uid);
                        }
                        //treeNode = zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, isParent:isParent, name:"new node" + (newCount++)});
                        for(var i= 0,l=data.data.length;i<l;i++){
                            //不仅添加节点。而且在本地保留
                            //这儿少一个判断
                            
                            if($.inArray(data.data[i].uid,mid)==-1){
                                zTree.addNodes(treeNode,{id:data.data[i].uid,pId:treeNode.id,isParent:false,name:data.data[i].username});
                            }
                            //本地数据保留
                            treeData[treeData.length]={};
                            treeData[treeData.length-1].id=data.data[i].uid;
                            treeData[treeData.length-1].pId=treeNode.id;
                            treeData[treeData.length-1].isParent=false;
                            treeData[treeData.length-1].name=data.data[i].username;
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
    $.ajax({
        type:'get',
        url:'../Rolemanagement/getData',
        dataType:'json',
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
            //单位根节点成员，不属于任何部门。。。。这个先慢点看。

            //单位根节点成员
            for(var i=0,l=data.arr_4['uid'].length;i<l;i++){
                treeData[treeData.length]={};
                treeData[treeData.length-1].id=data.arr_4["uid"][i];
                treeData[treeData.length-1].name=data.arr_4["username"][i];
                treeData[treeData.length-1].isParent=false;
                btreedata[data.arr_4["uid"][i]]=true;
            }




            //普通职员保存
            for(var i=0;i<data.arr_3.uid.length;i++){
                normal.push({"username":data.arr_3.username[i],"uid":data.arr_3.uid[i],"deptid":data.arr_3.deptid[i]});          //初始为空
            }
            //行政职员保存
            for(var i=0;i<data.arr_2.uid.length;i++){
                clerical.push({"username":data.arr_2.username[i],"uid":data.arr_2.uid[i],"deptid":data.arr_2.deptid[i]});          //初始为空
            }
            //管理员保存
            for(var i=0;i<data.arr_1.uid.length;i++){
                manage.push({"username":data.arr_1.username[i],"uid":data.arr_1.uid[i],"deptid":data.arr_1.deptid[i]});          //初始为空
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

            //初始化数据
            var zNodes=[];
            //筛选
            var mid=[]
            for(var i=0;i<manage.length;i++){
                mid.push(manage[i].uid);
            }
            //排除相同的数据。
            for(var i= 0,l=treeData.length;i<l;i++){
                if($.inArray(treeData[i].id,mid)==-1){
                    zNodes.push(treeData[i]);
                }
            }
            $.fn.zTree.init($("#mytree1"), setting, zNodes);
            namespace.manageInfo=manage;
            liNodeAdd("manageUl",namespace.manageInfo);
            //每次添加完以后就清空
            namespace.manageInfo=[];
        }
    })












})(window)









</script>
<?php include(TPL_DIR.'common/footer.php');?>