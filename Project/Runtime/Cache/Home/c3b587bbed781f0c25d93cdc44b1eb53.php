<?php if (!defined('THINK_PATH')) exit();?><!-- content -->
<style>
    #content{
        background-color: #fff;
        padding: 20px;
        box-shadow: 1;
        background-color: #fff;
        margin-bottom: 20px;
        border: 1px solid #d4d4d4;
        border-radius: 5px;
        -moz-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
        -webkit-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
    }
</style>
<form class="form-horizontal" role="form" >
    <fieldset>
      <legend><strong>新增用户</strong></legend>
        <div id="content">
              <div class="form-group">
                <div class="col-md-3 col-xs-3 col-md-offset-5 col-xs-offset-4" style="margin-top: 5px;">
                <img id="new_user_avatar" src="http://scap.mama.cn/upload/avatar/male_default.png" class="img-circle img-responsive " alt="Responsive image">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label" for="username">用户名:</label>
                <div class="col-md-4">
                  <input type="text" class="form-control" name="username" id="username" maxlength="30" placeholder="请输入用户登录帐号">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label" for="email">邮箱地址:</label>
                <div class="col-md-4">
                  <input type="text" class="form-control" name="email" id="email" maxlength="120" placeholder="请设置用户邮箱地址(推荐企业邮箱)">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label">性别:</label>
                    <div class="col-md-4">
                        <div id="radioBtn" class="btn-group">
                            <a class="btn btn-primary btn-sm active" data-toggle="radio" data-gender="male" style="font-weight: bold;">帅哥</a>
                            <a class="btn btn-primary btn-sm notActive" data-toggle="radio" data-gender="female" style="font-weight: bold;">美女</a>
                            <input type="hidden" name="gender" id="radio" value="male">
                        </div>
                    </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label">用户组:</label>
                    <div class="col-md-4">
                        <select name="groupid" class="form-control" style="font-weight: bold;">
                        <option value="1" selected>普通用户</option>
                        <option value="10">管理员</option>
                        </select>
                    </div>
              </div>
              <div class="form-group">
                <div class="col-md-4 col-md-offset-4" id="alertdiv" style="display: none;"></div>
              </div>
              <div class="form-group">
                <div class="col-md-offset-4 col-md-4">
                  <button type="button" class="btn btn-success" id="submit" data-loading-text="正在提交..." autocomplete="off">提交</button>
                </div>
              </div>
        </div>
    </fieldset>
  </form>
<!--/content-->
<script>
$(function(){
	$('#radioBtn a').on('click', function(){
	    var sel = $(this).data('gender');
	    var tog = $(this).data('toggle');
	    $('#'+tog).prop('value', sel);
	    $('a[data-toggle="'+tog+'"]').not('[data-gender="'+sel+'"]').removeClass('active').addClass('notActive');
	    $('a[data-toggle="'+tog+'"][data-gender="'+sel+'"]').removeClass('notActive').addClass('active');
	    $("#new_user_avatar").attr("src","http://scap.mama.cn/upload/avatar/"+sel+"_default.png");
	});
	
	$("#submit").on("click",function(){
		var username = $.trim($('input[name="username"]').val());
		var email    = $.trim($('input[name="email"]').val());
		var gender   = $('input[name="gender"]').val();
		var groupid  = $('select[name="groupid"]').val();
		if(username =='' || email==''){
			alertMsg("请输入完整的用户信息",null,"~>_<~");
		}else{
			var $btn = $(this).button('loading')
			$("#alertdiv").slideUp(300);
			$.ajax({
				type:"POST",
				url:"http://scap.mama.cn/Home/Admin/addUser",
				data:{username:username,email:email,gender:gender,groupid:groupid},
				complete:function(){
					$btn.button('reset');
				},
				success:function(data){
					if(data.code>0){
						swal({
							title:"成功",
							text :"新增用户成功 用户名: "+username+" 默认密码: password",
							type :"success",
							confirmButtonColor:"#3B5999",
							confirmButtonText: "OK"
						},function(isConfirm){
							setTimeout(function(){
								location.href="http://scap.mama.cn/Home/Main/index";
							},500);
						});
					}else{
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
	});
});
</script>