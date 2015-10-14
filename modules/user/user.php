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
	
	function user_get_display(){
		global $user_auth;
		$attributes = $user_auth->getAttributes();
		
		return $attributes['http://wso2.org/claims/givenname'][0].' '.$attributes['http://wso2.org/claims/lastname'][0];
		
	}
	
	
?>