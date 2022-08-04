
function updateClock()
 	{
 	var currentTime = new Date ( );
  	var currentHours = currentTime.getHours ( );
  	var currentMinutes = currentTime.getMinutes ( );
  	var currentSeconds = currentTime.getSeconds ( );
	var currentday = currentTime.getDay();
	var currentdate = currentTime.getDate();
	var currentyear = currentTime.getFullYear();
	
	
	//to get the current textual day
	var weekday = new Array(7);
	weekday[0]=  "Sunday";
	weekday[1] = "Monday";
	weekday[2] = "Tuesday";
	weekday[3] = "Wednesday";
	weekday[4] = "Thursday";
	weekday[5] = "Friday";
	weekday[6] = "Saturday";

	var day = weekday[currentTime.getDay()];
	
	
	//to get month of the current date
	var month = new Array();
	month[0] = "January";
	month[1] = "February";
	month[2] = "March";
	month[3] = "April";
	month[4] = "May";
	month[5] = "June";
	month[6] = "July";
	month[7] = "August";
	month[8] = "September";
	month[9] = "October";
	month[10] = "November";
	month[11] = "December";
	var currentmonth = month[currentTime.getMonth()];
	
	//db_update purpose
	var currentmonth_no = currentTime.getMonth()+1;//since array starts from 0 , added 1 here.
  	currentmonth_no = ( currentmonth_no < 10 ? "0" : "" ) + currentmonth_no;
  	currentdate = ( currentdate < 10 ? "0" : "" ) + currentdate;
	
  	// Pad the minutes and seconds with leading zeros, if required
  	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
  	currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

  	// Choose either "AM" or "PM" as appropriate
  	var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

  	// Convert the hours component to 12-hour format if needed
  	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

  	currentHours = ( currentHours < 10 ? "0" : "" ) + currentHours;

  	// Convert an hours component of "0" to "12"
  	currentHours = ( currentHours == 0 ) ? 12 : currentHours;

  	// Compose the string for display
  	var currentTimeString = day +"\n"+ currentmonth +"  "+ currentdate +"  - <br/>"+ currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
  	
  	var current_dbformatted_date =  currentyear+'-'+currentmonth_no+'-'+currentdate;
	var current_dbformatted_time = 	currentHours+':'+currentMinutes+':'+currentSeconds;
   	$("#clock").html(currentTimeString);
   	  	
 }