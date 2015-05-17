<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<link rel="stylesheet" href="http://scap.mama.cn/css/SearchInfoWindow.min.css" />
<style>
    pre{
        font-size: 14px;
    }
    .info-panel {
        position: relative;
        font-family: 'Roboto', sans-serif;
        border: 1px solid #d4d4d4;
        border-radius: 4px;
        -webkit-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
    }
    .info-panel > .panel-tags {
        position: absolute;
        top: 35px;
        right: -3px;
    }
    .info-panel > .panel-tags > ul {
        list-style: none;
        padding: 0px;
        margin: 0px;
    }
    .info-panel > .panel-tags > ul:hover {
        box-shadow: 0px 0px 6px rgb(0, 0, 0);
        box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.25);
    }
    .info-panel > .panel-tags > ul > li {
        display: block;
        right: 0px;
        width: 0px;
        padding: 5px 0px 5px 0px;
        font-size: 12px;
        color: #ffffff;
        font-weight: bold;
        overflow: hidden;
    }

    .info-panel > .panel-tags > ul > .panel-tag-default{
        background-color: #777!important;
    }

    .info-panel > .panel-tags > ul > .panel-tag-primary{
        background-color: #428bca!important;
    }

    .info-panel > .panel-tags > ul > .panel-tag-info{
        background-color: #5bc0de!important;
    }

    .info-panel > .panel-tags > ul > .panel-tag-success{
        background-color: #5cb85c!important;
    }

    .info-panel > .panel-tags > ul > .panel-tag-warning{
        background-color: #f0ad4e!important;
    }

    .info-panel > .panel-tags > ul > .panel-tag-danger{
        background-color: #d9534f!important;
    }

    .info-panel > .panel-tags > ul > .panel-tag-default::after {
        content:"";
        position: absolute;
        top: 0px;
        right: 0px;
        height: 100%;
        border-right: 3px solid #777;
    }
    .info-panel > .panel-tags > ul > .panel-tag-primary::after {
        content:"";
        position: absolute;
        top: 0px;
        right: 0px;
        height: 100%;
        border-right: 3px solid #428bca;
    }

    .info-panel > .panel-tags > ul > .panel-tag-info::after {
        content:"";
        position: absolute;
        top: 0px;
        right: 0px;
        height: 100%;
        border-right: 3px solid #5bc0de;
    }

    .info-panel > .panel-tags > ul > .panel-tag-success::after {
        content:"";
        position: absolute;
        top: 0px;
        right: 0px;
        height: 100%;
        border-right: 3px solid #5cb85c;
    }

    .info-panel > .panel-tags > ul > .panel-tag-warning::after {
        content:"";
        position: absolute;
        top: 0px;
        right: 0px;
        height: 100%;
        border-right: 3px solid #f0ad4e;
    }

    .info-panel > .panel-tags > ul > .panel-tag-danger::after {
        content:"";
        position: absolute;
        top: 0px;
        right: 0px;
        height: 100%;
        border-right: 3px solid #d9534f;
    }

    .info-panel > .panel-tags > ul:hover > li,
    .info-panel > .panel-tags > ul > li {
        padding: 5px 15px 5px 10px;
        width: auto;
        cursor: pointer;
        margin-left: auto;
    }
    .info-panel > .panel-tags > ul:hover > li {
        background-color: rgb(255, 255, 255);
    }
    .info-panel > .panel-tags > ul > li:hover {
        background-color: rgb(66, 127, 237);
        color: rgb(255, 255, 255);
    }
    .info-panel > .panel-heading {
        margin-top: 20px;
        padding-bottom: 5px;
        border-bottom: 0px;
        background-color: #fff;
    }
    .info-panel > .panel-heading > img {
        margin-right: 15px;
    }
    .info-panel > .panel-heading > h3 {
        margin: 0px;
        font-size: 14px;
        font-weight: 700;
        margin-top: 20px;
    }
    .info-panel > .panel-heading > h5 {
        color: rgb(153, 153, 153);
        font-size: 12px;
        font-weight: 400;
    }
    .info-panel > .panel-body {
        padding-top: 5px;
        font-size: 13px;
    }
    .info-panel > .panel-body > p {
        margin-top: 30px;
    }
    .info-panel > .panel-body > .info-panel-map {
        display: block;
        text-align: center;
        background-color: rgb(245, 245, 245);
        border: 1px solid rgb(217, 217, 217);
    }
    .info-panel > .panel-body > .info-panel-map > img {
        max-width: 100%;
    }

    .info-panel > .panel-footer {
        font-size: 14px;
        font-weight: 700;
        min-height: 54px;
    }

    .avatar-item {
        vertical-align: top;
        display: inline-block;
        text-align: center;
        width: 100px;
    }
    .avatar-item>img {
        width: 50px!important;
        height: 50px!important;
        margin-bottom: 5px;
    }
    .avatar-item>span {
        display: block;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        width: 100px;
    }
    @media (max-width: 767px) {
        .info-panel > .panel-body {
            padding-top: 50px;
        }
        h5{
            font-size: 12px;
        }
        h4{
            font-size: 14px;
        }
        h3{
            font-size: 16px;
        }
        pre{
            font-size: 12px;
        }
        .avatar-item {
            width: 80px;
        }
        .avatar-item>img {
            width: 40px!important;
            height: 40px!important;
            margin-bottom: 5px;
        }
        .avatar-item>span {
            width: 80px;
        }
    }
    .fixed-bootstrap-bdmap-conflict * {/*修复百度地图和bootstrap的css冲突 @hjh*/
        box-sizing:content-box;
    }
    .location{
        color: lightcoral ;
    }
    .location:hover{
        color: #d9534f;
        cursor: pointer;
    }
    #footer{
        padding-bottom: 20px;
        margin-bottom: 0px;
    }
.info-panel-map{
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #d4d4d4;
    -moz-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
    -webkit-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
}
</style>
<div class="content">
    <legend><span class="glyphicon glyphicon-hand-right" style="top:4px;width: 24px;"></span><strong> <?php echo ($activity["subject"]); ?></strong></legend>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
            <div class="panel panel-default info-panel">
                <div class="panel-tags" id="activity_status">
                    <ul>
                        <li class="<?php if($activity['status']==0){echo 'panel-tag-info';}elseif($activity['status']==1){echo'panel-tag-primary';}elseif($activity['status']==2){echo'panel-tag-warning';}elseif($activity['status']==10){echo'panel-tag-danger';}elseif($activity['status']<0){echo'panel-tag-default';} ?>"><?php echo ($activity["status_info"]); ?></li>
                    </ul>
                </div>
                <?php if($activity['has_join']){ ?>
                <div class="panel-tags " style="margin-top: 30px;">
                    <ul>
                        <li class="panel-tag-success">已经报名成功</li>
                    </ul>
                </div>
                <?php } ?>
                <div class="panel-heading">
                    <img class="[ img-circle pull-left ]" src="http://scap.mama.cn/upload/avatar/thumbmini_<?php echo ($activity["founder_avatar"]); ?>" onclick="loadPageProfile(<?php echo ($activity["uid"]); ?>);" style="cursor: pointer;"/>
                    <h3 class="hidden-xs" ><?php echo ($activity["username"]); ?></h3>
                    <h3 class="visible-xs"><?php echo ($activity["username"]); ?></h3>
                    <h5 class="hidden-xs"><span>发布于</span> - <span><?php echo ($activity["friendly_date"]); ?></span> </h5>
                </div>
                <div class="panel-body">
                    <h5 class="visible-xs" style="color: rgb(153, 153, 153);font-size: 10px;"><span>发布于</span> - <span><?php echo ($activity["friendly_date"]); ?></span> </h5>
                    <hr/>

                    <h3><span class="glyphicon glyphicon-bookmark" style="top:4px;"></span> 活动介绍</h3>
                    <p><pre><?php echo ($activity["info"]); ?></pre></p>

                    <h3><i class="glyphicon glyphicon-tags" style="top:4px;"></i> 类型</h3>
                    <p><h3><label class="label label-info"><?php echo ($activity["category_name"]); ?></label></h3></p>

                    <h3><i class="glyphicon glyphicon-time" style="top:4px;"></i> 时间</h3>
                    <p><h5>开始：<?php echo ($activity["starttime_r"]); ?></h5> <h5>结束：<?php echo ($activity["endtime_r"]); ?></h5></p>

                    <h3><i class="glyphicon glyphicon-map-marker location" style="top:4px;"></i> 活动坐标</h3>
                    <p><h4><?php echo ($activity["address"]); ?></h4></p>
                    <div class="info-panel-map fixed-bootstrap-bdmap-conflict" id="bdmap" style="width: 100%;height: 500px;">
                    </div>

                    <h3><i class="glyphicon glyphicon-flag" style="top:4px;"></i> 报名情况（<?php echo ($activity["left"]); ?>）</h3>
                    <p id="join_members">
                        <?php if(is_array($members)): $i = 0; $__LIST__ = $members;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$member): $mod = ($i % 2 );++$i;?><div class="avatar-item" id="join_members_<?php echo ($i); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo ($member["message"]); ?>" style="cursor: pointer;" onclick="loadPageProfile(<?php echo ($member["uid"]); ?>);">
                                <img class="img-circle" src="http://scap.mama.cn/upload/avatar/thumbmini_<?php echo ($member["avatar"]); ?>"/>
                                <span class="caption"><?php echo ($member["username"]); ?></span>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </p>
                </div>
                <?php if(!$activity['has_join'] && $activity['status']==0){ ?>
                <div class="panel-footer" id="footer">
                    <div class="input-group">
                        <input type="text" class="form-control" maxlength="30" id="message" placeholder="您可以对本次活动留言(可选)，留言将在报名时提交并显示在您的报名头像中."/>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-success" id="join">点击报名</button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>

    setTimeout("asyncLoadBDJS();",50)  //异步加载地图"

    /*异步加载*/
    function asyncLoadBDJS() {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://api.map.baidu.com/api?v=2.0&ak=KOU621q3r2lFxyGucuSxSCOE&callback=init";
        document.body.appendChild(script);
    }

    function init(){

        $.getScript("http://scap.mama.cn/js/vendor/SearchInfoWindow.min.js",function(){
            initmap();
        });
    }

    function initmap(){
        var map = new BMap.Map('bdmap');
        var poi = new BMap.Point(<?php echo ($activity["lng"]); ?>,<?php echo ($activity["lat"]); ?>);
        map.clearOverlays();
        map.centerAndZoom(poi, 16);
        map.enableScrollWheelZoom();
        var searchInfoWindow = null;
        searchInfoWindow = new BMapLib.SearchInfoWindow(map,"<?php echo ($activity["address"]); ?>", {
            title               : "位置",      //标题
            panel               : "panel",         //检索结果面板
            enableAutoPan       : true,     //自动平移
            enableSendToPhone   : false,
            searchTypes         :[
                BMAPLIB_TAB_SEARCH,   //周边检索
                BMAPLIB_TAB_TO_HERE,  //到这里去
                BMAPLIB_TAB_FROM_HERE //从这里出发
            ]
        });
        var marker = new BMap.Marker(poi); //创建marker对象
        searchInfoWindow.open(marker);
        marker.addEventListener("click", function(e){
            searchInfoWindow.open(marker);
        })
        map.addOverlay(marker); //在地图中添加marker
    }
    $(function(){
    	
    	$("[data-toggle='tooltip']").tooltip();
    	
        $(".location").on("click",function(){
           initmap();
        });
        $("#join").on("click",function(){
        	swal({
        		title: "提示",
   				text: "是否确认报名该活动?",
   				type: "warning",
   				showCancelButton: true,
   				confirmButtonColor: "#DD6B55",
   				confirmButtonText: "确认",
   				cancelButtonText:"取消",
   				closeOnConfirm: false }, 
   				function(isConfirm){
   					if(isConfirm){
   						var message  = $.trim($("#message").val()); 
   						var uid 	 = <?php echo (session('uid')); ?>;
   						var username = "<?php echo (session('username')); ?>";
   						var aid 	 = <?php echo ($activity["aid"]); ?>;
   	   					$.ajax({
   	   						type:"post",
   	   						url:"http://scap.mama.cn/Home/Activity/join",
   	   						data:{uid:uid,username:username,aid:aid,message:message},
   	   						success:function(data){
   	   							
   	   							if(data.code>0){
	   	   							$("#footer").slideUp(300);
		   							/*添加tag*/
	   	   							var tag = '<div class="panel-tags" id="join_success" style="margin-top: 30px;"><ul><li class="panel-tag-success">已经报名成功</li></ul></div>';
		   							$('#activity_status').after(tag);
		   							
		   							/*添加头像*/
		   							var message  = $.trim($("#message").val());
	   	   							var avatar = '<div class="avatar-item" data-toggle="tooltip" data-placement="top" title="'+message+'"><img class="img-circle" src="http://scap.mama.cn/upload/avatar/thumbmini_<?php echo (session('avatar')); ?>"/><span class="caption"><?php echo (session('username')); ?></span></div>';
		   							$("#join_members_1").before(avatar);
	   	   							$("[data-toggle='tooltip']").tooltip();
	   	   							swal({
		   	   							title:"成功",
		   	   							text :"您已报名成功！",
		   	   							type :"success",
		   	   							confirmButtonColor:"#3B5999",
		   	   							confirmButtonText: "OK"
	   	   							});
   	   							}else if(data.code<0){
	   	   							swal({
		   	 							title:"失败",
		   	 							text :data.error,
		   	 							type :"error",
		   	 							confirmButtonColor:"#3B5999",
		   	 							confirmButtonText: "OK",
		   	 							allowOutsideClick:true
	   	 							});
   	   							}
   	   						}
   	   					});
   					}
   				}
   			);
        });
    });
</script>