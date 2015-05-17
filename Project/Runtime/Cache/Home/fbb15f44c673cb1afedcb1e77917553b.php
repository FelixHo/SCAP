<?php if (!defined('THINK_PATH')) exit();?><style>
    .event-list {
		list-style: none;
		font-family: 'Lato', sans-serif;
		margin: 0px;
		padding: 0px;
	}
	
 	.event-list:hover {
 		cursor: pointer;
	 	-moz-box-shadow: 0 1px 20px rgba(0, 0, 0, 0.175);
	    -webkit-box-shadow: 0 1px 20px rgba(0, 0, 0, 0.175);
	    box-shadow: 0 1px 20px rgba(0, 0, 0, 0.175);
 		
	}
	
	.event-list > li {
		background-color: rgb(255, 255, 255);
		box-shadow: 0px 0px 5px rgb(51, 51, 51);
		box-shadow: 0px 0px 5px rgba(51, 51, 51, 0.7);
		padding: 0px;
		margin: 0px 0px 20px;
	}
	.event-list > li > time {
		display: inline-block;
		width: 100%;
		color: rgb(255, 255, 255);
		background-color: rgba(53, 133, 197, 0.62);
		padding: 5px;
		text-align: center;
		text-transform: uppercase;
	}
	.event-list > li:nth-child(even) > time {
		background-color: rgb(165, 82, 167);
	}
	.event-list > li > time > span {
		display: none;
	}
	.event-list > li > time > .day {
		display: block;
		font-size: 56pt;
		font-weight: 100;
		line-height: 1;
	}
	.event-list > li time > .month {
		display: block;
		font-size: 24pt;
		font-weight: 900;
		line-height: 1;
	}
	.event-list > li time > .year {
		display: block;
		font-size: 40pt;
		font-weight: 900;
		line-height: 1;
		margin-top:15px;
	}
	.event-list > li > img {
		width: 100%;
	}
	.event-list > li > .info {
		padding-top: 5px;
		text-align: center;
	}
	.event-list > li > .info > .title {
		font-size: 15pt;
		font-weight: 700;
		margin: 0px;
	}
	.event-list > li > .info > ul,
	.event-list > li > .social > ul {
		display: table;
		list-style: none;
		margin: 10px 0px 0px;
		padding: 0px;
		width: 100%;
		text-align: center;
	}
	.event-list > li > .social > ul {
		margin: 0px;
	}
	.event-list > li > .info > ul > li,
	.event-list > li > .social > ul > li {
		display: table-cell;
		cursor: pointer;
		color: rgb(30, 30, 30);
		font-size: 11pt;
		font-weight: bold;
        padding: 3px 0px;
	}
    .event-list > li > .info > ul > li > a {
		display: block;
		width: 100%;
		color: rgb(30, 30, 30);
		text-decoration: none;
	} 
    .event-list > li > .social > ul > li {    
        padding: 0px;
    }
    .event-list > li > .social > ul > li > a {
        padding: 3px 0px;
	} 
	.event-list > li > .info > ul > li:hover,
	.event-list > li > .social > ul > li:hover {
		color: rgb(30, 30, 30);
		background-color: rgb(200, 200, 200);
	}
	.facebook a,
	.twitter a,
	.google-plus a {
		display: block;
		width: 100%;
		color: rgb(75, 110, 168) !important;
	}
	.twitter a {
		color: rgb(79, 213, 248) !important;
	}
	.google-plus a {
		color: rgb(221, 75, 57) !important;
	}
	.facebook:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(75, 110, 168) !important;
	}
	.twitter:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(79, 213, 248) !important;
	}
	.google-plus:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(221, 75, 57) !important;
	}

	@media (min-width: 768px) {
		.event-list > li {
			position: relative;
			display: block;
			width: 100%;
			height: 120px;
			padding: 0px;
		}
		.event-list > li > time,
		.event-list > li > img  {
			display: inline-block;
		}
		.event-list > li > time,
		.event-list > li > img {
			width: 120px;
			float: left;
		}
		.event-list > li > .info {
			background-color: rgb(245, 245, 245);
			overflow: hidden;
		}
		.event-list > li > time,
		.event-list > li > img {
			width: 120px;
			height: 120px;
			padding: 0px;
			margin: 0px;
		}
		.event-list > li > .info {
			position: relative;
			height: 120px;
			text-align: left;
		}	
		.event-list > li > .info > .title, 
		.event-list > li > .info > .desc {
			padding: 0px 10px;
		}
		.event-list > li > .info > ul {
			position: absolute;
			left: 0px;
			bottom: 0px;
		}
		.event-list > li > .social {
			position: absolute;
			top: 0px;
			right: 0px;
			display: block;
			width: 40px;
		}
        .event-list > li > .social > ul {
            border-left: 1px solid rgb(230, 230, 230);
        }
		.event-list > li > .social > ul > li {			
			display: block;
            padding: 0px;
		}
		.event-list > li > .social > ul > li > a {
			display: block;
			width: 40px;
			padding: 10px 0px 9px;
		}
	}
</style>
<div class="content">
    <legend><strong>历史回顾</strong></legend>
    <div class="row">
        <?php if(is_array($activities)): $i = 0; $__LIST__ = $activities;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$activity): $mod = ($i % 2 );++$i;?><div class="col-xs-12 col-sm-6 col-lg-6 col-md-6 item" data-aid="<?php echo ($activity["aid"]); ?>">
                <ul class="event-list">
                    <li>
                        <time>
                            <span class="year" style="display: <?php echo ($activity["Y_display"]); ?>;"><?php echo ($activity["Y"]); ?></span>
                            <span class="day" style="display: <?php echo ($activity["j_display"]); ?>;"><?php echo ($activity["j"]); ?></span>
                            <span class="month"><?php echo ($activity["M"]); ?></span>
                        </time>
                        <?php if($activity['cover']){ ?>
                            <img src="http://scap.mama.cn/upload/post/image/thumbsmall_<?php echo ($activity["cover"]); ?>" />
                        <?php } ?>
                        <div class="info">
                            <h3 class="title"><?php echo ($activity["subject"]); ?></h3>
                            <ul>
                                <li style="width:33%;"><span class="glyphicon glyphicon-user"></span> <?php echo ($activity["member_num"]); ?> </li>
                                <li style="width:34%;"><span class="glyphicon glyphicon-comment"></span> <?php echo ($activity["replies"]); ?> </li>
                                <li style="width:33%;"><span class="glyphicon glyphicon-eye-open"></span> <?php echo ($activity["views"]); ?> </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<script>
    $(function(){
        $(".item").on("click",function(e){
            var aid = $(this).data('aid');
            loadPageViewPosts(aid);
            e.preventDefault();
        });
    });
</script>