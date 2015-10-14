<?php
	
	load_css();
	load_js();
	
	function load_home(){
		include(ABSPATH.'modules/views/header.php');
		include(ABSPATH.'modules/views/home.php');
		include(ABSPATH.'modules/views/footer.php');
	}
	
	function load_page($page){
		global $application_list;
		global $message_list;
		
		$params = explode('|', $page);
		if(array_key_exists($params[0], $application_list)){
			if(empty($params[1]) or $params[1] == '')
				$path = 'home';
			else{
				$path_temp = explode('&', $params[1]);
				$path = $path_temp[0];
			}
			include(ABSPATH.'apps/'.$params[0].'/init.php');
			include(ABSPATH.'modules/views/header.php');
			include(ABSPATH.'apps/'.$params[0].'/'.$path.'.php');
			include(ABSPATH.'modules/views/footer.php');
		} else{
			$message_list[] = array( 'type' => 'danger', 'message'	=> 'Η Εφαρμογή δεν εντοπίστηκε..' );
			load_home();
		}
	}
	
	function load_css(){
		global $css_files;
		
		$css_files[] = array('path' => 'assets/lib/bootstrap/dist/css/bootstrap.min.css');
		$css_files[] = array('path' => 'assets/lib/metisMenu/dist/metisMenu.min.css');
		$css_files[] = array('path' => 'assets/css/timeline.css');
		$css_files[] = array('path' => 'assets/css/sb-admin-2.css');   
		$css_files[] = array('path' => 'assets/lib/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css');
		$css_files[] = array('path' => 'assets/lib/datatables-responsive/css/dataTables.responsive.css');
		$css_files[] = array('path' => 'assets/lib/font-awesome/css/font-awesome.min.css');
	}
	
	function print_css(){
		global $css_files;
		foreach($css_files as $file)
			echo '<link href="'.URL.'/'.$file['path'].'" rel="stylesheet" type="text/css">';
	}
	
	function load_js(){
		global $js_files;
		
		$js_files[] = array('head' => false, 'path' => 'assets/lib/jquery/dist/jquery.min.js');
		$js_files[] = array('head' => false, 'path' => 'assets/lib/bootstrap/dist/js/bootstrap.min.js');
		$js_files[] = array('head' => false, 'path' => 'assets/lib/metisMenu/dist/metisMenu.min.js');
		$js_files[] = array('head' => false, 'path' => 'assets/js/sb-admin-2.js');
	}
	
	function print_js($head = false){
		global $js_files;
		foreach($js_files as $file){
			if($head){
				if($file['head'])
					echo '<script src="'.URL.'/'.$file['path'].'"></script>';
			}else{
				if(!$file['head'])
					echo '<script src="'.URL.'/'.$file['path'].'"></script>';
			}
		}		
	}
	
	function load_title($title = ''){
		global $app_title;
		if($title == '')
			$app_title = 'Πίνακας Διαχείρισης';
	}
	
	function print_title(){
		global $app_title;
		if(!empty($app_title))
			echo ' | '.$app_title;
	}
	
	function print_sidebar(){
		global $side_menu;
		if(!empty($side_menu) and $side_menu != '')
			if(count($side_menu) > 0)
				foreach($side_menu as $menu){
					echo '<li><a href="'.$menu['url'].'">';
					if(!empty($menu['class'])) echo '<i class="'.$menu['class'].'"></i> ';
					echo $menu['text'].'</a>';
					// TODO: Print submenus
					// <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
					// ...
					echo '</li>';
				}
	}
	
	function print_messages(){
		global $message_list;
		if(!empty($message_list) and $message_list != '')
			if(count($message_list) > 0)
				foreach($message_list as $message)
					echo '<div class="row"><div class="col-sm-12"><div class="alert alert-'.$message['type'].'">'.$message['message'].'</div></div></div>';
	}
	
?>