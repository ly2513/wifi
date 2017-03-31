function AlertTips(msg,time){
	 $("#alertmsg").html(msg);
	 $("#msgbox").show();
	 setTimeout("$('#msgbox').hide()", time);
}
function isPhone(s){
	var patrn=/^1\d{10}$/;
	if (!patrn.exec(s)) return false
	return true
	
}
function isaccount(s){
	var patrn=/^[a-zA-Z0-9]{4,20}$/;
	if (!patrn.exec(s)) return false
	return true
}
function drop_confirm(msg, url){
    if(confirm(msg)){
        window.location = url;
    }
}