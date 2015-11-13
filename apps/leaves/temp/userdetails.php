<?php require("header.php"); ?>
<?php try { 
      $pdoObject = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
      $pdoObject -> exec("set names utf8");
      $sql = "SELECT * FROM ypallhlos INNER JOIN tmima ON ypallhlos.tmimaid=tmima.tmimaid INNER JOIN dieuthinsi ON ypallhlos.dieuthinsiid=dieuthinsi.dieuthinsiid INNER JOIN geniki_dieuthinsi ON ypallhlos.genikidid=geniki_dieuthinsi.genikidid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE username=:username";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':username'=>$_SESSION["username"]));
      if ($record = $statement -> fetch()) {
        $record_exists=true;
        $onoma=$record['onoma'];
        $epitheto=$record['epitheto'];
        $tmima=$record['tmname'];
        $gdieutthinsi=$record['gdname'];
        $dieuthinsi=$record['dname'];
        if ($record['fylo']=="0")
        {
            $fylo="Άνδρας";
        }
        else
        {
            $fylo="Γυναίκα";
        };
        $maxadeies=$record['max_adeies'];
        $adeiestrexon=$record['ypoloipo_adeion_trexon'];
        $adeiespalio=$record['ypoloipo_adeion_palio'];
        $idiotita=$record['idname'];
        
      } else $record_exists=false; 
      
      $sql2 = "SELECT Count(paidia.ypallhlosid) AS count_paidia FROM paidia WHERE ypallhlosid=:ypid";
      $statement = $pdoObject -> prepare($sql2);
      $statement->execute( array(':ypid'=>$_SESSION['ypallhlosid']));
      if ($record = $statement -> fetch()) {
          $paidia=$record['count_paidia'];      
      }

      if ($paidia>0)
      {
          $count_young=0;
          $sql2 = "SELECT * FROM paidia WHERE ypallhlosid=:ypid";
          $statement = $pdoObject -> prepare($sql2);
          $statement->execute( array(':ypid'=>$_SESSION['ypallhlosid']));
          while ($record = $statement -> fetch()) {
              if(time() < (strtotime('+17 years', strtotime($record['birthday']))))
          {
              $count_young++;
              
          }  
       }
      }
      $statement->closeCursor();
      $pdoObject = null;
    } catch (PDOException $e) {
        print "Database Error: " . $e->getMessage();
        die("Αδυναμία δημιουργίας PDO Object");
    } 
    ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Λογαριασμός</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Καρτέλα Υπαλλήλου
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Προσωπικά Στοιχεία
                        </div>
                                        <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Επώνυμο</label>
                                            <p class="form-control-static"><?php echo $epitheto; ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Όνομα</label>
                                            <p class="form-control-static"><?php echo $onoma; ?></p>
                                        </div>
                                      <div class="form-group">
                                            <label>Φύλο</label>
                                            <p class="form-control-static"><?php echo $fylo; ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Αρ. Παιδιών</label>
                                            <p class="form-control-static"><?php echo $paidia; ?></p>
                                        </div>
                                       <?php if ($paidia>0) { ?>
                                        <div class="form-group">
                                            <label>Ανήλικα Παιδιά</label>
                                            <p class="form-control-static"><?php echo $count_young; ?></p>
                                        </div>
                                       <?php } ?>
                                </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="panel panel-green">
                        <div class="panel-heading">
                            Επαγγελματικά Στοιχεία
                        </div>
                                        <div class="col-lg-12">
                                    <div class="form-group">
                                            <label>Γενική Διεύθυνση</label>
                                            <p class="form-control-static"><?php echo $gdieutthinsi; ?></p>
                                        </div>
                                     <div class="form-group">
                                            <label>Διεύθυνση</label>
                                            <p class="form-control-static"><?php echo $dieuthinsi; ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Τμήμα</label>
                                            <p class="form-control-static"><?php echo $tmima; ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Βαθμός</label>
                                            <p class="form-control-static"><?php echo $idiotita; ?></p>
                                        </div> 
                                        </div>
                                       </div>
                                </div>
                                        <div class="col-lg-4">
                                            <div class="panel panel-yellow">
                        <div class="panel-heading">
                            Στοιχεία Αδειών
                        </div>
                                                <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Μέγιστος Αριθμός Αδειών</label>
                                            <p class="form-control-static"><?php echo $maxadeies; ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Τρέχον Υπόλοιπο Αδειών</label>
                                            <p class="form-control-static"><?php echo $adeiestrexon; ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Παλαιό Υπόλοιπο Αδειών</label>
                                            <p class="form-control-static"><?php echo $adeiespalio; ?></p>
                                        </div>
                                                </div>
                                </div>
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

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>


</body>

</html>
