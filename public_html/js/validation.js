<?php $js_header = 1; require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php"); ?>

function motiomera_validateSkapaForetagForm(form){
	
	var RE03 = parseInt(document.getElementById("antalRE03").innerHTML);
	var RE04 = parseInt(document.getElementById("antalRE04").innerHTML);	
    if(isNaN(RE03) && isNaN(RE04)){
    	alert("Antal deltagare måste vara ett tal");
    	return false;
    }    
	if(form.namn.value == ""){
		alert("Fyll i företagets namn");
		return false;			
	}else if(RE03 <= 0 && RE04 <= 0 ){
		alert("Antal deltagare måste vara ifyllt");
		return false;		
	}else if(RE03 > 999 || RE04 > 999 ) {
		alert("För beställningar över 999 st var vänlig prata med vår kundtjänst. Tel 042-444 30 25.");
		return false;	
	}else if(!form.villkar.checked){
		alert("Du måste godkänna Allers integritetspolicy");
		return false;
	}
	return true;
}

function motiomera_validateBestallForm(form){
	var selected = false;
	for(var i = 0; i < form.kontotyp.length; i++){
		if(form.kontotyp[i].checked)
			selected = true;
	}
	if(!selected){
		alert("Du måste välja ett erbjudande");
		return false;
	}
	
	return true;
		

}

function motiomera_validateInstallningarForm(form){


	if(form.fnamn.value.length < <?= Medlem::MIN_LENGTH_FNAMN ?>){
		alert("Förnamnet är för kort");
		return false;
	}else if(form.enamn.value.length < <?= Medlem::MIN_LENGTH_ENAMN ?>){
		alert("Efternamnet är för kort");
		return false;
	}else if(form.fnamn.value.length > <?= Medlem::MAX_LENGTH_FNAMN ?>){
		alert("Förnamnet är för långt\nMax längd för förnamn : " + <?= Medlem::MAX_LENGTH_FNAMN; ?> + " tecken");
		return false;
	}else if(form.enamn.value.length > <?= Medlem::MAX_LENGTH_ENAMN ?>){
		alert("Efternamnet är för långt\nMax längd för efternamn : " + <?= Medlem::MAX_LENGTH_ENAMN; ?> + " tecken");
		return false;
	}else if(form.kid.value == ""){
		alert("Välj en ort");
		return false;
	}else if(!email_regex.test(form.epost.value)){
		alert("Ogiltig e-postadress");
		return false;
	}else if(form.andraLosen.value == 1 && form.losen.value.length < <?= Medlem::MIN_LENGTH_LOSEN ?>){
		alert("Lösenordet måste vara minst <?= Medlem::MIN_LENGTH_LOSEN ?> tecken");
		return false;
	}else if(form.andraLosen.value == 1 && form.losen.value != form.losen2.value){
		alert("Lösenorden matchar inte");
		return false;
	}else{
		var selTab = mmGetSelectedTab("mmTabBox_installningar");
		getById("mmInstallningarFlik").value = selTab;
		if(form.foretagsnyckel.value != "" && form.foretagsnyckelOld.value != form.foretagsnyckel.value){			
			mm_ajaxValidera("mmForetagsnyckelError", "foretagsnyckel", form.foretagsnyckel.value, function(response){
				if(response == "1"){;
					form.submit();
				}else if(response == "UPPTAGEN"){
					alert("Företagsnyckeln är upptagen");
				}else{
					alert("Företagsnyckeln är ogiltig");
				}
			});
		}else{
			form.submit();
		}
		return false;
	}
}

function motiomera_validateSkapaMail(form){
	if(form.msg.value.length == 0){
		alert("Du måste skriva ett meddelande");
		return false;
	}
	form.submit();
}

function motiomera_validateSkapaLag(form){

	if(form.namn.value == ""){
		alert("Fyll i ett lagnamn");
		return false;
	}
	if(form.namn.value.length>=40){
		alert("Lagnamnet är för långt.\nMaximalt 40 tecken är tillåtet.\n(Längd på namn angivet : " + form.namn.value.length + ")");
		return false;
	}
	
	var words = form.namn.value.split(/\s/);
	for(i=0;i<words.length;i++) {
		if(words[i].length > 20) {
			alert("Ett ord i ditt lagnamn (" + words[i] + ") innehåller för många tecken.\nMax antal tillåtna tecken är 20.");
			return false;
		}
	}
	
	return true;
	
}

function motiomera_validateSkapaMedlemForm(form){
	var selected = false;
	for(var i = 0; i < form.kontotyp.length; i++){
		if(form.kontotyp[i].checked){
			selected = true;
			selectedValue = form.kontotyp[i].value;
		}
	}
	
	if(form.anamn.value.length < <?= Medlem::MIN_LENGTH_ANAMN ?>){
		alert("Smeknamnet är för kort");
		return false;
	}else if(form.fnamn.value.length < <?= Medlem::MIN_LENGTH_FNAMN ?>){
		alert("Förnamnet är för kort");
		return false;
	}else if(form.enamn.value.length < <?= Medlem::MIN_LENGTH_ENAMN ?>){
		alert("Efternamnet är för kort");
		return false;
	}else if(form.anamn.value.length > <?= Medlem::MAX_LENGTH_ANAMN ?>){
		alert("Smeknamnet är för långt\nMax längd för smeknamn : " + <?= Medlem::MAX_LENGTH_ANAMN; ?> + " tecken");
		return false;
	}else if(form.fnamn.value.length > <?= Medlem::MAX_LENGTH_FNAMN ?>){
		alert("Förnamnet är för långt\nMax längd för förnamn : " + <?= Medlem::MAX_LENGTH_FNAMN; ?> + " tecken");
		return false;
	}else if(form.enamn.value.length > <?= Medlem::MAX_LENGTH_ENAMN ?>){
		alert("Efternamnet är för långt\nMax längd för efternamn : " + <?= Medlem::MAX_LENGTH_ENAMN; ?> + " tecken");
		return false;
	}else if(form.kid.value == ""){
		alert("Välj en ort");
		return false;
	}else if(!email_regex.test(form.epost.value)){
		alert("Ogiltig e-postadress");
		return false;
	}else if(form.losenord.value.length < <?= Medlem::MIN_LENGTH_LOSEN ?>){
		alert("Lösenordet måste vara minst <?= Medlem::MIN_LENGTH_LOSEN ?> tecken");
		return false;
	}else if(form.losenord.value != form.losenord2.value){
		alert("Lösenorden matchade inte");
		return false;
	}else if(!selected){
		alert('Du måste välja ett medlemskap');
		return false;
	}else if(selectedValue == "foretagsnyckel" && form.foretagsnyckel.value == ""){
		alert("Du måste fylla i din företagsnyckel");
		return false;
	}else if(!getById("integritetspolicy").checked){
		alert("Du måste godkänna allers integritetspolicy innan du kan gå vidare");
		return false;
	}else{
		mm_ajaxValidera('mmANamnError', 'anamn', form.anamn.value, function(response){
			if(response == "1"){
				mm_ajaxValidera('mmEpostError', 'epost', form.epost.value, function(response){
					if(response == "1"){
						
						if(form.foretagsnyckel.value != ""){
							mm_ajaxValidera('mmForetagsnyckelError', 'foretagsnyckel', form.foretagsnyckel.value, function(response){
								if(response == "1"){
									form.submit();
								}else if(response == "OGILTIG"){
									alert("Företagsnyckeln är ogiltig");
									document.getElementById("mmForetagsnyckelError").innerHTML = "Ogiltig företagsnyckel";
								}else{
									alert("Företagsnyckeln är upptagen");
									document.getElementById("mmForetagsnyckelError").innerHTML = "Företagsnyckel är upptagen";		
								}
							});
						}else{
						
							form.submit();
						}
					}else{
						alert('Epostadressen är upptagen');
					}
				});
			}else{
				alert('Användarnamnet är upptaget');
			}
		});
	}
}

function mm_kontaktValidera(form){

	if(!email_regex.test(form.epost.value)){
		alert("Ogiltig e-postadress");
		return false;
	}else if(form.meddelande.value == ""){
		alert("Du måste lämna ett meddelande");
		return false;
	}else{
		return true;
	}

}


function mm_ajaxValidera(errorElement, typ, varde, onComplete){
	var error = document.getElementById(errorElement);
	var ajax = new motiomera_ajax('/ajax/actions/validate.php', "POST");
		ajax.addParam("typ", typ);
		ajax.addParam("varde", varde);
		if(onComplete){
			ajax.onComplete = onComplete;
		}else{
			ajax.onComplete = function(response){
				if(response == "1"){
					error.style.display = "none";
				}else{
					error.style.display = "inline";
				}
			}
		}
		ajax.makeRequest();
}



function mm_skapaFotoalbumValidera(form){

	if(form.namn.value == ""){
		
		alert("Fotoalbumet måste ha ett namn");
		return false;
	
	}
	
	return true;

}

function mm_skapaQuizValidera (form) {
	
	if (form.namn.value == "") {
		alert("Ditt quiz måste ha ett namn");
		return false;
	};
	var unansweredQuestion = false;
	var questions = 0;
	$('.fragor textarea').each(function(c){
		if ($.trim($(this).val()).length) {
			$('input', $(this).parent().parent().parent()).each(function(i){
				if (!$(this).val().length) {
					alert("Du måste ange rätt svar och två felaktiga svar på fråga " + (c+1));
					unansweredQuestion = true;
				}
			})
			questions ++;
		};
	})
	if (unansweredQuestion) {
		return false;
	};
	if (questions == 0) {
		alert("Ditt quiz måste ha minst en fråga");
		return false;
	}
	
	return true;
}

function mm_skapaGruppValidera(form){

	if(form.namn != undefined && form.namn.value == ""){
		alert("Fyll i ett namn för klubben");
		return false;
	}else if(form.publik.value == ""){
		alert("Välj om gruppen ska vara publik eller ej");
		return false;
	}else{
		//
		if (form.namn != undefined)	{
			mm_ajaxValidera("gruppNamnError", "gruppnamn", form.namn.value, function(response){
				if(response == "OK"){
					form.submit();
				}else{
					alert("Gruppnamnet är upptaget");			
				}
			
			});
		}
		else
			form.submit();
	}

}
