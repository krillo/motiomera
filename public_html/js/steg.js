<?php
$mc = new Memcache;
$mc->connect("127.0.0.1", 11211);
$filename = md5("steg.js".$_SERVER['PHPSESSID']);
$ttl = 3600; // 3600 sec = 1 hour

$content = $mc->get($filename);

if ($content) {
 header("Content-Type: text/javascript");
	print $content;
	exit;
} else {

ob_start();
// Cache whole output, in the end of this file we store it in memcached - jb
?>

<?php $js_header = 1; require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php"); ?>
var gamlaSteg = new Array();

function motiomera_steg_rapportera(){
	gamlaSteg = new Array();
	if(gamlaSteg.length == 0){
		var ajax = new motiomera_ajax("/ajax/actions/getstegbydatum.php", "GET");

			ajax.onComplete = function(response){
				var result = motiomera_parseAsObject(response);

				for(var i = 0; i < result.length; i++){ // gör objekt av datum
					var datum = new Date;

					datum.setTime(Date.parse(result[i]["datum"]));

					result[i]["datum"] = datum;
				}
				
				for(var i = 0; i < result.length; i++){
				
					var time = Math.ceil(result[i]["datum"].getTime() / (1000*60*60*24));

					if(gamlaSteg[time] == undefined){
						gamlaSteg[time] = new Array();
					}

					gamlaSteg[time][gamlaSteg[time].length] = result[i];
				
				}
										
				motiomera_steg_rapportera_do();
			}
			ajax.makeRequest();
	
	}else{
		motiomera_steg_rapportera_do();
	}



}

function motiomera_steg_rapportera_do(){
	var ajax = new motiomera_ajax("/popup/pages/steg.php", "GET");
		ajax.onComplete = function(response){
			motiomera_steg_rapportera_show(response);
		}
	ajax.makeRequest();
}

var motiomera_break_mmpopushow = false;
var motiomera_global_response = "";
function motiomera_steg_rapportera_show(response) {
	if(mmPopup && !motiomera_break_mmpopushow) {
		motiomera_break_mmpopushow = true;
		mmPopup.show(580, 265);
		mmPopup.setContent(response);
		motiomera_steg_updatePreview();
		stegKalender.onSelectDate = function(){
			motiomera_steg_doljAktiviteter();
			motiomera_steg_updatePreview();
		}
		
		var flaggedDates = new Object();

		<?php
		if(isset($USER)){
			$datum = Steg::listDatumByMedlem($USER);
			foreach($datum as $datum=>$steg){
				echo 'flaggedDates["' . $datum . '"] = "' . number_format($steg, false, ",", " ") . ' steg";' . "\n";
			}
		}
		?>

		stegKalender.setFlaggedDates(flaggedDates);

		
		stegKalender.init();
		
		activitySelects();
		stegAddVerification();
	}
	else if(!motiomera_break_mmpopushow) {
		motiomera_global_response = response;
		setTimeout("motiomera_steg_rapportera_show(motiomera_global_response)",100);
	}
		
	
	
}

function motiomera_stegNext(fas, par1, par2, par3){

	var ajax = new Ajax("/popup/pages/steg.php", "POST");
	ajax.addParam("fas", fas);	
	if(fas == "aktivitet"){
		ajax.addParam("aid", par1);
	}else if(fas == "spara"){
		ajax.addParam("aid", par1);
		ajax.addParam("antal", par2);
	}

	ajax.onComplete = function(response){
		mmPopup.setContent(response);
		mmPopup.show();		
	}
	ajax.makeRequest();
}

var mm_antal_aktiva_steg = 0;

function motiomera_steg_addSteg(){


	if(!motiomera_steg_validera())
		return false;
		
	mm_antal_aktiva_steg++;

	var aid_inputs = document.getElementsByName("steg_aid");
	if(aid_inputs.length == 1) {
		var aid_input = document.getElementById("steg_aid");
	}
	else {
		var aid_input = aid_inputs[1];
	}
	var antal_input = document.getElementById("motiomera_steg_antal");
	var visa_table = document.getElementById("motiomera_steg_preview_header");

	visa_table.style.display = 'block';
	
	var antal = antal_input.value;
	var aid = aid_input.value;

	
	antal_input.value = "";

	var newRow = new Object({'datum':stegKalender.getSelectedDate(),'aid':aid, 'antal':antal});
	var newArr = new Array(newRow);
	rapporteradeSteg = newArr.concat(rapporteradeSteg);
	motiomera_steg_updatePreview();
}

function motiomera_steg_removeSteg(id){

	var result = new Array();

	for(var i = 0; i < rapporteradeSteg.length; i++){

		if(i != id) {
			result[result.length] = rapporteradeSteg[i];
			mm_antal_aktiva_steg--;
		}
	}
	rapporteradeSteg = result;
	motiomera_steg_updatePreview();
}

function motiomera_steg_updatePreview(){

	var table = document.getElementById("motiomera_steg_preview");
	
	var aktiviteter = motiomera_steg_getAktiviteter();
	var i = 0;
	var html = '';
	var monthNames = motiomera_kalender_listManader()

	var tbody = document.createElement("tbody");
	var time = Math.ceil(stegKalender.getSelectedDate().getTime() / (1000*60*60*24));

	if(gamlaSteg[time] != undefined){
		var rapporteradeStegT = gamlaSteg[time].concat(rapporteradeSteg);
		var antalGamla = gamlaSteg[time].length;
	}else{
		var rapporteradeStegT = rapporteradeSteg;
		var antalGamla = 0;
	}
	
	var gamla = true;
	
	if(rapporteradeStegT.length > 0) {
	
		var visa_table = document.getElementById("motiomera_steg_preview_header");

		visa_table.style.display = 'block';
	

	}
	else if(mm_antal_aktiva_steg > 0) {
		
		var visa_table = document.getElementById("motiomera_steg_preview_header");

		visa_table.style.display = 'block';
	
	}
	else {
	
		var visa_table = document.getElementById("motiomera_steg_preview_header");

		visa_table.style.display = 'none';
		
	}
	
	for(var j = 0; j < rapporteradeStegT.length; j++){
	
		if(j == antalGamla){
		
			if(antalGamla != 0){
		
				var tr2 = document.createElement("tr");
				var td2 = document.createElement("td");
					td2.setAttribute("colspan", 4);
				
					var txt2 = document.createElement("hr");
				
				tr2.appendChild(td2);
				td2.appendChild(txt2);
				
				tbody.appendChild(tr2);
			}			
			gamla = false;	
		}

	
		var row = rapporteradeStegT[j];
		var datum = row["datum"];

		var datumStr = datum.getDate() + " " + monthNames[datum.getMonth()].toLowerCase();

		var tr = document.createElement("tr");
		if(gamla)
			tr.className = "motiomera_steg_table_gamla";
			
		var cell1 = document.createElement("td");
			cell1.className = "motiomera_steg_table_cell1";
					

		var cell1Text = document.createTextNode(datumStr);
		
		var cell2 = document.createElement("td");
			cell2.className = "motiomera_steg_table_cell2";
						
		var cell2Text = document.createTextNode(aktiviteter[row["aid"]]["namn"]);

		var cell2punkt5 = document.createElement("td");
			cell2punkt5.className = "motiomera_steg_table_cell3";
						
		var cell2punkt5Text;
		if (aktiviteter[row["aid"]]["enhet"]!="steg")
			cell2punkt5Text = document.createTextNode(row["antal"] + " " + aktiviteter[row["aid"]]["enhet"]);
		else
			cell2punkt5Text = document.createTextNode("");

		var cell3 = document.createElement("td");
			cell3.className = "motiomera_steg_table_cell3";
		
		
		var call3Text;
		if (aktiviteter[row["aid"]]["enhet"]=="steg") {
			cell3Text = document.createTextNode(row["antal"] + " " + aktiviteter[row["aid"]]["enhet"]);			
		}
		else {
			cell3Text = document.createTextNode((aktiviteter[row["aid"]]["varde"]*row["antal"]) + " steg");
		}

		var cell4 = document.createElement("td");
			cell4.className = "motiomera_steg_table_cell4";
		
		if(!gamla || row["last"] >= 0){

			var cell4Link = document.createElement("a");
				cell4Link.setAttribute("href", row["id"]);
				cell4Link.id = i;
				cell4Link.time = time;
				cell4Link.stegId = row["id"];
				cell4Link.onclick = function() {
					if(confirm("Är du säker på att du vill ta bort den här raden?")) {
						if(this.stegId > 0){
							motiomera_steg_removeGamlaSteg(this.time, this.stegId);
						}else{

							motiomera_steg_removeSteg(this.id - antalGamla);
						}
					}
					return false;
				}

			var cell4Img = document.createElement("img");
				cell4Img.src = "/img/icons/Papperskorg.gif";
				cell4Img.className = "mmStegrapportPapperskorg";
				cell4Img.setAttribute("alt", "");
	
			cell4Link.appendChild(cell4Img);
			cell4.appendChild(cell4Link);
		}
		
		
		
		cell1.appendChild(cell1Text);
		cell2.appendChild(cell2Text);
		cell2punkt5.appendChild(cell2punkt5Text);
		cell3.appendChild(cell3Text);
		
		tr.appendChild(cell1);
		tr.appendChild(cell2);
		tr.appendChild(cell2punkt5);
		tr.appendChild(cell3);
		tr.appendChild(cell4);
		
		tbody.appendChild(tr);
		
		i++;
	}

	if(table.childNodes.length > 0){
		table.removeChild(table.firstChild);
	}

	table.appendChild(tbody);

	if(i > 0)
		document.getElementById("motiomera_steg_spara").style.display = "block";
	else
		document.getElementById("motiomera_steg_spara").style.display = "none";
	
}

function motiomera_steg_removeGamlaSteg(time, id){


	var ajax = new motiomera_ajax("/ajax/actions/delete.php", "POST");
		ajax.addParam("table", "steg");
		ajax.addParam("id", id);
		
		
	ajax.makeRequest();

	var hit = false;

	for(var i = 0; i < gamlaSteg[time].length; i++){

		if(gamlaSteg[time][i]["id"] == id)
			hit = true;
			
		if(hit && i != gamlaSteg[time].length-1){
			gamlaSteg[time][i] = gamlaSteg[time][i+1];
			
		}
			
		if(hit && i == gamlaSteg[time].length-1){
			delete gamlaSteg[time][i];
			gamlaSteg[time].length--;;
		}
	}
	
	motiomera_steg_updatePreview();
	
}

function motiomera_steg_getAktiviteter(){
	var aktiviteter = new Object();
<?php 
$aktiviteter = Aktivitet::listAll();
foreach($aktiviteter as $aktivitet){

	echo "	aktiviteter[" . $aktivitet->getId() . "] = new Object({'namn':'" . $aktivitet->getNamn() . "','enhet':'" . $aktivitet->getEnhet() . "','varde':'" . $aktivitet->getVarde() ."','grade':'" . $aktivitet->getSvarighetsgrad() . "'});\n";

}
?>
	return aktiviteter;
}

function motiomera_steg_getGrades(aktivitet) {
	
	var aktiviteter = motiomera_steg_getAktiviteter();
	
	obj = new Object();
	
	for(akt in aktiviteter) {
		akt_obj = aktiviteter[akt];
				
		if(akt_obj["namn"] == aktivitet) {
			temp_akt = new Object({'namn':akt_obj["namn"],'value':akt_obj["varde"],'grade':akt_obj["grade"],'id':akt});

			obj[akt] = temp_akt;
		}
	}
	
	return obj;
	
}

function motiomera_steg_getAktivitetsVarde(aktivitetsid){
<?
$aktiviteter = Aktivitet::listAll();
$first=true;
foreach($aktiviteter as $aktivitet){
	echo !$first?"else ":"";
	echo "if (aktivitetsid == ".$aktivitet->getId().") return ".$aktivitet->getVarde().";\n";
	$first=false;
}
?>
}

function motiomera_steg_save(){
	
	
	var url = "/actions/save.php?table=steg&antalsteg="+rapporteradeSteg.length;
	
	for(var i = 0; i < rapporteradeSteg.length; i++){
		var month = (rapporteradeSteg[i]["datum"].getMonth()+1 < 10) ? "0" + (rapporteradeSteg[i]["datum"].getMonth() + 1) : rapporteradeSteg[i]["datum"].getMonth()+1;
		var date = (rapporteradeSteg[i]["datum"].getDate() < 10) ? "0" + rapporteradeSteg[i]["datum"].getDate() : rapporteradeSteg[i]["datum"].getDate();
		var datumStr = rapporteradeSteg[i]["datum"].getFullYear() + "-" + month + "-" + date;
		
		url = url + "&steg" + i + "_aid=" + rapporteradeSteg[i]["aid"];
		url = url + "&steg" + i + "_datum=" + datumStr;
		url = url + "&steg" + i + "_antal=" + rapporteradeSteg[i]["antal"];
	
	
	
	}
	
	
}

function motiomera_steg_visaAktiviteter(){
	document.getElementById('motiomera_steg_valjAktiviteteLink').style.display = 'none'; 
	document.getElementById('motiomera_steg_enhet').style.display = 'none'; 
	document.getElementById('aktivitetLista').style.display = 'block';
}

function motiomera_steg_doljAktiviteter(){
/*	document.getElementById('motiomera_steg_valjAktiviteteLink').style.display = 'inline'; 
	document.getElementById('motiomera_steg_enhet').style.display = 'inline'; 
	document.getElementById('aktivitetLista').style.display = 'none';
	document.getElementById('aktivitetLista').selectedIndex = 5;
*/}


function motiomera_steg_validera(){
	
	var date = new Date();
	var futureDate;

	if(date.getTime() < stegKalender.getSelectedDate().getTime())
		var futureDate = true;

	if(document.getElementById("motiomera_steg_antal").value == "" || !isInt(document.getElementById("motiomera_steg_antal").value)){
		alert("Värdet måste vara ett heltal");	
		return false;
	}else if(futureDate){
		alert("Du kan inte ange ett datum i framtiden");
		return false;
	}else if(document.getElementById("motiomera_steg_antal").value >= <?= Steg::MAX_STEG_PER_RAPPORT ?>){
		alert("Du kan inte rapportera så många steg.");
		return false;
	}else if(document.getElementById("motiomera_steg_antal").value >= <?= Steg::VARNING_STEG_PER_RAPPORT ?>){
		return confirm("Vill du rapportera " +  document.getElementById("motiomera_steg_antal").value + "steg?");
	}else if(document.getElementById("motiomera_steg_antal").value >= 1440 && motiomera_steg_getAktivitetsVarde(document.getElementById("steg_aid").value)!=1) {
		alert("Du kan inte rapportera en aktivitet på så många minuter.");
		return false;
	}else if (motiomera_steg_getAktivitetsVarde(document.getElementById("steg_aid").value)*document.getElementById("motiomera_steg_antal").value > <?= Steg::MAX_STEG_PER_RAPPORT ?>){
		alert("Du kan inte rapportera en aktivitet som motsvarar så många steg.");
		return false;
	}else if (motiomera_steg_getAktivitetsVarde(document.getElementById("steg_aid").value)*document.getElementById("motiomera_steg_antal").value > <?= Steg::VARNING_STEG_PER_RAPPORT ?>){
		return confirm("Vill du rapportera " +  document.getElementById("motiomera_steg_antal").value + "minuter?");
	}else{
		return true;
	}
}

<?php
$content = ob_get_contents();
$mc->set($filename, $content, MEMCACHE_COMPRESSED, $ttl);
ob_end_clean();
}
?>
