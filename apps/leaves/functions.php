<?php
	
	function get_remaining_leaves(){ //Μέθοδος για εμφάνιση υπολοίπου ημερών άδειας τρέχοντος χρήστη
		global $db, $user;
		if(!empty($user->unit_g)){ //Αν ο χρήστης έχει κάνει αίτηση για άδεια
			$query = $db->prepare('SELECT * FROM `leaves` where id = :user_id' );
			
			$query->bindValue(':user_id', 	$user->id,				PDO::PARAM_STR); 	
			$query->execute();
			$leaves = $query->fetchObject();	
			return $leaves->remaining_leaves;
		}
	}
	
	
	
	
	function get_user_afm() { //afm ΤΡΈΧΩΝ ΧΡΗΣΤΗ
		global  $user;
		return  $user->afm;
	}
	
	function get_user_dieuthinsi() {  // ΔΙΕΥΘΥΝΣΗ ΠΟΥ ΑΝΗΚΕΙ Ο ΤΡΕΧΩΝ ΧΡΗΣΤΗΣ
		global  $user;
		return  $user->unit_g;
	}
	
	function afm_to_name($afm){ //Επιστρέφει το Ονοματεπώνυμο του user με ΑΦΜ $afm 
		global $db;
		$query = $db->prepare('SELECT first_name, last_name FROM main_users WHERE afm = :afm' );
		$query->bindValue(':afm',	$afm,				PDO::PARAM_STR); 
		$query->execute();
		$row = $query->fetch();
		return $row['first_name']. " ".$row['last_name'];
		
	} 
	
	function afm_to_email($afm){ //Επιστρέφει το email του user με ΑΦΜ $afm 
		global $db;
		$query = $db->prepare('SELECT email FROM main_users WHERE afm = :afm' );
		$query->bindValue(':afm',	$afm,				PDO::PARAM_STR); 
		$query->execute();
		$row = $query->fetch();
		return $row['email'];
		
	} 
	
	function afm_to_id($afm){ //επιστρέφει το id en;ow xr;hsth me ΑΦΜ $afm.
		global $db;
		$query = $db->prepare('SELECT id FROM main_users WHERE afm = :afm' );
		$query->bindValue(':afm',	$afm,				PDO::PARAM_STR); 
		$query->execute();
		$row = $query->fetch();
		return $row['id'];
		
	} 
	
	
	
	
	function get_remaining_leaves_for_user($user_id){ //Μέθοδος για εμφάνιση υπολοίπου ημερών άδειας οποιουδήποτε χρήστη
		global $db;
		
		$query = $db->prepare('SELECT * FROM `leaves` where id = :user_id' );
		
		$query->bindValue(':user_id', 	$user_id,				PDO::PARAM_STR); 	
		$query->execute();
		$leaves = $query->fetchObject();	
		
		return $leaves->remaining_leaves;
	}
	
	
	
	function get_all_leaves(){
		global $db;
		$query = $db->prepare('SELECT * from leaves_submissions');			
		$query->execute();
		return $query->fetchAll();	
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Save New Application Details
	*  -------------------------------------------------------------------------------------*/
	function save_new_application(){
		if(isset($_POST['num_leaves']) and intval(trim($_POST['num_leaves'])) > 0){ //Αν ο αριθμός ημερών δεν είναι κενός και το αριθμός είναι μεγαλύτερος του 0
			global $db, $user, $message_list;
			
			// Check if the number of days requested is available (remaining days)
			if (trim($_POST['leave_type']) == 2) {
				$afm = trim($_POST['user_tel']);
				if( intval(trim($_POST['num_leaves'])) > get_remaining_leaves_for_user(afm_to_id($afm)) ){
					$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Το υπόλοιπο των ημερών αδείας του υπαλλήλου σας δεν επαρκεί.' );
					return;
				}
			}
			else {
				if(intval(trim($_POST['num_leaves'])) > get_remaining_leaves()){
					$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Το υπόλοιπο των ημερών αδείας σας δεν επαρκεί.' );
					return;
				}
			}
			
			$query = $db->prepare('INSERT INTO leaves_submissions (leave_id, employee_afm, type_id, date_submitted, submitted_by, date_starts, date_ends, num_leaves, ip_submitted, remaining_leaves, filename) VALUES(NULL, :employee_afm, :type_id, :date_submitted, :submitted_by, :date_starts, :date_ends, :num_leaves, :ip_submitted, :remaining_leaves, :filename)');
			
			$afm = $user->afm;
			$submitted_by = '';
			if(trim($_POST['leave_type']) == 2){ // This is  request via telephone 
				$afm = trim($_POST['user_tel']);
				$submitted_by = $user->afm;
			}
			
			$submission_date = date("Y-m-d H:i:s");
			$filename = date("Y-m-d_H_i_s").'_'.get_rand_id(5).'.pdf';
			
			$query->bindValue(':employee_afm', 		$afm, 							PDO::PARAM_STR); 
			$query->bindValue(':type_id', 			trim($_POST['leave_type']), 	PDO::PARAM_INT); 
			$query->bindValue(':date_submitted', 	$submission_date, 				PDO::PARAM_STR); 
			$query->bindValue(':submitted_by', 		$submitted_by, 					PDO::PARAM_STR); 
			$query->bindValue(':date_starts', 		trim($_POST['date_starts']), 	PDO::PARAM_STR); 
			$query->bindValue(':date_ends', 		trim($_POST['date_ends']), 		PDO::PARAM_STR);  
			$query->bindValue(':num_leaves', 		trim($_POST['num_leaves']), 	PDO::PARAM_INT);  
			$query->bindValue(':ip_submitted', 		$_SERVER['REMOTE_ADDR'], 		PDO::PARAM_STR);   
			$query->bindValue(':remaining_leaves', 	get_remaining_leaves(), 		PDO::PARAM_STR);   
			$query->bindValue(':filename', 			$filename, 						PDO::PARAM_STR);   
			
			$query->execute();
			
			$id = $db->lastInsertId();
			if ($id != 0) {
				
				// Prepare the pdf body
				$leave_user = get_user_details_by_afm($afm);
				$pdf_body = '<h4>Αίτηση Άδειας</h4>';
				$pdf_body .= '<p>Όνομα: '.$leave_user->first_name.'</p>';
				$pdf_body .= '<p>Επίθετο: '.$leave_user->last_name.'</p>';
				$pdf_body .= '<p>Ημερομηνία Έναρξης Άδειας: '. printDate(trim($_POST['date_starts'])).'</p>';
				$pdf_body .= '<p>Ημερομηνία Λήξης Άδειας: '. printDate(trim($_POST['date_ends'])).'</p>';
				$pdf_body .= '<p>Ημέρες Άδειας: '.trim($_POST['num_leaves']).'</p>';
				$pdf_body .= '<p>Ημέρομηνία Υποβολής: '. printDate($submission_date).'</p>';
				
				// Also print the pdf
				$full_path_filename = getcwd().'/apps/leaves/files/'.$filename;
				print_pdf($full_path_filename, $pdf_body);
				
				if(DEBUG){ // This is in development mode..
					$address 	= LEAVES_DEBUG_USER_EMAIL;
					$receiver	= LEAVES_DEBUG_USER_NAME;
				} else {	// Send email to the employer
					$address 	= $leave_user->email;
					$receiver	= $leave_user->first_name.' '.$leave_user->last_name;
				}
				
				$subject 	= 'Η Αίτηση Αδείας σας υποβλήθηκε επιτυχώς'; 
				$body 		= '<p>Η Αίτηση Αδείας σας υποβλήθηκε επιτυχώς</p>';
				$body 		.= '<p>Επισυνάπτεται αντίγραφο της αίτησή σας.</p>';
				$body 		.= '<p>Θα ενημερωθείτε με νεώτερο email μετά το πέρας της επεξεργασίας της αίτησής σας.</p>';
				
				email_send($address, $receiver, $subject, $body, $full_path_filename);
				
				if(trim($_POST['leave_type']) != 2){ // If it is by telephone no need to alert the supervisors
					// Maybe send this no matter what..?
					$supervisors = get_user_supervisors($leave_user);
					foreach($supervisors as $supervisor){
					
						if(DEBUG){ // This is in development mode..
							$address 	= LEAVES_DEBUG_USER_EMAIL;
							$receiver	= LEAVES_DEBUG_USER_NAME;
						} else {	// Send email to the supervisor
							$address 	= $supervisor['email'];
							$receiver	= $supervisor['first_name'].' '.$supervisor['last_name'];
						}
						
						if(DEBUG)
							$message_list[] = array( 'type' => 'message', 'message'	=> 'Ενημερώθηκε ηλεκτρονικά ο '.$supervisor['first_name'].' '.$supervisor['last_name'] );
							
						$subject 	= 'Νέα Αίτηση Αδείας απο τον '.$leave_user->first_name.' '.$leave_user->last_name; 
						$body 		= '<p>O '.$leave_user->first_name.' '.$leave_user->last_name.' υπέβαλλε νέα αίτηση αδείας.</p>';
						$body 		.= '<p>Συνδεθείτε για να επεξεργαστείτε την αίτηση.</p>';
						$body 		.= '<p><a href="'.URL.'?p=leaves|applications">'.URL.'</a></p>';
						
						email_send($address, $receiver, $subject, $body);
					}
				}
				
				$message_list[] = array( 'type' => 'success', 'message'	=> 'Η Αίτηση καταχωρήθηκε επιτυχώς..' );
				
			}else
				$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Η Αίτηση δεν καταχωρήθηκε επιτυχώς..' );

			//echo $query->getSQL(); //For debug
		}
	
	}
	/* 	-------------------------------------------------------------------------------------
	*	Save Canceled Application Details
	*  -------------------------------------------------------------------------------------*/
	function save_cancel_application(){
		if(isset($_GET['cancel']) and intval(trim($_GET['cancel'])) > 0){
			global $db, $user, $message_list;
			
			$leave_id = intval(trim($_GET['cancel']));
			$leave = get_leave($leave_id);	
			if($leave->employee_afm != $user->afm){ // This is someone's else
				$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Η Αίτηση αυτή δεν αντιστοιχίζεται σε εσάς..' );
				return;
			}
			
			$query = $db->prepare('UPDATE  leaves_submissions set canceled = 1 where leave_id = :leave_id');
			$query->bindValue(':leave_id', 		$leave_id, 	PDO::PARAM_INT); 
			
			$query->execute();
		
			$message_list[] = array( 'type' => 'success', 'message'	=> 'Η Αίτηση ακυρώθηκε επιτυχώς..' );

		}
	}
	
	function get_leave_type($leave){ //Τύποι άδειας
		if($leave['type_id'] == 0 ) return 'Κανονική';
		if($leave['type_id'] == 1 ) return 'Σχολική';
		if($leave['type_id'] == 2 ) return 'Τηλεφωνική';
	}
	
	function get_leave_status($leave){ //Κατάσταση άδειας
		
		if($leave['canceled_by'] != 0 and $leave['canceled'] != 0)  return 'Ανακλήθηκε';
		if($leave['canceled'] != 0)  return 'Ακυρώθηκε';
		
		if($leave['signature_by'] != 0 and $leave['status'] == 1)  return 'Εγκεκριμένη';
		if($leave['signature_by'] != 0 and $leave['status'] == 0)  return 'Απορρίφθηκε';
		
		return 'Υπο Εξέταση';
	}
	
	function get_my_leaves($afm = ''){	//Φόρτωση αιτήσεων άδειας βάσει ΑΦΜ
		global $db, $user, $message_list;
		$query = $db->prepare('SELECT * from leaves_submissions where employee_afm=:employee_afm order by date_submitted DESC');
		
		$employee_afm = $user->afm;
		if($afm != '')
			$employee_afm = $afm;
			
		$query->bindValue(':employee_afm', 		$employee_afm, 					PDO::PARAM_STR); 			
		$query->execute();
		return $query->fetchAll();	
	}
	
	
	
	
	
	/******************   FUNCTIONS ΓΙΑ ΤΟΝ ΟΡΙΣΜΟ ΑΝΤΙΚΑΤΑΣΤΑΤΗ           ****************************************************/
	function IsEmployeeAntikatastatisProistamenos() {
		global $db, $user;
		//$sql="SELECT primkey FROM antikatastatis_proistamenos where DieuthinsiID = :unit_g AND antikatastasi_apo<= CURDATE() AND CURDATE()<=antikatastasi_eos AND energos=1 AND (:AFM=antikat_afm_a OR :AFM=antikat_afm_b)" ;
		$query = $db->prepare('SELECT primkey FROM `antikatastatis_proistamenos` where DieuthinsiID = :unit_g AND antikatastasi_apo<= CURDATE() AND CURDATE()<=antikatastasi_eos AND energos=1 AND (:AFM=antikat_afm_a OR :AFM=antikat_afm_b)');
		$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
		$query->bindValue(':AFM', 	$user->afm,				PDO::PARAM_STR);
		$query->execute();
		if ($query->rowCount() != 0) return true; else return false;
	}
	
	/* function is_dieuthintis_apon() {
		$sql="SELECT primkey FROM `antikatastatis_proistamenos` where DieuthinsiID = :unit_g AND antikatastasi_apo<= CURDATE() AND CURDATE()<=antikatastasi_eos" ;
		
		$query = $db->prepare($sql);
		$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR);  
		$query->execute();
		if ($query->rowCount() != 0) return true; else return false;
	}  */
	
	
	function save_edit_antikatastatis_proistamenos(){
		if(!user_is_manager('manager')) return;
		
		global $db, $message_list;
		/*  echo "Antikatastatis1_afm:".$_POST['Antikatastatis1_afm']."\r\n";
		 echo "Antikatastatis2_afm:".$_POST['Antikatastatis2_afm']."\r\n";
		 echo "AntikatastatisApo:".strtotime($_POST['AntikatastatisApo'])."\r\n";
		 echo "AntikatastatisEws:".$_POST['AntikatastatisEws']."\r\n";
		 echo "DieuthinsiID:".$_POST['dieuthinsi_id']."\r\n";
		 echo "trim_DieuthinsiID:".trim($_POST['dieuthinsi_id'])."\r\n";
		 echo "DieuthintisAFM:".$_POST['dieuthintis_afm']."\r\n"; */
		 
	     //testara();
		if ( isset($_POST['Antikatastatis1_afm'])  || isset($_POST['Antikatastatis2_afm']) ||  isset($_POST['AntikatastatisApo']) || isset($_POST['AntikatastatisEws']) ) {
			if ((is_null($_POST['Antikatastatis1_afm']) ||  $_POST['Antikatastatis1_afm']==0) || trim($_POST['AntikatastatisApo'])=="" || trim($_POST['AntikatastatisEws'])=="" ) {
				$message_list[] = array( 'type' => 'warning', 'message'	=> 'Δεν έχουν συμπληρωθεί όλα τα απαιτούμενα στοιχεία' );
			}
			else { 
				$query_l = $db->prepare("INSERT INTO antikatastatis_proistamenos (DieuthintisAFM, DieuthinsiID, antikat_afm_a, antikat_afm_b, antikatastasi_apo, antikatastasi_eos) VALUES(:DieuthintisAFM, '".$_POST['dieuthinsi_id']."', :anti_afm_A, :anti_afm_B, :antikat_apo, :antikat_eos)");
//				$query_l->bindValue(':idDieuthinsi', 					trim($_POST['dieuthinsi_id)']), 			PDO::PARAM_INT);
				$query_l->bindValue(':DieuthintisAFM', 					trim($_POST['dieuthintis_afm']), 			PDO::PARAM_INT);
				$query_l->bindValue(':anti_afm_A', 					trim($_POST['Antikatastatis1_afm']), 			PDO::PARAM_INT);
				$query_l->bindValue(':anti_afm_B', 					trim($_POST['Antikatastatis2_afm']), 			PDO::PARAM_INT);
				$query_l->bindValue(':antikat_apo', 			trim($_POST['AntikatastatisApo']), 	PDO::PARAM_INT);
				$query_l->bindValue(':antikat_eos', 		trim($_POST['AntikatastatisEws']), 	PDO::PARAM_INT);
				
				$query_l->execute();
				
				
				if ($query_l->rowCount() != 0) {
					$message_list[] = array( 'type' => 'success', 'message'	=> 'Ο Αντικαταστάτης ορίσθηκε επιτυχώς..' );
					
					
					//send email to antikatastatisA
					$afm1=trim($_POST['Antikatastatis1_afm']);
					$subject 	= 'Ορισμός Αντικαταστάτη Προϊστάμενου'; 
					$address1 	= afm_to_email($afm1);
					$receiver1	= afm_to_name($afm1);
					$body1 		= '<p>Έχετε οριστεί πρώτος Αντικαταστάτης Προϊστάμενος</p>';
					$body1 		.= '<p>από '.PrintDate(trim($_POST['AntikatastatisApo'])).' έως '.PrintDate(trim($_POST['AntikatastatisEws'])).'</p>';
					email_send($address1, $receiver1, $subject, $body1);
					//send email to antikatastatisΒ
					if (!is_null($_POST['Antikatastatis2_afm']) &&  $_POST['Antikatastatis2_afm']!=0) {
						$afm2=trim($_POST['Antikatastatis2_afm']);
						$address2 	= afm_to_email($afm2);
						$receiver2	= afm_to_name($afm2);
						$body2 		= '<p>Έχετε οριστεί δεύτερος Αντικαταστάτης Προϊστάμενος</p>';
						$body2 		.= '<p>από '.PrintDate(trim($_POST['AntikatastatisApo'])).' έως '.PrintDate(trim($_POST['AntikatastatisEws'])).'</p>';
						email_send($address2, $receiver2, $subject, $body2);
					}
					
				}else{
					$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Πρόβλημα κατα την ενημέρωση!' );
				} 
			}
		}
		
	}
	
	
	function PausiEnergouAntikatastati($id){  // Παύση ενεργού(ων) αντικαταστάτη(ων) Προϊσταμένου με id $id. Επιστρέφει true, εάν η αντικατάσταση έγινε επιτυχώς διαφορετικά επιστρέφει false
		global $db;
		$str="UPDATE antikatastatis_proistamenos set energos=0, date_anenergos='".date("Y-m-d H:i:s")."' where primkey = :IDAntikatastati";
		// echo $str;
		// papara();
		$query = $db->prepare($str);
		$query->bindValue(':IDAntikatastati', 	$id,				PDO::PARAM_STR); 
		$query->execute();
		if ($query->rowCount() != 0) {
			//send email to antikatastatisA
			$query = $db->prepare('SELECT * FROM antikatastatis_proistamenos WHERE primkey = :IDAntikatastati' );
			$query->bindValue(':IDAntikatastati', 	$id,				PDO::PARAM_STR); 
			$query->execute();
			$row = $query->fetch();
			 
			$afm1=$row['antikat_afm_a'];
			$subject 	= 'Παύση Αντικαταστάτη Προϊστάμενου'; 
			$address1 	= afm_to_email($afm1);
			// $address1='th.michtis@pdm.gov.gr';
			$receiver1	= afm_to_name($afm1);
			
			$body1 		= '<p>Ο ορισμός σας ως πρώτο Αντικαταστάτη Προϊστάμενο</p>';
			$body1 		.= '<p>από '.PrintDate($row['antikatastasi_apo']).' έως '.PrintDate($row['antikatastasi_eos']).' έχει αναιρεθεί.</p>';
			/* echo "address1:".$address1."\r\n";
			echo "receiver1:".$receiver1."\r\n";
			echo "subject:".$subject."\r\n";
			echo "body1:".$body1."\r\n"; */
			//papara();
			email_send($address1, $receiver1, $subject, $body1);
			//send email to antikatastatisΒ
			if (!is_null($row['antikat_afm_b']) && $row['antikat_afm_b']!=0) {
				$afm2=$row['antikat_afm_b'];
				$address2 	= afm_to_email($afm2);
				// $address2='th.michtis@pdm.gov.gr';
				$receiver2	= afm_to_name($afm2);
				$body2 		= '<p>Ο ορισμός σας ως δεύτερος Αντικαταστάτη Προϊστάμενο</p>';
				$body2 		.= '<p>από '.PrintDate($row['antikatastasi_apo']).' έως '.PrintDate($row['antikatastasi_eos']).' έχει αναιρεθεί.</p>';
				email_send($address2, $receiver2, $subject, $body2);
			}
			return true;
		}
		else return false;
	}
	
	function get_my_trexon_antikatastates(){ //Εμφάνιση των αντικαταστατών προϊσταμένων που έχουν οριστεί από τον Διευθυντή για το τρέχον χρονικό διάστημα
		global $db, $user;
		
		$query = $db->prepare('SELECT primkey, antikat_afm_a, antikat_afm_b, antikatastasi_apo, antikatastasi_eos FROM antikatastatis_proistamenos WHERE DieuthinsiID = :dnsi AND energos=1 AND CURDATE()<=antikatastasi_eos ORDER BY  antikatastasi_eos' );
		$query->bindValue(':dnsi', 	$user->unit_g,				PDO::PARAM_STR); 
		$query->execute();
		$antikatastates = $query->fetchAll();
		return $antikatastates;
	}
	
	/*************************************************************************************************************************************/
	
	
	function get_my_employees_leaves(){ //Εμφάνιση αιτήσεων άδειας των υφισταμένων ενός χρήστη
		global $db, $user, $message_list, $application_list;
		
		if(!empty($user->unit_gd) and trim($user->unit_gd) != 'gram0-6'){
		
			if($user->type == 'proist/nos_diefthyns'){
				$query = $db->prepare('SELECT * FROM `main_users` where unit_gd = :unit_gd AND afm !=:afm and active=1 ORDER BY  last_name ASC' );
				$query->bindValue(':unit_gd', 	$user->unit_gd,				PDO::PARAM_STR); 
			}elseif($user->type == 'proist/nos_gen_dieft'){
				$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm and active=1 ORDER BY  last_name ASC' );
				$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
			}else{ //proist/nos_tmimatos
				$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm AND type NOT IN (\'proist/nos_diefthyns\', \'proist/nos_gen_dieft\') and active=1 ORDER BY  last_name ASC' );
				$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
			}
			
			$query->bindValue(':afm', 		$user->afm, 				PDO::PARAM_STR); 	
			$query->execute();
			$employees = $query->fetchAll();	
			$all_leaves = array();
			foreach($employees as $employee){
				$cur_leaves = get_my_leaves($employee['afm']);
				$all_leaves = array_merge($cur_leaves, $all_leaves);
			}
			return $all_leaves; 
		// } else if(trim($user->unit_gd) == 'gram0-6') { // This is someone else.. The Overall Administrator
			} else if(empty($user->unit_gd)) { // This is someone else.. The Overall Administrator
			 if(trim($user->username) == $application_list['leaves']['in_app_users']['overall']){
				$query = $db->prepare('SELECT * FROM `main_users` where active=1 ORDER BY  last_name ASC' );
				$query->execute();
				$employees = $query->fetchAll();	
				$all_leaves = array();
				foreach($employees as $employee){
					$cur_leaves = get_my_leaves($employee['afm']);
					$all_leaves = array_merge($cur_leaves, $all_leaves);
				}
				return $all_leaves; 
			 }
		}
	}
	
	
	
	function get_leave_user($employee_afm){ //Εμφάνιση χρήστη βάσει ΑΦΜ
		global $db;
		$query = $db->prepare('SELECT * from main_users where afm =:afm and active=1');
		
		$query->bindValue(':afm', 			$employee_afm, 				PDO::PARAM_STR); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_leave_user_stats($leave_user){ //Εμφάνιση άδειας βάσει ID χρήστη
		global $db;
		$query = $db->prepare('SELECT * from leaves where id =:id ');
		
		$query->bindValue(':id', 			$leave_user->id, 			PDO::PARAM_INT); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_leave($leave_id){  //Εμφάνιση άδειας βάσει ID άδειας και ΑΦΜ χρήστη
		global $db, $user, $message_list;
		$query = $db->prepare('SELECT * from leaves_submissions where leave_id =:leave_id AND employee_afm=:employee_afm');
		
		$query->bindValue(':leave_id', 		$leave_id, 					PDO::PARAM_INT); 
		$query->bindValue(':employee_afm', 	$user->afm, 				PDO::PARAM_STR); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_employee_leave($leave_id){  //Εμφάνιση άδειας βάσει ID άδειας
		global $db, $user, $message_list;
		
		if(!get_user_is('director')) return;
		
		//TODO: Check if current user is supervisor
		$query = $db->prepare('SELECT * from leaves_submissions where leave_id =:leave_id');
		
		$query->bindValue(':leave_id', 		$leave_id, 					PDO::PARAM_INT); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_employees(){ //Εμφάνιση υπαλλήλων βάσει ΑΦΜ
		global $db, $user, $message_list, $application_list;
		if(!empty($user->unit_gd)){
		
			if($user->type == 'proist/nos_gen_dieft'){
				$query = $db->prepare('SELECT * FROM `main_users` where unit_gd = :unit_gd AND afm !=:afm and active=1 ORDER BY  last_name ASC' );
				$query->bindValue(':unit_gd', 	$user->unit_gd,				PDO::PARAM_STR); 
			}elseif($user->type == 'proist/nos_diefthyns'){
				$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm and active=1 ORDER BY  last_name ASC' );
				$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
			}else{ //proist/nos_tmimatos
				$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm AND type NOT IN (\'proist/nos_diefthyns\', \'proist/nos_gen_dieft\') and active=1 ORDER BY  last_name ASC' );
				$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
			}
			
			$query->bindValue(':afm', 		$user->afm, 				PDO::PARAM_STR); 	
			$query->execute();
			//echo $query->getSQL();
			return $query->fetchAll();	
		} else {
			if(trim($user->username) == $application_list['leaves']['in_app_users']['overall']){
				$query = $db->prepare('SELECT * FROM `main_users` where active=1 ORDER BY  last_name ASC' );
				$query->execute();
				return $query->fetchAll();	
			 }
		}
	}
		
	function get_employeesProistamenous(){ // εμφανίζει τους Προϊσταμένους Τμήματος της τρέχουσας Δ/νσης
		global $db, $user, $message_list;
		if(!empty($user->unit_gd)){
			if($user->type == 'proist/nos_diefthyns'){
			$sql="SELECT * FROM `main_users` where unit_g = :unit_g AND type = 'proist/nos_tmimatos' AND afm !=:afm ORDER BY  last_name ASC" ;
			$query = $db->prepare($sql);
			$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR);  
			}elseif($user->type == 'proist/nos_gen_dieft'){
				$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm ORDER BY  last_name ASC' );
				$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
			}else{ //proist/nos_tmimatos
				$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm AND type NOT IN (\'proist/nos_diefthyns\', \'proist/nos_gen_dieft\') ORDER BY  last_name ASC' );
				$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
			}
			
			$query->bindValue(':afm', 		$user->afm, 				PDO::PARAM_STR); 	
			$query->execute();
			return $query->fetchAll();	
		}
	}
	
	function save_edit_application(){ //Αποθήκευση επεξεργασμένης αίτησης
		global $db, $user, $message_list;
		
		if(!get_user_is('director')) return;
		if(!isset($_POST['leave_id']) or trim($_POST['leave_id']) == '') return;
		
		//TODO: Check if application already saved
		//TODO: Check if current user is really supervisor of the leave submitter
		
		$query = $db->prepare('UPDATE leaves_submissions set status=:status, signature_by=:signature_by, signature_date=:signature_date, comments=:comments, ip_approved = :ip_approved where leave_id =:leave_id');
		
		$query->bindValue(':leave_id', 			trim($_POST['leave_id']),		PDO::PARAM_INT); 
		$query->bindValue(':status', 			trim($_POST['approve_type']),	PDO::PARAM_INT); 
		$query->bindValue(':signature_by', 		$user->afm,						PDO::PARAM_STR); 
		$query->bindValue(':signature_date', 	date("Y-m-d H:i:s"), 			PDO::PARAM_STR); 
		$query->bindValue(':comments', 			trim($_POST['comments']),		PDO::PARAM_STR); 
		$query->bindValue(':ip_approved', 		$_SERVER['REMOTE_ADDR'], 		PDO::PARAM_STR);
		$query->execute();
		if ($query->rowCount() != 0) {
			$message_list[] = array( 'type' => 'success', 'message'	=> 'Η Αίτηση αποθηκεύτηκε επιτυχώς..' );
			update_leave_days(trim($_POST['leave_id'])); 			// Remove the remaining days
			if(trim($_POST['approve_type']) == 1){
				update_leave_on_production(trim($_POST['leave_id']));	// Pass the variables for the leave on the production server of the employers database
			}
			
		}else{
			$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Η Αίτηση δεν αποθηκεύτηκε επιτυχώς..' );
		}
	}
	
	function save_recall_application(){
		global $db, $user, $message_list;
		
		if(!get_user_is('director')) return;
		if(!isset($_POST['leave_id']) or trim($_POST['leave_id']) == '') return;
		
		//TODO: Check if current user is really supervisor of the leave submitter
		
		$query = $db->prepare('UPDATE leaves_submissions set canceled=1, canceled_by=:canceled_by, canceled_date=:canceled_date, canceled_days=:canceled_days, comments=:canceled_comments, ip_canceled = :ip_canceled where leave_id =:leave_id');
		
		$query->bindValue(':leave_id', 			trim($_POST['leave_id']),		PDO::PARAM_INT); 
		$query->bindValue(':canceled_by', 		$user->afm,						PDO::PARAM_STR); 
		$query->bindValue(':canceled_days', 	trim($_POST['days_canceled']),	PDO::PARAM_INT); 
		$query->bindValue(':canceled_date', 	date("Y-m-d H:i:s"), 			PDO::PARAM_STR); 
		$query->bindValue(':canceled_comments', trim($_POST['comments']),		PDO::PARAM_STR); 
		$query->bindValue(':ip_canceled', 		$_SERVER['REMOTE_ADDR'], 		PDO::PARAM_STR);
		$query->execute();
		if ($query->rowCount() != 0) {
			$message_list[] = array( 'type' => 'success', 'message'	=> 'Η άδεια Ανακλήθηκε επιτυχώς..' );
			subtract_leave_days(trim($_POST['leave_id'])); 			// Add the days not recalled
		}else{
			$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Η άδεια δεν ανακλήθηκε επιτυχώς..' );
		} 
		//echo $query->getSQL(); //For debug
	}
	
	// After the Process of a Canceling a Leave it Adds backe the owned days
	function subtract_leave_days($leave_id){
		global $db, $message_list;
		
		$leave = get_employee_leave($leave_id);	
		$leave_user = get_user_by_leave($leave);	
		
		$remaining_leaves = intval(get_remaining_leaves_for_user($leave_user->id));		
		$remaining_leaves = $remaining_leaves + $leave->canceled_days;
		
		$query = $db->prepare('UPDATE leaves set remaining_leaves=:remaining_leaves where id =:id');
		$query->bindValue(':remaining_leaves', 	$remaining_leaves,		PDO::PARAM_INT); 
		$query->bindValue(':id', 				$leave_user->id,		PDO::PARAM_INT); 
		$query->execute();

		$subject 	= 'Η Αδεία σας Ανακλήθηκε'; 
		$body 		= '<p>Η Αδεία σας Ανακλήθηκε.</p>';
		$body 		.= '<p>Ανακλήθηκαν '.$leave->canceled_days.' από τις '.$leave->num_leaves.' μέρες.</p>';
		$body 		.= '<p>Αφορά την άδεια με τα παρακάτω στοιχεία: </p>';
		$body 		.= '<p>- Ημέρομηνία Υποβολής: '. printDate($leave->date_submitted).'</p>';
		$body 		.= '<p>- Ημερομηνία Έναρξης Άδειας: '. printDate($leave->date_starts).'</p>';
		$body 		.= '<p>- Ημερομηνία Λήξης Άδειας: '. printDate($leave->date_ends).'</p>';
		
		if(DEBUG){ // This is in development mode..
			$address 	= LEAVES_DEBUG_USER_EMAIL;
			$receiver	= LEAVES_DEBUG_USER_NAME;
		} else {
			$address 	= $leave_user->email;
			$receiver	= $leave_user->first_name.' '.$leave_user->last_name;
		}
		email_send($address, $receiver, $subject, $body);
	}

	// After the Process of a Leave it Removes if approved the days from his remaining leaves.
	function update_leave_days($leave_id){  //Ενημέρωση υπολοίπου ημερών άδειας
		global $db, $message_list;
		
		$leave = get_employee_leave($leave_id);	
		$leave_user = get_user_by_leave($leave);	

		if($leave->signature_by != 0 and $leave->status == 1){
			$subject 	= 'Η Αίτηση Αδείας σας Εγκρίθηκε'; 
			$body 		= '<p>Η Αίτηση Αδείας σας Εγκρίθηκε</p>';
			$body 		.= '<p>Αφορά την άδεια με τα παρακάτω στοιχεία: </p>';
			$body 		.= '<p>- Ημέρομηνία Υποβολής: '. printDate($leave->date_submitted).'</p>';
			$body 		.= '<p>- Ημέρες Αδείας: '.$leave->num_leaves.'</p>';
			$body 		.= '<p>- Ημερομηνία Έναρξης Άδειας: '. printDate($leave->date_starts).'</p>';
			$body 		.= '<p>- Ημερομηνία Λήξης Άδειας: '. printDate($leave->date_ends).'</p>';
			
			$remaining_leaves = intval(get_remaining_leaves_for_user($leave_user->id));
			$remaining_leaves = $remaining_leaves - $leave->num_leaves;
			
			$query = $db->prepare('UPDATE leaves set remaining_leaves=:remaining_leaves where id =:id');
			$query->bindValue(':remaining_leaves', 	$remaining_leaves,		PDO::PARAM_INT); 
			$query->bindValue(':id', 				$leave_user->id,		PDO::PARAM_INT); 
			$query->execute();
		
		}
		
		if($leave->signature_by != 0 and $leave->status == 0){
			$subject 	= 'Η Αίτηση Αδείας σας Απορρίφθηκε'; 
			$body 		= '<p>Η Αίτηση Αδείας σας Απορρίφθηκε</p>';
			$body 		.= '<p>Αφορά την άδεια με τα παρακάτω στοιχεία: </p>';
			$body 		.= '<p>- Ημέρομηνία Υποβολής: '. printDate($leave->date_submitted).'</p>';
			$body 		.= '<p>- Ημέρες Αδείας: '.$leave->num_leaves.'</p>';
			$body 		.= '<p>- Ημερομηνία Έναρξης Άδειας: '. printDate($leave->date_starts).'</p>';
			$body 		.= '<p>- Ημερομηνία Λήξης Άδειας: '. printDate($leave->date_ends).'</p>';
			$body 		.= '<p>Λόγος Απόρριψης: '. $leave->comments.'</p>';
		}
		
		if(DEBUG){ // This is in development mode..
			$address 	= LEAVES_DEBUG_USER_EMAIL;
			$receiver	= LEAVES_DEBUG_USER_NAME;
		} else {
			$address 	= $leave_user->email;
			$receiver	= $leave_user->first_name.' '.$leave_user->last_name;
		}
		email_send($address, $receiver, $subject, $body);
	}		
	
	function save_edit_user_leaves(){
		if(!user_is_manager('manager') || !IsEmployeeAntikatastatisProistamenos() ) return;
		
		global $db, $message_list;
		
		if(isset($_POST['num_leaves'])){
			$query_l = $db->prepare('UPDATE leaves set num_leaves=:num_leaves, past_leaves= :past_leaves, remaining_leaves = :remaining_leaves where id =:id');
			
			$remaining_leaves = intval(trim($_POST['num_leaves'])) + intval(trim($_POST['past_leaves']));
			
			$query_l->bindValue(':id', 					trim($_POST['user_list']), 			PDO::PARAM_INT);
			$query_l->bindValue(':num_leaves', 			trim($_POST['num_leaves']), 	PDO::PARAM_INT);
			$query_l->bindValue(':past_leaves', 		trim($_POST['past_leaves']), 	PDO::PARAM_INT);
			$query_l->bindValue(':remaining_leaves', 	$remaining_leaves, 				PDO::PARAM_INT);
			$query_l->execute();
			
			if ($query_l->rowCount() != 0) {
				$message_list[] = array( 'type' => 'success', 'message'	=> 'Οι ημέρες ενημερώθηκαν επιτυχώς..' );
			}else{
				$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Πρόβλημα κατα την ενημέρωση!' );
			} 
		}
	}
    
	
	// Given a Leave Object returns the User Object for that Leave
	function get_user_by_leave($leave){ 
		global $db;
		$query = $db->prepare('SELECT * from main_users where afm=:afm and active=1');
		$query->bindValue(':afm', 		$leave->employee_afm, 					PDO::PARAM_STR); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_user_supervisors($user){ 
		global $db;
		
		$super_roles = array(
			'proist/nos_gen_dieft',
			'proist/nos_diefthyns', 
			//'proist/nos_tmimatos'
		);
		
		$query = $db->prepare("SELECT * from main_users where type in ('".implode("','", $super_roles)."') and unit_g = :unit_g and unit_gd = :unit_gd and active=1 ");
		$query->bindValue(':unit_gd', 		$user->unit_gd, 					PDO::PARAM_STR); 	
		$query->bindValue(':unit_g', 		$user->unit_g, 					PDO::PARAM_STR); 			
		$query->execute();
		//echo $query->getSQL(); //For debug
		return $query->fetchAll();	
	}
	
	
	
	// Returns numbers for leaves more analytical
	function get_analytics_data($leave_stats){
		$details = array(
			'current' => array($leave_stats->num_leaves, $leave_stats->num_leaves),
			'past' => array($leave_stats->past_leaves, $leave_stats->past_leaves),
		);
		
		$all_leaves = $leave_stats->num_leaves + $leave_stats->past_leaves;
		if($all_leaves > $leave_stats->remaining_leaves){
			$leave_diff = $all_leaves - $leave_stats->remaining_leaves;
			
			if($leave_diff > $leave_stats->past_leaves){ // He has already consumed his past leave days
				$details['past'][0] = 0;
				$details['current'][0] = $leave_stats->remaining_leaves;
			} else{
				$temp = $leave_stats->past_leaves -  $leave_diff;
				$details['past'][0] = $temp;
			}
		}		
		return $details;
	}
	
	function user_is_manager($type){
		global $user;
		global $application_list ;
		if(trim($type) == 'manager'){
			if(trim($user->type) == 'proist/nos_diefthyns') 	return true;
			if(trim($user->type) == 'proist/nos_tmimatos') 		return false;
			if(trim($user->type) == 'proist/nos_gen_dieft') 	return true;
			
			if(isset($application_list['leaves']['in_app_users']['overall']))
				if(trim($user->username) == $application_list['leaves']['in_app_users']['overall']) 	return true;
		}
		return trim($user->type) == trim($type);
	}
	
	// Adds the Leave Record on the Live Production Database
	function update_leave_on_production($leave){ //Ενημέρωση στοιχείων άδειας στον server παραγωγής
		global $db, $message_list;
		
		$leave_user = get_user_by_leave($leave_id);	
		
		// TODO This is to be implemented with REST API Calls
		/*
		$data = array(
			'par_employee_id'		=>	$leave_user->auto_id,
			'par_usage_code'		=>	$leave_user->usage_code,
			'par_date_requested'	=>	2015-10-08,
			'par_date_from'			=>	2015-10-11,
			'par_date_to'			=>	2015-10-15,
			'par_leave_total'		=>	$leave_user->,
			'par_leave_used'		=>	$leave->num_leaves,
			'par_leave_remaining'	=>	get_remaining_leaves_for_user($leave_user->id),
		);
		
		$db_data = simplexml_load_file(WSO2WEB.'/services/pdm_leaves.SecureHTTPEndpoint/insertleaves4/all?'.implode('&', $data).''); 
		*/
	}

?>