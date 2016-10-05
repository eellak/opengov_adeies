<!-- <script type="text/javascript">
           function OnSelectionFilterAdeies (select) {
            var AdeiesSelected = select.options[select.selectedIndex];
			//localStorage.setItem("DnsiSelected", DnsiSelected.value);
            var url = 'apps/leaves/applications.php?FilterAdeies='+AdeiesSelected.value;
			           
            window.location.href=url;
            //alert ("The selected option is " + DnsiSelected.value);
            }
</script> -->


<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Αιτήσεις Υπαλλήλων</h3>
	</div>
</div>
 <?php 
	global $user;
	if(isset($_GET['save'])){
		save_edit_application();
		print_messages(); 
	}
	if(isset($_GET['recall'])){
		save_recall_application();
		print_messages(); 
	}
?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Πίνακας Καταχωρημένων Αιτήσεων  <br> 
				<?php
				/* echo "EpilogiDnsis:".$_POST['EpilogiDnsis']." <br>";
				echo "FiltroEpexergasia:".$_POST['FiltroEpexergasia']."<br>"; */
				if ($_POST['FiltroEpexergasia']=="ProsEpexergasia") 
					$ProsEpexergasia=1;
				else $ProsEpexergasia=0;
				?>
				
				
				 
						 
				
				<form name="FilterForm" id="FilterForm" method="POST" action="<?php echo URL; ?>/?p=leaves|applications">

					<select name="EpilogiDnsis"  style="width:450px">
						<?php if ($user->type == 'ektelestikos_grammat' || $user->type == 'proist/nos_gen_dieft') {
						?>
						<option selected value='OLES'><ΟΛΕΣ ΟΙ ΔΙΕΥΘΥΝΣΕΙΣ></option>
						<?php }
						global $db;
						// $query="SELECT unit_g,department FROM main_departments group by unit_g order by department";
						$query = $db->prepare('SELECT unit_g,department FROM main_departments group by unit_g order by department' );
						$query->execute();
						$Dnseis=$query->fetchAll();
						foreach($Dnseis as $Dnsi){
							if ($user->type == 'proist/nos_diefthyns' || $user->type == 'proist/nos_tmimatos') 
								if ($Dnsi['unit_g']!=$user->unit_g) continue;
							$str_echo='<option value ="'.$Dnsi['unit_g'].'"';
							//IF ( isset($_POST['EpilogiDnsis']) ) {		
								IF ($_POST['EpilogiDnsis']==$Dnsi['unit_g']) 
									$str_echo=$str_echo.' selected="SELECTED"';
							//}
							$str_echo=$str_echo.'">'.$Dnsi['department'].'</option>'."\r\n";
							echo $str_echo;
						}
						?>
					</select>
				
					<select name="FiltroEpexergasia"  style="width:150px">
						<option selected value='OLES'><ΟΛΕΣ ΟΙ ΑΔΕΙΕΣ></option>
						<option  value='ProsEpexergasia' <?php IF ( isset($_POST['FiltroEpexergasia']) ) {		
								IF ($_POST['FiltroEpexergasia']=='ProsEpexergasia') 
									echo ' selected="SELECTED"';
							} 
						    ?>  ><ΠΡΟΣ ΕΠΕΞΕΡΓΑΣΙΑ></option>
					</select>
					<input type="submit" name="SUBMIT" value="ΦΙΛΤΡΑΡΙΣΜΑ">
				</form>
				
				 
				 <!-- <input type="radio" name="FilterAdeies" value="all" checked> Όλες<br>
				 <input type="radio" name="FilterAdeies" value="ProsEpexergasia"> Προς Επεξεργασία<br> -->
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<table class="table table-striped table-bordered table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th style="vertical-align:top" >Ημερομηνία Υποβολής</th>
								<th style="vertical-align:top">Επώνυμο Υπαλλήλου</th>
								<th style="vertical-align:top">Όνομα Υπαλλήλου</th>
								<th style="vertical-align:top">Είδος Άδειας</th>
								<th style="vertical-align:top">Αριθμός Ημερών</th>
								<th style="vertical-align:top">Από</th>
								<th style="vertical-align:top">Εώς</th>
								<th style="vertical-align:top">Κατάσταση</th>     
								<?php if($user->type != 'proist/nos_tmimatos'){ ?>
									<th style="vertical-align:top">Ενέργειες</th> 
								<?php } ?>
							</tr>
						</thead>
						<tbody>
						<?php 
						//Ερώτημα για εμφάνιση αδειών των υφισταμένων
							
							
							$my_leaves =  get_my_employees_leaves();
							foreach($my_leaves as $leave){
								
							
								// Αν την εχει ακυρώσει ο υπάλληλος μην τη δείξεις
								if($leave['signature_by'] == 0 and $leave['canceled'] == 1) continue;
								
								// Αν υπάρχει φίλτρο να εμφανίζονται μόνο οι ανυπόγραφες άδειες
									if ($ProsEpexergasia==1) {
										if ($leave['signature_by'] <> 0) {
											  
											 continue;
										}
									}
								
								
								//Κλήση μεθόδου για αναζήτηση στοιχείων υπαλλήλου
								$leave_user = get_user_details_by_afm($leave['employee_afm']);
								
								// Αν υπάρχει φίλτρο για τον εκτελεστικό να εμφανίζει μόνο μία συγκεκριμένη Δ/νση
								// if ( isset($_GET['EpilogiDnsis']) ) {
									if ($_POST['EpilogiDnsis']<>'OLES') {
										// echo $leave_user->unit_g."<br>";
										//papadia();
										if  ($leave_user->unit_g!=$_POST['EpilogiDnsis'])
											continue;
									}
								// }
								
								$class = 'info';
								//Ορισμός κατάστασης άδειας
								if($leave['signature_by'] != 0 and $leave['status'] == 1)  $class = 'success';
								if($leave['signature_by'] != 0 and $leave['status'] == 0)  $class = 'danger';
								
								if($leave['canceled'] == 1)  $class = 'warning';
								
								echo "<tr class='$class'>";
								//Πίνακας με τα στοιχεία της άδειας
								echo "<td>".printDate($leave['date_submitted'])."</td>";
								echo "<td>".$leave_user->last_name."</td>";
								echo "<td>".$leave_user->first_name."</td>";
								//Κλήση μεθόδου για αναζήτηση τύπου άδειας
								echo "<td>".get_leave_type($leave)."</td>";
								if($leave['canceled'] == 1){
									$taken_leaves = $leave['num_leaves'] - $leave['canceled_days'];
									echo "<td>".$taken_leaves.' ('.$leave['num_leaves'].")</td>";
								}else
									echo "<td>".$leave['num_leaves']."</td>";
								echo "<td>".printDate($leave['date_starts'])."</td>";
								echo "<td>".printDate($leave['date_ends'])."</td>";
								//Κλήση μεθόδου για αναζήτηση κατάστασης άδειας
								echo "<td>".get_leave_status($leave)."</td>";
								
								 if($user->type != 'proist/nos_tmimatos' || IsEmployeeAntikatastatisProistamenos() ){
									//Αν δεν έχει αξιολογηθεί η αίτηση άδειας εμφάνιση επιλογής για επεξεργασία
									if($leave['signature_by'] == 0 ){ 
										// Αν δεν την εχει ακυρώσει ο υπάλληλος
										if($leave['canceled'] != 1)
											echo "<td><a href='".URL."/?p=leaves|edit&id=".$leave['leave_id']."'><button type='button' class='btn btn-primary btn-circle'><i class='fa fa-pencil'></i></button></a></td>";
										else
											echo "<td>&nbsp;</td>";
									}else{
										// Αν έχει εγκριθεί
										if($leave['status'] == 1){ 
											if($leave['canceled'] != 1) { // Αν δεν εχει ήδη ανακληθεί
												if(strtotime($leave['date_ends']) >= strtotime(date("Y/m/d"))) { // Αν δεν έχει λήξει η άδεια
													
													echo "<td><a href='".URL."/?p=leaves|recall&id=".$leave['leave_id']."'><button type='button' class='btn btn-danger btn-circle'><i class='fa fa-close'></i></button></a></td>";
												}
												else
													echo "<td>&nbsp;</td>";
											}
											else
												echo "<td>&nbsp;</td>";
										}else
											echo "<td>&nbsp;</td>";
									}echo '</tr>';
								}
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-6 -->
</div>