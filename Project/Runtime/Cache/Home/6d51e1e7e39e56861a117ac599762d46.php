<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<link href="http://scap.mama.cn/css/bootstrap-datetimepicker/bootstrap-datetimepicker.css" rel="stylesheet" media="screen">
<style>
.content{
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #d4d4d4;
    border-radius: 4px;
    -webkit-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
}
.map{
	height: 400px;
	margin-bottom: 10px;
	margin-top:20px; 
    background-color: #fff;
    margin-bottom: 20px;
    border: 1px solid #d4d4d4;
    -moz-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
    -webkit-box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.175);
}
.input-group-addon{
    background-color: #3B5999;
    color: #ffffff;
}
    @media (max-width: 767px) {
        .content{
            padding: 10px;
        }
    }
</style>
<!-- content -->
<form class="form-horizontal" role="form" >
	<fieldset>
	    <legend><strong>我来组织</strong></legend>
        <div class="content">
            <div class="form-group" style="padding-top:10px;">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-bookmark"></i>
                        </span>
                        <input class="form-control" name="subject" id="subject" maxlength="25" placeholder="活动标题（不多于25字）" type="text"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <textarea style="resize:vertical;" id="info" class="form-control" placeholder="在这里输入活动的相关介绍,注意事项等信息..." rows="6" name="comment"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="input-group date datetimepicker" data-date-format="yyyy-mm-dd hh:ii" data-link-field="starttime">
                        <span class="input-group-addon hidden-xs"><strong>开始时间</strong></span>
                        <span class="input-group-addon" data-toggle="tooltip" title="选择日期" data-placement="top"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input class="form-control"  style="text-align: center;" size="16" type="text" value="" readonly placeholder="请设置活动开始时间">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"  data-toggle="tooltip" title="点击清空日期" data-placement="top"><span class="fa-times"></span></span></span>
                    </div>
                    <input type="hidden" id="starttime" value="" />
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="input-group date datetimepicker" data-date-format="yyyy-mm-dd hh:ii" data-link-field="endtime">
                        <span class="input-group-addon hidden-xs"><strong>结束时间</strong></span>
                        <span class="input-group-addon" data-toggle="tooltip" title="选择日期" data-placement="top"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input class="form-control"  style="text-align: center;" size="16" type="text" value="" readonly placeholder="请设置活动结束时间">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"  data-toggle="tooltip" title="点击清空日期" data-placement="top"><span class="fa-times"></span></span></span>
                    </div>
                    <input type="hidden" id="endtime" value="" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="input-group">
                    <span class="input-group-addon"><strong>人数限额</strong></span>
                        <input type="number" id="limit" class="form-control text-center" placeholder="不限制">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="input-group">
                    <span class="input-group-addon"><strong>活动类型</strong></span>
                        <select name="category" id="category" class="form-control" style="font-weight: bold;">
                            <option value="1" selected>运动</option>
                            <option value="2">出游</option>
                            <option value="3">聚会</option>
                            <option value="4">联谊</option>
                            <option value="5">娱乐</option>
                            <option value="999">其他</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" style="padding-top:10px;">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button class="btn btn-danger" type="submit" id="resetloc" data-toggle="tooltip" title="点击复位地图位置" data-placement="top">
                                <i class="glyphicon glyphicon-map-marker"></i>
                            </button>
                        </div>
                        <input class="form-control" type="text" name="address" id="address" placeholder="双击地图标记活动位置" />
                        <input type="hidden" name="location" id="location"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-search"></i>
                        </span>
                        <input class="form-control" id="searchplace" placeholder="搜索地点" type="text"/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="map" id="mapdiv"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-lg-12" id="alertdiv" style="display: none;"></div>
                <div class="col-md-12 col-sm-12 col-lg-12 text-center" style="margin-top: 20px;">
                    <button type="button" class="btn btn-success btn-lg" id="submit" style="width: 200px;" data-loading-text="请稍后..." autocomplete="off">
                        <i class="glyphicon glyphicon-hand-up"></i>  马上发起
                    </button>
                </div>
            </div>
        </div>
	</fieldset>
</form>
<script>
$(function(){
	$('input[type="number"]').on({
		mouseup:function(){
			var val = $(this).val();
			if(!isPositiveInteger(val)){
				$(this).val("不限制");
			}
			$(this).attr('value',$.trim(val));
		},
		blur:function(){
			var val = $(this).val();
			if(!isPositiveInteger(val)){
				$(this).val("不限制");
			}
			$(this).attr('value',$.trim(val));
		}
	});
	
    $("#submit").on("click",function(){
        var _subject = $.trim($("#subject").val());
        var _info    = $.trim($("#info").val());
        var _starttime = $("#starttime").val();
        var _endtime = $("#endtime").val();
        var _limit   = $("#limit").val();
        var _category=$("#category option:selected").val();
        var _location=$('#location').val();
        var _address = $.trim($("#address").val());
        if(_subject==''||_info==''||_starttime==''||_endtime==''||_address==''){
            alertMsg("请填写完整的活动详情信息!");
        }else if(_location==''){
            alertMsg("请在地图上双击标记活动的大概位置!");
        }else{
            var $btn = $(this).button('loading')
            $("#alertdiv").slideUp(300);
            $.ajax({
                type:"POST",
                url:"http://scap.mama.cn/Home/Activity/createActivity",
                data:{subject:_subject,info:_info,starttime:_starttime,endtime:_endtime,limit:_limit,category:_category,location:_location,address:_address},
                complete:function(){
                    $btn.button('reset')
                },
                success:function(data){
                    if(data.code>0){
                        swal({
                            title:"成功",
                            text :"活动已成功发起，赶紧通知您的小伙伴吧~",
                            type :"success",
                            confirmButtonColor:"#3B5999",
                            confirmButtonText: "OK"
                        },function(isConfirm){
                            setTimeout(function(){
                                $(".activities").trigger("click");
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

	function isPositiveInteger(str) {
	    return /^\+?([1-9]\d*)$/.test(str);
	}
})
</script>
<script type="text/javascript">
	
	setTimeout("asyncLoadBDJS();",50)  //异步加载地图"
	
	/*异步加载*/
	function asyncLoadBDJS() {
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "http://api.map.baidu.com/api?v=2.0&ak=KOU621q3r2lFxyGucuSxSCOE&callback=init";
		document.body.appendChild(script);
	}

	function init(){
		var map = new BMap.Map("mapdiv");  // 创建地图实例  
		var point = new BMap.Point(113.335,23.138);
		map.centerAndZoom(point,17);       // 初始化地图，设置中心点坐标和地图级别  
		map.enableScrollWheelZoom();       //启用滚轮放大缩小，默认禁用
	    map.disableDoubleClickZoom()
		
		var ac = new BMap.Autocomplete({   //建立一个自动完成的对象
			"input" : "searchplace",
			"location" : map
			});
		
		var myValue;
		
		ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
			var _value = e.item.value;
			myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;		
			setPlace();
		});
	
		map.addEventListener("dblclick", function(e){
			map.clearOverlays();
			var lng = e.point.lng;
			var lat = e.point.lat;
			var point = new BMap.Point(lng,lat);
	        $('input[name="location"]').val(lng+","+lat);
			var marker = new BMap.Marker(point);  // 创建标注
			map.addOverlay(marker);               // 将标注添加到地图中
			marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
			
			/*逆地址解析*/
			var geoc = new BMap.Geocoder();
			var pt = e.point;
			geoc.getLocation(pt, function(rs){
				var addComp = rs.addressComponents;
				var address =addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
				$('input[name="address"]').val(address);
			});
		});
		
		function setPlace(){
			map.clearOverlays();    //清除地图上所有覆盖物
			function myFun(){
				var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
				map.centerAndZoom(pp, 18);
				map.addOverlay(new BMap.Marker(pp));    //添加标注
			}
			var local = new BMap.LocalSearch(map, { //智能搜索
			  onSearchComplete: myFun
			});
			local.search(myValue);
		}

        $("#resetloc").on("click",function(e){
            resetPlace();
            $('input[name="address"]').val('');
            $('input[name="location"]').val('');
            e.preventDefault();
        });

		/*地图复位*/
		function resetPlace(){
			map.clearOverlays();
			var point = new BMap.Point(113.335,23.138);
			map.centerAndZoom(point,17);
		}
	}
</script>  
<!--/content-->