<?php
	
	function leaves_sidebar(){
		global $application_list ;
		global $user;
		global $side_menu;
		$side_menu = array( //Φόρτωση βασικών επιλγών sidebar
			array('url' => URL.'/?p=leaves|home', 			'class' => 'fa fa-home fa-fw', 			'text' => 'Εφαρμογή Αδειών'),
			array('url' => URL.'/?p=leaves|new', 			'class' => 'fa fa-edit fa-fw', 			'text' => 'Νέα Αίτηση'),
			array('url' => URL.'/?p=leaves|myleaves', 		'class' => 'fa fa-file-word-o fa-fw', 	'text' => 'Οι Αιτήσεις μου'),
		);
		
		if(get_user_is('director')){ //Αν ο χρήστης έχει αυξημένα δικαιώματα, προσθήκη επιπλέον επιλογών
			$side_menu[] = array('url' => URL.'/?p=leaves|applications', 	'class' => 'fa fa-users fa-fw', 		'text' => 'Αιτήσεις Υπαλλήλων');
			if($user->username == $application_list['leaves']['in_app_users']['statistics']){
				$side_menu[] = array('url' => URL.'/?p=leaves|statistics', 	'class' => 'fa fa-bar-chart-o fa-fw', 	'text' => 'Στατιστικά Αδειών');
			}
		}
	}
	
	function prepare_pages(){
		global $css_files;
		global $js_files;
		global $application_list;
		
		$page = '';
		$params = explode('|', trim($_GET['p'])); //Έλεγχος ορισμάτων URL
		if(array_key_exists($params[0], $application_list)){	
			if(empty($params[1]) or $params[1] == '')
				$page = 'home';
			else{
				$path_temp = explode('&', $params[1]);
				$page = $path_temp[0];
			}
		} 

		$css_files[] = array('path' => 'apps/leaves/style.css');
		
		switch($page){	
			case 'new':			//Prepare the New Application Page
				$css_files[] = array('path' => 'assets/lib/bootstrap-datepicker/css/datepicker.css');
				$js_files[] =  array('head' => false, 'path' => 'assets/lib/bootstrap-datepicker/js/bootstrap-datepicker.js');
				$js_files[] =  array('head' => false, 'path' => 'apps/leaves/js/new_form.js');
				break;
			case 'myleaves':	//Prepare the My Leaves Pages
				$js_files[] =  array('head' => false, 'path' => 'assets/lib/datatables/media/js/jquery.dataTables.min.js');
				$js_files[] =  array('head' => false, 'path' => 'assets/lib/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js');
				$js_files[] =  array('head' => false, 'path' => 'apps/leaves/js/my_leaves.js');
				break;
			case 'applications':	//Prepare the Applications Page
				$js_files[] =  array('head' => false, 'path' => 'assets/lib/datatables/media/js/jquery.dataTables.min.js');
				$js_files[] =  array('head' => false, 'path' => 'assets/lib/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js');
				$js_files[] =  array('head' => false, 'path' => 'apps/leaves/js/my_leaves.js');
				break;
			case 'edit':		//Prepare the Edit Application Page
				$js_files[] =  array('head' => false, 'path' => 'apps/leaves/js/edit_leave.js');
				break;
		}
		
	}
?>