<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title><?php echo C('sitename');?>-管理后台</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<!-- bootstrap -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap.css" rel="stylesheet" />
       <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />
    <!-- libraries -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/icons.css" />


    <!-- open sans font -->
      <link href='<?php echo ($Theme['P']['root']); ?>/italic.css' rel='stylesheet' type='text/css' />

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

   
	<!-- libraries -->
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/uniform.default.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/select2.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/compiled/form-showcase.css" type="text/css" media="screen" />
	<link  href="<?php echo ($Theme['P']['root']); ?>/kindeditor/themes/default/default.css" type="text/css" rel="stylesheet">
<body>
    <!-- navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-inner">
            <button type="button" class="btn btn-navbar visible-phone" id="menu-toggler">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
            <a class="brand" href="<?php echo U('index');?>"><img src="<?php echo ($Theme['P']['img']); ?>/wifilogo-mini.png" /></a>
            

            <ul class="nav pull-right">        
            	<li class=" hidden-phone">
                	
                   		<a href="<?php echo U('Index/systemdata');?>">
                   		<?php if($authlist_count > 50000): ?><font color="red">
                   			<script>
                   			alert('警告：授权数据量过大，可能造成系统运行缓慢，请立即点击右上角"系统优化"功能进行优化，保证系统运行流畅');
                   			</script>
                   			
                   			系统优化(需优化)
                   			</font>
                   			<?php else: ?>
                   			系统优化<?php endif; ?>
                   		</a>
                </li>       
               <li class=" hidden-phone">
                    	<a href="<?php echo U('Index/liences');?>">应用授权</a>
                </li>
                
                <li class=" hidden-phone">
                    	<a href="javascript:void(0);">登录帐号:(<?php echo (session('adminmame')); ?>)</a>
                </li>
                 <li class=" hidden-phone">
                    	<a href="<?php echo U('Index/pwd');?>">修改密码</a>
                </li>
                <li class="settings hidden-phone">
                    <a href="<?php echo U('login/loginout');?>" role="button">
                        <i class="icon-share-alt"></i>
                    </a>
                </li>
            </ul>            
        </div>
    </div>
    <!-- end navbar -->
  <!-- sidebar -->
    <div id="sidebar-nav">
        <ul id="dashboard-menu">
        <?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['pid'] == 1): if(in_array($vo['id'],$navids)): if($vo['single'] == 1): if((strtolower($nownav['m']) == strtolower($vo['m']) ) && strtolower($nownav['a']) == strtolower($vo['a'])): ?><li class="active">
	                <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
	                </div>
	        		<?php else: ?>
	        		    <li><?php endif; ?>
           			    <a href="<?php echo U(''.$vo['m'].'/'.$vo['a'].'');?>"  >
                      <i class="<?php echo ($vo["ico"]); ?>"></i>
                      <span><?php echo ($vo["title"]); ?></span>
                    </a>
            	    </li>
       		  <?php else: ?>
       	
				      <?php if($nownav['a'] == $vo['id']): ?><li class="active">
                  <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
                  </div>
        		  <?php else: ?>
        		    <li><?php endif; ?>
       			  <a class="dropdown-toggle" href="#" >
                <i class="<?php echo ($vo["ico"]); ?>"></i>
                <span><?php echo ($vo["title"]); ?></span>
                <i class="icon-chevron-down"></i>
              </a>
              <?php if($nownav['a'] == $vo['id']): ?><ul class="active submenu">
        		  <?php else: ?>
        		    <ul class="submenu"><?php endif; ?>
         
        			<?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sonnode): $mod = ($i % 2 );++$i; if($sonnode['pid'] == $vo['id']): if(in_array($sonnode['id'],$navids)): ?><li>
		                    <a href="<?php echo U(''.$sonnode['m'].'/'.$sonnode['a'].'');?>"><?php echo ($sonnode['title']); ?></a>
                        <!-- <?php print_r($sonnode);?> -->
		                  </li><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
           </li><?php endif; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>

    <!-- end sidebar -->
	<!-- main container -->
    <div class="content" style="height: 100%;">
   	    <div class="container-fluid">
            <div id="pad-wrapper" class="form-page">
                <div class="row-fluid form-wrapper">
             		<div class="span12">
                        <h4>编辑广告</h4>
                    </div>
                    <!-- left column -->
                    <div class="span8 column">
                        <form name="doad"  action="__URL__/editad"  method="post" enctype="multipart/form-data" >
                            <div class="alert span10" style="display: none;">
						      <span id="alertmsg"></span>
						    </div>

                           <div class="field-box">
                                <label>广告位置:</label>
                             	<div class="span8">
                                    <label class="radio">
                                        <input type="radio" name="ad_pos" id="status1" value="0" <?php if(($info['ad_pos']) == "0"): ?>checked=''<?php endif; ?>/>
                                        	首页
                                    </label> 
                                    <label class="radio">
                                        <input type="radio" name="ad_pos" id="status1" value="1" <?php if(($info['ad_pos']) == "1"): ?>checked=''<?php endif; ?>  />
                                            认证页面
                                    </label>
                                </div>                   
                            </div>
                             <div class="field-box">
                                <label>广告类别:</label>
                             	 <div class="span8">
                                    <label class="radio">
                                        <input type="radio" name="mode" value="1" <?php if(($info['mode']) == "1"): ?>checked<?php endif; ?> />
                                        	图片广告
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="mode" value="0" <?php if(($info['mode']) == "0"): ?>checked<?php endif; ?>/>
                                        		图文广告
                                    </label>
                                </div>                   
                            </div>
                            	<div class="field-box">
                                <label>标题:</label>
                                <input class="span8" type="text"  data-toggle="tooltip" data-trigger="focus" title="请输入标题" data-placement="right" name="title" id="title" value="<?php echo ($info['title']); ?>"/>
                            </div>
                            <div class="field-box">
                                <label>内容:</label>
                                <textarea rows="" cols="" class="span8" name="info"><?php echo ($info["info"]); ?></textarea>
                            </div>
                           	<div class="field-box">
                                <label>排序:</label>
                                <input class="span8" type="text"  data-toggle="tooltip" data-trigger="focus" title="请输入广告排序：数字越大越靠后" data-placement="right" name="ad_sort" id="ad_sort" value="<?php echo ($info['ad_sort']); ?>"/>
                            </div>
                         	<div class="field-box">
                                <label>广告图片:</label>
                                <img src="<?php echo ($info['ad_thumb']); ?>" alt="" style="width:100px;100px">
                                <input type="file"  name="img" id="upload"/>
                            </div>
                            <div class="field-box ">
								<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
                                <input type="submit"   class="btn-glow primary " id="btn_save" value="保存信息">
                                &nbsp;
								<a class="btn-glow  " href="<?php echo U('index');?>">返回列表</a>
                            </div>
                        </form>
                    </div>
                    <!-- right column -->
                    <div class="span4 column pull-right">

                    </div>
                </div>
            </div>
        </div>
    </div>


	<!-- scripts -->

<script src="<?php echo ($Theme['P']['root']); ?>/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/kindeditor/lang/zh_CN.js" type="text/javascript"></script>
<script src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/jquery.uniform.min.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
<script>
	KindEditor.ready(function(K) {
		K.create('textarea[name="info"]', {
			autoHeightMode : true,
			items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template',  'cut', 'copy', 'paste',
				        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
				        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
				        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
				        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 
				        'flash', 'media', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
				        'anchor', 'link', 'unlink', '|', 'about'],
	        afterUpload:false,
	        allowFlashUpload:false,
	        allowFileUpload:false,
	        allowImageUpload:false,
	        allowMediaUpload:false,
		});
	});
</script>
<script type="text/javascript"> 
    $(function () {
        // add uniform plugin styles to html elements
        $("input:checkbox, input:radio").uniform();

    });
</script>
</body>
</html>