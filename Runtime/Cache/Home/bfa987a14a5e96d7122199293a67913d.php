<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>大转盘游戏</title>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link type="text/css" rel="stylesheet" href="<?php echo ADDON_PUBLIC_PATH;?>/css/reset.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo ADDON_PUBLIC_PATH;?>/css/zhuan.css" />
	<script type="text/javascript" src="<?php echo ADDON_PUBLIC_PATH;?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo ADDON_PUBLIC_PATH;?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo ADDON_PUBLIC_PATH;?>/js/jquery.easing.min.js"></script>
	<script type="text/javascript" src="<?php echo ADDON_PUBLIC_PATH;?>/js/jquery.rotate.min.js"></script>
	<script type="text/javascript" src="<?php echo ADDON_PUBLIC_PATH;?>/js/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo ADDON_PUBLIC_PATH;?>/js/jquery.metadata.js"></script>
	<script type="text/javascript" src="<?php echo ADDON_PUBLIC_PATH;?>/js/zhuan.js"></script>
</head>

<body>
<div class="body">
	<img class="background" src="<?php echo ADDON_PUBLIC_PATH;?>/image/background7.png" />
	<img class="notstart" src="<?php echo ADDON_PUBLIC_PATH;?>/image/notstart.png"/>
	<img class="finish" src="<?php echo ADDON_PUBLIC_PATH;?>/image/finish.png"/>
	<img class="finish1" src="<?php echo ADDON_PUBLIC_PATH;?>/image/finish1.png" />
	<section class="index">
		<!--<img class="word" src="image/7.png"/>-->
		<input type="button" value="我要抽奖" />
	</section><!--活动介绍首页-->

	<section class="zhuanpan" >
		<img class="png1" src="<?php echo ADDON_PUBLIC_PATH;?>/image/chou.png"/>
		<div class="zhezhao"></div>
		<img class="png" src="<?php echo ADDON_PUBLIC_PATH;?>/image/zhizhen.png" alt="转盘开启"/>
	</section><!--抽奖页面-->



	<div class="jump1" >
		<div class="jump">
			<p class="text" ></p>
		</div>
		<input class="buy" type="button" value="立即领奖" />
	</div><!--弹框页面-->


	<section class="sign">
		<form id="yanzheng" action="<?php echo U('insert?survey_id='.$_GET['survey_id']);?>&id=<?php echo ($_GET['id']); ?>" method="post" >
			<input class="name" type="text" name="name" placeholder="请输入您的姓名" value="<?php echo ($info["truename"]); ?>"/>
			<input class="phone" type="text" name="phone"  placeholder="请输入您的手机号码" value="<?php echo ($info["mobile"]); ?>" pattern="[0-9]{11}" />
			<input id="ok" type="submit" value="确定" />
		</form>
	</section><!--填写表单页面-->

</div>
</body>
</html>