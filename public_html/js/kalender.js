<?php $js_header = 1; include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php"; ?>
// kalendrar
var stegKalender = new motiomera_kalender("steg");
	stegKalender.setDisableFutureDates(true);
var	klubbKalender = new motiomera_kalender("klubbStartdatum");
var skapaForetagKalender = new motiomera_kalender("skapaForetag");
	skapaForetagKalender.selectedDate = ymdToDate('<?= FORETAG::STARTDATUM_INTERVAL_START ?>');
function motiomera_kalender(id){

	this.id = id;
	this.prefix = "motiomera_kalender_";
	
	this.root = document.getElementById(this.prefix + this.id);
	this.dateCells;
	this.monthName;
	
	this.initDone = false;
	
	this.date = new Date();
	this.selectedDate = new Date();
	this.selectedDate.setHours(0);
	this.selectedDate.setMinutes(0);
	this.selectedDate.setSeconds(0);
	this.selectedDate.setMilliseconds(0);
	
	this.flaggedDates = null;
	
	this.disableFutureDates = false;
	this.disablePastDates = false;
	
	this.intervalStart = null;
	this.intervalStop = null;
	
	this.setDisableFutureDates = function(disable){
		this.disableFutureDates = disable;
	}

	this.setDisablePastDates = function(disable){
		this.disablePastDates = disable;
	}
	
	this.setFlaggedDates = function(flaggedDates){
		this.flaggedDates = flaggedDates;
	}
	
	this.setIntervalStart = function(date){
		this.intervalStart = date;
	}

	this.setIntervalStop = function(date){
		this.intervalStop = date;
	}
	
	this.init = function(){
	
		this.initDone = true;

		_this = this;
	
		var table = document.createElement("table");
			table.setAttribute("border", "0");
			table.setAttribute("cellPadding", "0");
			table.setAttribute("cellSpacing", "0");
			table.className = "motiomera_kalender";
			
		var tbody = document.createElement("tbody");
		
		var monthRow = document.createElement("tr");
			monthRow.className = "motiomera_kalender_month_row";
			
		var monthRowCell = document.createElement("td");
			monthRowCell.colSpan = 7;
			
		var prevMonth = document.createElement("span");
			prevMonth.className = "motiomera_kalender_prevMonth";
			
		var prevMonthLink = document.createElement("a");
			prevMonthLink.setAttribute("href", "#");
			prevMonthLink.onclick = function(){
				_this.prevMonth();
				return false;
			}
			
		var prevMonthLinkTextNode = document.createTextNode("<");
		
		prevMonthLink.appendChild(prevMonthLinkTextNode);
		prevMonth.appendChild(prevMonthLink);
		
		var nextMonth = document.createElement("span");
			nextMonth.className = "motiomera_kalender_nextMonth";
			
		var nextMonthLink = document.createElement("a");
			nextMonthLink.setAttribute("href", "#");
			nextMonthLink.onclick = function(){
				_this.nextMonth();
				return false;
			}

		var nextMonthLinkTextNode = document.createTextNode(">");
		
		nextMonthLink.appendChild(nextMonthLinkTextNode);
		nextMonth.appendChild(nextMonthLink);
			
		var month = document.createElement("span");
		
		this.monthName = month;
		
		var weekdayRow = document.createElement("tr");
			weekdayRow.className = "motiomera_kalender_weekday_row";
			
		var weekdays = new Array("M", "T", "O", "T", "F", "L", "S");
		
		for(var i = 0; i < 7; i++){
			var textNode = document.createTextNode(weekdays[i]);
			var cell = document.createElement("td");
				cell.appendChild(textNode);
			weekdayRow.appendChild(cell);
		}				
		
		monthRowCell.appendChild(prevMonth);
		monthRowCell.appendChild(nextMonth);
		monthRowCell.appendChild(month);
		
		monthRow.appendChild(monthRowCell);
		
		tbody.appendChild(monthRow);
		
		tbody.appendChild(weekdayRow);
		
		this.dateCells = new Array();

		for(var i = 0; i < 6; i++){
			
			var weekRow = document.createElement("tr");
				weekRow.className = "motiomera_kalender_week_row";
				
			for(j = 0; j < 7; j++){
				var weekRowCell = document.createElement("td");
				var weekRowCellLink = document.createElement("a");
					weekRowCellLink.setAttribute("href", "#");

				
				this.dateCells[this.dateCells.length] = weekRowCellLink;
				
				weekRowCell.appendChild(weekRowCellLink);
				weekRow.appendChild(weekRowCell);
			}
			tbody.appendChild(weekRow);
		
		}
							
		table.appendChild(tbody);

		document.getElementById(this.prefix + this.id).appendChild(table);
		this.date.setTime(this.selectedDate.getTime());
		this.updateDates();
		
	}
	
	this.updateDates = function(){
	

		var numDaysOfMonth = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
		var monthNames = motiomera_kalender_listManader();
		var first = new Date();
				
		first.setFullYear(this.date.getFullYear());
		first.setMonth(this.date.getMonth());
		first.setDate(1);
			
		var offset = first.getDay()-1;
		
		if(offset < 0) offset = 6;
		
		var lastDayOfMonth;

		var textNode = document.createTextNode(monthNames[this.date.getMonth()] + " " + this.date.getFullYear());
		if(this.monthName.hasChildNodes())
			this.monthName.removeChild(this.monthName.firstChild);
		this.monthName.appendChild(textNode);

		var now = new Date();
		
		for(var i = 0; i < 42; i++){

			if(this.dateCells[i].hasChildNodes())
				this.dateCells[i].removeChild(this.dateCells[i].firstChild);
			if(i < offset){
				// förra månaden
				var lastMonth = new Date();
					lastMonth.setTime(first.getTime() - ((1000*60*60*24) * (offset - i)));

				 
				var textNode = document.createTextNode(lastMonth.getDate());
				this.dateCells[i].appendChild(textNode);
				this.dateCells[i].className = "motiomera_kalender_notCurrent";
				this.dateCells[i].dateD = new Date();
				this.dateCells[i].dateD.setTime(lastMonth.getTime());
				this.dateCells[i].onclick = function(){
					_this.selectDate(this.dateD);
					return false;
				}

			}else{
				var daysOfMonth = numDaysOfMonth[this.date.getMonth()];
				if(this.date.getMonth() == 1 && new Date(this.date.getFullYear(),1,29).getDate() == 29){
					daysOfMonth++;
				}
				

				
				if(i - offset < daysOfMonth){
					// denna månad
					
					
					var thisDate = i - offset + 1;
					var textNode = document.createTextNode(thisDate);
					this.dateCells[i].appendChild(textNode);
					
					var thisDateO = new Date();
						thisDateO.setTime(this.date);
						thisDateO.setDate(thisDate);
						thisDateO.setHours(0);
						thisDateO.setMinutes(0);
						thisDateO.setSeconds(0);
						thisDateO.setMilliseconds(0);


					var monthZero = (thisDateO.getMonth()+1 > 9) ? thisDateO.getMonth()+1 : "0" + (thisDateO.getMonth()+1);
					var dateZero = (thisDateO.getDate() > 9) ? thisDateO.getDate() : "0" + (thisDateO.getDate());
					var dateStr = thisDateO.getFullYear() + "-" + monthZero + "-" + dateZero;

					var flagged = (this.flaggedDates && this.flaggedDates[dateStr] != undefined);
					

					
					if(_compareDates(thisDateO, this.selectedDate)){
						this.dateCells[i].className = "motiomera_kalender_selected";
					}else if(_compareDates(thisDateO, now)){ // idag
						this.dateCells[i].className = "motiomera_kalender_today";
					}else{
						if(!this.dateWithinInterval(thisDateO)){
							this.dateCells[i].className = "motiomera_kalender_disabled";
						}else if(thisDateO.getTime() > now.getTime() && this.disableFutureDates){
							this.dateCells[i].className = "motiomera_kalender_disabled";
						}else if(thisDateO.getTime() < now.getTime() && this.disablePastDates){
							this.dateCells[i].className = "motiomera_kalender_disabled";
						}else{
							this.dateCells[i].className = "";
						}
					}
					
					if(flagged){
						var separate = (this.dateCells[i].className == "") ? "" : " ";
						this.dateCells[i].className = this.dateCells[i].className + separate + "motiomera_kalender_flagged";
						this.dateCells[i].title = this.flaggedDates[dateStr]
					}
					
					this.dateCells[i].dateD = new Date();
					this.dateCells[i].dateD.setTime(thisDateO.getTime());
					this.dateCells[i].onclick = function(){
						_this.selectDate(this.dateD);
						return false;
					}
					
					lastDayOfMonth = i;

				}else{
					// nästa månad
					var thisDate = i - lastDayOfMonth;
					
					var nextMonth = new Date();
						nextMonth.setFullYear(this.date.getFullYear());
						nextMonth.setMonth(this.date.getMonth());
						nextMonth.setDate(numDaysOfMonth[nextMonth.getMonth()]);
						nextMonth.setTime(nextMonth.getTime()+(1000*60*60*24));
						nextMonth.setDate(thisDate);
						
					
					var textNode = document.createTextNode(thisDate);
					this.dateCells[i].appendChild(textNode);
					this.dateCells[i].className = "motiomera_kalender_notCurrent";
					this.dateCells[i].dateD = new Date();
					this.dateCells[i].dateD.setTime(nextMonth.getTime());
					this.dateCells[i].onclick = function(){
						_this.selectDate(this.dateD);
						return false;
					}
				}
			}
		}
	}
	
	this.changeMonth = function(month, year){

		var newDate = new Date();
		newDate.setDate(1);
		newDate.setMonth(month);
		newDate.setFullYear(year);

		this.date.setTime(newDate.getTime());

		this.updateDates();

	}
	
	this.nextMonth = function(){
		var month = (this.date.getMonth()+1 == 12) ? 0 : this.date.getMonth()+1;
		var year = (month == 0) ? this.date.getFullYear()+1 : this.date.getFullYear();
		this.changeMonth(month, year);

	}
	
	this.prevMonth = function(){
		var month = (this.date.getMonth() == 0) ? 11 : this.date.getMonth()-1;
		var year = (month == 11) ? this.date.getFullYear()-1 : this.date.getFullYear();
		this.changeMonth(month, year);
	}
	
	this.selectDate = function(date){
		this.selectedDate = date;
		this.changeMonth(date.getMonth(), date.getFullYear());
		this.onSelectDate(date);
	}
	
	this.getSelectedDate = function(){
		var date = new Date();
		date.setTime(this.selectedDate.getTime());
		return date;
	}
	
	this.onSelectDate = function(date){}
		
	function _compareDates(date1, date2){
		if(date1.getDate() == date2.getDate() && date1.getMonth() == date2.getMonth() && date1.getFullYear() == date2.getFullYear())
			return true
		else 
			return false;
	}

	this.dateWithinInterval = function (date){
	
		if(!date)
			date = this.getSelectedDate();
	
		var pass = true;
		
		if(this.intervalStart != null){
			if(this.intervalStart.getTime() > date.getTime())
			pass = false;
		}
		
		if(this.intervalStop != null){
			if(this.intervalStop.getTime() < date.getTime())
			pass = false;
		}		
	
		return pass;
	
	}
	
}

function motiomera_kalender_listManader(){
		return new Array("Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec");
}
