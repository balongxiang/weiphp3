<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|WeiPHP管理平台</title>
    <link href="/weiphp3/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/weiphp3/Public/Admin/css/base.css?v=<?php echo SITE_VERSION;?>" media="all">
    <link rel="stylesheet" type="text/css" href="/weiphp3/Public/Admin/css/common.css?v=<?php echo SITE_VERSION;?>" media="all">
    <link rel="stylesheet" type="text/css" href="/weiphp3/Public/Admin/css/module.css?v=<?php echo SITE_VERSION;?>" />
    <link rel="stylesheet" type="text/css" href="/weiphp3/Public/Admin/css/style.css?v=<?php echo SITE_VERSION;?>" media="all">
    <link rel="stylesheet" type="text/css" href="/weiphp3/Public/Admin/css/store.css?v=<?php echo SITE_VERSION;?>" media="all">
	<link rel="stylesheet" type="text/css" href="/weiphp3/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css?v=<?php echo SITE_VERSION;?>" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/weiphp3/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/weiphp3/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/weiphp3/Public/Admin/js/jquery.mousewheel.js?v=<?php echo SITE_VERSION;?>"></script>
    <!--<![endif]-->
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo" <?php if(C('SYSTEM_LOGO')) { ?> style="float: left;margin-left: 2px;width: 198px;height: 49px;background:url('<?php echo C('SYSTEM_LOGO');?>') no-repeat " <?php } ?>></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav">
            <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="<?php echo (get_nickname($mid)); ?>"><?php echo (get_nickname($mid)); ?></em></li>
                <li><a href="<?php echo U('Home/Index/index');?>">返回前台</a></li>
                <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                <?php if(!empty($_extra_menu)): ?>
                    <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                    <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
                        <ul class="side-sub-menu">
                            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                    <a class="item" href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul><?php endif; ?>
                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                <span>您的位置:</span>
                <?php $i = '1'; ?>
                <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                    <?php else: ?>
                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                    <?php $i = $i+1; endforeach; endif; ?>
            </div><?php endif; ?>
            <!-- nav -->
            

            
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>用户列表</h2>
	</div>
	<div class="cf">
		<div class="fl">
            <a class="btn" href="<?php echo U('add');?>">新 增</a>
            <button class="btn ajax-post" url="<?php echo U('changeStatus?method=resumeUser');?>" target-form="ids">启 用</button>
            <button class="btn ajax-post" url="<?php echo U('changeStatus?method=forbidUser');?>" target-form="ids">禁 用</button>
            <button class="btn ajax-post confirm" url="<?php echo U('changeStatus?method=deleteUser');?>" target-form="ids">删 除</button>
            <button class="btn setting_group" url="<?php echo U('changeGroup');?>" target-form="ids">设置用户组</button>
        </div>

        <!-- 高级搜索 -->
		<div class="search-form fr cf">
<div class="sleft">
		    <select name="group">
            <option value="<?php echo U('Admin/User/index',array('group_id'=>0,'nickname'=>I('nickname')));?>" <?php if(($$group_id) == "0"): ?>selected<?php endif; ?> >全部用户</option>
			    <?php if(is_array($auth_group)): $i = 0; $__LIST__ = $auth_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo U('Admin/User/index',array('group_id'=>$vo['id'],'nickname'=>I('nickname')));?>" <?php if(($vo['id']) == $group_id): ?>selected<?php endif; ?> ><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		    </select>
    </div>
			<div class="sleft">
				<input type="text" name="nickname" class="search-input" value="<?php echo I('nickname');?>" placeholder="请输入用户昵称或者ID">
				<a class="sch-btn" href="javascript:;" id="search" url="<?php echo U('index',array('group_id'=>$group_id));?>"><i class="btn-search"></i></a>
			</div>
		</div>
    </div>
    <!-- 数据列表 -->
    <div class="data-table table-striped">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">UID</th>
		<th class="">昵称</th>
        <th class="">用户组</th>
<!--        <th class="">公众号数（管理数/创建数）</th>
        <th class="">最多可创建公众号数</th>-->
		<th class="">金币值</th>
        <th class="">经验值</th>
		<th class="">登录次数</th>
		<th class="">最后登录时间</th>
		<th class="">最后登录IP</th>
		<th class="">状态</th>
        <th class="">审核</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<?php if(!empty($_list)): if(is_array($_list)): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><input class="ids" type="checkbox" name="id[]" value="<?php echo ($vo["uid"]); ?>" /></td>
			<td><?php echo ($vo["uid"]); ?> </td>
			<td><?php echo ($vo["nickname"]); ?></td>
            <td><?php echo ($vo["group"]); ?></td>
<!--            <td><?php echo ($vo["admin_public_count"]); ?></td>
            <td><?php echo ($vo["public_count"]); ?></td>-->
			<td><?php echo ($vo["score"]); ?></td>
            <td><?php echo ($vo["experience"]); ?></td>
			<td><?php echo (intval($vo["login_count"])); ?></td>
			<td><span><?php echo (time_format($vo["last_login_time"])); ?></span></td>
			<td><span><?php echo long2ip($vo['last_login_ip']);?></span></td>
			<td><?php if(($vo["status"]) == "1"): ?><a href="<?php echo U('User/changeStatus?method=forbidUser&id='.$vo['uid']);?>" class="ajax-get"><?php echo ($vo["status_text"]); ?></a>
				<?php else: ?>
				<a href="<?php echo U('User/changeStatus?method=resumeUser&id='.$vo['uid']);?>" class="ajax-get"><?php echo ($vo["status_text"]); ?></a><?php endif; ?></td>
            <td><?php if(($vo["is_audit"]) == "1"): ?><a href="<?php echo U('User/changeStatus?method=audit_0&id='.$vo['uid']);?>" class="ajax-get"><?php echo ($vo["audit_text"]); ?></a>
				<?php else: ?>
				<a href="<?php echo U('User/changeStatus?method=audit_1&id='.$vo['uid']);?>" class="ajax-get"><?php echo ($vo["audit_text"]); ?></a><?php endif; ?></td>
			<td>
            <?php if($vo[is_admin]): ?><a href="<?php echo U('AuthManager/public_count?uid='.$vo['uid']);?>">公众号设置</a>
                <a href="<?php echo U('ManagerMenu/lists',array('uid'=>$vo['uid']));?>">导航管理</a><?php endif; ?>    
                <a href="<?php echo U('AuthManager/group?uid='.$vo['uid']);?>" class="confirm ajax-get">删除</a>
                </td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<?php else: ?>
		<td colspan="11" class="text-center"> aOh! 暂时还没有内容! </td><?php endif; ?>
	</tbody>
    </table> 
	</div>
    <div class="page">
        <?php echo ($_page); ?>
    </div>
    
    <!-- 用户分组弹框 -->
    <div class="group_html" style="display:none">
    	<div class="manage_group normal_dialog">
            <h6>设置用户分组</h6>
            <a class="close" href="javascript:;" onClick="$('.thinkbox-modal-blackout-default,.thinkbox-default').hide()">关闭</a>
            <div class="content">
            <select name="type" id="select_type" style="width:25%">
                    <option value="0">移入</option>
                    <option value="1">移出</option>
            </select>
            <select name="group" id="select_group" style="width:70%">
                <?php if(is_array($auth_group)): $i = 0; $__LIST__ = $auth_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <div class="btn_wrap"><button class="btn setting_group" url="<?php echo U('changeGroup');?>" target-form="ids">确定</button></div>
            </div>
        </div>
    </div>

        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">感谢使用<a href="http://www.weiphp.cn" target="_blank">WeiPHP</a>管理平台</div>
                <div class="fr">V<?php echo C('SYSTEM_UPDATRE_VERSION');?></div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    <script type="text/javascript">
	var  IMG_PATH = "/weiphp3/Public/Admin/images";
	var  STATIC = "/weiphp3/Public/static";
	var  ROOT = "/weiphp3";
	var  UPLOAD_PICTURE = "<?php echo U('home/File/uploadPicture',array('session_id'=>session_id()));?>";
	var  UPLOAD_FILE = "<?php echo U('File/upload',array('session_id'=>session_id()));?>";
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "/weiphp3", //当前网站地址
            "APP"    : "/weiphp3/index.php?s=", //当前项目地址
            "PUBLIC" : "/weiphp3/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    <script type="text/javascript" src="/weiphp3/Public/static/think.js?v=<?php echo SITE_VERSION;?>"></script>
    <script type="text/javascript" src="/weiphp3/Public/Admin/js/common.js?v=<?php echo SITE_VERSION;?>"></script>
    <script type="text/javascript">
        +function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();
    </script>
    
	<script src="/weiphp3/Public/static/thinkbox/jquery.thinkbox.js?v=<?php echo SITE_VERSION;?>"></script>

	<script type="text/javascript">
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});
	//回车搜索
	$(".search-input").keyup(function(e){
		if(e.keyCode === 13){
			$("#search").click();
			return false;
		}
	});
	$('select[name=group]').change(function(){
		location.href = this.value;
	});	
    //导航高亮
    highlight_subnav('<?php echo U('User/index');?>');
	//设置分组
	$('.setting_group').click(function(){
		var html = $($('.group_html').html());
		query = $('.ids').serialize();
		if(query==""){
			alert('请选择用户');
			return;
		}
		$.thinkbox(html);
		$('button',html).click(function(){
			that = this;
			target = $(that).attr('url');
			query = query + "&group_id="+$('#select_group', html).val() + "&type="+$('#select_type', html).val();
			$(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
				location.reload();
				$('.thinkbox-modal-blackout-default,.thinkbox-default').hide();
            });
		})
	})
	</script>

</body>
</html>