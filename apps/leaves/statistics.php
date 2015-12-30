<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Στατιστικά Αδειών</h3>
	</div>
</div>
 <?php 
	print_messages(); 
?>
<div class="row">
	<div class="col-lg-12">
		<div class="dataTable_wrapper">
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Επώνυμο Υπαλλήλου</th>
						<th>Όνομα Υπαλλήλου</th>
						<th>Υπόλοιπο</th>
						<th>Άδειες</th>
						<th>Απών</th>     
					</tr>
				</thead>
				<tbody>
					<?php 
						$employees = get_employees();
						
						$employees_afms = array();
						foreach($employees as $employee)
							$employees_afms[$employee->afm] = array(
																'leave_num' => 0,
															);
						$present = true;
						$leaves = get_all_leaves();
						foreach($leaves as $leave){ 
							
							if($leave['signature_by'] != 0 and $leave['status'] == 1){ // Count only the approved
								if($leave['canceled'] == 1) {
									$taken = $leave['num_leaves'] - $leave['canceled_days'];
									if($taken>0){
										$employees_afms[$leave['employee_afm']]['leave_num']++;
									}
								} else{
									$employees_afms[$leave['employee_afm']]['leave_num']++;
								}
								
								if($present){ 
									$today = date('Y-m-d');
									$today = date('Y-m-d', strtotime($today));
									
									$leave_start = date('Y-m-d', strtotime( $leave['date_starts']));
									$leave_end = date('Y-m-d',   strtotime( $leave['date_ends']));
									
									if (($today > $leave_start) && ($today < $leave_end)){
										$present = false;
									}
								}
							}
							
						}
						
						foreach($employees as $employee){ ?>
							<tr>
								<td><?php echo $employee['last_name']; ?></td>
								<td><?php echo $employee['first_name']; ?></td>
								<td><?php echo get_remaining_leaves_for_user($employee['id']); ?></td>
								<td><?php echo $employees_afms[$employee['afm']]['leave_num']; ?></td>
								<td><?php echo ($present? 'Όχι': 'Ναι') ; ?></td>   
							</tr>
						<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>