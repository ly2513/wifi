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
  	<div class="container-fluid">

  
      <div id="pad-wrapper">
        <div class="row-fluid ">
                    <h4 class="title">
                        在线路由统计<small></small>
                       
                    </h4>
                    <div id="pad-wrapper">
                            	<div class="btn-group pull-right">
                            	 <button class="hide " id="today"></button>
                            <button class="glow left " id="live">在线路由</button>
                            <button class="glow right" id="die">离线路由</button>
                        </div>
                    </div>
                    <div class="row span11">
                        <div id="placeholder" style="height:300px;"></div>
                    </div>
                </div>
<!-- orders table -->

            <div class="table-wrapper orders-table section">
                <div class="row head">
                    <div class="col-md-12">
                        <h4>统计列表</h4>
                    </div>
                </div>

                <div class="row filter-block">
                    <div class="pull-right">
                  
                    </div>
                </div>
				
                <div class="span8">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                               <th>ID</th>
	        				   <th>路由商家</th>
	        				   <th>最后在线时间</th>
                               
                            </tr>
                        </thead>
                         <tbody id="gridbox">
                        	
                           
                        </tbody>
                    </table>
                    <div id="ajaxpage" class="pagination pull-right">
            		
            		</div>
                </div>
            
            	
            </div>
           
            <!-- end orders table -->
            
          </div>
    </div>
	</div>

	<!-- scripts -->
 <script src="<?php echo ($Theme['P']['js']); ?>/jquery.min.js"></script>
<script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/bootstrap.min.js"></script>

    <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/theme.js"></script>
  <script src="<?php echo ($Theme['P']['root']); ?>/bootadmin/js/common.js"></script>
<script src="<?php echo ($Theme['P']['js']); ?>/flot/jquery.flot.js"></script> 
<script src="<?php echo ($Theme['P']['js']); ?>/flot/jquery.flot.pie.js"></script> 
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo ($Theme['P']['js']); ?>/flot/excanvas.min.js"></script><![endif]-->  

<script>


function AjaxPage(obj){
	var url=$(obj).attr('jump');
	$.getJSON(url,function(data){
		data=eval(data)  ;
		
		 rendertable(data);
	});
}

$(function () {
	
	var stack = 0, bars = true, lines = false, steps = false;
	
	    
	
  	  $("#today").bind("click",function(){
		  $.ajax({
			  url: '<?php echo U('report/routechart');?>',
		        type: "get",
				data:{
					'mode':'today',
					
					},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
						},
				success:function(jsondata){
						var json=eval(jsondata);
						
						var dataset=[];
						var piedata = [];
						
						piedata.push({ label: "在线路由:"+json[0].livecount+"台", data: Math.floor(json[0].livecount) });
								
						
										
						piedata.push({label:"离线路由:"+json[0].diecount+"台",data:Math.floor(json[0].diecount)});

						

						

						 $.plot($("#placeholder"), piedata, { 

							series: {
								pie: { 
									show: true,
									 
								}
						 
							},
							legend: {
						            show: false
						     },
							}
							
						 );
					
						
				}
			  });
  	  	  });

  	$("#live").bind("click",function(){
		  $.ajax({
			  url: '<?php echo U('report/routechart');?>',
		        type: "get",
				data:{
					'flag':'a',
					'info':1
					},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
						},
				success:function(data){
						var bt=[];
						data=eval(data)  ;
						
						 rendertable(data);
				}
			  });
	  	  });
  	$("#die").bind("click",function(){
		  $.ajax({
			  url: '<?php echo U('report/routechart');?>',
		        type: "get",
				data:{
					'flag':'d',
					'info':1
					},
				dataType:'json',
				error:function(){
						AlertTips("服务器忙，请稍候再试",2000);
						},
				success:function(data){
						var bt=[];
						data=eval(data)  ;
						
						 rendertable(data);
				}
			  });
	  	  });
		$("#today").trigger("click");
  	 
  	doRef();
});
function doRef(){
	setTimeout(function(){
		$("#today").trigger("click");
		},30000);
	
}
function rendertable(data){
	
	$("#gridbox").empty();
	$("#ajaxpage").empty();
	var trHtml="";
	var sumshow=0;
	var sumhit=0;
	var dt=data.list;
	for(var vo in dt){
		trHtml+="<tr>";
		trHtml+="<td>"+dt[vo].id+"</td>";

		trHtml+="<td>"+dt[vo].shopname+"</td>";
		trHtml+="<td>"+dt[vo].last_heartbeat_time+"</td>";
		trHtml+="</tr>";
		

	}
	
	$("#gridbox").append(trHtml);
	$("#ajaxpage").append(data.pg);
}


</script>
</body>
</html>