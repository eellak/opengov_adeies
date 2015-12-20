<?php
	
	function get_remaining_leaves(){
		global $db, $user;
		if(!empty($user->unit_g)){
			$query = $db->prepare('SELECT * FROM `leaves` where id = :user_id' );
			
			$query->bindValue(':user_id', 	$user->id,				PDO::PARAM_STR); 	
			$query->execute();
			$leaves = $query->fetchObject();	
			
			return $leaves->remaining_leaves;
		}
	}
	
	function get_remaining_leaves_for_user($user_id){
		global $db, $user;
		
		$query = $db->prepare('SELECT * FROM `leaves` where id = :user_id' );
		
		$query->bindValue(':user_id', 	$user_id,				PDO::PARAM_STR); 	
		$query->execute();
		$leaves = $query->fetchObject();	
		
		return $leaves->remaining_leaves;
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Save New Application Details
	*  -------------------------------------------------------------------------------------*/
	function save_new_application(){
		if(isset($_POST['num_leaves']) and intval(trim($_POST['num_leaves'])) > 0){
			global $db, $user, $message_list;
			
			// Check if the number of days requested is available (remaining days)
			if(intval(trim($_POST['num_leaves'])) > get_remaining_leaves()){
				$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Το υπόλοιπο των ημερών αδείας σας δεν επαρκεί.' );
				return;
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
				$pdf_body .= '<p>Ημερομηνία Έναρξης Άδειας: '.trim($_POST['date_starts']).'</p>';
				$pdf_body .= '<p>Ημερομηνία Λήξης Άδειας: '.trim($_POST['date_ends']).'</p>';
				$pdf_body .= '<p>Ημέρες Άδειας: '.trim($_POST['num_leaves']).'</p>';
				$pdf_body .= '<p>Ημέρομηνία Υποβολής: '.$submission_date.'</p>';
				
				// Also print the pdf
				$full_path_filename = getcwd().'/apps/leaves/files/'.$filename;
				print_pdf($full_path_filename, $pdf_body);
				
				if(DEBUG){ // This is in development mode..
					$address 	= 'fotis.routsis@gmail.com';
					$receiver	= 'Fotis Routsis';
					$subject 	= 'Η Αίτηση Αδείας σας υποβλήθηκε επιτυχώς'; 
					$body 		= '<p>Η Αίτηση Αδείας σας υποβλήθηκε επιτυχώς</p>';
				} else {
					// Send email to the employer
					$address 	= $leave_user->email;
					$receiver	= $leave_user->first_name.' '.$leave_user->last_name;
					$subject 	= 'Η Αίτηση Αδείας σας υποβλήθηκε επιτυχώς'; 
					$body 		= '<p>Η Αίτηση Αδείας σας υποβλήθηκε επιτυχώς</p>';
					
					if(trim($_POST['leave_type']) != 2){ 
						// TODO: Also send email to the Supervisor
					}
				}
				email_send($address, $receiver, $subject, $body, $full_path_filename);
				$message_list[] = array( 'type' => 'success', 'message'	=> 'Η Αίτηση καταχωρήθηκε επιτυχώς..' );
				
			}else
				$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Η Αίτηση δεν καταχωρήθηκε επιτυχώς..' );

			//echo $query->getSQL();
		}
	}
	
	function get_leave_type($leave){
		if($leave['type_id'] == 0 ) return 'Κανονική';
		if($leave['type_id'] == 1 ) return 'Σχολική';
		if($leave['type_id'] == 2 ) return 'Τηλεφωνική';
	}
	
	function get_leave_status($leave){
		if($leave['signature_by'] != 0 and $leave['status'] == 1)  return 'Εγκεκριμένη';
		if($leave['signature_by'] != 0 and $leave['status'] == 0)  return 'Απορρίφθηκε';
		return 'Υπο Εξέταση';
	}
	
	function get_my_leaves($afm = ''){
		global $db, $user, $message_list;
		$query = $db->prepare('SELECT * from leaves_submissions where employee_afm=:employee_afm');
		
		$employee_afm = $user->afm;
		if($afm != '')
			$employee_afm = $afm;
			
		$query->bindValue(':employee_afm', 		$employee_afm, 					PDO::PARAM_STR); 			
		$query->execute();
		return $query->fetchAll();	
	}
	
	function get_my_employees_leaves(){
		global $db, $user, $message_list;
		if(!empty($user->unit_gd)){
		
			if($user->type == 'proist/nos_diefthyns'){
				$query = $db->prepare('SELECT * FROM `main_users` where unit_gd = :unit_gd AND afm !=:afm ORDER BY  last_name ASC' );
				$query->bindValue(':unit_gd', 	$user->unit_gd,				PDO::PARAM_STR); 
			}elseif($user->type == 'proist/nos_gen_dieft'){
				$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm ORDER BY  last_name ASC' );
				$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
			}else{ //proist/nos_tmimatos
				$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm AND type NOT IN (\'proist/nos_diefthyns\', \'proist/nos_gen_dieft\') ORDER BY  last_name ASC' );
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
		}
	}
	
	function get_leave_user($employee_afm){
		global $db;
		$query = $db->prepare('SELECT * from main_users where afm =:afm ');
		
		$query->bindValue(':afm', 			$employee_afm, 				PDO::PARAM_STR); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_leave_user_stats($leave_user){
		global $db;
		$query = $db->prepare('SELECT * from leaves where id =:id ');
		
		$query->bindValue(':id', 			$leave_user->id, 			PDO::PARAM_INT); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_leave($leave_id){
		global $db, $user, $message_list;
		$query = $db->prepare('SELECT * from leaves_submissions where leave_id =:leave_id AND employee_afm=:employee_afm');
		
		$query->bindValue(':leave_id', 		$leave_id, 					PDO::PARAM_INT); 
		$query->bindValue(':employee_afm', 	$user->afm, 				PDO::PARAM_STR); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_employee_leave($leave_id){
		global $db, $user, $message_list;
		
		if(!get_user_is('director')) return;
		
		//TODO: Check if current user is supervisor
		$query = $db->prepare('SELECT * from leaves_submissions where leave_id =:leave_id');
		
		$query->bindValue(':leave_id', 		$leave_id, 					PDO::PARAM_INT); 			
		$query->execute();
		return $query->fetchObject();	
	}
	
	function get_employees(){
		global $db, $user, $message_list;
		if(!empty($user->unit_gd)){
		
			if($user->type == 'proist/nos_diefthyns'){
				$query = $db->prepare('SELECT * FROM `main_users` where unit_gd = :unit_gd AND afm !=:afm ORDER BY  last_name ASC' );
				$query->bindValue(':unit_gd', 	$user->unit_gd,				PDO::PARAM_STR); 
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
	
	function save_edit_application(){
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
			if(trim($_POST['approve_type']) == 1){
				update_leave_days(trim($_POST['leave_id'])); 			// Remove the remaining days
				update_leave_on_production(trim($_POST['leave_id']));	// Pass the variables for the leave on the production server of the employers database
			}
			
		}else{
			$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Η Αίτηση δεν αποθηκεύτηκε επιτυχώς..' );
		}
	}
	
	function update_leave_days($leave_id){
		global $db, $user, $message_list;
		
		$leave= get_employee_leave($leave_id);
		
		$query = $db->prepare('SELECT * from main_users where afm=:afm');
		$query->bindValue(':afm', 		$leave->employee_afm, 					PDO::PARAM_STR); 			
		$query->execute();
		$leave_user = $query->fetchObject();	
		
		$remaining_leaves = intval(get_remaining_leaves_for_user($leave_user->id));
		$remaining_leaves = $remaining_leaves - $leave->num_leaves;
		
		$query2 = $db->prepare('UPDATE leaves set remaining_leaves=:remaining_leaves where id =:id');
		$query2->bindValue(':remaining_leaves', 	$remaining_leaves,		PDO::PARAM_INT); 
		$query2->bindValue(':id', 				$leave_user->id,		PDO::PARAM_INT); 
		
		$query2->execute();
		//echo $query2->getSQL();

		if($leave->signature_by != 0 and $leave->status == 1){
			$subject 	= 'Η Αίτηση Αδείας σας Εγκρίθηκε'; 
			$body 		= '<p>Η Αίτηση Αδείας σας Εγκρίθηκε</p>';
		}
		
		if($leave->signature_by != 0 and $leave->status == 0){
			$subject 	= 'Η Αίτηση Αδείας σας Απορρίφθηκε'; 
			$body 		= '<p>Η Αίτηση Αδείας σας Απορρίφθηκε</p>';
		}
		
		if(DEBUG){ // This is in development mode..
			$address 	= 'fotis.routsis@gmail.com';
			$receiver	= 'Fotis Routsis';
		} else {
			$address 	= $leave_user->email;
			$receiver	= $leave_user->first_name.' '.$leave_user->last_name;
		}
		email_send($address, $receiver, $subject, $body, $full_path_filename);
	}	
	
	function update_leave_on_production($leave_id){
		global $db, $user, $message_list;
		
		$leave= get_employee_leave($leave_id);
		$query = $db->prepare('SELECT * from main_users where afm=:afm');
		$query->bindValue(':afm', 		$leave->employee_afm, 					PDO::PARAM_STR); 			
		$query->execute();
		$leave_user = $query->fetchObject();
		
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