<?php

    //Επιλογή php αρχείων για εισαγωγή
	require_once(ABSPATH.'apps/leaves/functions.php');
	require_once(ABSPATH.'apps/leaves/views.php');
	
	// TODO: Check access level here..
	init_leaves();
	
	function init_leaves(){
		leaves_sidebar();		// Initiate the menus
		prepare_pages();		// Prepare pages (css, javascripts etc)
	}
	
?>