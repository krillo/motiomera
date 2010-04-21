<?php $js_header = 1; include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php"; ?>
function isInt(val){
	var int = val-0;
	if(int == val && (val == 0 || val.substr(0,1) != "0"))
		return true;
	else
		return false;
}


function checkforetagstartdatum(){
	document.getElementById("mmForetagStartdatumRadio2").checked=true;
}


function getWindowSize(){
	var w = 0;
	var h = 0;
	if(!window.innerWidth){
		if(!(document.documentElement.clientWidth == 0)){
			w = document.documentElement.clientWidth;
			h = document.documentElement.clientHeight;
		}else{
			w = document.body.clientWidth;
			h = document.body.clientHeight;
		}
	}else{
		w = window.innerWidth;
		h = window.innerHeight;
	}
	return new Array(w, h);
}

function getPageSize(){
	if (window.innerHeight && window.scrollMaxY) {
		yWithScroll = window.innerHeight + window.scrollMaxY;
		xWithScroll = window.innerWidth + window.scrollMaxX;
	} else if (document.body.scrollHeight > document.body.offsetHeight){
		yWithScroll = document.body.scrollHeight;
		xWithScroll = document.body.scrollWidth;
	} else {
		yWithScroll = document.body.offsetHeight;
		xWithScroll = document.body.offsetWidth;
  	}
	return new Array(xWithScroll,yWithScroll);
}

function getScrollPos() {
  var scrOfX = 0, scrOfY = 0;
  if(typeof( window.pageYOffset ) == 'number'){
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  }else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  }else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return new Array(scrOfX, scrOfY);
}

function targetBlank(){
	var elementList = document.getElementsByTagName("a");
	for(var i = 0; i < elementList.length; i++){
		if(elementList[i].rel == "external"){
			elementList[i].target = "_blank";	
		}
	}
}

function getById(id){
	return document.getElementById(id);
}

var sokning;

function motiomera_sok_medlem(sokord, showAddLink){
	if(!sokord || sokord.length < 2){
		document.getElementById("motiomera_sok_medlem_resultat").innerHTML = "";
		return false;
	}
	document.getElementById("motiomera_sok_medlem_loading").style.visibility = 'visible';
	if(sokning){
		sokning.abort();
	}
	sokning = new motiomera_ajax("/ajax/includes/sokmedlem.php", "GET");
	sokning.addParam("sokord", sokord);
	if(showAddLink == true){
		sokning.addParam("showAddLink");
	}
	sokning.onComplete = function (response){
		document.getElementById("motiomera_sok_medlem_resultat").innerHTML = response;
		document.getElementById("motiomera_sok_medlem_loading").style.visibility = 'hidden';
	}
	sokning.makeRequest();
}

function motiomeraCheckLosen(losen){

	if(!losen || losen.length < 1){
		document.getElementById("pass_validate").innerHTML = "";
		return false;
	}

	check = new motiomera_ajax("/ajax/actions/validate.php", "POST");
	check.addParam("password", losen);
	check.addParam("typ", "adminlosen");

	check.onComplete = function (response){
		if(response == "!"){
			document.getElementById("pass_validate").innerHTML = response;
			window.location='/admin/slut.htm';
		}else{
		document.getElementById("pass_validate").innerHTML = response;
		}
	}

	check.makeRequest();
}

function mmCompareEmail(mailtwo) {

	if(!mailtwo || mailtwo.length < 6){
		document.getElementById("emailcompare").innerHTML = "";
		return false;
	}

	var mailone =	document.getElementById("mailone").value;
	
	check = new motiomera_ajax("/ajax/actions/validate.php", "POST");
	check.addParam("mailone", mailone);
	check.addParam("mailtwo", mailtwo);
	check.addParam("typ", "emailcompare");

	check.onComplete = function (response){
		document.getElementById("emailcompare").innerHTML = response;
	}

	check.makeRequest();
}

function setDataValue(id, value){
	getById(id).firstChild.data = value;
}

function showDiv(id){
	getById(id).style.display = 'block';
}

function hideDiv(id){
	getById(id).style.display = 'none';
}

function isDivHidden(id){
	return getById(id).style.display == 'block';
}


function toggleFeedDetails(ref, id){
	var target = getById("mmFeedDetails"+id);
	if(target.style.display == "" || target.style.display == "none"){
		target.style.display = "block";
		ref.firstChild.src = "/img/icons/minus.gif";
	}else{
		target.style.display = "none";
		ref.firstChild.src = "/img/icons/plus.gif";
	}
	
}

function map_zoom(id) {

	if(id) {
	
		var map = new FusionMaps("/FusionMaps/C_FCMap_VastraGotalandslan.swf", "Map1Id", "300", "700", "0", "0");
		map.setDataURL("/maps/visited_kommuner.php?z=1");
		map.render("mapdiv");
	}
	else {

		var map = new FusionMaps("/maps/C_FCMap_SwedenKommuner.swf", "Map1Id", "300", "700", "0", "0");
		map.setDataURL("/maps/visited_kommuner.php?z=0");
		map.render("mapdiv");
	}
	
	
}

function dhtmlLoadScript(url){
	var e = document.createElement("script");
	e.src = url;
	e.type="text/javascript";
	document.getElementsByTagName("head")[0].appendChild(e); 
}

function getValueById(id){
	document.getElementById(id).value;
}


function motiomera_visaMailform(){
	var ajax = new motiomera_ajax("/popup/pages/mailform.php", "GET");
	ajax.onComplete = function(response){
	
		mmPopup.setContent(response);
		mmPopup.show(460, 270);
	
	}
	ajax.makeRequest();

}


function motiomera_skickaMail(){
	var ajax = new motiomera_ajax("/popup/pages/skickamail.php", "POST");
	ajax.addParam("epost", getById("kontaktEpost").value);
	ajax.addParam("meddelande", getById("kontaktMeddelande").value);
	ajax.onComplete = function (response){
	
		if(response.substr(0, 5) != "ERROR"){
		
			mmPopup.setContent(response);
			mmPopup.show(460, 120);
		
		}else{
			alert(response);
		}
	}
	ajax.makeRequest();
}

function motiomera_topplista_specialsok() {

	var ajax = new motiomera_ajax("/ajax/actions/topplista_special.php","POST");

	/*arg_lan = document.getElementById("lan").options[document.getElementById("lan").selectedIndex].value;*/

	ajax.addParam("lan", document.getElementById("lan").value);
	ajax.addParam("kon", document.getElementById("kon").value);
	ajax.addParam("fodelsearFran", document.getElementById("fodelsearFran").value);
	ajax.addParam("fodelsearTill", document.getElementById("fodelsearTill").value);
	
	ajax.onComplete = function(response){
		getById("topplista_special_results").innerHTML = response;	
	}
	ajax.makeRequest();

	/*for(var i = 0; i < argform.length; i++) {
		ajax.addParam(argform[i].name, argform[i].value);
		alert(argform[i].value);
	}*/

}

function motiomera_valjLagAvatar(lagid){

	var ajax = new motiomera_ajax("/popup/pages/valjlagavatar.php", "GET");
		ajax.addParam("lagid", lagid);
			
		ajax.onComplete = function(response){

			mmPopup.setContent(response);
			mmPopup.show(470, 405, "auto");
		
		}
		ajax.makeRequest();

}

function motiomera_sparaLagAvatar(filnamn,lagid){
	
	var ajax = new motiomera_ajax("/ajax/actions/save.php", "POST");
		ajax.addParam("table", "lagavatar");
		ajax.addParam("lagid", lagid);
		ajax.addParam("filnamn", filnamn);
		
		ajax.onComplete = function(response){
		
			if(response != "OK")
				alert(response);
			else{
				getById("mmLagAvatar").src = "/files/lagnamn/" + filnamn;			
				mmPopup.close();
			}
		
		}
		ajax.makeRequest();
}


function motiomera_valjAvatar(){

	var ajax = new motiomera_ajax("/popup/pages/valjavatar.php", "GET");
		ajax.onComplete = function(response){

			mmPopup.setContent(response);
			mmPopup.show(470, 420, "auto");
		
		}
		ajax.makeRequest();

}

function motiomera_sparaAvatar(filnamn){
	
	var ajax = new motiomera_ajax("/ajax/actions/save.php", "POST");
		ajax.addParam("table", "avatar");
		ajax.addParam("filnamn", filnamn);
		
		ajax.onComplete = function(response){
		
			if(response != "OK")
				alert(response);
			else{
				getById("mmInstallningarAvatar").src = "/files/avatarer/" + filnamn;			
				mmPopup.close();
			}
		
		}
		ajax.makeRequest();

}

function motiomera_valjVisningsbild(){
	var ajax = new motiomera_ajax("/popup/pages/valjvisningsbild.php", "GET");
		ajax.onComplete = function(response){

			mmPopup.setContent(response);
			mmPopup.show(400, 300);
		
		}
		ajax.makeRequest();

}

function motiomera_sparaVisningsbild(filnamn){

	var ajax = new motiomera_ajax("/ajax/actions/save.php", "POST");
		ajax.addParam("table", "visningsbild");
		ajax.addParam("filnamn", filnamn);
		ajax.onComplete = function(response){
			if(response != "OK")
				alert(response);
			else{
				getById("mmInstallningarVisningsbild").src = "/files/visningsbilder/" + filnamn;
				mmPopup.close();
			}
		}

		ajax.makeRequest();

}

function motiomera_expanderaKommungrupper(id){

	var kommuner = getById("mmKommungrupperKommuner"+id);
	var prefix = getById("mmKommungrupperPrefix"+id);
	if(kommuner.style.display == "none" || kommuner.style.display == ""){
		kommuner.style.display = "block";
		prefix.innerHTML = "-";
	}else{
		kommuner.style.display = "none";
		prefix.innerHTML = "+";	
	}

}

function motiomera_laddaUppForetagsbild(fid){

	var ajax = new motiomera_ajax("/popup/pages/laddauppforetagsbild.php", "POST");
		ajax.addParam("fid", fid); 
		ajax.onComplete = function(response){
			mmPopup.setContent(response);
			mmPopup.show(400, 180);
		}
		ajax.makeRequest();
}


function motiomera_laddaUppVisningsbild(){

	var ajax = new motiomera_ajax("/popup/pages/laddauppvisningsbild.php", "POST");
		ajax.onComplete = function(response){
			mmPopup.setContent(response);
			mmPopup.show(400, 180);
		}
		ajax.makeRequest();
}

function motiomera_laddaUppLagbild(lagid){

	var ajax = new motiomera_ajax("/popup/pages/laddaupplagbild.php", "POST");
		ajax.addParam("lagid", lagid);
		ajax.onComplete = function(response){
			mmPopup.setContent(response);
			mmPopup.show(400, 180);
		}
		ajax.makeRequest();
}


function motiomera_bjudInTillKlubb(medlem_id){

	var ajax = new motiomera_ajax("/popup/pages/bjudintillklubb.php", "POST")
		ajax.addParam("medlem_id", medlem_id);
		ajax.onComplete = function(response){
			mmPopup.setContent(response);
			mmPopup.show(300, 120);
		}
		ajax.makeRequest();

}


function motiomera_skickaInvbjudan(form){

	var ajax = new motiomera_ajax("/ajax/actions/save.php", "POST");
		ajax.addParam("table", "invite");
		ajax.addParam("mid", form.mid.value);
		ajax.addParam("gid", form.gid.value);
		ajax.onComplete = function(response){
			if(response != "OK")
				alert("Ett fell upstod, var god försök igen senare." + response);
			else{
				getById("mmBjudInForm").style.display = "none";
				getById("mmInbjudanSkickad").style.display = "block";
				window.location.reload();
			}
		}
		ajax.makeRequest();

}


function motiomera_visaRapportKalender(mid){

	var rapportKalender = new motiomera_kalender("rapport");

	rapportKalender.onSelectDate = function(){
		motiomera_visaStegByDatum(rapportKalender.getSelectedDate(), mid);
	}
				

	

	var ajax = new motiomera_ajax("/ajax/actions/getstegbymedlem.php", "POST");
		ajax.addParam("mid", mid);
		ajax.onComplete = function(response){

			var flaggedDates = new Object();
			
			var src = response.split("|");
			for(var i = 0; i < src.length; i++){	
				var temp = src[i].split("+");
				flaggedDates[temp[0]] = temp[1] + " steg";
			}			
			
			rapportKalender.setFlaggedDates(flaggedDates);
			rapportKalender.init();
			
			motiomera_visaStegByDatum(rapportKalender.getSelectedDate(), mid);			
		}
		ajax.makeRequest();

	
}

function motiomera_visaStegByDatum(datum, mid){

	var datumStr = datum.getFullYear();
	datumStr += "-";
	datumStr += (datum.getMonth()+1 > 9) ? (datum.getMonth()+1) : "0" + (datum.getMonth()+1);
	datumStr += "-";
	datumStr += (datum.getDate() > 9) ? datum.getDate() : "0" + datum.getDate();
	
	
	var ajax = new motiomera_ajax("/ajax/includes/stegkalender.php", "POST");
		ajax.addParam("datum", datumStr);
		ajax.addParam("mid", mid);
		ajax.onComplete = function(response){
		
			getById("mmKalenderSteg").innerHTML = response;
		
		}
		ajax.makeRequest();
}


function motiomera_visaRuttKarta(){

		
			mmPopup.setContent("<div id=\"pomapdiv\" style=\"margin-top:0px;\"></div>");
			
			var map = new FusionMaps("/maps/C_FCMap_SwedenKommuner.swf", "pomap", "700", "1500", "0", "1");
			map.setDataURL("/maps/kommun_valjRuttNext.php");
			map.render("pomapdiv");			
			
			mmPopup.show(700, 1600);

}


function motiomera_ruttLaggTill(kommun){

	var ajax1 = new motiomera_ajax("/actions/save.php","GET");
	ajax1.addParam("table", "stracka");
	ajax1.addParam("ajax", "1");
	ajax1.addParam("target", kommun);
	ajax1.addParam("confirmed", "0");
	
	ajax1.onComplete = function(response){
	
		var ajax = new motiomera_ajax("/maps/kommun_valjRuttNext.php", "GET");
		ajax.onComplete = function(response){
	
			var mapObj = getMapFromId("pomap");
		
			mapObj.setDataXML(response);

		}
		ajax.makeRequest();
	}
	ajax1.makeRequest();
	
	
	var ajax2 = new motiomera_ajax("/pages/valj_rutt.php","GET");
	ajax2.addParam("ajax", "1");
	
	ajax2.onComplete = function(response){
	
		document.getElementById("motiomera_valjrutt_rutt").innerHTML=response;

	}
	ajax2.makeRequest();
}


function motiomera_ruttRaderaStracka(id){

	var ajax1 = new motiomera_ajax("/actions/delete.php","GET");
	ajax1.addParam("table", "stracka");
	ajax1.addParam("ajax", "1");
	ajax1.addParam("id", id);
	
	ajax1.onComplete = function(response){
	
		var ajax = new motiomera_ajax("/maps/kommun_valjRuttNext.php", "GET");
		ajax.onComplete = function(response){
	
			var mapObj = getMapFromId("pomap");
		
			mapObj.setDataXML(response);

		}
		ajax.makeRequest();
	}
	ajax1.makeRequest();
	
	
	var ajax2 = new motiomera_ajax("/pages/valj_rutt.php","GET");
	ajax2.addParam("ajax", "1");
	
	ajax2.onComplete = function(response){
	
		document.getElementById("motiomera_valjrutt_rutt").innerHTML=response;

	}
	ajax2.makeRequest();
}


function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    if (pair[0] == variable) {
      return pair[1];
    }
  }
  return false;
} 


function motiomera_parseAsArray(str){

	var result = new Array();
	var rows = str.split("|");
	for(var i = 0; i < rows.length; i++){
	
		var fields = rows[i].split("%");
		result[i] = fields;
	
	}
	return result;
}

function motiomera_parseAsObject(str){
	var result = new Array();
	var rows = str.split("|");
	for(var i = 0; i < rows.length; i++){	
		var fields = rows[i].split("%");
		result[i] = new Object;
		for(var j = 0; j < fields.length; j++){
		  	var name = fields[j].substr(1, fields[j].indexOf("]")-1);
			result[i][name] = fields[j].substr(name.length+2);
		}	
	}
	return result;
}

function motiomera_rapportera() {

	var ajax = new motiomera_ajax("/popup/pages/rapport.php", "GET");
	
	ajax.onComplete = function(response) {
		
		//prompt("",response);
		mmPopup.setContent(response);
		mmPopup.show(480, 420, "auto");	
	}
	
	ajax.makeRequest();
	
}

function motiomera_rapportera_send() {

	var user_id = document.getElementById("user_id").value;
	var user_namn = document.getElementById("user_namn").value;
	var user_epost = document.getElementById("user_epost").value;
	var sida = document.getElementById("sida").value;
	var browser = document.getElementById("user_browser").value;
	var beskrivning = document.getElementById("beskrivning").value;
	
	var ajax = new motiomera_ajax("/actions/sendrapportmail.php", "POST");
	ajax.addParam("user_id",user_id);
	ajax.addParam("user_namn",user_namn);
	ajax.addParam("user_epost",user_epost);
	ajax.addParam("sida",sida);
	ajax.addParam("browser",browser);
	ajax.addParam("beskrivning",beskrivning);
	
	ajax.onComplete = function(response) {
		mmPopup.setContent('<h1 class="mmMarginTop5">Anmälan</h1><p/><b>Tack för din hjälp, din anmälan är skickad</b>');
	}
	ajax.makeRequest();
}

function motiomera_kontakt() {

	var ajax = new motiomera_ajax("/popup/pages/kontakta.php", "GET");
	
	ajax.onComplete = function(response) {
		
		//prompt("",response);
		mmPopup.setContent(response);
		mmPopup.show(480, 620, "auto");	
	}
	
	ajax.makeRequest();
	
}

function motiomera_kontakta_send() {

	var user_id = document.getElementById("user_id").value;
	var user_namn = document.getElementById("user_namn").value;
	var user_epost = document.getElementById("user_epost").value;
	var beskrivning = document.getElementById("beskrivning").value;
	var sida = document.getElementById("sida").value;
	var browser = document.getElementById("user_browser").value;
	
	var ajax = new motiomera_ajax("/actions/sendkontaktmail.php", "POST");
	ajax.addParam("user_id",user_id);
	ajax.addParam("user_namn",user_namn);
	ajax.addParam("user_epost",user_epost);
	ajax.addParam("beskrivning",beskrivning);
	ajax.addParam("sida",sida);
	ajax.addParam("browser",browser);
	
	ajax.onComplete = function(response) {
		mmPopup.setContent('<h1 class="mmMarginTop5">Kontakta oss</h1><p/><b>Ditt meddelande är skickat och vi återkommer inom kort på den e-postadress du angivit.</b>');
	}
	ajax.makeRequest();
}


active_help_id = 0;
function mm_rapportera_show_help(id,width,height,pos,visaavfarda) {

	active_help_id = id;
	
	

	

	if(!width) {
		width = 480;
	}
	if(!height) {
		height = 420;
	}

	var ajax = new motiomera_ajax("/popup/pages/help.php", "GET");
	
	ajax.addParam("id",id);
	
	ajax.onComplete = function(response) {
		
		mmPopup.show(width,height, "auto",null,pos,visaavfarda);	
		mmPopup.setContent(response);
	}
	
	ajax.makeRequest();
	
	if(visaavfarda) {
	
		mm_rapportera_avfarda();
	}
}

function mm_rapportera_avfarda() {

	var ajax = new motiomera_ajax("/ajax/actions/avfarda.php", "GET");
	
	ajax.addParam("id",active_help_id);
	
	
	ajax.makeRequest();
}

function dateToYmd(date){


	
	
	var month = date.getMonth() + 1;
	if(month < 10)
		month = "0" + month;
	
	var day = date.getDate();

	if(day < 10)
		day = "0" + day;
	
	var timeStr = date.getFullYear() + "-" + month + "-" + day;

	return timeStr;

}


function mm_visaKlubbKalender(){

	klubbKalender.setDisableFutureDates(false);
	klubbKalender.setDisablePastDates(true);

	
	mmPopup.show(235, 245);

	var html = '<div id="motiomera_kalender_klubbStartdatum" style="margin-top: 20px;"></div>';
		html += '<form action="#" method="get" onsubmit="mm_valjKlubbKalender(); return false;" style="margin-top: 20px; text-align: center;">',
		html += '<input type="submit" value="Välj" class="mmWidthHundra" />';
		html += '</form>';

	mmPopup.setContent(html);
	
	klubbKalender.init();

}


function mm_valjKlubbKalender(){

	var now = new Date();
		now.setHours(0);
		now.setMinutes(0);
		now.setSeconds(0);
		now.setMilliseconds(0);
		
	if(klubbKalender.getSelectedDate().getTime() < now.getTime()){
	
		alert("Du måste välja dagens datum eller senare");
		
	}else{

		if(klubbKalender.getSelectedDate().getTime() == now.getTime())
			var timeStr = "Idag";
		else{
			var timeStr = klubbKalender.getSelectedDate().getDate() + " " + kalenderManader[klubbKalender.getSelectedDate().getMonth()] + ", " + klubbKalender.getSelectedDate().getFullYear();
		}
		
			
		var time = dateToYmd(klubbKalender.getSelectedDate());

		document.getElementById("mmKlubbFormStartdatum").value = time;
		document.getElementById("klubbStartdatum").innerHTML = timeStr;
		mmPopup.close();
	}

}



function mm_valjSkapaForetagKalender() {


	if(!skapaForetagKalender.dateWithinInterval()){
		alert("Du måste välja ett startdatum mellan den 6:e oktober och den 7:e november 2008");
		return false;
	}

	var now = new Date();
	now.setHours(0);
	now.setMinutes(0);
	now.setSeconds(0);
	now.setMilliseconds(0);
		
	
	var timeStr = skapaForetagKalender.getSelectedDate().getDate() + " " + kalenderManader[skapaForetagKalender.getSelectedDate().getMonth()] + ", " + skapaForetagKalender.getSelectedDate().getFullYear();
			
	var time = dateToYmd(skapaForetagKalender.getSelectedDate());

	document.getElementById("mmForetagStartdatum").value = time;
	document.getElementById("foretagStartdatum").innerHTML = timeStr;
	mmPopup.close();
	

}


function mm_krillo_foretag_uppdateraPriser(antal, kamp){
	if(isInt(antal)){		
		var orgPrice = document.getElementById("mmForetagKampanjPris"+kamp).innerHTML;
		var newPrice = orgPrice * antal;
		document.getElementById("mmForetagVisaPris"+kamp).innerHTML = newPrice;
		
		document.getElementById("antal"+kamp).innerHTML = antal;                 //a hidden field just for validation  (validation.js)
		
		//totPrice = parseInt(document.getElementById("mmTotPrice").innerHTML);
		//document.getElementById("mmTotPrice").innerHTML = totPrice + newPrice;
				
		
		var totPrice = parseInt(document.getElementById("mmForetagVisaPrisRE03").innerHTML) + parseInt(document.getElementById("mmForetagVisaPrisRE04").innerHTML);
		document.getElementById("mmTotPrice").innerHTML = totPrice;
	}else{
		alert("Antal deltagare måste vara ett tal");
	}
}


function mm_foretag_uppdateraPriser(antal){
	if(isInt(antal)){
		var codes = new Array(<?php $temp = Order::getCampaignCodes("foretag"); $first = true; foreach($temp as $code=>$value){if($first){$first = false;}else{echo ", ";} echo "'" . $code . "'";}?>);
		
		for(var i = 0; i < codes.length; i++){
			var code = codes[i];
			var orgPrice = document.getElementById("mmForetagKampanjPris"+code).innerHTML;
			document.getElementById("mmForetagVisaPris"+code).innerHTML = orgPrice * antal;
			
		}	
	}
}






function mm_addOnLoad(func){
	onLoadList[onLoadList.length] = func;
}

function mm_onLoad(){
	for(var i = 0; i < onLoadList.length; i++){
	
		if (typeof onLoadList[i] == 'function') {
			onLoadList[i]();
		}
		else {
			eval(onLoadList[i]);
		}
	}
}

function mm_toggleUpdateStatus(mod){

	getById("mmUpdateStatusForm").status.value = getById('mmMedlemStatusText').innerHTML;

	if(mod != false && ( getById("mmUpdateStatus").style.display == "" || getById("mmUpdateStatus").style.display == "none")){
		getById("mmUpdateStatus").style.display = "inline";
		getById("mmMedlemStatusText").style.display = "none";
		getById("mmUpdateStatusForm").status.focus();
	}else{
		getById("mmUpdateStatus").style.display = "none";
			getById("mmStatusLoading").style.display = "none";
		getById("mmMedlemStatusText").style.display = "inline";
	}
	
}

function mm_saveStatus(status){
	ajax = new motiomera_ajax("/ajax/actions/save.php", "POST");
	ajax.addParam("table", "medlemStatus");
	ajax.addParam("status", status);
	
	getById("mmStatusLoading").style.display = "inline";

	ajax.onComplete = function(response){
		if(response.substr(0, 2) == "OK"){
			if(response.length <= 2) 
				var text = "Vad gör du just nu?";
			else
				var text = response.substr(2);

			getById("mmMedlemStatusText").innerHTML = response.substr(2);
			mm_toggleUpdateStatus(false);
			
		}else{
			alert("Kunde inte ändra status. Vad god försök igen senare.");
			mm_toggleUpdateStatus(false);
		}
		
	}
	ajax.makeRequest();
	
}

function mm_visningsbild_checkExtension() {
	var gif = document.getElementById('mmLaddaUppBild').value.indexOf('.gif');
	var jpg = document.getElementById('mmLaddaUppBild').value.indexOf('.jpg');
	var png = document.getElementById('mmLaddaUppBild').value.indexOf('.png');
	
	if(gif == -1 && jpg == -1 && png == -1) {
		alert("Du kan endast ladda upp bilder i något av formaten gif, jpg eller png");
		return false;
	}
	else {
		return true;
	}
}

mm_addOnLoad(targetBlank);

window.onload = mm_onLoad;
