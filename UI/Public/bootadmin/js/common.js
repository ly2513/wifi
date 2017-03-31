function drop_confirm(msg, url){
    if(confirm(msg)){
        window.location = url;
    }
}
function Tips(el,msg,state,time){
	var cname=state?"onError":"onSuccess";
	$('#'+el+"").html('').show().html('<div class="'+cname+'">'+msg+'</div>');
    setTimeout("$('#" + el + "').hide()", time);
}

function Tipbox(el,msg,state,time){
	var cname=state?"onError":"onSuccess";
	
   
}

function isPhone(s){
	var patrn=/^1\d{10}$/;
	if (!patrn.exec(s)) return false ;
	return true ;
	
}
function isNums(s){
	
	var patrn=/^[0-9]{1,20}$/; 
	if (!patrn.exec(s)) return false ;
	return true ;
	
}
function isaccount(s){
	var patrn=/^[a-zA-Z0-9]{4,20}$/;
	if (!patrn.exec(s)) return false ;
	return true ;
}
function AlertTips(msg,time){
	 $("#alertmsg").html(msg);
	 $(".alert").show();
	 setTimeout("$('.alert').hide()", time);
}