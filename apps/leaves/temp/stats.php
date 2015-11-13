<?php require("header.php"); ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Στατιστικά Αδειών</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
             <div class="col-lg-12">
                 <div class="panel panel-info">
                 <?php if ($_GET["mode"]==1)
                 { ?>
                    <div class="panel-heading">Παρόντες υπάλληλοι ανά ημερομηνία</div>
                    <div class="panel-body">
                        <label>Επιλέξτε την ημερομηνία που σας ενδιαφέρει και πατήστε "Φόρτωση"</label>
                        <input class="form-control , col-lg-3" name="date_parontes" id="dpd1" size="16" type="text" value="ΕΕΕΕ/ΜΜ/ΗΗ"/><button class="col-lg-2 , btn btn-default" value="Φόρτωση" onclick="search();">Φόρτωση</button>
                        <div id="results"><br></div>
                    </div>      
                 <?php } else if ($_GET["mode"]==2)
                 { ?>
                    <div class="panel-heading">Απόντες υπάλληλοι ανά ημερομηνία</div>
                    <div class="panel-body">
                        <input class="form-control" name="date_apontes" id="dpd2" size="16" type="text" value="ΕΕΕΕ/ΜΜ/ΗΗ"/>
                    </div>  
                 <?php } else if ($_GET["mode"]==3)
                 { ?>
                    <div class="panel-heading">Απόντες υπάλληλοι μεταξύ διαστήματος ημερομηνιών</div>
                    <div class="panel-body">
                        <p>Παρόντες υπάλληλοι για την ημερομηνία 
                        test</p></div>  
                 <?php } else if ($_GET["mode"]==4)
                 { ?>
                    <div class="panel-heading">Εβδομαδιαία-Μηνιαία αναφορά</div>
                    <div class="panel-body">
                        <p>Παρόντες υπάλληλοι για την ημερομηνία 
                        test</p></div>  
                 <?php } else {
                 } ?>
                    </div>
             </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    
    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    
    <script src="../addons/datepicker/js/bootstrap-datepicker.js"></script>
    
    <script type="text/javascript">

 $(document).ready(function(){
     
         var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 $('#dpd1').datepicker({
				format: 'yyyy-mm-dd'
			});
                        $('#dpd2').datepicker({
				format: 'yyyy-mm-dd'
			});
var checkin = $('#dpd3').datepicker({
  onRender: function(date) {
    return date.valueOf() < now.valueOf() ? 'disabled' : '';
  }
}).on('changeDate', function(ev) {
  if (ev.date.valueOf() > checkout.date.valueOf()) {
    var newDate = new Date(ev.date)
    newDate.setDate(newDate.getDate() + 1);
    checkout.setValue(newDate);
  }
  checkin.hide();
  $('#dpd4')[0].focus();
}).data('datepicker');
var checkout = $('#dpd4').datepicker({
  onRender: function(date) {
    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
  }
}).on('changeDate', function(ev) {
  checkout.hide();
}).data('datepicker');
       });
       
       function search() {
	var xmlhttp;
	if (window.XMLHttpRequest) {
		xmlhttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	else {
		alert("Your browser does not support XMLHTTP!");
  }
  
  var d = new Date();
  var url= "statLoader.php?foo="+d;  
  document.getElementById('results').innerHTML="&nbsp;&nbsp;<img src='img/AjaxLoader.gif' class='spinner' alt='Spinner'/>";
  var date= document.getElementById("dpd1").value;
  xmlhttp.open("POST",url,true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send("date="+date);  
  xmlhttp.onreadystatechange=function() {
		if(xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("results").innerHTML= xmlhttp.responseText;
                        initiate();
		} else 
        document.getElementById("results").innerHTML= "Σφάλμα φόρτωσης!";
	}       
}

    function initiate() {
    $('#dataTables-example').DataTable( {
        language: {
            lengthMenu: "Εμφάνιση _MENU_ καταχωρήσεων ανά σελίδα",
            zeroRecords: "Κανένα Αποτέλεσμα",
            info: "Σελίδα _PAGE_ από _PAGES_",
            infoEmpty: "Δεν υπάρχουν διαθέσιμα δεδομένα",
            infoFiltered: "(φιλτραρίστηκαν _MAX_ συνολικές καταχωρήσεις)",
            loadingRecords: "Φόρτωση...",
            processing:     "Επεξεργασία...",
    search:         "Αναζήτηση:",
    paginate: {
        first:      "Πρώτη",
        last:       "Τελευταία",
        next:       "Επόμενη",
        previous:   "Προηγούμενη"
    },
    aria: {
        sortAscending:  ": αύξουσα ταξινόμηση",
        sortDescending: ": φθίνουσα ταξινόμηση"
    }
        }
    } );
}
    </script>


</body>

</html>
