<?php
include('config.php');
include('modules/helpers.php');
$message = '';

global $db; 
$db = new PDOTester('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);

/*
	ypallilos
	proist/nos_diefthyns
	proist/nos_tmimatos
	proist/nos_gen_dieft
*/

function get_gen_department($id){
	global $db; 
	$query_dept = $db->prepare('SELECT * from main_departments where unit_gd=:unit_gd');
	$query_dept->bindValue(':unit_gd', $id, PDO::PARAM_STR);
	$query_dept->execute();
	$query_dept_results = $query_dept->fetchAll();
	return $query_dept_results[0]['gen_department'];

}

function get_department($idg, $idgd){
	global $db; 
	$query_dept = $db->prepare('SELECT * from main_departments where unit_g=:unit_g and unit_gd=:unit_gd');
	$query_dept->bindValue(':unit_g', $idg, PDO::PARAM_STR);
	$query_dept->bindValue(':unit_gd', $idgd, PDO::PARAM_STR);
	$query_dept->execute();
	$query_dept_results = $query_dept->fetchAll();
	return $query_dept_results[0]['department'];
}

function get_office($idg, $idgd, $idt){
	global $db; 
	$query_dept = $db->prepare('SELECT * from main_departments where unit_g=:unit_g and unit_gd=:unit_gd and unit_t=:unit_t');
	$query_dept->bindValue(':unit_g', $idg, PDO::PARAM_STR);
	$query_dept->bindValue(':unit_gd', $idgd, PDO::PARAM_STR);
	$query_dept->bindValue(':unit_t', $idt, PDO::PARAM_STR);
	$query_dept->execute();
	$query_dept_results = $query_dept->fetchAll();
	//echo $query_dept->getSQL() ;
	return $query_dept_results[0]['office'];
	
}

$query_all = $db->prepare('SELECT * from main_users');
$query_all->execute();
$all_list = $query_all->fetchAll();

$skip = array('e.grammateas');
$users = array();
$structure = array();

foreach($all_list as $user){
	
	if(in_array($user['username'], $skip )) continue;
	
	$users[$user['afm']] = $user;
	
	//Gen Dief
	if(array_key_exists($user['unit_gd'],$structure)){
		
		if($user['type'] == 'proist/nos_gen_dieft'){
				
			$structure[$user['unit_gd']]['user'] = $user['afm'];
			
		} else if($user['type'] == 'proist/nos_diefthyns'){
			
			$structure[$user['unit_gd']][$user['unit_g']]['user'] = $user['afm']; 
			
		} else if($user['type'] == 'proist/nos_tmimatos'){
		
			$structure[$user['unit_gd']][$user['unit_g']][$user['unit_t']]['user'] = $user['afm'];	
		
		} else if($user['type'] == 'ypallilos'){
			
			$structure[$user['unit_gd']][$user['unit_g']][$user['unit_t']]['ypall'][] = $user['afm'];			
		}
		
	} else {
		if($user['type'] == 'proist/nos_gen_dieft'){
				
			$structure[$user['unit_gd']] = array( 'user' => $user['afm'] );
			
		} else if($user['type'] == 'proist/nos_diefthyns'){
			
			$structure[$user['unit_gd']] = array(
												'user' => '',
												$user['unit_g'] => array(
													'user' => $user['afm']
												)
											);
			
		} else if($user['type'] == 'proist/nos_tmimatos'){
			
			$structure[$user['unit_gd']] = array(
											'user' => '',
											$user['unit_g'] => array(
												'user' => '',
												$user['unit_t'] => array(
													'user' => $user['afm'],
												)
											)
										);
			
		} else if($user['type'] == 'ypallilos'){
			
			$structure[$user['unit_gd']] = array(
											'user' => '',
											$user['unit_g'] => array(
												'user' => '',
												$user['unit_t'] => array(
													'user' => '',
													'ypall' => array(
															$user['afm'],
														)
												)
											)
										);
			
		}
	}
}

//print_r($structure);

echo '<table border=1 cellpadding=3 width="100%"><thead></thead><tbody>';
foreach($structure as $gen_id => $gen_dief_dept){  
	$gen_dief = $users[$gen_dief_dept['user']];
	
	echo '<tr style="background: black; color: #ffffff;">';
	echo '<td colspan="4"><strong>Γενική Διεύθυνση</strong></td>';
	echo '<td><strong>'.get_gen_department($gen_id ).'</strong></td>';
	echo '<td><strong>'.$gen_dief['first_name'].'</strong></td>';
	echo '<td><strong>'.$gen_dief['last_name'].'</strong></td>';
	echo '<td><strong>'.$gen_dief['username'].'</strong></td>';
	echo '<td><strong>'.$gen_dief['email'].'</strong></td>';
	echo '</tr>';
	
	foreach($gen_dief_dept as $dief_id => $dief_dept){ if($dief_id == 'user') continue;
		$dief = $users[$dief_dept['user']];
		
		echo '<tr style="background: yellow;">';
		echo '<td></td><td colspan="3"><strong>Διεύθυνση</strong></td>';
		echo '<td><strong>'.get_department($dief_id , $gen_id).'</strong></td>';
		echo '<td>'.$dief['first_name'].'</td>';
		echo '<td>'.$dief['last_name'].'</td>';
		echo '<td>'.$dief['username'].'</td>';
		echo '<td>'.$dief['email'].'</td>';
		echo '</tr>';
		
		foreach($dief_dept as $tmim_id => $tmim_dept){ if($tmim_id == 'user') continue;
			$tmimatos = $users[$tmim_dept['user']];
			
			echo '<tr style="background: #e4e1e1;">';
			echo '<td></td><td></td><td  colspan="2"><strong>Τμήμα</strong></td>';
			echo '<td>'.get_office($dief_id , $gen_id, $tmim_id).'</td>';
			echo '<td>'.$tmimatos['first_name'].'</td>';
			echo '<td>'.$tmimatos['last_name'].'</td>';
			echo '<td>'.$tmimatos['username'].'</td>';
			echo '<td>'.$tmimatos['email'].'</td>';
			echo '</tr>';
			
			foreach($tmim_dept['ypall'] as $ypallilos_id){
				$ypallilos = $users[$ypallilos_id];
				echo '<tr>';
				echo '<td></td><td></td><td colspan="3"></td>';
				echo '<td>'.$ypallilos['first_name'].'</td>';
				echo '<td>'.$ypallilos['last_name'].'</td>';
				echo '<td>'.$ypallilos['username'].'</td>';
				echo '<td>'.$ypallilos['email'].'</td>';
				echo '</tr>';
				
			}
			
		}
	}
}
echo '</tbody></table>';

/*
echo '<table border=1 cellpadding=3 width="100%"><thead></thead><tbody>';
foreach($gen_dief_list as $gen_dief){
	echo '<tr style="background: black; color: #ffffff;">';
	echo '<td colspan="4"><strong>Γενική Διεύθυνση</strong></td>';
	echo '<td><strong>'.get_gen_department($gen_dief['unit_gd']).'</strong></td>';
	echo '<td><strong>'.$gen_dief['first_name'].'</strong></td>';
	echo '<td><strong>'.$gen_dief['last_name'].'</strong></td>';
	echo '<td><strong>'.$gen_dief['username'].'</strong></td>';
	echo '<td><strong>'.$gen_dief['email'].'</strong></td>';
	echo '</tr>';
	
	$users[] = $gen_dief['username'];
	
	$diefthyns = $db->prepare('SELECT * from main_users where type=:type and unit_gd=:unit_gd');
	$diefthyns->bindValue(':type', 'proist/nos_diefthyns', PDO::PARAM_STR);
	$diefthyns->bindValue(':unit_gd', $gen_dief['unit_gd'], PDO::PARAM_STR);
	$diefthyns->execute();
	$diefthyns_list = $diefthyns->fetchAll();
	
	foreach($diefthyns_list as $diefthyns){
		echo '<tr style="background: yellow;">';
		echo '<td></td><td colspan="3"><strong>Διεύθυνση</strong></td>';
		echo '<td><strong>'.get_department($diefthyns['unit_g'], $gen_dief['unit_gd']).'</strong></td>';
		echo '<td>'.$diefthyns['first_name'].'</td>';
		echo '<td>'.$diefthyns['last_name'].'</td>';
		echo '<td>'.$diefthyns['username'].'</td>';
		echo '<td>'.$diefthyns['email'].'</td>';
		echo '</tr>';
	
		$users[] = $diefthyns['username'];
		
		$tmimatos = $db->prepare('SELECT * from main_users where type=:type and unit_gd=:unit_gd and unit_g=:unit_g');
		$tmimatos->bindValue(':type', 'proist/nos_tmimatos', PDO::PARAM_STR);
		$tmimatos->bindValue(':unit_gd', $gen_dief['unit_gd'], PDO::PARAM_STR);
		$tmimatos->bindValue(':unit_g', $diefthyns['unit_g'], PDO::PARAM_STR);
		$tmimatos->execute();
		$tmimatos_list = $tmimatos->fetchAll();
		
		foreach($tmimatos_list as $tmimatos){
			echo '<tr style="background: #e4e1e1;">';
			echo '<td></td><td></td><td  colspan="2"><strong>Τμήμα</strong></td>';
			echo '<td>'.get_office($diefthyns['unit_g'], $gen_dief['unit_gd'], $tmimatos['unit_t']).'</td>';
			echo '<td>'.$tmimatos['first_name'].'</td>';
			echo '<td>'.$tmimatos['last_name'].'</td>';
			echo '<td>'.$tmimatos['username'].'</td>';
			echo '<td>'.$tmimatos['email'].'</td>';
			echo '</tr>';
			
			$users[] = $tmimatos['username'];
			
			$ypallilos = $db->prepare('SELECT * from main_users where type=:type and unit_gd=:unit_gd and unit_g=:unit_g and unit_t=:unit_t');
			$ypallilos->bindValue(':type', 'ypallilos', PDO::PARAM_STR);
			$ypallilos->bindValue(':unit_gd', $gen_dief['unit_gd'], PDO::PARAM_STR);
			$ypallilos->bindValue(':unit_g', $diefthyns['unit_g'], PDO::PARAM_STR);
			$ypallilos->bindValue(':unit_t', $tmimatos['unit_t'], PDO::PARAM_STR);
			$ypallilos->execute();
			$ypallilos_list = $ypallilos->fetchAll();
			
			foreach($ypallilos_list as $ypallilos){
				echo '<tr>';
				echo '<td></td><td></td><td colspan="3"></td>';
				echo '<td>'.$ypallilos['first_name'].'</td>';
				echo '<td>'.$ypallilos['last_name'].'</td>';
				echo '<td>'.$ypallilos['username'].'</td>';
				echo '<td>'.$ypallilos['email'].'</td>';
				echo '</tr>';
				
				$users[] = $ypallilos['username'];
			}
		
		}
	}
}
echo '</tbody></table>';

// Diefthinseis xoris Gen Dief
$query_all = $db->prepare('SELECT * from main_users');
$query_all->execute();
$all_list = $query_all->fetchAll();

echo '<table border=1 cellpadding=3 width="100%"><thead></thead><tbody>';
foreach($all_list as $diefthyns){
	
	if(in_array($diefthyns['username'], $users)) continue;
	if($diefthyns['type'] != 'proist/nos_diefthyns') continue;
	//echo $diefthyns['username'].'<br />';
	
	echo '<tr style="background: yellow;">';
	echo '<td></td><td colspan="3"><strong>Διεύθυνση</strong></td>';
	echo '<td><strong>'.get_department($diefthyns['unit_g'], $diefthyns['unit_gd']).'</strong></td>';
	echo '<td>'.$diefthyns['first_name'].'</td>';
	echo '<td>'.$diefthyns['last_name'].'</td>';
	echo '<td>'.$diefthyns['username'].'</td>';
	echo '<td>'.$diefthyns['email'].'</td>';
	echo '</tr>';

	$users[] = $diefthyns['username'];
	
	$tmimatos = $db->prepare('SELECT * from main_users where type=:type and unit_gd=:unit_gd and unit_g=:unit_g');
	$tmimatos->bindValue(':type', 'proist/nos_tmimatos', PDO::PARAM_STR);
	$tmimatos->bindValue(':unit_gd', $diefthyns['unit_gd'], PDO::PARAM_STR);
	$tmimatos->bindValue(':unit_g', $diefthyns['unit_g'], PDO::PARAM_STR);
	$tmimatos->execute();
	$tmimatos_list = $tmimatos->fetchAll();
	
	foreach($tmimatos_list as $tmimatos){
		echo '<tr style="background: #e4e1e1;">';
		echo '<td></td><td></td><td  colspan="2"><strong>Τμήμα</strong></td>';
		echo '<td>'.get_office($diefthyns['unit_g'], $diefthyns['unit_gd'], $tmimatos['unit_t']).'</td>';
		echo '<td>'.$tmimatos['first_name'].'</td>';
		echo '<td>'.$tmimatos['last_name'].'</td>';
		echo '<td>'.$tmimatos['username'].'</td>';
		echo '<td>'.$tmimatos['email'].'</td>';
		echo '</tr>';
		
		$users[] = $tmimatos['username'];
		
		$ypallilos = $db->prepare('SELECT * from main_users where type=:type and unit_gd=:unit_gd and unit_g=:unit_g and unit_t=:unit_t');
		$ypallilos->bindValue(':type', 'ypallilos', PDO::PARAM_STR);
		$ypallilos->bindValue(':unit_gd', $diefthyns['unit_gd'], PDO::PARAM_STR);
		$ypallilos->bindValue(':unit_g', $diefthyns['unit_g'], PDO::PARAM_STR);
		$ypallilos->bindValue(':unit_t', $tmimatos['unit_t'], PDO::PARAM_STR);
		$ypallilos->execute();
		$ypallilos_list = $ypallilos->fetchAll();
		
		foreach($ypallilos_list as $ypallilos){
			echo '<tr>';
			echo '<td></td><td></td><td colspan="3"></td>';
			echo '<td>'.$ypallilos['first_name'].'</td>';
			echo '<td>'.$ypallilos['last_name'].'</td>';
			echo '<td>'.$ypallilos['username'].'</td>';
			echo '<td>'.$ypallilos['email'].'</td>';
			echo '</tr>';
			
			$users[] = $ypallilos['username'];
		}
	
	}

}

echo '</tbody></table>';

$all_list = $query_all->fetchAll();

foreach($all_list as $diefthyns){		

	if(in_array($diefthyns['username'], $users)) continue;
	
	echo $diefthyns['username'].'<br />';
}	

if($message != '')	echo $message;
*/
?>