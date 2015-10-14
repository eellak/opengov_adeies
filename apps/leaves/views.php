<?php
	
	function leaves_sidebar(){
		global $side_menu;
		$side_menu = array( 
			array('url' => URL.'/?p=leaves|home', 			'class' => 'fa fa-home fa-fw', 			'text' => 'Εφαρμογή Αδειών'),
			array('url' => URL.'/?p=leaves|new', 			'class' => 'fa fa-edit fa-fw', 			'text' => 'Νέα Αίτηση'),
			array('url' => URL.'/?p=leaves|myleaves', 		'class' => 'fa fa-file-word-o fa-fw', 	'text' => 'Οι Αιτήσεις μου'),
			array('url' => URL.'/?p=leaves|applications', 	'class' => 'fa fa-users fa-fw', 		'text' => 'Αιτήσεις Υπαλλήλων'),
			array('url' => URL.'/?p=leaves|statistics', 	'class' => 'fa fa-bar-chart-o fa-fw', 	'text' => 'Στατιστικά Αδειών'),
		);
	}
?>