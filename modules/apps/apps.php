<?php
	
	/* 	-------------------------------------------------------------------------------------
	*	Loads the Applciations this User is Eligible for
	*  -------------------------------------------------------------------------------------*/
	function load_applications($all_apps){
		global $application_list;
		
		foreach($all_apps as $slug => $app){
			if(can_user_use_app($app))
				$application_list[$slug] = $app;
		}
	}
	
	/* 	-------------------------------------------------------------------------------------
	*	Checks if User is Eligible for specific App
	*  -------------------------------------------------------------------------------------*/
	function can_user_use_app($app){
		
		if(!$app['active']) return false;
		
		if(!$app['user_specific']){
		
			if($app['user_group'] == 'all') return true;
			
			// TODO: Here Check Group Related Details
		} else{
			// TODO: Here Check User Related Details
		}
		
	}
	
?>