<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<link href="http://scap.mama.cn/css/timeline.css" rel="stylesheet" media="screen">
<style>
.heading-title{
    font-size: medium;
    font-weight: bold;
    height: 100%;
    margin-left: 55px;
    padding-top: 10px;
}
.timeline-panel:hover{
    color: #000000;
    text-shadow: 0px 2px 3px #aaa;
}
.timeline-header-line {
    border-top:1px solid #DDD;
    border-bottom:1px solid #DDD;
    padding:5px 0px 5px 15px;
    font-size: 12px;
}
.textline-content{
    padding: 10px;
    padding-bottom: 0px;
    padding-top: 0px;
}
.textline-content>pre>p{
    border-left: 3px #FFA8A8 solid;
    padding-left: 5px;
}
.textline-content>pre>p:hover{
    border-left: 3px #d9534f solid;
}
pre {
    padding: 5px;
    padding-bottom: 0px;
    margin-bottom: -10px;
    border: none;
    border-radius: 0px;
}

.tooltip{
    position:absolute;
    z-index:1020;
    display:block;
    visibility:visible;
    padding:5px;
    font-size:11px;
    opacity:0;
    filter:alpha(opacity=0);
}
.tooltip.in{
    /*opacity:0;
    filter:alpha(opacity=80);*/
}
.tooltip.top{
    margin-top:-2px;
}
.tooltip.right{
    margin-left:2px;
}
.tooltip.bottom{
    margin-top:2px;
}
.tooltip.left{
    margin-left:-2px;
}
.tooltip.top .tooltip-arrow{
    bottom:0;
    left:0;
    margin-left:0;
    border-left:0 solid transparent;
    border-right:5px solid transparent;
    border-top:0 solid #000;
}
.tooltip.left .tooltip-arrow{
    bottom:0;
    left:0;
    margin-left:0;
    border-left:0 solid transparent;
    border-right:5px solid transparent;
    border-top:0 solid #000;
}
.tooltip.bottom .tooltip-arrow{
    bottom:0;
    left:0;
    margin-left:0;
    border-left:0 solid transparent;
    border-right:5px solid transparent;
    border-top:0 solid #000;
}
.tooltip.right .tooltip-arrow{
    bottom:0;
    left:0;
    margin-left:0;
    border-left:0 solid transparent;
    border-right:5px solid transparent;
    border-top:0 solid #000;
}
.tooltip-inner{
    width:200px;
    padding:3px 8px;
    color:#fff;
    text-align:center;
    text-decoration:none;
    background-color:#313131;
    -webkit-border-radius:0px;
    -moz-border-radius:0px;
    border-radius:0px;
}
.tooltip-arrow{
    position:absolute;
    width:0;
    height:0;
}

@media (max-width: 767px) {
    .heading-title{
        font-size: small;
        font-weight: bold;
        height: 100%;
        margin-left: 35px;
        padding-top: 2px;
    }
}
</style>
<!-- content -->
<legend><strong><?php echo ($title); ?></strong></legend>
    <ul class="timeline">
        <?php if(is_array($activities)): $i = 0; $__LIST__ = $activities;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$activity): $mod = ($i % 2 );++$i;?><li <?php if($i%2==0){echo'class="timeline-inverted"';} ?> >
          <div class="timeline-badge primary"><a><i class="glyphicon glyphicon-record" rel="tooltip" title="在<?php echo ($activity["daydiff"]); ?>开始"></i></a></div>
          <div class="timeline-panel activitypanel"  style="cursor:pointer" data-aid="<?php echo ($activity["aid"]); ?>">

                <div class="timeline-heading" style="float: left;width: 100%;vertical-align: middle;">
                    <div style="float: left;">
                        <img src="http://scap.mama.cn/upload/avatar/thumbmini_<?php echo ($activity["avatar"]); ?>" class="img-avatar" />
                    </div>
                    <div class="heading-title">
                        <span><?php echo ($activity["subject"]); ?></span>
                    </div>
                </div>
            
                <div class="timeline-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 text-left timeline-header-line">
                            <span class="glyphicon glyphicon-user"></span>&nbsp;By <a href="#"><?php echo ($activity["username"]); ?></a>
                            | <span class="glyphicon glyphicon-tags"></span>  <?php echo ($activity["category_name"]); ?>
                            | <span class="glyphicon glyphicon-check"></span> <?php echo ($activity["members_num"]); ?>人 已报
                              <div style="display: inline;float: right" class="hidden-xs"><span style="margin-right:10px; color:gray;"><?php echo ($activity["friendly_date"]); ?> </span></div>
                              <div style="display: inline;line-height: 0.5;" class="visible-xs"><span style="margin-right:10px; color:gray;"><br/><?php echo ($activity["friendly_date"]); ?> </span></div>
                        </div>
                    </div>
                     <div class="row" style="padding-top: 5px;">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                                <span class="label <?php if($activity['status']==0){echo 'label-info';}elseif($activity['status']==1){echo'label-primary';}elseif($activity['status']==2){echo'label-warning';}elseif($activity['status']==10){echo'label-danger';}elseif($activity['status']<0){echo'label-default';} ?>"><?php echo ($activity["status_info"]); ?></span>
                                 <?php if($activity['has_join']){ ?>
                                 <span class="label label-success">已报名</span>
                                 <?php } ?>
                            </div>
                     </div>
                    <div class="row" style="padding-top: 5px;">
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12" style="color: gray;">
                            <small><span class="glyphicon glyphicon-time"></span> <?php echo ($activity["starttime_r"]); ?> ~ <?php echo ($activity["endtime_r"]); ?></small>
                        </div>
                    </div>
                    <div class="textline-content">
                        <pre><p><?php echo ($activity["info"]); ?></p></pre>
                    </div>
                </div>

                <div class="timeline-footer"  style="padding: 6px;">
                    <small><span class="glyphicon glyphicon-map-marker" style="color: #ff0000;padding-left: 5px;"></span></small>
                    <img class="img-responsive" style="border: 1px gray dashed;width:100%;max-height:200px;" src="http://api.map.baidu.com/staticimage?width=640&height=200&center=<?php echo ($activity["location"]); ?>&zoom=15&markers=<?php echo ($activity["location"]); ?>&markerStyles=l&dpiType=ph" />
                </div>
          </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
        <li class="clearfix" style="float: none;"></li>
    </ul>
<script type="text/javascript">

function adjust(){
	/*响应式 修复calc在mobile下不兼容问题*/
	var screen_w = $(window).width();
	if(screen_w<768){
	    $('.timeline-panel').css('width', '100%').css('width', '-=50px');
	}else{
	    $('.timeline-panel').css('width', '90%');
	}
	$(window).resize(function() {
	    var screen_w = $(window).width();
	    if(screen_w<768){
	        $('.timeline-panel').css('width', '100%').css('width', '-=50px');
	    }else{
	        $('.timeline-panel').css('width', '90%');
	    }
	});
}


$(function(){
    $(".activitypanel").on("click",function(){
        var aid = $(this).data('aid');
        $("#maincontent").empty();
        $("#maincontent").load("http://scap.mama.cn/Home/activity/activityInfo?aid="+aid);
    })

    var my_posts = $("[rel=tooltip]");

	var size = $(window).width();
	for(i=0;i<my_posts.length;i++){
		the_post = $(my_posts[i]);

		if(the_post.hasClass('invert') && size >=768 ){
			the_post.tooltip({ placement: 'left'});
			the_post.css("cursor","pointer");
		}else{
			the_post.tooltip({ placement: 'rigth'});
			the_post.css("cursor","pointer");
		}
	}
});
</script>
<!--/content-->