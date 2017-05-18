<?php

	if (!ini_get('display_errors') and DEBUG)
		ini_set('display_errors', '1');
	
	// Set a lower PHP version here
	if (version_compare(PHP_VERSION, '5.5.0', '<')) {
		exit('Σφάλμα! Απαιτείται έκδοση PHP τουλάχιστον 5.5.0.');
	}

	require_once(ABSPATH.'modules/helpers.php');		// Load Helper Functions	
	require_once(ABSPATH.'modules/user/user.php');		// Load User Management Module
	require_once(ABSPATH.'modules/apps/apps.php');		// Load Applications Management Module
	require_once(ABSPATH.'modules/notify/notify.php');	// Load eMail functionality
	require_once(ABSPATH.'modules/print/print.php');	// Load PDF Related Module
	require_once(ABSPATH.'modules/views/views.php');	// Load Views Related Module
	//require_once(ABSPATH.'modules/protocol/protocol.php');	// Load Protocol Module

	function initiate(){
		include(ABSPATH.'applist.php');
		set_connection();						// Start the Database Connection
		get_user_details();						// Get the User Record from the DB
		check_active_user();
		load_applications($all_apps);			// Load App List for the User
		initiate_email();
	}
	
	function set_connection(){	// Initiates the Database Connection
		global $db;
		//$db =  new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
		$db = new PDOTester('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
	}
	
?>