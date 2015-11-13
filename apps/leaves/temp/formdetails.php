<?php require("header.php"); ?>
<?php try { 
      $pdoObject = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
      $pdoObject -> exec("set names utf8");
      if ($_SESSION['idiotita']==0)
      {
      $sql = "SELECT * FROM adeies INNER JOIN ypallhlos ON ypallhlos.ypallhlosid=adeies.ypallhlosid INNER JOIN typos_adeias ON typos_adeias.typos_id=adeies.typos_id INNER JOIN tmima ON ypallhlos.tmimaid=tmima.tmimaid INNER JOIN dieuthinsi ON ypallhlos.dieuthinsiid=dieuthinsi.dieuthinsiid INNER JOIN geniki_dieuthinsi ON ypallhlos.genikidid=geniki_dieuthinsi.genikidid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE adeies.ypallhlosid=:ypallhlosid AND adeies.adeia_id=:adeia_id";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':adeia_id'=>$_GET['id'], ':ypallhlosid'=>$_SESSION['ypallhlosid']));
      }
      else if ($_SESSION['idiotita']==1)
      {
      $sql = "SELECT * FROM adeies INNER JOIN ypallhlos ON ypallhlos.ypallhlosid=adeies.ypallhlosid INNER JOIN typos_adeias ON typos_adeias.typos_id=adeies.typos_id INNER JOIN tmima ON tmima.tmimaid=ypallhlos.tmimaid INNER JOIN dieuthinsi ON ypallhlos.dieuthinsiid=dieuthinsi.dieuthinsiid INNER JOIN geniki_dieuthinsi ON ypallhlos.genikidid=geniki_dieuthinsi.genikidid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE ypallhlos.tmimaid=:tmimaid AND adeies.adeia_id=:adeia_id";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':adeia_id'=>$_GET['id'], ':tmimaid'=>$_SESSION['tmimaid']));
      }
      else if ($_SESSION['idiotita']==2)
      {
      $sql = "SELECT * FROM adeies INNER JOIN ypallhlos ON ypallhlos.ypallhlosid=adeies.ypallhlosid INNER JOIN typos_adeias ON typos_adeias.typos_id=adeies.typos_id INNER JOIN tmima ON ypallhlos.tmimaid=tmima.tmimaid INNER JOIN dieuthinsi ON ypallhlos.dieuthinsiid=dieuthinsi.dieuthinsiid INNER JOIN geniki_dieuthinsi ON ypallhlos.genikidid=geniki_dieuthinsi.genikidid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE ypallhlos.dieuthinsiid=:dieuthinsiid AND adeies.adeia_id=:adeia_id";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':adeia_id'=>$_GET['id'], ':dieuthinsiid'=>$_SESSION['dieuthinsiid']));
      }
      else if ($_SESSION['idiotita']==3)
      {
      $sql = "SELECT * FROM adeies INNER JOIN ypallhlos ON ypallhlos.ypallhlosid=adeies.ypallhlosid INNER JOIN typos_adeias ON typos_adeias.typos_id=adeies.typos_id INNER JOIN tmima ON ypallhlos.tmimaid=tmima.tmimaid INNER JOIN dieuthinsi ON ypallhlos.dieuthinsiid=dieuthinsi.dieuthinsiid INNER JOIN geniki_dieuthinsi ON ypallhlos.genikidid=geniki_dieuthinsi.genikidid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE ypallhlos.genikidid=:genikidid AND adeies.adeia_id=:adeia_id";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':adeia_id'=>$_GET['id'], ':genikidid'=>$_SESSION['genikidid']));
      }
      else if ($_SESSION['idiotita']==4 || $_SESSION['idiotita']==5)
      {
      $sql = "SELECT * FROM adeies INNER JOIN ypallhlos ON ypallhlos.ypallhlosid=adeies.ypallhlosid INNER JOIN typos_adeias ON typos_adeias.typos_id=adeies.typos_id INNER JOIN tmima ON ypallhlos.tmimaid=tmima.tmimaid INNER JOIN dieuthinsi ON ypallhlos.dieuthinsiid=dieuthinsi.dieuthinsiid INNER JOIN geniki_dieuthinsi ON ypallhlos.genikidid=geniki_dieuthinsi.genikidid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE adeies.adeia_id=:adeia_id";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':adeia_id'=>$_GET['id']));
      }
      if ($record = $statement -> fetch()) {
        $record_exists=true;
        $onoma=$record['onoma'];
        $ypallhlosid=$record['ypallhlosid'];
        $epitheto=$record['epitheto'];
        $idiotita=$record['idiotita_id'];
        $type=$record['typosname'];
        $datesubmitted=$record['date_submitted'];
        $datestarts=$record['date_starts'];
        $dateends=$record['date_ends'];
        $katastasi=$record['katastasi_id'];
        $aitisiid=$record['adeia_id'];
        $days=$record['ar_adeiwn'];
        if ($record['fylo']=="0")
        {
            $fylo="Άνδρας";
        }
        else
        {
            $fylo="Γυναίκα";
        };
        $tmima=$record['tmname'];
        $gdieutthinsi=$record['gdname'];
        $dieuthinsi=$record['dname'];
        $maxadeies=$record['max_adeies'];
        $idiotitaname=$record['idname'];
        $adeiestrexon=$record['ypoloipo_adeion_trexon'];
        $adeiespalio=$record['ypoloipo_adeion_palio'];
        
      } else $record_exists=false; 

      $sql2 = "SELECT Count(paidia.ypallhlosid) AS count_paidia FROM paidia WHERE ypallhlosid=:ypid";
      $statement = $pdoObject -> prepare($sql2);
      $statement->execute( array(':ypid'=>$ypallhlosid));
      if ($record = $statement -> fetch()) {
          $paidia=$record['count_paidia'];      
      }

      if ($paidia>0)
      {
          $count_young=0;
          $sql2 = "SELECT * FROM paidia WHERE ypallhlosid=:ypid";
          $statement = $pdoObject -> prepare($sql2);
          $statement->execute( array(':ypid'=>$ypallhlosid));
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
                    <h1 class="page-header">Λεπτομέρειες Αίτησης</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <?php 
                    if ($katastasi==0)
                    { ?>
                        <div class="panel panel-info">
                            <div class="panel-heading">Η αίτηση βρίσκεται σε στάδιο αξιολόγησης</div>
                   <?php }
                    else if ($katastasi==1)
                    { ?>
                        <div class="panel panel-success">
                            <div class="panel-heading">Η αίτηση εγκρίθηκε</div>
                   <?php }
                    else { ?>
                        <div class="panel panel-danger">
                            <div class="panel-heading">Η αίτηση απορρίφθηκε</div>
                    <?php }
                    ?>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-3">
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
                                <div class="col-lg-3">
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
                                            <p class="form-control-static"><?php echo $idiotitaname; ?></p>
                                        </div> 
                                        </div>
                                       </div>
                                </div>
                                <div class="col-lg-3">
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
                                <div class="col-lg-3">
                                      <div class="panel panel-red">
                        <div class="panel-heading">
                            Τρέχουσα Άδεια
                        </div>
                                                <div class="col-lg-12">
                                    <div class="form-group">
                                            <label>Είδος Άδειας</label>
                                            <p class="form-control-static"><?php echo $type; ?></p>
                                        </div>
                                    <div class="form-group">
                                            <label>Ημερομηνία Υποβολής</label>
                                            <p class="form-control-static"><?php echo $datesubmitted; ?></p>
                                        </div>
                                    <div class="form-group">
                                            <label>Αιτούμενες Ημέρες</label>
                                            <p class="form-control-static"><?php echo $days; ?></p>
                                        </div>
                                    <div class="form-group">
                                            <label>Ημερομηνία Έναρξης</label>
                                            <p class="form-control-static"><?php echo substr($datestarts, 0, 10); ?></p>
                                        </div>
                                    <div class="form-group">
                                            <label>Ημερομηνία Λήξης</label>
                                            <p class="form-control-static"><?php echo substr($dateends, 0, 10); ?></p>
                                        </div>
                                    <?php if($_SESSION['idiotita']>$idiotita)
                                                {
                                    if ($katastasi==0){ ?>
                                    <div class="form-group">
                                            <label>Ενέργειες</label>
                                            <p class="form-control-static">
                                                <?php 
                                                    echo '<a href="form_handler.php?mode=validate&uid='.$_SESSION['ypallhlosid'].'&aid='.$aitisiid.'"/><button type="button" class="btn btn-outline btn-success">Έγκριση</button></a>&nbsp&nbsp<a href="form_handler.php?mode=cancel&uid='.$_SESSION['ypallhlosid'].'&aid='.$aitisiid.'"/><button type="button" class="btn btn-outline btn-danger">Απόρριψη</button></a>';                                             
                                                ?>
                                                
                                            </p>
                                        </div>
                                                <?php } } ?>
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
    <script src='../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>


</body>

</html>
