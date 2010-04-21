function motiomera_ajax(url, method, params, autoRequest){

	var url = url;
	var method = method;
	var params = params;
	var response;
	var request;
	
	var _this = null;
	
	if(autoRequest === true)
		_makeRequest();
	
	this.makeRequest = function(){
		_this = this;
		this.addParam("rand", Math.random() * 10000);
		_makeRequest(_this);	
	}
	
	this.addParam = function(variable, value){
		if(params != null)
			params = params + "&";
		else
			params = "";
		params += variable + "=" + escape(value);
		
	}
	
	this.abort = function(){
		this.onComplete = function(e){}
	}
	
	this.onComplete = function(e) {};
	
	this.onError = function(e) {
		if(e == 403){
			alert("Du har inte tillåtelse att utföra den här åtgärden. (Utloggad?)");
		}else{
			alert("Ajax Error: " + e);
		}
	};
	
	this.getUrl = function(){
		return url;
	}
	
	this.getResponseText = function(){
		return request.responseText;
	}
	
	this.addOnComplete = function(func){
		this.onComplete = func;
	}
		
	function _makeRequest(_this){

		request = _getRequest();
		request.onreadystatechange = _stateHandler;
		try{
			if(method == "POST"){
				request.open(method, url);
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				request.send(params);
			}else{
				var urlParams = (params != null) ? "?" + params : "";
				request.open(method, url + urlParams);
				request.send(null);
			}
		}catch(e){
			_this.onError(e);
		}
	}
	
	function _getRequest(){
		var obj;
		try{
			obj = new XMLHttpRequest();
		}catch (error){
			try{
				obj = new ActiveXObject("Microsoft.XMLHTTP");
			}catch (error){
				return null;
			}
		}
		return obj;
	}
	
	function _stateHandler(){
		if(request.readyState == 4){
			if(request.status >= 200 && request.status < 300){
				_onComplete(request.responseText);
			}else{
				_this.onError(request.status);
			}
		}
	}
	
	function _onComplete(){
		_this.onComplete(request.responseText);
	}
}
