<?php

require_once('config.php');			// Load all Configuration Parameters
require_once('functions.php');		// Load all needed base Functions

user_session_manager();				// Start the Session Manager

if (!user_is_logged_in()) {			// Check if User is Logged In
	require_once('modules/user/login.php');	//If not Logged In show Login Screen
}else{
	
	initiate();						// Initiate all Needed Variables/Methods
	//debug();
	
	if(isset($_GET['p']))
		load_page(trim($_GET['p']));
	else
		load_home();
}

?>