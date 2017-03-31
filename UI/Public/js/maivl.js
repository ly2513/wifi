/*navigator*/
var iNav = (function(){
	var types = {radio:1, checkbox:2, oneToggle:3}
	var nav = function(){
		this.type = types.radio;
		this.eIndex = 0;
		this.len = 0;
		this.eOn = false;
		this.reClick = false;
	}
	nav.prototype = {
		stype: function(typ){
			this.type = types[typ] || this.type;
			return this;
		},
		swth: function(el, els, fn){
			var that = this;
			that.el = el;
			that.els = els;
			switch(that.type){
				case types.radio:
					for(var i=0,ci; ci=els[i];i++){
						if(ci == el){
							that.eIndex = i;
							that.reClick = ci.classList.add("on");
						}else{
							ci.classList.remove("on");
						}
					}
					that.len = i;
				break;
				case types.checkbox:
					that.eOn = el.classList.toggle("on");
				break;
				case types.oneToggle:
					for(var i=0,ci; ci=els[i]; i++){
						if(ci == el){
							that.eIndex = i;
							that.eOn = ci.classList.toggle("on");
						}else{
							ci.classList.remove("on");
						}
					}
					that.len = i;
				break;
				default:
				break;
			}
			that.type = types.radio;
			if(typeof fn === "function"){fn.apply(that);}
			return that;
		}
	}
	return new nav();
})();


/*第三方插件：html模板生成器*/
var iTemplate = (function(){
	var template = function(){};
	template.prototype = {
		makeList: function(tpl, json, fn){
			var res = [], $10 = [], reg = /{(.+?)}/g, json2 = {}, index = 0;
			for(var el in json){
				if(typeof fn === "function"){
					json2 = fn.call(this, el, json[el], index++)||{};
				}
				res.push(
					 tpl.replace(reg, function($1, $2){
					 	return ($2 in json2)? json2[$2]: (undefined === json[el][$2]? json[el]:json[el][$2]);
					})
				);
			}
			return res.join('');
		}
	}
	return new template();
})();