function Tips(el,msg,state,time){
	var cname=state?"onError":"onSuccess";
	$('#'+el+"").html('').show().html('<div class="'+cname+'">'+msg+'</div>');
    setTimeout("$('#" + el + "').hide()", time);
}

function isPhone(s){
	var patrn=/^1[0-9]{10}$/;
	if (!patrn.exec(s)) return false
	return true
	
}