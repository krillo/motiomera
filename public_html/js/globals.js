var email_regex = /^.+@.+\..{2,4}$/;
var aNamnOK = false;
var rapporteradeSteg = new Array();

var mmPopup;
var mmPopupWidth;
var mmPopupHeight;
var mmPopupOverlay;
var mmPopupContainer;
var mmPopupShadow;
var mmPopupContent;
var quizScript = "/js/the_quiz.js";
var MAX_QUIZ_SECONDS = 60;

var onLoadList = new Array();

var kalenderManader = new Array("januari", "februari", "mars", "april", "maj", "juni", "juli", "augusti", "september", "oktober", "november", "december");



function ymdToDate(ymd){

	var month = ymd.substr(5,2);
	var date = ymd.substr(9);
	
	if(month.substr(0,1) == "0") month = month.substr(1);
	month = month-1;
	
	if(date.substr(0,1) == "0") date = date.substr(1);
	
	var dateO = new Date();
		dateO.setYear(ymd.substr(0, 4));
		dateO.setMonth(month);
		dateO.setDate(date);
		dateO.setHours(0);
		dateO.setMinutes(0);
		dateO.setSeconds(0);
		dateO.setMilliseconds(0);
		
	return dateO;


}