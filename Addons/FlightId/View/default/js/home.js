// JavaScript Document

$('#born').click(function(){
	
   $.Dialog.loading();
   $.post($('#yanzheng').attr('action'),
   $('#yanzheng').serializeArray(),
   function(data){
		window.location.href = data.url;
   });
   
});

$(document).ready(function() {
    $("#yanzheng").validate({
			rules:{
					
					phone:{
							required:true,
							minlength:11,
							maxlength:11,
							digits:true,
						}
				},
			messages:{
					
					phone:{
							required:"必须填写您的手机号",
							minlength:"您输入的不足11位数",
							maxlength:"您输入的超过11位数",
							digits:"请输入有效的数字",
						}
				}
		});
});

