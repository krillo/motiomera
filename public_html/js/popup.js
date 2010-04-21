// Variabler

var mmPopup;

function init_motiomera_popup(){

	mmPopup = new motiomera_popup();
	mmPopupOverlay = document.getElementById("motiomera_popup_overlay");
	mmPopupContainer = document.getElementById("motiomera_popup");
	mmPopupShadow = document.getElementById("motiomera_popup_shadow");
	mmPopupContent = document.getElementById("motiomera_popup_content");
	
	document.getElementById("motiomera_popup_close").onclick = function() {
		mmPopup.close();
		motiomera_break_mmpopushow = false;
		return false;
	}
	
}

function motiomera_popup(){
		
	this.closeTimeout = null;
		
	this.show = function(width, height, overflow, pos, visaavfarda){
		
		
		
		if(!width)
			width = 400;
			
		if(!height)
			height = 300;
	
		if(!overflow)
			overflow = "hidden";
			
		if(!pos)
			pos = "center";
			
		var pageSize = getPageSize();
		var sctollPos = getScrollPos();
		mmPopupOverlay.style.height = pageSize[1] + "px";


		if(width > 0 && height > 0){
			mmPopupWidth = width;
			mmPopupHeight = height;
		}else{
			width = mmPopupWidth;
			height = mmPopupHeight;
		}
		
		_mmPopup_show(pos);

		
		mmPopupContainer.style.overflow = overflow;

	}
	
	this.setSize = function(width, height){
		mmPopupContainer.style.width = width + "px";
		mmPopupContainer.style.height = height + "px";	
	}
	
	this.close = function(){
		_this = this;
		_mmPopup_close(_this);
	}
	
	this.setContent = function(content){
		mmPopupContent.innerHTML = content;
	}
	
	this.setCloseTimeout = function(time){
		_this = this;
		this.closeTimeout = setTimeout (this.close, time );
	}

	function _mmPopup_toggleSelects(hide){
		var ie = true;
		try{
			obj = new ActiveXObject("Microsoft.XMLHTTP");
		}catch (error){
			ie = false;
		}

		if(ie){
			var selects = document.getElementsByTagName("select");		
			for(var i = 0; i < selects.length; i++){
				selects[i].style.visibility = (hide) ? "hidden" : "visible";
			}
		}
	
	}
	
	function _mmPopup_toggleFlash(mod){
	
		var divs = document.getElementsByTagName("div");
		
		for(var i = 0; i < divs.length; i++) {
		
			if(divs[i].className == "mmFlash"){
				divs[i].style.visibility = (mod) ? "visible" : "hidden";
				
			}
			
		}
	
	}
	
	function _mmPopup_show(pos){
	
		_mmPopup_toggleSelects(true);
	
		_mmPopup_updatePosition(pos);
		
		// hide flash
			
		_mmPopup_toggleFlash(false);
		
		
		
		mmPopupContainer.style.width = mmPopupWidth + "px";
		mmPopupContainer.style.height = mmPopupHeight + "px";
		mmPopupShadow.style.width = mmPopupWidth + "px";
		mmPopupShadow.style.height = mmPopupHeight + "px";
		
		

		mmPopupContainer.style.display = "block";
		mmPopupShadow.style.display = "block";
		
		if(pos == "center"){
			mmPopupOverlay.style.display = "block";
		}
	}
	
	function _mmPopup_updatePosition(pos){

		var size = getWindowSize();
		var scrollPos = getScrollPos();
		var winWidth = size[0];
		var winHeight = size[1];		
	
		var padding = 50;
	
		if(pos == "center"){

			var left = Math.round((winWidth - mmPopupWidth) / 2);		
			var top = Math.round((winHeight - mmPopupHeight) / 2);
			
			var topmod = top + scrollPos[1];
			
			if(topmod < 0) {
				topmod = 0;
			}
			
		}else if(pos == "topleft"){
		
			var left 
		
		
			
		}else if(pos == "bottom" || pos == "bottomcenter"){

			var left = Math.round((winWidth - mmPopupWidth) / 2);		
			var top = winHeight - padding - mmPopupHeight;
			
			var topmod = top + scrollPos[1];
			
			if(topmod < 0) {
				topmod = 0;
			}
		
		}
		
		
		
		mmPopupContainer.style.left = left + "px";
		mmPopupContainer.style.top = topmod + "px";
		mmPopupShadow.style.left = left + 5 + "px";
		mmPopupShadow.style.top = topmod + 5 + "px";
	}
	
	function _mmPopup_close(_this){
		
		_mmPopup_toggleSelects(false);
	
		if(_this.closeTimeout != null)
			clearTimeout(_this.closeTimeout);
			
		mmPopupOverlay.style.display = "none";
		mmPopupContainer.style.display = "none";
		mmPopupShadow.style.display = "none";
		mmPopupContent.innerHTML = "";
		
		_mmPopup_toggleFlash(true);
	}

}

mm_addOnLoad(init_motiomera_popup);