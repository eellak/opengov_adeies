<!DOCTYPE html>
<html>
<head>
 
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>JS Bin</title>
</head>
<body>
<script>
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
	
	function addDays(startDate,numberOfDays) // Απουσιάζει η στοιχειώδει συνάρτηση DateAdd 
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
	
	
	
	function CurrentYear() // Επιστρέφει το τρέχον έτος - Δεν υπάρχει η στοιχειώδη συνάρτηση Year()
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
  
   
      var startingDate;
      const daysToAdjust=10;
      var DateReturn,
          businessDaysLeft,
          tmpDate,
          iDate;
      var nonWorkingDays = [];
	  
		
	  startingDate= new Date('2016-12-20');
	  
	  tmpDate = new Date(startingDate.getTime());
      for (iDate = 0; iDate < 8*12; iDate++) {  // Αποθήκευσε στο array τα Σαββατοκύριακα για ένα χρόνο (8*12 τα σ.κ. του έτους) 
		  tmpDate=new Date(addDays(startingDate, iDate ));
           if(tmpDate.getDay()==0 || tmpDate.getDay()==6 ){
               nonWorkingDays.push(new Date(tmpDate)); 
           }
      } 
	  
	   console.log(nonWorkingDays);
	 
      //Add the holidays
	  nonWorkingDays.push(new CreateDayEortastiki(25,12,CurrentYear()));   // Χριστούγεννα 
	  nonWorkingDays.push(new CreateDayEortastiki(26,12,CurrentYear()));   // Χριστούγεννα 
	  nonWorkingDays.push(new CreateDayEortastiki(1,1,CurrentYear()));     // Πρωτοχρονιά
	  nonWorkingDays.push(new CreateDayEortastiki(6,1,CurrentYear()));    // Θεοφάνεια
	  nonWorkingDays.push(new CreateDayEortastiki(27,2,2016));  // ****** Καθαρά Δευτέρα πρέπει να αλλάζει κάθε χρόνο
	  nonWorkingDays.push(new CreateDayEortastiki(25,3,CurrentYear())); 
	  nonWorkingDays.push(new CreateDayEortastiki(14,4,2016));  // ****** Μεγάλη Παρασκευή - πρέπει να αλλάζει κάθε χρόνο
																// Μεγάλο Σάββατο και Μεγ. Κυριακή - πέφτουν πάντα σ.κ. δεν χρειάζεται να μπουν
	  nonWorkingDays.push(new CreateDayEortastiki(1,5,CurrentYear())); //Πρωτομαγιά
	   nonWorkingDays.push(new CreateDayEortastiki(5,6,2016));  // ************* Αγίου Πνεύματος - πρέπει να αλλάζει κάθε χρόνο
	  nonWorkingDays.push(new CreateDayEortastiki(15,8,CurrentYear())); // 15 Αύγουστος
	  nonWorkingDays.push(new CreateDayEortastiki(28,10,CurrentYear())); // 28η Οκτωβρίου
	  
	  
      //Determine the date of return
      businessDaysLeft=daysToAdjust;
	  
	  DateReturn= new Date(startingDate.getTime());
      while( businessDaysLeft>0 ){
          if ( !inArray(DateReturn,nonWorkingDays)) {
               businessDaysLeft--;
          }
		   DateReturn=new Date(addDays(DateReturn,1 ));
      }
	  debugger;
      console.log(DateReturn);
     
   
  </script>
</body>
</html>