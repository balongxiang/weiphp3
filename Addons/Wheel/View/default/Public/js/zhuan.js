// JavaScript Document

$('#ok').click(function(){
	$.Dialog.loading();
	$.post($('#yanzheng').attr('action'),
			$('#yanzheng').serializeArray(),
			function(data){
				window.location.href = data.url;
			});
});

$( document).ready(function() {
	$("section.index input[value]").click(
			function(){
				$("section.index").hide()
				$("section.zhuanpan").show()
				$(".zhezhao").hide()
			}
	)//首页跳转至抽奖页面

	var rotateFunc = function(awards,angle,text){
		$(".png1").stopRotate();
		$(".png1").rotate({
			angle:0,
			duration: 3000,
			animateTo: angle+1800,
			callback:function(){
				$(".text").html(text)
				$(".jump1").show()
			}
		})
	}


	$(".png").click(function(){
		var i = 0;
		$.ajax({
			url:'http://zochdesign.com/weiphp3/index.php?s=/addon/Wheel/Wheel/is_over/id/1.html',
			type:'post',
			data:'&id=1',
			async : false,
			error:function(){
				alert('error');
			},
			success:function(data){
				i = data;
			}
		});

		var j = 0;
		$.ajax({
			url:'http://zochdesign.com/weiphp3/index.php?s=/addon/Wheel/Wheel/aa/id/1.html',
			type:'post',
			data:'{"id":id}',
			dataType:'json',
			async : false,
			error:function(){
				alert('error');
			},
			success:function(data){

				j = data.id;
				// k = datas.prize_name;



				// alert("firstName = " + data.id);
			}

		});


		if(i==0){//活动正常进行
			$(".zhezhao").show()

			switch(j){
				case 0:
					rotateFunc(1,120,'恭喜你中了 <br/> <br/> <em>移动电源</em>');
					break;
				case 1:
					rotateFunc(2,180,'恭喜你中了 <br/> <br/> <em>精美饭盒</em>');
					break;
				case 2:
					rotateFunc(3,240,'恭喜你中了 <br/> <br/> <em>时尚水杯</em>');
					break;
				case 3:
					rotateFunc(4,300,'恭喜你中了 <br/> <br/> <em>USB数据线</em>');
					break;
				case 4:
					rotateFunc(5,360,'恭喜你中了 <br/> <br/> <em>酷炫卡贴</em>');
					break;
				case 5:
					rotateFunc(6,60,'谢谢参与 <br/>  <br/> 请再接再厉');
					break;
			}
		}else if(i==1){
			$(".notstart").show();//活动尚未开始
		}else if(i==2){
			$(".finish1").show();//活动已结束
		}else if(i==3){
			$(".finish").show();//活动已结束
		}else{}
	});//if循环活动未开始至结束

	$(".notstart").click(
			function(){
				$("section.index").show()
				$("section.zhuanpan").hide()
				$(".zhezhao").hide()
				$(".notstart").hide()
			}
	)//点击隐藏未开始弹窗并跳转至首页

	$(".finish1").click(
			function(){
				$("section.index").show()
				$("section.zhuanpan").hide()
				$(".zhezhao").hide()
				$(".finish1").hide()
			}
	)

	$(".finish").click(
			function(){
				$("section.index").show()
				$("section.zhuanpan").hide()
				$(".zhezhao").hide()
				$(".finish").hide()
			}
	)//点击隐藏已结束弹窗并跳转至首页



	//$(".jump").click(function(){
//				$(".jump1").hide()
//				$(".zhezhao").hide()
//			}
//		)



	// $(".png").click(function() {
//                lottery();
//              });
//          function lottery() {
//                $.ajax({
//                    type: 'POST',
//                    url: 'ajax.php',
//                    dataType: 'json',
//                    cache: false,
//                    error: function() {
//                        alert('Sorry，出错了！');
//                        return false;
//                    },
//                    success: function(json) {
//                        $(".png").unbind('click').css("cursor", "default");
//                        var angle = json.angle; //指针角度
//                        var prize = json.prize; //中奖奖项标题
//                        $(".png1").rotate({
//                            duration: 3000, //转动时间 ms
//                            angle: 0, //从0度开始
//                            animateTo: 3600 + angle, //转动角度
//                            easing: $.easing.easeOutSine, //easing扩展动画效果
//                            callback: function() {
//                                var resulte = confirm('恭喜您中得' + prize + '\n想要继续吗？');
//                                if (resulte) { //若是点击确定，继续抽奖
//                                    lottery();
//                                }else {
//									$(".zhuanpan").hide();
//									$(".sign").show();
//									}
//
//                            }
//                        });
//                    }
//                });
//            }
	var num = 0;
	$(".buy").click(
			function(){
				if(num<0){
					$(".jump1").fadeOut(),
							$(".zhezhao").hide()
					num ++;
				}
				else{
					$(".zhuanpan").fadeOut(),
							$(".jump1").fadeOut(),
							$(".sign").delay(500).fadeIn()
				}

			}
	),

			$("#yanzheng").validate({
				rules:{
					name:{
						required:true,
						minlength:2,
						maxlength:10,
					},
					phone:{
						required:true,
						minlength:11,
						maxlength:11,
						digits:true,
					}
				},
				messages:{
					name:{
						required:"必须填写用户名",
						minlength:"最少输入两个字符",
						maxlength:"最多输入10个字符",
					},
					phone:{
						required:"必须填写您的手机号",
						minlength:"您输入的不足11位数",
						maxlength:"您输入的超过11位数",
						digits:"请输入有效的数字",
					}
				}
			});




});