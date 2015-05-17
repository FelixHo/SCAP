<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no"">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <title>SCAP 盛成活动平台</title>
    <link rel="shortcut icon" href="http://119.29.22.179/image/favicon.ico">
	<link href="http://119.29.22.179/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
	
	<!--[if lt IE 9]>
      <script src="http://119.29.22.179/js/vendor/html5shiv.min.js"></script>
      <script src="http://119.29.22.179/js/vendor/respond.min.js"></script>
    <![endif]-->

	<script type="text/javascript" language="javascript" src="http://119.29.22.179/js/jquery/jquery-1.11.1.min.js"></script>
	
	<script type="text/javascript" language="javascript" src="http://119.29.22.179/js/bootstrap/bootstrap.min.js"></script>
	
	<script src="http://119.29.22.179/js/vendor/TweenLite.min.js"></script>
	
	<style type="text/css">
	body{
		font-family:'Helvetica Neue', Helvetica, 'Microsoft Yahei', 'Hiragino Sans GB', 'WenQuanYi Micro Hei', sans-serif;
	    background: url(http://119.29.22.179/image/login-back.png);
		background-color: #444;
	    background: url(http://119.29.22.179/image/login-pinlayer2.png),url(http://119.29.22.179/image/login-pinlayer1.png),url(http://119.29.22.179/image/login-back.png);
	}
	
	.vertical-offset-150{  
        margin-top: 15%;
	}
	</style>
</head>
<body>
<div class="container">
    <div class="row vertical-offset-150">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">请先登录</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form accept-charset="UTF-8" role="form">
                    <fieldset>
                        <div id="alertdiv" style="display: none"></div>
                        <div class="form-group">
                            <label for="username">用户名:</label><input class="form-control"  id="username" name="username" placeholder="请输入用户名" type="text">
			    		</div>
			    		<div class="form-group">
                            <label for="password">密码:</label><input class="form-control" id="password" name="password" placeholder="请输入密码" type="password" value="">
			    		</div>
                        <div class="form-group">
                            <label for="code">验证码:</label>
                            <div class="row">
                                <div class="col-xs-6 col-sm-8 col-md-6">
                                    <input class="form-control" id="code" name="code" placeholder="请输入验证码" type="text" value="">
                                </div>
                                <div class="col-xs-6 col-sm-4 col-md-6">
                                    <img src="http://119.29.22.179/Home/Login/verify" id="verify" class="img-rounded" style="width: 100%;height: 34px;" data-toggle="tooltip" data-placement="top" title="点击换一张"/>
                                </div>
                            </div>
                             </div>
                        <div class="form-group">
                         </div>
			    		<button class="btn btn-lg btn-success btn-block" type="button" id="login" data-loading-text="正在登陆..." autocomplete="off">登陆</button>
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
    $(function(){
        var host = location.protocol+'//'+location.host;
        $(document).mousemove(function(event){
            TweenLite.to($('body'),
                    .5,
                    { css:
                    {
                        backgroundPosition: ""+ parseInt(event.pageX/8) + "px "+parseInt(event.pageY/'12')+"px, "+parseInt(event.pageX/'15')+"px "+parseInt(event.pageY/'15')+"px, "+parseInt(event.pageX/'30')+"px "+parseInt(event.pageY/'30')+"px"
                    }
                    });
        });

        $("[data-toggle='tooltip']").tooltip();

        $("#verify").on("click",function(){
            changeVerifyImg();
        });

        $("#login").on("click",function(){
            var url = host+"/Home/login/index";
            var password = $("#password").val();
            var username = $("#username").val();
            var code     = $("#code").val();
            if($.trim(password)==''||$.trim(username)==''||$.trim(code)==''){
                alertMsg('请输入完整的登录信息!');
                return false;
            }
            
            var $btn = $(this).button('loading')
            
            $.ajax({
                type : "post",
                 url : url,
                data : {username:username,password:password,code:code},
                complete:function(){
                	$btn.button('reset')
                },
                success:function(data){
                    if(data.code <0){
                        changeVerifyImg();
                        alertMsg(data.error);
                    }else if(data.code == 1){
                        location.href = host+"/Home/Main/index";
                    }
                }
            });
            
            
        });
        
        $(document).keydown(function(event) {
            if (event.keyCode == 13) {
                $("#login").click();
                event.preventDefault();
            }
        });

        function changeVerifyImg(){
            var src = $("#verify").attr("src");
            $("#verify").attr("src", src.replace(/\?.*$/,'')+'?random='+Math.random());
        }

        function alertMsg(msg,type,tag){
            if($("#alert").length>0){
                $("#alert").remove();
            }
            type = type ? type : 'alert-danger';
            tag  = tag  ? tag  : '出错!';
            $("#alertdiv").append(
            '<div class="alert '+type+'" id="alert">'+
            '<a href="#" class="close" data-dismiss="alert">&times;</a>'+
            '<strong>'+tag+' </strong>' +msg+
            '</div>');
            $("#alertdiv").slideDown(300);
            $("#alert").on("close.bs.alert",function(){
                $("#alertdiv").slideUp(300);
            });
        }
    });
</script>
</html>