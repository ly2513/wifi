var G = {};

G.createFnQueue = function(shift) {
	var _list = [];

	return {
		add	: function(fn) {
			if ($.isFunction(fn)) 
				_list.push(fn);
		},

		exec	: function(o) {
			if (shift !== false) {
				while (_list.length > 0) {
					_list.shift()(o);
				}
			}
			else {
				for (var i = 0, len = _list.length; i < len; i++) {
					if (_list[i](o) === false) {
						return false; // 类似事件的回调函数
					}
				}
			}
		},

		clear	: function() {
			_list.length = 0;
		}
	};
};

G.app = {}; // 应用
G.logic = {}; // 业务公共逻辑相关
G.ui = {}; // 界面相关
G.util = {}; // 工具相关

if ($.browser.msie && parseInt($.browser.version, 10) < 7) {
	try {
		document.execCommand("BackgroundImageCache", false, true);
	} catch(e) {}
} 
/*
 * Cookie 相关操作
*/
G.util.cookie = {
	get	: function(name) {
		var r = new RegExp("(^|;|\\s+)" + name + "=([^;]*)(;|$)");
		var m = document.cookie.match(r);
		return (!m ? "": unescape(m[2]));
	},

	add	: function(name, v, path, expire, domain) {
		var s = name + "=" + escape(v)
			+ "; path=" + ( path || '/' ) // 默认根目录
			+ (domain ? ("; domain=" + domain) : ''); 
		if (expire > 0) {
			var d = new Date();
			d.setTime(d.getTime() + expire * 1000);
			s += ";expires=" + d.toGMTString();
		}
		document.cookie = s;
	},

	del	: function(name, domain) {
		document.cookie = name + "=;path=/;" +(domain ? ("domain=" + domain + ";") : '') +"expires=" + (new Date(0)).toGMTString();
	}
}; 

/*
 * token 相关操作
*/
G.util.token = {
	//给连接加上token
	addToken : function(url,type){
		//type标识请求的方式,jq标识jquery，lk标识普通链接,fr标识form表单,ow打开新窗口
		var token=this.getToken();
		//只支持http和https协议，当url中无协议头的时候，应该检查当前页面的协议头
		if(url=="" || (url.indexOf("://")<0?location.href:url).indexOf("http")!=0){
			return url;
		}
		if(url.indexOf("#")!=-1){
			var f1=url.match(/\?.+\#/);
			 if(f1){
				var t=f1[0].split("#"),newPara=[t[0],"&g_tk=",token,"&g_ty=",type,"#",t[1]].join("");
				return url.replace(f1[0],newPara);
			 }else{
				var t=url.split("#");
				return [t[0],"?g_tk=",token,"&g_ty=",type,"#",t[1]].join("");
			 }
		}
		//无论如何都把g_ty带上，用户服务器端判断请求的类型
		return token==""?(url+(url.indexOf("?")!=-1?"&":"?")+"g_ty="+type):(url+(url.indexOf("?")!=-1?"&":"?")+"g_tk="+token+"&g_ty="+type);
	},
	//获取转换后的token
	getToken : function(){
		var skey=G.util.cookie.get("skey"),
			token=skey==null?"":this.time33(skey);
			return token;
	},
	//skey转token
	time33 : function(str){
		//哈希time33算法
		for(var i = 0, len = str.length,hash = 5381; i < len; ++i){
		   hash += (hash << 5) + str.charAt(i).charCodeAt();
		};
		return hash & 0x7fffffff;
	}
}


/*新增token处理*/
G.util.getACSRFToken=function(){
        if(G.util.cookie.get("g_tk")){
		  return G.util._DJB(G.util.cookie.get("g_tk"))
		}else{
		  return false;
		};
}

G.util._DJB=function(str){
		var hash = 5381;
		for(var i = 0, len = str.length; i < len; ++i){
			hash += (hash << 5) + str.charAt(i).charCodeAt();
		}
		return hash & 0x7fffffff;
	}

G.util.token_post = function(options){
    var opt=jQuery.extend({
			  "type":"POST",
			  "cache":false,
			  "dataType":"json",
			  "timeout":8000
	        }, options);
	
	//加上token值
	if(G.util.getACSRFToken()){
		opt.url=options.url+"&token="+G.util.getACSRFToken();
	}
	
	//调用jQuery AJAX
	jQuery.ajax(opt);
};
 
G.util.parse = {
	url	: function(){

		var _myDecode = function(q){
			var q = (q + '').replace(/(&amp;|\?)/g, "&").split('&');
			var p = {};
			var c = q.length;
			for (var i = 0; i < c; i++) {
				var pos = q[i].indexOf('=');
				if ( - 1 == pos) continue;
				p[q[i].substr(0, pos).replace(/[^a-zA-Z0-9_]/g, '')] = unescape(q[i].substr(pos + 1));
			}

			return p;
		};

		var hash = location.href.toString().indexOf('#');
		if(hash < 0) hash = '';
		else {
			hash = location.href.toString().substring(hash, location.href.toString().length);
		}
		return {
			search	: _myDecode(location.search.substr(1)),
			hash	: _myDecode(hash)
		};
	},
}; 
/**
 * 请求本地存储
 * 避免操作的紊乱
 * @param Function fn 回调函数，它的参数是cache对象
 */
G.util.localShare = (function(){
	// 事件队列
	var _queue = G.createFnQueue(),
		_scriptLoaded = 0,
		_localCache = false;

	return function(fn) {
		_queue.add(fn);

		if (_scriptLoaded == 2 && _localCache) { // 加载已完成
			_queue.exec(_localCache);
			return;
		}

		if (_scriptLoaded == 1) { // 加载中
			return;
		}

		_scriptLoaded = 1;

		var ver = '1.1';
		$.ajax({
			dataType	: 'script',
			crossDomain	: true,
			cache	: true,
			scriptCharset	: 'gb2312',
			success	: function() {
				G.app.localShare(function() {
					_scriptLoaded = 2;
					_localCache = this,
					_queue.exec(_localCache);
				});
			}
		});
	};
})();
 
G.util.ping = {
	VISIT_INFO_KEY	: 'vinfo',
	_visMap	: ['lastVisit'],
	_performance	: false,

	getVisitInfo	: function() {
		var self = G.util.ping,
			visitInfo = G.util.cookie.get(self.VISIT_INFO_KEY),
			ret = {};

		visitInfo = visitInfo.split(',');
		$.each(self._visMap, function(k, v) {
			ret[v] = visitInfo[k] || '';
		});

		return ret;
	},

	setVisitInfo	: function(key, val) {
		var self = G.util.ping,
			visitInfo = self.getVisitInfo(),
			p = {},
			r = [];

		if (arguments.length < 2) {
			p = key;
		}
		else {
			p[key] = val;
		}

		visitInfo = $.extend(visitInfo, key);
		$.each(self._visMap, function(k, v) {
			r[k] = visitInfo[v] || '';
		});

		G.util.cookie.add(self.VISIT_INFO_KEY, r.join(','), '/', 24 * 3600 * 365, '.'+G.domain);
	},
}; 
(function(G, $, undefined){
	function tip(opt){
        var instanceOf = function(o, type) {
            return (o && o.hasOwnProperty && (o instanceof type));
        };
        if (!(instanceOf(this, tip))) {
            return new tip(opt);
        }
        opt = jQuery.extend({}, {
			"position" : "rightBottom",  // 提示tip相对于target的位置, 可选：'leftTop','rightTop','rightBottom','leftBottom'
			"distance" : 20,  //尖角相对于tip顶点的距离
			"width" : "120",
			"html" : "",	//提示信息
			"target" : null,	//tip相对停靠的节点, 类型： selector
			"buttons" : null,	//tip中的按钮文字, 如 ['确定', '取消']
			"group" : null,		//tip所属的组，当设定了这个参数，属于同一组的tip将在document中最多显示一个
			"className" : "global_tip",	//tip最外层 节点的样式
			"time" : null // time毫秒后自动关闭, 当存在按钮时，该参数无效
		//  "click_1" : function(){}	//点击第n个按钮时的回调函数, 从1开始
		}, opt || {});

		var self = this, target = $(opt.target), instance = target.data('tipInstnace');
		if(instance)
			instance.close();

		//属于同一组的tip只显示一个
		tip.instance = tip.instance || [];
		if(opt.group){
			for(var i = 0, len = tip.instance.length; i < len; i++){
				if(tip.instance[i].opt.group === opt.group){
					tip.instance[i].close();
				}
			}
		};

		var showButtons = (opt.buttons && !$.isArray(opt.buttons) ) || ($.isArray(opt.buttons) && opt.buttons.length > 0 );
		this.element = $('<div class="'+opt.className+'"><div class="content">'+opt.html+'</div>'+ ( showButtons ? '<div class="buttons"></div>' : '') +'<span class="arrow">◆<span class="inner">◆</span></span></div>').css('width', opt.width);
		this.opt = opt;
		this.opt.id = new Date().getTime();

		//展现按钮
		if(showButtons){
			var str = $.map( $.isArray(opt.buttons)? opt.buttons : [opt.buttons], function(value, index){
				return '<a href="#" onclick="return false" class="'+ ( index == 0 ? 'btn_strong' : 'btn_common' )+'">'+ value +'</a>';
			}).join('');
			var buttons = this.element.find('.buttons');
			buttons.append(str);
			buttons.find('a').each(function(index){
				$(this).click(function(){
					if( self.element.triggerHandler('click_' + ( index + 1 )) !== false )
						self.close();
				});
			}).first().focus();
		}
		this.element.appendTo(document.body);

		//time毫秒后自动关闭
		if(!showButtons && opt.time ){
			this.timer = setTimeout(function(){
				self.close();
			}, parseInt(opt.time, 10));
		}

		//确定尖角的位置
		var arrowCss = {}, innerCss = {}, distance = parseInt(opt.distance, 10);
		var sizeTop = $.browser.mozilla ? 12 : ($.browser.webkit ? 12 : 13)
		var sizeBottom = $.browser.mozilla ? 10 : ($.browser.webkit ? 10 : 10)
		switch(opt.position){
			case "leftTop" :
				arrowCss = { bottom : -1 * sizeTop, right : distance };
				innerCss = { top : -1 };
				break;
			case "rightTop" :
				arrowCss = { left : distance, bottom : -1 * sizeTop};
				innerCss = { top : -1 };
				break;
			case "leftBottom" :
				arrowCss = {top : -1 * sizeBottom, right : distance };
				innerCss = {top : 1 }
				break;
			default :
				arrowCss = { top : -1 * sizeBottom, left : distance};
				innerCss = { top : 1 };
				break;
		}

		var arrow = $(".arrow", this.element);
		arrow.css(arrowCss);
		$(".inner", this.element).css(innerCss);

		//确定整个tip的位置
		var arrowOffset = arrow.offset(),  targetOffset = target.offset(), point1, point2;
		switch(opt.position){
			case "leftTop":
			case "rightTop":
				point1 = {
					x : parseInt(arrowOffset.left, 10) + parseInt(arrow.width(), 10) / 2,
					y : parseInt(arrowOffset.top, 10) + parseInt( arrow.height(), 10)
				};

				point2 = {
					x : parseInt(targetOffset.left, 10) + parseInt(target.width(), 10) / 2,
					y : parseInt(targetOffset.top, 10)
				}
				break;
			default:
				point1 = {
					x : parseInt(arrowOffset.left, 10) + parseInt(arrow.width(), 10) / 2,
					y : parseInt(arrowOffset.top, 10)
				};

				point2 = {
					x : parseInt(targetOffset.left, 10) + parseInt(target.width(), 10) / 2,
					y : parseInt(targetOffset.top, 10) + parseInt(target.height(), 10)
				}
				break;
		}
		var pos = this.element.position();
		this.element.css({
			"left" : parseInt(pos.left) - point1.x + point2.x,
			"top" : parseInt(pos.top) - point1.y + point2.y
		});

		var self = this;
		self._close = function(){
			self && self.close();
		};
		$(window).bind('resize', self._close);

		tip.instance.push(this);
		target.data('tipInstnace', this);

		//通过形参绑定事件
		for(var name in opt){
			if( /^click_\d$/.test(name.toString())){
				this.bind(name, opt[name]);
			}
		}
	};

	$.extend(tip.prototype, {
		//绑定按钮事件
		bind : function(){
			this.element.bind.apply(this.element, $.makeArray(arguments) );
		},

		//关闭tip
		close: function(){
			clearTimeout(this.timer);
			$(window).unbind('resize', self._close);
			this.element.data('tipInstnace', null);
			for(var i = 0, len = tip.instance.length; i < len; i++ ){
				if( tip.instance[i].opt.id == this.opt.id){
					tip.instance.splice(i, 1);
					break;
				}
			}
			this.element.remove();
		},

		//tip中的按钮
		getButtons :function(){
			return this.element.find(".buttons>a");
		},

		//tip最外层dom(jquery对象)
		getElement : function(){
			return this.element;
		},

		//tip是否显示
		isShow: function(){
			return this.element[0].style.display !== 'none'
		},

		//显示tip
		show : function(){
			this.element[0].style.display = 'block';
		},

		//隐藏tip
		hide: function(){
			this.element[0].style.display = 'none';
		}
	});
	G.ui.arrowTip = tip;
})(G, jQuery); 
