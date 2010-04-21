function remove_multiple_folders(my_id){
	var id_to_remove_arr = new Array();
	for(i=0;i<folders_checked_ids.length;i++){
		var the_id = 'folder_selected_' + folders_checked_ids[i];
		if(document.getElementById(the_id).checked && document.getElementById(the_id).style.display != 'none'){
			id_to_remove_arr[id_to_remove_arr.length] = folders_checked_ids[i];
		}
	}
	if(id_to_remove_arr.length > 0){
		if(confirm("Vill du verkligen ta bort de markerade mapparna?")){
			var ajax = new motiomera_ajax("/actions/removefolder.php", "POST");
			ajax.addParam("my_id", my_id);
			ajax.addParam("multiple", "1");
			
			for(i=0;i<id_to_remove_arr.length;i++){
				ajax.addParam("folder_id_" + i, id_to_remove_arr[i]);
			}
			ajax.addParam("nroffolders", id_to_remove_arr.length);
			ajax.onComplete = function(response){
				for(i=0;i<id_to_remove_arr.length;i++){
					var folder_tr = 'folder_tr_' + id_to_remove_arr[i];
					document.getElementById(folder_tr).style.display = "none";
				}
			}
			ajax.makeRequest();
		}
	}
	
}


function remove_one_folders(folder_id, my_id){
	if(confirm("Vill du verkligen ta bort denna mapp?")){
		var ajax = new motiomera_ajax("/actions/removefolder.php", "POST");
		ajax.addParam("folder_id", folder_id);
		ajax.addParam("my_id", my_id);
		ajax.addParam("multiple", "0");
		
		ajax.onComplete = function(response){
			var folder_tr = 'folder_tr_' + folder_id;
			document.getElementById(folder_tr).style.display = "none";
		}
		ajax.makeRequest();
	}	
}

function moveToFolder(current_folder){
	var move_to_folder = document.getElementById('move_to_folder').value;
	if(move_to_folder != '-1'){
		if(move_to_folder != current_folder){
			var params = "";
			var nrofmails = 0;
			for(i=0;i<checked_ids.length;i++){
				var the_id = 'mail_selected_' + checked_ids[i];
				var the_mail_row_id = 'mail_tr_' + checked_ids[i];
				if(document.getElementById(the_id).checked && document.getElementById(the_id).style.display != 'none'){
					params += "&mail_id_" + nrofmails + "=" + checked_ids[i];
					nrofmails++;
				}
			}
			if(params != ''){
				var url = "/actions/movemailtofolder.php?folder_id=" + current_folder + "&move_to=" + move_to_folder + "&nrofmails=" + nrofmails;
				url += params;
				location.href = url;
			}
		}
	}
}


function showFolders(){
	
	if(document.getElementById('subfolders').style.display == 'none'){
		document.getElementById('subfolders').style.display = ''
	}
	else{
		document.getElementById('subfolders').style.display = 'none'
	}
}


function validateCreateFoldere(my_id){
	var folder_name = document.getElementById('folder_name').value;
	if(folder_name == ''){
		setDataValue('folder_exists', 'Du måste ange mappnamn');
		showDiv('folder_exists');
		return;
	}
	var ajax = new motiomera_ajax("/actions/mailfoldermanager.php", "POST");
	ajax.addParam("my_id", my_id);
	ajax.addParam("todo", "create");
	ajax.addParam("folder_name", folder_name);
		
	ajax.onComplete = function(response){
		if(response == '0'){
			setDataValue('folder_exists', 'Mappnamn finns redan!');
			showDiv('folder_exists');
			return;
		}
		location.reload(true);
	}
	ajax.makeRequest();
}

function select_all_folders(){
	var select_all_folders = document.getElementById('select_all_folders').checked;
	var set_to = false;
	if(select_all_folders){
		set_to = true;
	}
	for(i=0;i<folders_checked_ids.length;i++){
		var the_id = 'folder_selected_' + folders_checked_ids[i];
		document.getElementById(the_id).checked = set_to;
	}
}

function selectAllMails(){
	var select_all_mail = document.getElementById('select_all_mail').checked;
	var set_to = false;
	if(select_all_mail){
		set_to = true;
	}
	for(i=0;i<checked_ids.length;i++){
		var the_id = 'mail_selected_' + checked_ids[i];
		document.getElementById(the_id).checked = set_to;
	}
}

function remove_one_mail(mail_id,  remover_id, who_deletes){
	if(confirm("Vill du verkligen ta bort detta meddelande?")){
		var ajax = new motiomera_ajax("/actions/removemail.php", "POST");
		ajax.addParam("mail_id", mail_id);
		ajax.addParam("remover_id", remover_id);
		ajax.addParam("remover", who_deletes);
		
		ajax.onComplete = function(response){
			var the_mail_row_id = 'mail_tr_' + mail_id;
			document.getElementById(the_mail_row_id).style.display = "none";
			document.getElementById('reading_div').style.display = "none";
			document.getElementById('remove_div').style.display = "block";
		}
		ajax.makeRequest();
	}
}

function remove_multiple_mail(remover_id, who_deletes){
	var id_to_remove_arr = new Array();
	for(i=0;i<checked_ids.length;i++){
		var the_id = 'mail_selected_' + checked_ids[i];
		var the_mail_row_id = 'mail_tr_' + checked_ids[i];
		if(document.getElementById(the_id).checked && document.getElementById(the_id).style.display != 'none'){
			id_to_remove_arr[id_to_remove_arr.length] = checked_ids[i];
		}
	}
	if(id_to_remove_arr.length > 0){
		if(confirm("Vill du verkligen ta bort de markerade meddelandena?")){
			var ajax = new motiomera_ajax("/actions/removemail.php", "POST");
			ajax.addParam("mail_id", "0");
			ajax.addParam("mails_to_remove", id_to_remove_arr.length);
			ajax.addParam("remover_id", remover_id);
			ajax.addParam("remover", who_deletes);
			for(i=0;i<id_to_remove_arr.length;i++){
				ajax.addParam("mail_id_" + i, id_to_remove_arr[i]);
			}

			ajax.onComplete = function(response){
				for(i=0;i<id_to_remove_arr.length;i++){
					var the_mail_row_id = 'mail_tr_' + id_to_remove_arr[i];
					document.getElementById(the_mail_row_id).style.display = "none";
				}
			}
			ajax.makeRequest();
		}
	}
}


function answer_mail(){
	document.getElementById('reading_div').style.display = "none";
	document.getElementById('answering_div').style.display = "block";	
	
	
}




function send_answer_mail(mid,rmid){

	var amne = document.getElementById('amne').value;
	var msg = document.getElementById('msg').value;
	var ajax = new motiomera_ajax("/actions/sendinternmail.php", "POST");
	ajax.addParam("mid", mid);
	ajax.addParam("msg", msg);
	ajax.addParam("amne", amne);
	ajax.addParam("rmid", rmid);
	ajax.addParam("do", "send");
	
	ajax.onComplete = function(response){
		document.getElementById('answering_div').style.display = "none";
		document.getElementById('answered_div').style.display = "block";
	}
	ajax.makeRequest();
		
}



function motiomera_mail_read(id, is_inbox){
	var ajax = new motiomera_ajax("/popup/pages/read_mail.php", "GET");
	ajax.addParam("id", id);
	ajax.addParam("is_inbox", is_inbox);
	
	ajax.onComplete = function(response){
		if(is_inbox){
			setMailAsRead(id);
		}
		mmPopup.show(480, 285, "auto");	
		mmPopup.setContent(response);
	}
	ajax.makeRequest();
}

function motiomera_ansok_medlem(id){
	var ajax = new motiomera_ajax("/popup/pages/ansok_klubb.php", "GET");
	ajax.addParam("gid", id);

	ajax.onComplete = function(response){
		mmPopup.show(480, 285, "auto");	
		mmPopup.setContent(response);
	}
	ajax.makeRequest();
}

function setMailAsRead(id){
	var tr = 'mail_tr_' + id;
	var img = 'mail_img_' + id;
	//document.getElementById(tr).className = 'read_mail_row';
	if(document.getElementById(img).src.indexOf("MailUnreadIcon") > 0) {
		document.getElementById(img).src = "/img/icons/MailReadIcon_greenBG.gif";
	}
}



function motiomera_mail_send(id){
	var ajax = new motiomera_ajax("/popup/pages/send_mail.php", "GET");
	ajax.addParam("id", id);
	ajax.addParam("do", "send");
	ajax.onComplete = function(response){
		mmPopup.show(480, 285);	
		mmPopup.setContent(response);
	}
	ajax.makeRequest();
}

function motiomera_mail_send_action(){
	var amne = document.getElementById('amne').value;
	var msg = document.getElementById('msg').value;
	var mid = document.getElementById('mid').value;

	var ajax = new motiomera_ajax("/actions/sendinternmail.php", "POST");
	ajax.addParam("mid", mid);
	ajax.addParam("msg", msg);
	ajax.addParam("amne", amne);
	
	ajax.onComplete = function(response){
		document.getElementById('the_content').style.display = "none";
		document.getElementById('the_content_sent').style.display = "block";
	}
	ajax.makeRequest();
	
}

function motiomera_mail_write_new(){
	var ajax = new motiomera_ajax("/popup/pages/write_new.php", "GET");
	
	ajax.onComplete = function(response){
		mmPopup.show(480, 315);	
		mmPopup.setContent(response);
	}
	ajax.makeRequest();
}

function vb_mail(){
	document.getElementById('reading_div').style.display = "none";
	document.getElementById('fw_div').style.display = "block";	
}

function send_vb_mail(){
	if(document.getElementById('vb_to').value != '0'){
		var amne = document.getElementById('vb_amne').value;
		var msg = document.getElementById('vb_msg').value;
		var mid = document.getElementById('vb_to').value;
		var ajax = new motiomera_ajax("/actions/sendinternmail.php", "POST");
		ajax.addParam("mid", mid);
		ajax.addParam("msg", msg);
		ajax.addParam("amne", amne);
		ajax.addParam("do", "send");
		
		ajax.onComplete = function(response){
			document.getElementById('fw_div').style.display = "none";
			document.getElementById('fwded_div').style.display = "block";
		}
		ajax.makeRequest();
	}
}

function send_new_mail(mid, amne, msg){
/*	var amne = document.getElementById('new_amne').value;
	var msg = document.getElementById('new_msg').value;
	var mid = document.getElementById('new_to').value;*/

	if (amne.length<1) {
		alert('Du måste skriva något i fältet ämne');
	}
	else if (msg.length<1) {
		alert('Du måste skriva något i meddelande fältet');
	}
	else {
		var ajax = new motiomera_ajax("/actions/sendinternmail.php", "POST");
		ajax.addParam("mid", mid);
		ajax.addParam("msg", msg);
		ajax.addParam("amne", amne);
		ajax.addParam("do", "send");
		
		ajax.onComplete = function(response){
			if (response == 'ok') {
				document.getElementById('new_div').style.display = "none";
				document.getElementById('new_div_none').style.display = "block";
			}
			else if(response == 'ej_publik') {
				alert('Det gick ej att skicka brevet efters medlemmen har valt att bara ta emot brev från Motiomera-vänner.');
			}
			else if(response == 'blockerad_target') {
				alert('Brevet gick inte att leverera eftersom medlemmen du skickar brev till har blockerat dig.');
			}
			else if(response == 'blockerad_user') {
				alert('Brevet gick inte att leverera eftersom du har blockerat medlemmen du försöker skicka brev till.');
			}
			else if(response == 'mail_to_self') {
				alert('Du kan inte skicka brev till dig själv.');
			}
			else if(response == 'targetBlockMail') {
				alert('Medlemmen tar inte emot epost från andra än sina Motiomera-vänner.');
			}
			else if(response == 'blockedByProfile') {
				alert('Du kan ej skicka mail till medlemmen eftersom medlemmen begränsat från vilka han/hon vill ta emot Motiomera-mail.');
			}
			else {
				alert('Brevet gick ej skicka');
			}
		}
		ajax.makeRequest();
	}
}


function verify_member_by_string(argstr) {

		var url = '/actions/verifymember.php';
		var ajax = new motiomera_ajax(url,"POST");

		ajax.addParam("freestring", argstr);

		ajax.onComplete = function(response){
			if (response!='0')	{
				document.getElementById('validMember_OK').style.display = 'block';
				document.getElementById('validMember_ERR').style.display = 'none';
				document.getElementById('mailSubmitButton').disabled = false;
			}
			else {
				document.getElementById('validMember_OK').style.display = 'none';
				document.getElementById('validMember_ERR').style.display = 'block';
				document.getElementById('mailSubmitButton').disabled = true;
			}
		}
		ajax.makeRequest();

}

function send_new_mail_freestr(){
	if(document.getElementById('new_to').value != undefined) {
		
		var str = document.getElementById('new_to').value

		var amne = document.getElementById('new_amne').value;
		var msg = document.getElementById('new_msg').value;
		var mid = document.getElementById('new_to').value;
		send_new_mail(mid, amne, msg);

	}

}

function value2field(value, fieldname, exclude) {
	if (value != exclude)
		document.getElementById(fieldname).value = value;
}
