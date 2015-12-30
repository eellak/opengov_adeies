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
				Πίνακας Καταχωρημένων Αιτήσεων
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<table class="table table-striped table-bordered table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Ημερομηνία Υποβολής</th>
								<th>Επώνυμο Υπαλλήλου</th>
								<th>Όνομα Υπαλλήλου</th>
								<th>Είδος Άδειας</th>
								<th>Αριθμός Ημερών</th>
								<th>Κατάσταση</th>     
								<?php if($user->type != 'proist/nos_tmimatos'){ ?>
									<th>Ενέργειες</th> 
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
								
								//Κλήση μεθόδου για αναζήτηση στοιχείων υπαλλήλου
								$leave_user = get_user_details_by_afm($leave['employee_afm']);
								$class = 'info';
								//Ορισμός κατάστασης άδειας
								if($leave['signature_by'] != 0 and $leave['status'] == 1)  $class = 'success';
								if($leave['signature_by'] != 0 and $leave['status'] == 0)  $class = 'danger';
								
								if($leave['canceled'] == 1)  $class = 'warning';
								
								echo "<tr class='$class'>";
								//Πίνακας με τα στοιχεία της άδειας
								echo "<td>".$leave['date_submitted']."</td>";
								echo "<td>".$leave_user->last_name."</td>";
								echo "<td>".$leave_user->first_name."</td>";
								//Κλήση μεθόδου για αναζήτηση τύπου άδειας
								echo "<td>".get_leave_type($leave)."</td>";
								if($leave['canceled'] == 1){
									$taken_leaves = $leave['num_leaves'] - $leave['canceled_days'];
									echo "<td>".$taken_leaves.' ('.$leave['num_leaves'].")</td>";
								}else
									echo "<td>".$leave['num_leaves']."</td>";
								//Κλήση μεθόδου για αναζήτηση κατάστασης άδειας
								echo "<td>".get_leave_status($leave)."</td>";
								
								 if($user->type != 'proist/nos_tmimatos'){
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
											if($leave['canceled'] != 1) // Αν δεν εχει ήδη ανακληθεί
												echo "<td><a href='".URL."/?p=leaves|recall&id=".$leave['leave_id']."'><button type='button' class='btn btn-danger btn-circle'><i class='fa fa-close'></i></button></a></td>";
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