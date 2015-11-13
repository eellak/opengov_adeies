<?php
require('db_params.php');
require('../addons/tcpdf/tcpdf_import.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Περιφέρεια Δυτικής Μακεδονίας');
$pdf->SetTitle('Αίτηση άδειας του ');
$pdf->SetSubject('Λεπτομέρειες αίτησης');
// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default font subsetting mode
$pdf->setFontSubsetting(true);
// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 12, '', true);
// Add a page
$pdf->AddPage();
// the form input data
         $aitoumenes=$_GET['ar_adeiwn'];
         $trexouses=$_GET['trexouses'];
         $datesubmitted=$_GET['datesubmitted'];
         $datestarts=substr($_GET['datestarts'], 0, 10);
         $dateends=substr($_GET['dateends'], 0, 10);
         $vathmos=$_GET['vathmos'];
         $onoma=$_GET['onoma'];
         $epitheto=$_GET['epitheto'];
         $gdname=$_GET['gdname'];
         $dname=$_GET['dname'];
         $tmname=$_GET['tmname'];
        
// The HTML Content with the form data
$html = <<<_EOM
<html lang="el">
	<body>
		<h1 style="text-align:left;font-size:15px;">Περιφέρεια Δυτικής Μακεδονίας</h1>
                <p style="text-align:left;font-size:13px;">Γενική Διεύθυνση $gdname</p>
                <p style="text-align:left;font-size:13px;">Διεύθυνση $dname</p>
                <p style="text-align:left;font-size:13px;">Τμήμα $tmname</p>
        <br>
        <hr/>
        <br>
		<p><strong>Ονοματεπώνυμο Εργαζομένου:</strong> $onoma $epitheto</p>
		<p><strong>Βαθμός:</strong> $vathmos</p>
                <p><strong>Ημερομηνία Υποβολής Αίτησης:</strong> $datesubmitted</p>
		<p><strong>Αιτούμενες ημέρες άδειας:</strong> $aitoumenes</p>
                <p><strong>Επιθυμητή Ημερομηνία Έναρξης:</strong> $datestarts</p>
                <p><strong>Επιθυμητή Ημερομηνία Λήξης:</strong> $dateends</p>
        <br>
        <hr/>
        <br>
                <p><strong>Υπόλοιπο Ημερών:</strong> $trexouses</p>
	</body>
</html>
_EOM;
// Print text using writeHTMLCell()
$pdf->writeHTML($html, true, false, false, false, '');
// ---------------------------------------------------------
// Close and output PDF document
// replace 'I' with 'D' to force document download
$filename=date("Y-m-d H-i-s").'-'.rand(00000, 99999);
$filepath=__DIR__ .'\\files\\'.$filename.'.pdf';
$smallpath='files\\'.$filename.'.pdf';
$pdf->Output($filepath, 'F');

try { 
    $pdoObject = new PDO("mysql:host=$dbhost; dbname=$dbname;charset=UTF8", $dbuser, $dbpass);
    $pdoObject -> exec("set names utf8"); 
    $sql='INSERT INTO files (adeia_id, filename, filepath) VALUES (:adeia_id, :filename, :filepath)';
    $statement = $pdoObject->prepare($sql);
    $myresult=$statement->execute( array(':adeia_id'=>$_GET['id'], ':filename'=>$filename, ':filepath'=>$smallpath ));
    $statement->closeCursor();
    $pdoObject = null;
  } catch (PDOException $e) {
      header('Location: myforms.php?type=danger&msg=PDO Exception: '.$e->getMessage());
      exit();
  }
  
  if ( !$myresult ) {
      header('Location: myforms.php?type=danger&msg=Σφάλμα: αποτυχία εκτέλεσης ερωτήματος');
    exit();
  }
  else {
header('Location: myforms.php?type=success&msg=Η αίτησή σας καταχωρήθηκε επιτυχώς!');
  }
?>