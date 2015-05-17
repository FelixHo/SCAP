<?php if (!defined('THINK_PATH')) exit();?><style>
.list-item:first-child { margin-top: 0 !important; }
.list-item { margin-top: 20px; }
.list-item .col-md-2 { border-left: 1px dotted #ccc; min-height: 160px; background-color: rgba(151, 196, 228, 0.7);box-shadow: -2px 0px 5px #AEAFB2;-moz-box-shadow: -2px 0px 5px #AEAFB2; -webkit-box-shadow: -2px 0px 5px #AEAFB2;}
.list-item ul { padding-left: 0 !important; list-style: none;  }
.list-item ul li { font-size: 400 ; line-height: 30px; }
.list-item ul li i { padding-right: 5px; }
.list-item h3 { margin-top: 0 !important; margin-bottom: 15px !important; font-size:18px;color:black;text-shadow: 0px 2px 4px #bbb; }
.list-item h3 > a, .list-item i { color: #248dc1 !important; }
.list-item-content{min-height: 175px;}
.avatar-box{
	max-height: 120px;
	overflow: hidden;
	line-height: 23px;
}
.avatar-item {
        vertical-align: top;
        display: inline-block;
        text-align: center;
        width: 50px;
        font-weight: bold;
}
.avatar-item>img {
    width: 32px!important;
    height: 32px!important;
    margin-bottom: 5px;
}
.avatar-item>span {
    display: block;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    width: 50px;
}
.item-box{
    background-color: #fff;
    border: 1px solid #d4d4d4;
    border-radius: 4px;
    position: relative;
    -moz-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    padding-top: 15px;
}
.item-box:hover{
    cursor: pointer;
    -moz-box-shadow: 0 1px 20px rgba(0, 0, 0, 0.175);
    -webkit-box-shadow: 0 1px 20px rgba(0, 0, 0, 0.3);
    box-shadow: 0 1px 20px rgba(0, 0, 0, 0.3);
}
 @media (max-width: 767px) {
 	
	.list-item .col-md-2 { 
		border-top: 1px dotted #ccc;
        min-height: 50px;
        padding-top: 12px;
        background-color: rgba(151, 196, 228, 0.7);
    }
	.avatar-box{
		max-height: 240px;
		overflow: hidden;
		line-height: 23px;
	}
}
</style>
<div class="content" style="padding-bottom: 10px;">
    <legend><strong>正在进行</strong></legend>
    <?php if(is_array($activities)): $i = 0; $__LIST__ = $activities;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$activity): $mod = ($i % 2 );++$i;?><article class="list-item row item-box" data-aid="<?php echo ($activity["aid"]); ?>">
            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 list-item-content">
                <h3><span class="glyphicon glyphicon-bookmark" style="top: 4px;color: #d9534f"></span> <?php echo ($activity["subject"]); ?></h3>
                <div class="avatar-box">
                    <?php if(is_array($activity['members'])): $i = 0; $__LIST__ = $activity['members'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$member): $mod = ($i % 2 );++$i;?><div class="avatar-item">
                            <img class="img-circle" src="http://scap.mama.cn/upload/avatar/thumbmini_<?php echo ($member["avatar"]); ?>"/>
                            <span><?php echo ($member["username"]); ?></span>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                <ul class="hidden-xs">
                    <li><i class="glyphicon glyphicon-time" style="top: 4px;"></i> <label class="label label-default" ><?php echo ($activity["friendly_date"]); ?></label></li>
                    <li><i class="glyphicon glyphicon-tags" style="top: 4px;"></i> <label class="label label-primary" ><?php echo ($activity["category_name"]); ?></label></li>
                    <li><i class="glyphicon glyphicon-user" style="top: 4px;"></i> <span><span class="badge" style="background-color: #d9534f;color: #ffffff"><?php echo ($activity["members_num"]); ?></span></span></li>
                    <li><i class="glyphicon glyphicon-comment" style="top: 4px;"></i> <span class="badge" style="background-color: #d9534f;color: #ffffff"><?php echo ($activity["replies"]); ?></span></li>
                    <li><i class="glyphicon glyphicon-eye-open" style="top: 4px;"></i> <span class="badge" style="background-color: #d9534f;color: #ffffff"><?php echo ($activity["views"]); ?></span></li>
                </ul>
                <ul class="visible-xs" style="float: left;">
                    <li><small style="color: #fff;"><?php echo ($activity["friendly_date"]); ?></small></li>
                    <li>
                        <i class="glyphicon glyphicon-user" style="top: 4px;"></i> <span><span class="badge" style="background-color: #d9534f;color: #ffffff"><?php echo ($activity["members_num"]); ?></span></span>
                        <i class="glyphicon glyphicon-comment" style="top: 4px;margin-left: 5px;"></i> <span class="badge" style="background-color: #d9534f;color: #ffffff"><?php echo ($activity["replies"]); ?></span>
                        <i class="glyphicon glyphicon-eye-open" style="top: 4px;margin-left: 5px;"></i> <span class="badge" style="background-color: #d9534f;color: #ffffff"><?php echo ($activity["views"]); ?></span>
                    </li>
                </ul>
            </div>
        </article><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<script>
    $(function(){
        $(".list-item").on("click",function(){
            var aid = $(this).data('aid')
            loadPageViewPosts(aid);
        })
    })
</script>