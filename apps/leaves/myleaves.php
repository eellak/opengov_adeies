<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Οι Αιτήσεις Μου</h1>
	</div>
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Πίνακας Καταχωρημένων Αιτήσεων
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Ημερομηνία Υποβολής</th>
                                            <th>Είδος Άδειας</th>
                                            <th>Κατάσταση</th>   
                                            <th>Ενέργειες</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php /*try { 
      $pdoObject = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
      $pdoObject -> exec("set names utf8");
      $sql = "SELECT * FROM adeies INNER JOIN typos_adeias ON adeies.typos_id=typos_adeias.typos_id INNER JOIN katastash ON adeies.katastasi_id=katastash.katastasi_id INNER JOIN files ON adeies.adeia_id=files.adeia_id WHERE ypallhlosid=:ypallhlosid";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':ypallhlosid'=>$_SESSION['ypallhlosid']));
      while ($record = $statement -> fetch()) {
          if ($record['katastasi_id']=="0")
          {
              echo "<tr class='info'>";
          }
          else if ($record['katastasi_id']=="1")
          {
              echo "<tr class='success'>";
          }
          else
          {
              echo "<tr class='danger'>";
          }
          echo "<td>".$record['date_submitted']."</td>";
          echo "<td>".$record['typosname']."</td>";
          echo "<td>".$record['katname']."</td>";
          echo "<td><a href='formdetails.php?id=".$record['adeia_id']."'><button type='button' class='btn btn-primary btn-circle'><i class='fa fa-list'></i></button></a>"
                  . "&nbsp&nbsp<a href='".$record['filepath']."'><button type='button' class='btn btn-success btn-circle'><i class='fa fa-link'></i></button></a></td>";
          echo "</tr>"; 
      }     
      $statement->closeCursor();
      $pdoObject = null;
    } catch (PDOException $e) {
        print "Database Error: " . $e->getMessage();
        die("Αδυναμία δημιουργίας PDO Object");
    } */
    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->


    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
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
} );
    </script>
