<?php
include('../config.php');
include('../modules/helpers.php');
$message = '';

$ldap_connection = ldap_connect(LDAP_HOST);
if (FALSE === $ldap_connection){
   $message = 'Could not Connect to Server..';
}else{

	ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version..<br />');
	ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.
	ldap_set_option($ldap_connection, LDAP_OPT_DEBUG_LEVEL, 7);

	$bind = ldap_bind($ldap_connection, 'uid='.LDAP_USER.',ou=People,dc=pdm,dc=gov,dc=gr', LDAP_PASS);

	if ($bind){
		$ldap_base_dn = 'ou=People,dc=pdm,dc=gov,dc=gr';
		$search_filter = '(&(objectClass=PdmEduPerson)(uid=*))';
		$result = ldap_search($ldap_connection, $ldap_base_dn, $search_filter);
		if (FALSE !== $result){
			$ad_users = array();
			$entries = ldap_get_entries($ldap_connection, $result);				
			for ($x=0; $x<$entries['count']; $x++){
				
				$primary_dn = str_replace(',ou=units,dc=pdm,dc=gov,dc=gr', '', strtolower(trim($entries[$x]['pdmpersonprimaryorgunitdn'][0])));
				$primary_dn = str_replace('ou=', '', $primary_dn);
				$user_name = strtolower(trim($entries[$x]['uid'][0]));
				
				// Load the Auto ID from the Service Provider
				$auto_ids = simplexml_load_file(WSO2.'/services/pdm_leaves.HTTPEndpoint/getUsersPDM/all?param0='.trim($entries[$x]['pdmpersonvat'][0]));
				
				foreach($auto_ids->pdmuser as $usage_xml){
					$usage = (array)$usage_xml;
					if(substr($usage['USAGE_CODE'], -4) == date('Y')){
						$ad_users[$user_name] = array(
							'email' 			=> strtolower(trim($entries[$x]['mail'][0])),
							'first_name' 		=> trim($entries[$x]['givenname'][0]),
							'last_name' 		=> trim($entries[$x]['sn;lang-el'][0]),
							'amka' 				=> trim($entries[$x]['pdmpersonamka'][0]),
							'afm' 				=> trim($entries[$x]['pdmpersonvat'][0]),
							'type'				=> strtolower(trim($entries[$x]['pdmpersonaffiliation'][0])),
							'unit'				=> $primary_dn,
							'auto_id'			=> $usage['AUTO_ID'],
							'usage_code'		=> $usage['USAGE_CODE'],
							'date_hired'		=> substr($usage['DATE_DIORISMOU'], 0, 10),
							'date_permanent'	=> substr($usage['DATE_MONIMOPOIHSHS'], 0, 10),
						);
						break;
					}
				}
			}
			echo "Retrieved ". count($ad_users) ." Users from ".$entries['count']." Active Directory entries"."<br />";
			
			if(!isset($_GET['noinsert'])){
				//$db =  new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
				$db = new PDOTester('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
				$cnt_inserted =  0;
				foreach($ad_users as $username => $user){
					if ($db) {
						// TODO: Check if exists
						$query = $db->prepare('INSERT INTO main_users (id, username, email, first_name, last_name, amka, afm, type, auto_id, usage_code,  date_hired, date_permanent, num_leaves, past_leaves, remaining_leaves, unit_p, unit_t, unit_g, unit_gd) VALUES(NULL, :username, :email, :first_name, :last_name, :amka, :afm, :type, :auto_id, :usage_code, :date_hired, :date_permanent, :num_leaves, :past_leaves, :remaining_leaves, :unit_p, :unit_t, :unit_g, :unit_gd)');
						
						$query->bindValue(':username', 			$username, 				PDO::PARAM_STR);
						$query->bindValue(':email', 			$user['email'], 		PDO::PARAM_STR);
						$query->bindValue(':first_name', 		$user['first_name'], 	PDO::PARAM_STR);
						$query->bindValue(':last_name', 		$user['last_name'], 	PDO::PARAM_STR);
						$query->bindValue(':amka', 				$user['amka'], 			PDO::PARAM_STR);
						$query->bindValue(':afm', 				$user['afm'], 			PDO::PARAM_STR);
						$query->bindValue(':type', 				$user['type'], 			PDO::PARAM_STR);
						$query->bindValue(':auto_id', 			$user['auto_id'], 		PDO::PARAM_INT);
						$query->bindValue(':usage_code', 		$user['usage_code'], 	PDO::PARAM_STR);
						$query->bindValue(':date_hired', 		$user['date_hired']						);
						$query->bindValue(':date_permanent', 	$user['date_permanent']					);
						
						// TODO: Re-Check This
						$units_str = explode(',', $user['unit']);
						$units = array(
							't'		=> '',
							'd'		=> '',
							'gd'	=> ''
						);
						foreach($units_str as $unit){
							$temp_units = explode('.', $unit);
							if($temp_units[0] == 't')
								$units['t'] = $temp_units[1];
							elseif($temp_units[0] == 'd')
								$units['d'] = $temp_units[1];
							elseif($temp_units[0] == 'gd')
								$units['gd'] = $temp_units[1];
						}
						$query->bindValue(':unit_p', 		'1', 				PDO::PARAM_STR);
						$query->bindValue(':unit_t', 		$units['t'], 		PDO::PARAM_STR);
						$query->bindValue(':unit_g', 		$units['d'], 		PDO::PARAM_STR);
						$query->bindValue(':unit_gd', 		$units['gd'], 		PDO::PARAM_STR);
						
						// TODO: Calculate Leave Dates
						$query->bindValue(':num_leaves', 		'25', 			PDO::PARAM_INT);
						$query->bindValue(':past_leaves', 		'0', 			PDO::PARAM_INT);
						$query->bindValue(':remaining_leaves', '25', 			PDO::PARAM_INT);
					
						$query->execute();
						
						$id = $db->lastInsertId();
						
						if ($id != 0) 
							$cnt_inserted++;
						else
							echo 'Error for: '.$query->getSQL() . PHP_EOL;
					}
				}
				echo "Saved ".  $cnt_inserted ." Users in the Database"."<br />";
			}
			
			if(isset($_GET['print']))
				echo print_pretty($ad_users);
			
			if(isset($_GET['debug']))
				echo print_pretty($entries);
			
		} else{
			$message = 'Could not perform Search..';
		}
		ldap_unbind($ldap_connection); 
	}else{
		$message = 'Could not bind to Server..';
	}
}

if($message != '')	echo $message;

?>