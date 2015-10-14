
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Αιτήσεις Υπαλλήλων</h1>
                </div>
                <!-- /.col-lg-12 -->
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
                                            <th>Επώνυμο Υπαλλήλου</th>
                                            <th>Όνομα Υπαλλήλου</th>
                                            <th>Είδος Άδειας</th>
                                            <th>Κατάσταση</th>     
                                            <th>Ενέργειες</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php try { 
      $pdoObject = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
      $pdoObject -> exec("set names utf8");
      if ($_SESSION['idiotita']=='1')
      {
        $sql = "SELECT * FROM adeies INNER JOIN typos_adeias ON adeies.typos_id=typos_adeias.typos_id INNER JOIN katastash ON adeies.katastasi_id=katastash.katastasi_id INNER JOIN ypallhlos ON adeies.ypallhlosid=ypallhlos.ypallhlosid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE (ypallhlos.tmimaid=:tmimaid) AND (ypallhlos.ypallhlosid!=:ypallhlosid)";
        $statement = $pdoObject -> prepare($sql);
        $statement->execute( array(':tmimaid'=>$_SESSION['tmimaid'], ':ypallhlosid'=>$_SESSION['ypallhlosid']));
      }
      else if ($_SESSION['idiotita']=='2')
      {
          $sql = "SELECT * FROM adeies INNER JOIN typos_adeias ON adeies.typos_id=typos_adeias.typos_id INNER JOIN katastash ON adeies.katastasi_id=katastash.katastasi_id INNER JOIN ypallhlos ON adeies.ypallhlosid=ypallhlos.ypallhlosid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE (ypallhlos.dieuthinsiid=:dieuthinsiid) AND (ypallhlos.ypallhlosid!=:ypallhlosid)";
        $statement = $pdoObject -> prepare($sql);
        $statement->execute( array(':dieuthinsiid'=>$_SESSION['dieuthinsiid'], ':ypallhlosid'=>$_SESSION['ypallhlosid']));
      }
      else
      {
        $sql = "SELECT * FROM adeies INNER JOIN typos_adeias ON adeies.typos_id=typos_adeias.typos_id INNER JOIN katastash ON adeies.katastasi_id=katastash.katastasi_id INNER JOIN ypallhlos ON adeies.ypallhlosid=ypallhlos.ypallhlosid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE (ypallhlos.genikidid=:genikidid) AND (ypallhlos.ypallhlosid!=:ypallhlosid)";
        $statement = $pdoObject -> prepare($sql);
        $statement->execute( array(':genikidid'=>$_SESSION['genikidid'], ':ypallhlosid'=>$_SESSION['ypallhlosid']));
      }
      
      while ($record = $statement -> fetch()) {
          if ($record['idiotita_id']<$_SESSION['idiotita'])
          {
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
          echo "<td>".$record['epitheto']."</td>";
          echo "<td>".$record['onoma']."</td>";
          echo "<td>".$record['typosname']."</td>";
          echo "<td>".$record['katname']."</td>";
          echo "<td><a href='formdetails.php?id=".$record['adeia_id']."'>Λεπτομέρειες</a></td>";
          echo"</tr>"; 
          }
      }     
      $statement->closeCursor();
      $pdoObject = null;
    } catch (PDOException $e) {
        print "Database Error: " . $e->getMessage();
        die("Αδυναμία δημιουργίας PDO Object");
    } 
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
   
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

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
