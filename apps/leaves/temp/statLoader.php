<?php require("is_logged_in.php"); ?>
<?php
  require('db_params.php');
  if ($_POST['date']=="")
      {
  echo '<p class="center">Το αίτημα που υποβλήθηκε είναι κενό</p>';
  }
  else
  {
     try {
    $hasany=false;
    $pdoObject = new PDO("mysql:host=$dbhost;dbname=$dbname;", $dbuser, $dbpass);
    $pdoObject -> exec("set names utf8");
    $sql = "SELECT epitheto, onoma FROM ypallhlos INNER JOIN adeies on ypallhlos.ypallhlosid=adeies.ypallhlosid WHERE katastasi_id=1 AND (:date NOT BETWEEN date_starts AND date_ends) ORDER BY epitheto ASC";
    $statement = $pdoObject->prepare($sql);
    $statement->execute( array(':date'=>$_POST['date']));
    echo '<div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Επώνυμο</th>
                                            <th>Όνομα</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
    while ( $record = $statement->fetch() ) {
        $hasany=true;
        echo '<tr><td>'.$record['epitheto'].'</td><td>'.$record['onoma'].'</td></tr>';
    }
    echo '</tbody></table></div>';
    if($hasany==false)
    {
         echo '<p class="center">Κανένας υπάλληλος δεν είναι παρόν την ημερομηνία που επιλέξατε</p>';
    }
    $statement->closeCursor();
    $pdoObject = null;  
  } catch (PDOException $e) {  
      die("Database Error: " . $e->getMessage());
    }
  }
?>
