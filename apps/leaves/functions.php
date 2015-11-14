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
	
	/* 	-------------------------------------------------------------------------------------
	*	Save New Application Details
	*  -------------------------------------------------------------------------------------*/
	function save_new_application(){
		if(isset($_POST['num_leaves']) and intval(trim($_POST['num_leaves'])) > 0){
			global $db, $user, $message_list;
			
			// TODO: Make some more checks for more security
			
			$query = $db->prepare('INSERT INTO leaves_submissions (leave_id, employee_afm, type_id, date_submitted, submitted_by, date_starts, date_ends, num_leaves, ip_submitted, remaining_leaves) VALUES(NULL, :employee_afm, :type_id, :date_submitted, :submitted_by, :date_starts, :date_ends, :num_leaves, :ip_submitted, :remaining_leaves)');
			
			$afm = $user->afm;
			$submitted_by = '';
			if(trim($_POST['leave_type']) == 2){ // This is telephone
				$afm = trim($_POST['user_tel']);
				$submitted_by = $user->afm;
			}
			
			// TODO: Add filename if needed
			
			$query->bindValue(':employee_afm', 		$afm, 							PDO::PARAM_STR); 
			$query->bindValue(':type_id', 			trim($_POST['leave_type']), 	PDO::PARAM_INT); 
			$query->bindValue(':date_submitted', 	date("Y-m-d H:i:s"), 			PDO::PARAM_STR); 
			$query->bindValue(':submitted_by', 		$submitted_by, 					PDO::PARAM_STR); 
			$query->bindValue(':date_starts', 		trim($_POST['date_starts']), 	PDO::PARAM_STR); 
			$query->bindValue(':date_ends', 		trim($_POST['date_ends']), 		PDO::PARAM_STR);  
			$query->bindValue(':num_leaves', 		trim($_POST['num_leaves']), 	PDO::PARAM_INT);  
			$query->bindValue(':ip_submitted', 		$_SERVER['REMOTE_ADDR'], 		PDO::PARAM_STR);   
			$query->bindValue(':remaining_leaves', 	get_remaining_leaves(), 		PDO::PARAM_STR);   
			
			$query->execute();
			
			$id = $db->lastInsertId();
			if ($id != 0) {
				// TODO: Fix this when in production 
				// TODO: Set the correct messages
				// TODO: Also send email to the Supervisor
				$address 	= 'fotis.routsis@gmail.com';
				$receiver	= 'Fotis Routsis';
				$subject 	= 'Η Αίτηση Αδείας σας υποβλήθηκε επιτυχώς'; 
				$body 		= '<p>Η Αίτηση Αδείας σας υποβλήθηκε επιτυχώς</p>';
				email_send($address, $receiver, $subject, $body);
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
		if(!empty($user->unit_g)){
			$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm ORDER BY  last_name ASC' );
			
			$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
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
		if(!empty($user->unit_g)){
			$query = $db->prepare('SELECT * FROM `main_users` where unit_g = :unit_g AND afm !=:afm ORDER BY  last_name ASC' );
			
			$query->bindValue(':unit_g', 	$user->unit_g,				PDO::PARAM_STR); 
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
		
		//TODO: Check if current user is supervisor
		
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
				// TODO: Update the remaining days for the user.
			}
			// TODO: Send email to user for his application
		}else{
			$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! Η Αίτηση δεν αποθηκεύτηκε επιτυχώς..' );
		}
	}
?>