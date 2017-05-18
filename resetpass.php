<?php
include('config.php');
include('modules/helpers.php');
include('modules/notify/notify.php');
/**
 *   LDAP PHP Change Password Webpage
 *   @author:   Matt Rude <http://mattrude.com>
 *   @website:  http://technology.mattrude.com/2010/11/ldap-php-change-password-webpage/
 *
 *
 *              GNU GENERAL PUBLIC LICENSE
 *                 Version 2, June 1991
 *
 * Copyright (C) 1989, 1991 Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 */
 
$message = array();
$message_css = "";
 
function changePassword($user,$oldPassword,$newPassword,$newPasswordCnf){
	global $message;
	global $message_css;
	
	$con = ldap_connect(LDAP_HOST);
	if (FALSE === $con){
	   $message[] = "Σφάλμα E100 - Επικοινωνήστε με τον Διαχειριστή (Αδυναμία Σύνδεσης).";
		return false;
	}
	
	ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version..<br />');
	ldap_set_option($con, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.
	ldap_set_option($con, LDAP_OPT_DEBUG_LEVEL, 7);
	
	$dn = 'ou=People,dc=pdm,dc=gov,dc=gr';
	$sf = "(&(objectClass=PdmEduPerson)(uid=$user))"; //"(|(uid=$user)(mail=$user))"

	$user_search = ldap_search($con,$dn,$sf);
	$user_get = ldap_get_entries($con, $user_search);
	$user_entry = ldap_first_entry($con, $user_search);
	$user_dn = ldap_get_dn($con, $user_entry);
	$user_id = $user_get[0]["uid"][0];
	$user_givenName = $user_get[0]["givenname"][0].' '.$user_get[0]['sn;lang-el'][0];
	$user_mail  = strtolower($user_get[0]['mail'][0]);
	
	//$message[] = "Username: " . $user_id;
	//$message[] = "Name: " . $user_givenName;
	//$message[] = "email: " . $user_mail;
	//$message[] = "DN: " . $user_dn;
	//$message[] = "Current Pass: " . $oldPassword;
	//$message[] = "New Pass: " . $newPassword;
   
	/* Start the testing */
	
	if (ldap_bind($con, $user_dn, $oldPassword) === false) {
		$message[] = "Σφάλμα E101 - Το Όνομα Χρήστη ή το Συνθηματικό είναι λάθος.";
		return false;
	}
	
	if (ldap_bind($con, 'uid='.LDAP_USER.',ou=People,dc=pdm,dc=gov,dc=gr', LDAP_PASS) === false) {
		$message[] = "Σφάλμα E300 - Επικοινωνήστε με τον Διαχειριστή (Αδυναμία Bind).";
		return false;
	}
	
	if ($newPassword != $newPasswordCnf ) {
		$message[] = "Σφάλμα E102 - Τα νέα συνθηματικά δεν ταιριάζουν!";
		return false;
	}
	
	$encoded_newPassword = "{SHA}" . base64_encode( pack( "H*", sha1( $newPassword ) ) );
	
	if (strlen($newPassword) < 8 ) {
		$message[] = "Σφάλμα E103 - Το νέο συνθηματικό είναι σύντομο (τουλάχιστον 8 χαρακτήρες).";
		return false;
	}
	if (!preg_match("/[0-9]/",$newPassword)) {
		$message[] = "Σφάλμα E104 -Το νέο συνθηματικό πρέπει να περιέχει τουλάχιστον ένα αριθμό.";
		return false;
	}
	if (!preg_match("/[a-zA-Z]/",$newPassword)) {
		$message[] = "Σφάλμα E105 - Το νέο συνθηματικό πρέπει να περιέχει τουλάχιστον ένα γράμμα.";
		return false;
	}
	if (!preg_match("/[A-Z]/",$newPassword)) {
		$message[] = "Σφάλμα E106 - Το νέο συνθηματικό πρέπει να περιέχει τουλάχιστον ένα κεφαλαίο γράμμα.";
		return false;
	}
	if (!preg_match("/[a-z]/",$newPassword)) {
		$message[] = "Σφάλμα E107 - Το νέο συνθηματικό πρέπει να περιέχει τουλάχιστον ένα μικρό γράμμα.";
		return false;
	}
	if (!$user_get) {
		$message[] = "Σφάλμα E200 - Επικοινωνήστε με τον Διαχειριστή (Αδυναμία Αναζήτησης).";
		return false;
	}

	/* And Finally, Change the password */
	$entry = array();
	$entry["userpassword"] = "$encoded_newPassword";
   
	if (ldap_modify($con,$user_dn,$entry) === false){
		$error = ldap_error($con);
		$errno = ldap_errno($con);
		$message[] = "Σφάλμα E201 - Επικοινωνήστε με τον Διαχειριστή.<br />";
		$message[] = "$errno - $error";
	} else {
		initiate_email();
		$message_css = "yes";
		//$user_mail = 'fotis.routsis@gmail.com';
		email_send($user_mail, $user_givenName, 'Ενημέρωση - Επιτυχής αλλαγή συνθηματικού', "Αγαπητέ/ή $user_givenName,<br />
Το συνθηματικό σας για την σύνδεσή σας στις Εσωτερικές Υπηρεσίες της ΠΔΜ (http://apps.pdm.gov.gr/in/) τροποποιήθηκε επιτυχώς.<br />Αν δεν προχωρήσατε εσείς στην αλλαγή παρακαλούμε επικοινωνήστε άμεσα με το διαχειριστή συστημάτων σας, διαφορετικά αγνοήστε το παρόν μήνυμα.
 <br /><br />
--<br />
Το παραπάνω μήνυμα είναι αυτοματοποιημένο, παρακαλούμε μην απαντήσετε σε αυτό.<br />");
		$message[] = "Ο κωδικός σας έχει τροποποιηθεί επιτυχώς.<br/>Επιπλέον ένα μήνυμα ηλεκτρονικού ταχυδρομείου εχει σταλεί στον email σας.";
	}
}
 
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Περιφέρεια Δυτικής Μακεδονίας - Αλλαγή Συνθηματικού</title>
	
    <link href="http://apps.pdm.gov.gr/in/assets/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://apps.pdm.gov.gr/in/assets/css/sb-admin-2.css" rel="stylesheet">
    <link href="http://apps.pdm.gov.gr/in/assets/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div class="container">
        <div class="row">
			<div id="headerlogin">
				<a href="index.php"/><img id="headerlogo" src="http://apps.pdm.gov.gr/in/assets/img/logo.png" alt="Περιφέρεια Δυτικής Μακεδονίας" /></a>
			</div>
            <div class="col-md-5 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading  text-center">
                        <h3 class="panel-title">Αλλαγή Συνθηματικού Χρήστη</h3>
                    </div>
                    <div class="panel-body">
						<form method="post" id="resetform" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<?php
						  if (isset($_POST["submitted"])) {
							changePassword($_POST['username'],$_POST['oldPassword'],$_POST['newPassword1'],$_POST['newPassword2']);
							global $message_css;
							if ($message_css == "yes") {
							  ?><div class="alert alert-success"><?php
							 } else {
							  ?><div class="alert alert-danger"><?php
							  $message[] = "<strong>To συνθηματικό σας ΔΕΝ τροποποιήθηκε.</strong>";
							}
							foreach ( $message as $one ) { echo "<p>$one</p>"; }
						  ?></div><?php
						  } ?>
						  
						  <div class="alert alert-message">
								<p>Το Νέο Συνθηματικό σας θα πρέπει</p>
								<ul>
									<li>Nα έχει μηκος τουλάχιστον 8 χαρακτήρων</li>
									<li>Nα περιέχει τουλάχιστον 1 κεφαλαίο χαρακτήρα</li>
									<li>Nα περιέχει τουλάχιστον 1 πεζό χαρακτήρα</li>
									<li>Nα περιέχει τουλάχιστον 1 αριθμό</li>
								</ul>
							</div>
							
							<div class="form-group">
								<label>Όνομα Χρήστη</label>
								<input class="form-control" name="username" id="username" type="text" autocomplete="off" />
							</div>
							
							<div class="form-group">
								<label>Υπάρχων Συνθηματικό</label>
								<input class="form-control" name="oldPassword" id="oldPassword" type="password" autocomplete="off" />
							</div>
							
							<div class="form-group">
								<label>Νέο Συνθηματικό</label>
								<input class="form-control" name="newPassword1" id="newPassword1" type="password" autocomplete="off" />
							</div>
							
							<div class="form-group">
								<label>Νέο Συνθηματικό (επανάληψη)</label>
								<input class="form-control" name="newPassword2" id="newPassword2" type="password" autocomplete="off" />
							</div>
							
							<input name="submitted" type="submit" class="btn btn-lg btn-success btn-block" value="Αλλαγή Συνθηματικού"/>
						</form>
					</div>
                </div>
				<p class="text-center">
					<a href="http://apps.pdm.gov.gr/in/">Είσοδος</a> | <a href="http://apps.pdm.gov.gr/in/people.php" target="_blank">Κατάλογος Χρηστών</a>
				</p>
            </div>
        </div>
    </div>

    <script src="http://apps.pdm.gov.gr/in/assets/lib/jquery/dist/jquery.min.js"></script>
    <script src="http://apps.pdm.gov.gr/in/assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>