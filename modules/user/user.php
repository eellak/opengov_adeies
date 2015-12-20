<?php
	
	require_once(SIMPLESAMLPHP);					// Include the SimpleSAMLPHP library	
	
	function user_session_manager(){				// Initiate user session manament via SimpleSAML
		global $user_auth;
		
		$user_auth = new SimpleSAML_Auth_Simple('wso2-sp');
	}
	
	
	/* 	-------------------------------------------------------------------------------------
	*	Check if there is an Active Session	
	*  -------------------------------------------------------------------------------------*/
	function user_is_logged_in(){						
		global $user_auth;
		
		return $user_auth->isAuthenticated();
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Get the User LogIn URL
	*  -------------------------------------------------------------------------------------*/
	function user_get_login_url($class = array('')){	
		global $user_auth;
	
		$url = $user_auth->getLoginURL();
		return '<a href="' . htmlspecialchars($url) . '" class="'.implode(' ', $class).'">Σύνδεση</a>';
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Get the User LogOut URL
	*  -------------------------------------------------------------------------------------*/
	function user_get_logout_url($class = array('')){	
		global $user_auth;
		
		$user_auth->requireAuth();
		$url = $user_auth->getLogoutURL();
		return '<a href="' . htmlspecialchars($url) . '"><i class="'.implode(' ', $class).'"></i> Αποσύνδεση</a>';
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Get the User Display Name based on the Session by the LDAP
	*  -------------------------------------------------------------------------------------*/
	function user_get_display(){
		global $user;
		return $user->first_name.' '.$user->last_name;
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Get the User Details from the Database
	*  -------------------------------------------------------------------------------------*/
	function get_user_details(){
		global $user_auth, $user, $db;
			
		$username_temp = $user_auth->getAuthData('saml:sp:NameID');
		$username = str_replace('PDM.GOV.GR/', '', $username_temp['Value']);
		$query = $db->prepare('SELECT * from main_users where username = :username');
		$query->bindValue(':username', 			$username, 				PDO::PARAM_STR);
		$query->execute();
		$user = $query->fetchObject();
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Get the User Details from the Database by his/her VAT num
	*  -------------------------------------------------------------------------------------*/
	function get_user_details_by_afm($afm){
		global $db;
		$query = $db->prepare('SELECT * from main_users where afm = :afm');
		$query->bindValue(':afm', 			$afm, 				PDO::PARAM_STR);
		$query->execute();
		return $query->fetchObject();
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Check if user id Director or Employee
	*  -------------------------------------------------------------------------------------*/
	function get_user_is($type){
		global $user;
		if(trim($type) == 'director'){
			if(trim($user->type) == 'proist/nos_diefthyns') 	return true;
			if(trim($user->type) == 'proist/nos_tmimatos') 		return true;
			if(trim($user->type) == 'proist/nos_gen_dieft') 	return true;
		}
		return trim($user->type) == trim($type);
	}
	
	
?>