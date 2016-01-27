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
		global $user;
		
		if(!$app['active']) return false;
		
		if($app['user_specific']){
			if(in_array($user->username, $app['users'])) return true;
			else return false;
		} 
		
		return true;
	}
	
	
?>