<?php session_start(); ?>
<?php require('db_params.php');

if ($_GET['mode']=="add")
{
    if ($_POST['typos_id']==2)
    {
        $ypallhlosid=$_POST['ypallhlos_telephone'];
    }
    else
    {
        $ypallhlosid=$_SESSION['ypallhlosid'];
    }
try { 
    $pdoObject = new PDO("mysql:host=$dbhost; dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
    $pdoObject -> exec("set names utf8"); 
    $sql='INSERT INTO adeies (ypallhlosid, typos_id, date_submitted, date_starts, date_ends, ar_adeiwn) VALUES (:ypallhlosid, :typos_id, CURRENT_TIMESTAMP, :date_starts, :date_ends, :ar_adeiwn)';
    $statement = $pdoObject->prepare($sql);
    $myresult=$statement->execute( array(':ypallhlosid'=>$ypallhlosid, ':typos_id'=>$_POST['typos_id'], ':date_starts'=>$_POST['date_starts'], ':date_ends'=>$_POST['date_ends'], ':ar_adeiwn'=>$_POST['ar_adeiwn'] ));
    $statement->closeCursor();
    $pdoObject = null;
  } catch (PDOException $e) {
      header('Location: form.php?type=danger&msg=PDO Exception: '.$e->getMessage());
      exit();
  }
  
  if ( !$myresult ) {
      header('Location: form.php?type=danger&msg=Σφάλμα: αποτυχία εκτέλεσης ερωτήματος'.$ypallhlosid);
    exit();
  }  
  else
  {
    $pdoObject = new PDO("mysql:host=$dbhost; dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
    $pdoObject -> exec("set names utf8"); 
    $sql = "SELECT * FROM adeies INNER JOIN ypallhlos ON ypallhlos.ypallhlosid=adeies.ypallhlosid INNER JOIN tmima ON ypallhlos.tmimaid=tmima.tmimaid INNER JOIN dieuthinsi ON ypallhlos.dieuthinsiid=dieuthinsi.dieuthinsiid INNER JOIN geniki_dieuthinsi ON ypallhlos.genikidid=geniki_dieuthinsi.genikidid INNER JOIN idiotites ON ypallhlos.idiotita_id=idiotites.idiotita_id WHERE adeies.ypallhlosid=:ypallhlosid ORDER BY adeies.adeia_id DESC";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':ypallhlosid'=>$ypallhlosid));
      if ($record = $statement -> fetch()) {
         $ar_adeiwn=$record['ar_adeiwn'];
         $trexouses=$record['ypoloipo_adeion_trexon'];
         $adeia_id=$record['adeia_id'];
         $datesubmitted=$record['date_submitted'];
         $datestarts=$record['date_starts'];
         $dateends=$record['date_ends'];
         $vathmos=$record['idname'];
         $onoma=$record['onoma'];
         $epitheto=$record['epitheto'];
         $gdname=$record['gdname'];
         $dname=$record['dname'];
         $tmname=$record['tmname'];
        
      }
      header('Location: generator.php?onoma='.$onoma.'&epitheto='.$epitheto.'&vathmos='.$vathmos.'&gdname='.$gdname.'&dname='.$dname.'&tmname='.$tmname.'&datestarts='.$datestarts.'&dateends='.$dateends.'&datesubmitted='.$datesubmitted.'&trexouses='.$trexouses.'&ar_adeiwn='.$ar_adeiwn.'&id='.$adeia_id);
//    header('Location: myforms.php?type=success&msg=Η αίτησή σας καταχωρήθηκε επιτυχώς!');
  }
}

else if ($_GET['mode']=="validate")
{
    try { 
    $pdoObject = new PDO("mysql:host=$dbhost; dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
    $pdoObject -> exec("set names utf8"); 
    $sql = "SELECT * FROM adeies INNER JOIN ypallhlos ON ypallhlos.ypallhlosid=adeies.ypallhlosid WHERE adeies.adeia_id=:adeia_id";
      $statement = $pdoObject -> prepare($sql);
      $statement->execute( array(':adeia_id'=>$_GET['aid']));
      if ($record = $statement -> fetch()) {
         $days=$record['ar_adeiwn'];
         $trexouses=$record['ypoloipo_adeion_trexon'];
         $ypid=$record['ypallhlosid'];
        
      }
    $trexouses=$trexouses-$days;
    
    $sql1='UPDATE adeies SET katastasi_id=:katastasi_id WHERE adeia_id=:adeia_id';
    $statement = $pdoObject->prepare($sql1);
    $myresult=$statement->execute( array(':adeia_id'=>$_GET['aid'], ':katastasi_id'=>1));
    
    $sq2='UPDATE ypallhlos SET ypoloipo_adeion_trexon=:ypoloipo_adeion_trexon WHERE ypallhlosid=:ypallhlosid';
    $statement = $pdoObject->prepare($sq2);
    $myresult=$statement->execute( array(':ypallhlosid'=>$ypid, ':ypoloipo_adeion_trexon'=>$trexouses));
    
    $statement->closeCursor();
    $pdoObject = null;
  } catch (PDOException $e) {
      header('Location: form_evaluate.php?type=danger&msg=PDO Exception: '.$e->getMessage());
      exit();
  }
  
  if ( !$myresult ) {
      header('Location: form_evaluate.php?type=danger&msg=Σφάλμα: αποτυχία εκτέλεσης ερωτήματος');
    exit();
  }  
  else
  {
    header('Location: form_evaluate.php?type=info&msg=Έγινε έγκριση της αίτησης');
  }
    
}

else if ($_GET['mode']=="cancel")
{
    try { 
    $pdoObject = new PDO("mysql:host=$dbhost; dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
    $pdoObject -> exec("set names utf8"); 
    $sql='UPDATE adeies SET katastasi_id=:katastasi_id WHERE adeia_id=:adeia_id';
    $statement = $pdoObject->prepare($sql);
    $myresult=$statement->execute( array(':adeia_id'=>$_GET['aid'], ':katastasi_id'=>2));
    $statement->closeCursor();
    $pdoObject = null;
  } catch (PDOException $e) {
      header('Location: form_evaluate.php?type=danger&msg=PDO Exception: '.$e->getMessage());
      exit();
  }
  
  if ( !$myresult ) {
      header('Location: form_evaluate.php?type=danger&msg=Σφάλμα: αποτυχία εκτέλεσης ερωτήματος');
    exit();
  }  
  else
  {
    header('Location: form_evaluate.php?type=info&msg=Έγινε απόρριψη της αίτησης');
  }
}
else
{
    header('Location: form_evaluate.php?type=danger&msg=Σφάλμα επιλογών!');
}
?>