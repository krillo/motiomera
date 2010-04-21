function mmTabBoxChangeTab(id, tab, ajax, params){

	var root = document.getElementById(id);
	
	var tabs = document.getElementById(id + "_tabs");
	var numTabs = mmGetNumberOfTabs(id);
	
	for(var i = 0; i < numTabs; i++){
		var thisTab = document.getElementById(id+"_tab_"+i);
		var thisContent = document.getElementById(id+"_content_"+i);
		if(i == tab){
			thisTab.className = "mmTabBoxTabSelected";
			thisContent.style.display = "block";
			if(ajax != null && thisContent.innerHTML == ""){
				thisContent.innerHTML = "Laddar...";
				var myDelegate = new mmTabBoxDelegate(id, tab, ajax, params);
				myDelegate.getContent();
			}
				
		}else{
			thisTab.className = "mmTabBoxTab";
			thisContent.style.display = "none";
		}
	}
}

function mmGetNumberOfTabs(id){
	return document.getElementById(id + "_info").innerHTML;
}

function mmGetSelectedTab(id){
	var num = mmGetNumberOfTabs(id);
	for(var i = 0; i < num; i++){
		if(document.getElementById(id + "_tab_" + i).className == "mmTabBoxTabSelected")
			return i;
	}
}

function mmTabBoxDelegate(id, tab, url, params){

	var id = id;
	var tab = tab;
	var url = url;
	var params = params;
	
	this.getContent = function(){
	
		var myAjax = new motiomera_ajax(url, "GET", params);
		myAjax.addOnComplete(_responseHandle);
		myAjax.makeRequest();
	
	}
	
	function _responseHandle(response){
		tabContent = document.getElementById(id + "_content_"+tab);
		tabContent.innerHTML = response;
	}
}