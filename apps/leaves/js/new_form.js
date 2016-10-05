 function inArray(needle,haystack)
    {
      var 
	  count=haystack.length;
      for(var i=0;i<count;i++)
      {
          if(haystack[i].getTime()==needle.getTime()){return true;}
      }
      return false;
    }
	
	function addDays(startDate,numberOfDays) //  συνάρτηση DateAdd 
	{
		var returnDate = new Date(
								startDate.getFullYear(),
								startDate.getMonth(),
								startDate.getDate()+numberOfDays,
								startDate.getHours(),
								startDate.getMinutes(),
								startDate.getSeconds());
		return returnDate;
	}
	
	
	
	function CurrentYear() // Επιστρέφει το τρέχον έτος 
	{  
		var CurrentDate = Date.now();
		
		var CurrentDateObj = new Date(CurrentDate);
		return CurrentDateObj.getFullYear();  }
		
	function CreateDayEortastiki(d,m,y) { //Δημιουργεί εορταστική ημερομηνία για το τρέχον έτος την ημέρα d, το μήνα m και το έτος y.
		var dt = new Date(y,m-1,d-1,0,0,0,0);
		/* var y;
		dt.setHours(0, 0, 0,0);   
		dt.setFullYear(y,m-1,d); // Το κερασάκι στη τούρτα. Ειλικρινά δεν μπορώ να καταλάβω γιατί χρειάζεται να προσθέσω μιά μέρα στη κ.....java function  */
		return dt;
	}
	
	
	
  function FindDateReturn(startingDate,daysToAdjust) { 
 
 // var startingDate = document.getElementById('dpd1').innerHTML; 
  
  // var daysToAdjust = document.getElementById("num_leaves").value;
   // console.log(daysToAdjust) ;
  // console.log(startingDate) ; 
  // debugger;
  
  var DateReturn,
	  businessDaysLeft,
	  tmpDate,
	  iDate;
  var nonWorkingDays = [];
  
  if ( daysToAdjust>0 ) {
	   tmpDate = new Date(startingDate.getTime());
  for (iDate = 0; iDate < 8*12; iDate++) {  // Αποθήκευσε στο array τα Σαββατοκύριακα για ένα χρόνο (8*12 τα σ.κ. του έτους) 
	  tmpDate=new Date(addDays(startingDate, iDate ));
	   if(tmpDate.getDay()==0 || tmpDate.getDay()==6 ){
		   nonWorkingDays.push(new Date(tmpDate)); 
	   }
  } 
  
   // console.log(nonWorkingDays);
 
  //Add the holidays
  nonWorkingDays.push(new CreateDayEortastiki(25,12,CurrentYear()));   // Χριστούγεννα 
  nonWorkingDays.push(new CreateDayEortastiki(26,12,CurrentYear()));   // Χριστούγεννα 
  nonWorkingDays.push(new CreateDayEortastiki(1,1,CurrentYear()));     // Πρωτοχρονιά
  nonWorkingDays.push(new CreateDayEortastiki(6,1,CurrentYear()));    // Θεοφάνεια
  
  nonWorkingDays.push(new CreateDayEortastiki(14,3,2016));  // ****** Καθαρά Δευτέρα πρέπει να αλλάζει κάθε χρόνο
  nonWorkingDays.push(new CreateDayEortastiki(27,2,2017));
  nonWorkingDays.push(new CreateDayEortastiki(19,2,2018));
  nonWorkingDays.push(new CreateDayEortastiki(25,3,CurrentYear())); 
  nonWorkingDays.push(new CreateDayEortastiki(29,4,2016));  // ****** Μεγάλη Παρασκευή - πρέπει να αλλάζει κάθε χρόνο
  nonWorkingDays.push(new CreateDayEortastiki(14,4,2017));
  nonWorkingDays.push(new CreateDayEortastiki(6,4,2018));
															// Μεγάλο Σάββατο και Μεγ. Κυριακή - πέφτουν πάντα σ.κ. δεν χρειάζεται να μπουν
  nonWorkingDays.push(new CreateDayEortastiki(1,5,CurrentYear())); //Πρωτομαγιά
  nonWorkingDays.push(new CreateDayEortastiki(20,6,2016));  // ************* Αγίου Πνεύματος - πρέπει να αλλάζει κάθε χρόνο
  nonWorkingDays.push(new CreateDayEortastiki(5,6,2017));
  nonWorkingDays.push(new CreateDayEortastiki(28,5,2018));
  nonWorkingDays.push(new CreateDayEortastiki(15,8,CurrentYear())); // 15 Αύγουστος
  nonWorkingDays.push(new CreateDayEortastiki(28,10,CurrentYear())); // 28η Οκτωβρίου
  
  
  //Determine the date of return
  businessDaysLeft=daysToAdjust;
  
  
  var tmpDate= new Date(startingDate.getTime());
  // debugger;
  
  while( businessDaysLeft>0 ){
	   DateReturn= new Date(tmpDate.getTime());
	   // console.log(DateReturn);
	  if ( !inArray(tmpDate,nonWorkingDays)) {
		   businessDaysLeft--;
	  }
	   tmpDate=new Date(addDays(tmpDate,1 ));
  }
  
  // console.log(DateReturn);
  return DateReturn;
  // document.getElementById("dpd2").value = DateReturn;
  // document.getElementById('dpd2').innerHTML = DateReturn;
  //debugger;
  //console.log(DateReturn);
  }
 
}









$(document).ready(function(){
			 
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	
	
	//$('#dpd1').datepicker({	format: 'yyyy-mm-dd' });
	//$('#dpd2').datepicker({	format: 'yyyy-mm-dd' });
	
	$('#dpd1').datetimepicker({	format: 'YYYY-MM-DD' });
	$('#dpd2').datetimepicker({	format: 'YYYY-MM-DD' });
	
	var checkin = $('#dpd1').datetimepicker({
		  onSelect: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		  }
		}).on('dp.change', function(ev) {
			var checkout = 	$('#dpd2').datetimepicker({	format: 'YYYY-MM-DD' });
			// var MeresAdeia = $('#num_leaves');
			var AdeiaApo = document.getElementById("dpd1").value;
			var MeresAdeia = document.getElementById("num_leaves").value;
			/* console.log(AdeiaApo) ;
			console.log(MeresAdeia) ;
			debugger; */
			//checkout.setValue(FindDateReturn(new Date(AdeiaApo),MeresAdeia));
			document.getElementById("dpd2").value = moment(FindDateReturn(new Date(AdeiaApo),MeresAdeia)).format('YYYY-MM-DD'); 
			//var checkoutDateObj = checkout.toDate();
			if (ev.valueOf() > checkout.valueOf()) {
				var newDate = new Date(ev.valueOf());
				checkout.setValue(FindDateReturn(newDate,MeresAdeia));
				//newDate.setDate(newDate.getDate() + 1);
				// checkout.setValue(newDate);
			}
			
			
			// checkin.hide();
			$('#dpd2')[0].focus();
		}).on('dp.change', function(ev) {
			//checkout.hide();
			
		}).data('datepicker');
		
		var checkout = $('#dpd2').datetimepicker({
			onSelect: function(date) {
				// return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
			}
		}).on('dp.change', function(ev) {
			//checkout.hide();
		}).data('datepicker');
		/* console.log(checkin) ;
		console.log(checkout) ; 
		debugger; */
	
	$('#leaveform').submit(function(e){;
		
		var errored = $('#errorer');
		var has_error = false;
		
		$('.required').each(function(){
			if($(this).val() !=''){}else{
				 has_error = true;
			} 
		});
		
		if( has_error) errored.html('Συμπληρώστε τα απαραίτητα πεδία').fadeIn();
		
		if(has_error)
			e.preventDefault();
	});
	
	$('#leaveform input').on('change', function() {
		if($('input[name=leave_type]:checked', '#leaveform').val() != 2){
			$('#showremaining').html();
		} else{
			var remaining = $('option:selected', $("#user_tel")).attr('rel');
			$('#showremaining').html('Υπόλοιπο αδειών <strong>'+remaining+'</strong>');
		}
	});
	
	 $("#user_tel").change(function(){ 
		var remaining = $('option:selected', this).attr('rel');
		$('#showremaining').html('Υπόλοιπο αδειών <strong>'+remaining+'</strong>');
    }); 
	
});