<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Αιτήσεις Υπαλλήλων</h3>
	</div>
</div>
 <?php 
	if(isset($_GET['save'])){
		save_edit_application();
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
								<th>Ενέργειες</th> 
							</tr>
						</thead>
						<tbody>
						<?php 
						//Ερώτημα για εμφάνιση αδειών των υφισταμένων
							$my_leaves =  get_my_employees_leaves();
							foreach($my_leaves as $leave){
								//Κλήση μεθόδου για αναζήτηση στοιχείων υπαλλήλου
								$user = get_user_details_by_afm($leave['employee_afm']);
								$class = 'info';
								//Ορισμός κατάστασης άδειας
								if($leave['signature_by'] != 0 and $leave['status'] == 1)  $class = 'success';
								if($leave['signature_by'] != 0 and $leave['status'] == 0)  $class = 'danger';
								echo "<tr class='$class'>";
								//Πίνακας με τα στοιχεία της άδειας
								echo "<td>".$leave['date_submitted']."</td>";
								echo "<td>".$user->last_name."</td>";
								echo "<td>".$user->first_name."</td>";
								//Κλήση μεθόδου για αναζήτηση τύπου άδειας
								echo "<td>".get_leave_type($leave)."</td>";
								echo "<td>".$leave['num_leaves']."</td>";
								//Κλήση μεθόδου για αναζήτηση κατάστασης άδειας
								echo "<td>".get_leave_status($leave)."</td>";
								//Αν δεν έχει αξιολογηθεί η αίτηση άδειας εμφάνιση επιλογής για επεξεργασία
								if($leave['signature_by'] == 0)
									echo "<td><a href='".URL."/?p=leaves|edit&id=".$leave['leave_id']."'><button type='button' class='btn btn-primary btn-circle'><i class='fa fa-pencil'></i></button></a></td>";
								else
									echo "<td>&nbsp;</td>";
								echo '</tr>';
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