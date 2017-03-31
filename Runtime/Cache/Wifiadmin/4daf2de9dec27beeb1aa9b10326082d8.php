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
      <link href="<?php echo ($Theme['P']['root']); ?>/font/italic.css" rel='stylesheet' type='text/css' />

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

   
	<!-- libraries -->
<link href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/compiled/tables.css" type="text/css" rel="stylesheet"  />
<link rel="stylesheet" href="<?php echo ($Theme['P']['root']); ?>/bootadmin/css/lib/bootstrap.datepicker.css" />
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
                        
		                  </li><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
           </li><?php endif; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>

    <!-- end sidebar -->

	<!-- main container -->
    <div class="content" style="height: 100%;">
  
      <div id="pad-wrapper">
          <!-- orders table -->
            <div class="table-wrapper orders-table section">
                <div class="row head">
                    <div class="col-md-12">
                        <h4>注册用户</h4>
                    </div>
                </div>
		<form method="post">
                <div class="row-fluid form-page">
                   
                  <div class="form-wrapper">
                  <div class="span6 column">
                  	
                  	<div>
                  	<div class="span6">
                  			 <label class="span3">开始时间:</label>
 <input type="text" id="sdate" value="<?php echo ($qjson->sdate); ?>" data-date-format="yyyy-mm-dd" class="pull-left datepicker" name="sdate" readonly="readonly">
 
                  		</div>
                  		<div class="span6">
                  			<label class="span3">结束时间:</label>
   <input type="text" id="edate" value="<?php echo ($qjson->edate); ?>" data-date-format="yyyy-mm-dd" class="pull-left  datepicker" name="edate" readonly="readonly">
    	
                  		</div>
                  	</div>
                  		
                  		
                  <div>
                  <div class="span6">
                  			 <label class="span3">用户名:</label>
                             <input type="text" name="uname" value="<?php echo ($qjson->uname); ?>">
                  		</div>
                  	<div class="span6">
                  	<label class="span3">手机号码:</label>
                    <input type="text" name="uphone" value="<?php echo ($qjson->uphone); ?>">
                           
                           
                  	</div>
                  </div>
                  <div>
                  	<div class="span6">
                  			 <label class="span3">用户类别:</label>
                               <select name="mode">
                               	<option value="-1" <?php if($qjson->mode=="-1"){echo "selected";} ?>>所有</option>
                               		<option value="0" <?php if($qjson->mode=="0"){echo "selected";} ?>>帐号注册</option>
                               			<option value="1" <?php if($qjson->mode=="1"){echo "selected";} ?>>手机注册</option>
                               	 </select>
                  	</div>
                  		<div class="span6">
                  		</div>
                  		</div>
                  		
                  </div>
                  
                  </div>
                 
                  <div class="span2">
                  	<div>
                  		 	<input  type="submit" class=" span6 btn btn-success" value="查询">
                  	</div>
                  	<div>
                  	</br>
        			<a href="<?php echo U('Report/downuser');?>" class="span6 btn btn-info" >导出</a>
                  	</div>
                  	<input type="hidden" name="query" value="1">
                 
                  	
                  
                  </div>
                  </div>
                    		</form>	
                               
                         </div>

                <div class="">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-1">
                                   		 编号
                                </th>
                                <th class="col-md-2">
                                    <span class="line"></span>
                                   	添加日期
                                </th>
                                  <th class="col-md-2">
                                    <span class="line"></span>
                                    	所属商户
                                </th>
                                <th class="col-md-2">
                                    <span class="line"></span>
                                    	用户名
                                </th>
                                <th class="col-md-2">
                                    <span class="line"></span>
                                    	手机号码
                                </th>
                                <th class="col-md-1">
                                    <span class="line"></span>
                                  		Mac地址
                                </th>
                                <th class="col-md-2">
                                    <span class="line"></span>
                                   		注册方式
                                </th>
                                   <th class="col-md-2">
                                    <span class="line"></span>
                                   		 浏览器
                                </th>
                              
                            </tr>
                        </thead>
                        <tbody>
                        	<?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!-- row -->
                            <tr class="<?php if(($i) == "1"): ?>first<?php endif; ?>">
                                <td>
                                   <?php echo ($i); ?>
                                </td>
                                <td>
                                   <?php echo (date('Y-m-d ',$vo["add_time"])); ?>
                                </td>
                                  <td>
                                   <?php echo ($vo["shopname"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["user"]); ?>
                                </td>

                                <td>
                                  <?php echo ($vo["phone"]); ?>
                                </td>
                                 <td>
                                  <?php echo ($vo["mac"]); ?>
                                </td>
                                 <td>
                                  <?php echo getAuthWay($vo['mode']);?>
                                </td>
                                  <td>
                                  <?php echo ($vo["browser"]); ?>
                                </td>
                               
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                           
                        </tbody>
                    </table>
                </div>
            
            	<div class="pagination pull-right">
            		<?php echo ($page); ?>
            	</div>
            </div>
            <!-- end orders table -->
          </div>
    </div>


	<!-- scripts -->
 <script src="<?php echo ($Theme['P']['js']); ?>/jquery.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>
    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/jquery.uniform.min.js"></script>
      <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.datepicker.js"></script> 
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
  <script type="text/javascript">
                     

                        $(function () {
                        	 $('.datepicker').datepicker();
                            // add uniform plugin styles to html elements
                            $("input:checkbox, input:radio").uniform();

                        });
                    </script>
</body>
</html>