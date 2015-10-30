var hourlist=[];
for(var i=0;i<=24;i++){
	if(i<10){
		hourlist.push(["0"+i,i+"点"]);
	}else{
		hourlist.push([i,i+"点"]);
	}
}
function init_chart(data){
	var bt=[];
	var bt_reg=[];
	var bt_phone=[];
	var bt_log=[];
	var bt_key=[];
	data=eval(data)  ;
	for(var vo in data){
		bt.push([data[vo].t,data[vo].ct]);
		bt_reg.push([data[vo].t,data[vo].ct_reg]);
		bt_phone.push([data[vo].t,data[vo].ct_phone]);
		bt_key.push([data[vo].t,data[vo].ct_key]);
		bt_log.push([data[vo].t,data[vo].ct_log]);
	}
	var ds=[{label:"总数",data:bt},{label:"注册认证",data:bt_reg},{label:"手机认证",data:bt_phone},{label:"一键上网",data:bt_key},{label:"帐号登录",data:bt_log},];
	$.plot($("#statsChart"), ds, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:{show: true,}}});
	ct_auth(data);
}

function ct_auth(data){
	var sum=0;
	for(var vo in data){
		sum+=parseFloat( data[vo].ct);
	}
	$("#sumauth").text(sum);
}
function ct_ad(data){
	var sum=0;
	for(var vo in data){
		sum+=parseFloat( data[vo].showup);
	}
	$("#sumad").text(sum);
}

function ct_user(data){
	var sum=0;
	for(var vo in data){
		sum+=parseFloat( data[vo].totalcount);
	}
	$("#sumuser").text(sum);
}

function ct_shopad(data){
	var sum=0;
	for(var vo in data){
		sum+=parseFloat( data[vo].showup);
	}
	$("#sumshopad").text(sum);
}

function authchart(authurl){
		  $.ajax({
			  url: authurl,
		        type: "get",
				data:{
					'mode':'today'
					},
				dataType:'json',
				success:function(data){
					init_chart(data);
				}
			  });
	  	  
}
function mchart(authurl){
	$.ajax({
		  url: authurl,
	        type: "get",
			data:{
				'mode':'today'
				
				},
			dataType:'json',
			success:function(data){
					data=eval(data);
					var bt_total=[];
					var bt_reg=[];
					var bt_phone=[];
					data=eval(data);
					for(var vo in data){
						bt_total.push([data[vo].t,data[vo].totalcount]);
						bt_reg.push([data[vo].t,data[vo].regcount]);
						bt_phone.push([data[vo].t,data[vo].phonecount]);
					}
					var dataset=[{label:"注册总人数",data:bt_total},{label:"帐号注册",data:bt_reg},{label:"手机注册",data:bt_phone}];
					$.plot($("#userchart"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:{ show: true,}}});
				ct_user(data);	
			}
		  });
}
function shopad(authurl){
	$.ajax({
			  url: authurl,
		        type: "get",
				data:{
					'mode':'today'
					},
				dataType:'json',
				success:function(data){
						var bt=[];
						var bt_hit=[];
						data=eval(data);
						for(var vo in data){
							
							bt.push([data[vo].t,data[vo].showup]);
							bt_hit.push([data[vo].t,data[vo].hit]);
						}
						var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}];
						 $.plot($("#shopadchart"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:  { show: true,}}});
							ct_shopad(data);
				}
			  });
}
function adchar(charurl){
$.ajax({ url: charurl,
		        type: "get",
				data:{
					'mode':'today'
					
					},
				dataType:'json',
			
				success:function(data){
						var bt=[];
						var bt_hit=[];
						data=eval(data);
						for(var vo in data){
							
							bt.push([data[vo].t,data[vo].showup]);
							bt_hit.push([data[vo].t,data[vo].hit]);
						}
						var dataset=[{label:"广告展示",data:bt},{label:"广告点击",data:bt_hit}];
						 $.plot($("#adchart"), dataset, {grid: { hoverable: true, clickable: true, borderColor:'#000',borderWidth:1},xaxis:{ticks:hourlist},series:{lines:{fill:true, show: true}, points:{ show: true,}}});
						
							ct_ad(data);
				}
			  });
}
function getversion(dt){
	$.ajax({ url: "http://wifi.ahwifi.com/index.php/api/index/version",
        type: "get",
		data:dt,
		dataType:'json',
		success:function(data){
			
				if(data.up){
					if(data.show){
						$("#vermsg").html(data.tips);
						$(".alert").show();
					}
				}else{
					$("#vermsg").html("您当前的系统为最新版本");
					$(".alert").show();
				}
		}
	  });
	
}
