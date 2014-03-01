/* =====================================================================================
*
*  Init a backup
*
*/

function initForceBackup(only) {
	jQuery("#wait_backup").show();
	jQuery("#backupButton").attr('disabled', 'disabled');
	jQuery("#backupButton2").attr('disabled', 'disabled');
	
	var arguments = {
		action: 'initBackupForce', 
		type_backup: only
	} 
	
	var self = this;  
  	self.only_save = only ;  
  
	//POST the data and append the results to the results div
	jQuery.post(ajaxurl, arguments, function(response) {
		jQuery("#zipfile").html(response);
		forceBackup(self.only_save) ; 
	});    
}

/* =====================================================================================
*
*  Test FTP
*
*/

function testFTP() {
	jQuery("#wait_testFTP").show();
	jQuery("#testFTP_button").attr('disabled', 'disabled');
	
	ftp_host = jQuery("#ftp_host").val();
	ftp_login = jQuery("#ftp_login").val();
	ftp_pass = jQuery("#ftp_pass").val();
	
	var arguments = {
		action: 'testFTP', 
		ftp_host: ftp_host ,
		ftp_login: ftp_login ,
		ftp_pass: ftp_pass 
	} 
	  
	//POST the data and append the results to the results div
	jQuery.post(ajaxurl, arguments, function(response) {
		jQuery("#testFTP_info").html(response);
		jQuery("#wait_testFTP").hide();
		jQuery("#testFTP_button").removeAttr('disabled');
	}).error(function() { 
		jQuery("#wait_testFTP").hide();
		jQuery("#testFTP_button").removeAttr('disabled');
		alert("Please retry - Problem"); 
	});    
}

/* =====================================================================================
*
*  Force a backup
*
*/

function forceBackup(only) {
	var self = this;  
  	self.only_save = only ;  
	
	var arguments = {
		action: 'backupForce', 
		type_backup: only
	} 
	//POST the data and append the results to the results div
	jQuery.post(ajaxurl, arguments, function(response) {
		if ((""+response+ "").indexOf("backupEnd") !=-1) {
			progressBar_modifyProgression(100);
			progressBar_modifyText("");
			var arguments2 = {
				action: 'updateBackupTable'
			} 	
			jQuery.post(ajaxurl, arguments2, function(response) {
				jQuery("#zipfile").html(response);
				jQuery("#backupButton").removeAttr('disabled');
				jQuery("#backupButton2").removeAttr('disabled');
				jQuery("#wait_backup").hide();
			}) ; 
		} else {
			jQuery("#zipfile").html(response);
			forceBackup(self.only_save);
		} 
	}).error(function(x,e) { 
		if (x.status==0){
			//Offline
		} else if (x.status==500){
			jQuery("#zipfile").html("Error 500: The ajax request is retried");
			forceBackup(self.only_save) ; 
		} else {
			alert("Error "+x.status) ; 
			jQuery("#backupButton").removeAttr('disabled');
			jQuery("#backupButton2").removeAttr('disabled');
			jQuery("#wait_backup").hide();
		}
	});
		
}

/* =====================================================================================
*
*  Delete a backup
*
*/

function deleteBackup(racineF) {	
	var arguments = {
		action: 'deleteBackup',
		racine: racineF
	} 
	
	//POST the data and append the results to the results div
	jQuery.post(ajaxurl, arguments, function(response) {
		if (((""+response+ "").indexOf("error") !=-1)||((""+response+ "").indexOf("Error") !=-1)) {
			alert(response);
		} else {
			jQuery("#zipfile").html(response);
		}
	});    
}

/* =====================================================================================
*
*  Cancel a backup
*
*/

function cancelBackup() {	
	var arguments = {
		action: 'cancelBackup'
	} 
	
	//POST the data and append the results to the results div
	jQuery.post(ajaxurl, arguments, function(response) {
		if (((""+response+ "").indexOf("error") !=-1)||((""+response+ "").indexOf("Error") !=-1)) {
			alert(response);
		} else {
			jQuery("#zipfile").html(response);
		}
	});    
}