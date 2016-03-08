<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Στατιστικά Αδειών</h3>
	</div>
</div>
 <?php 
	print_messages(); 
	$employees = get_employees(); 
	
	$employeelist = 'display:none;'; 
	$since  = 'display:none;'; 
	$until  = 'display:none;'; 
	
	if(isset($_POST['stattype'])){
		if(trim($_POST['stattype']) == 'single') {
			$employeelist = ''; 
			$since  = ''; 
			$until  = ''; 
		}
		if(trim($_POST['stattype']) == 'day') {
			$since  = ''; 
		}
		if(trim($_POST['stattype']) == 'period') {
			$since  = ''; 
			$until  = '';  
		}
	}
?>
<div class="row">
	<div class="col-lg-12">

		<form name="statform" id="statform" method="post" action="<?php echo URL; ?>/?p=leaves|statistics">
			<div class="col-md-4">
				<div class="form-group" id="employeelist" style="<?php echo $employeelist; ?>">
					<label>Υπάλληλοι</label>
					<select name="userstat" class="form-control" id="userstat">
						<option value="all">Όλοι</option>
					<?php 
						foreach($employees as $employee){
							$selected='';
							if(isset($_POST['userstat']) and trim($_POST['userstat']) == $employee['afm'])
								$selected=' selected="selected"';
								
							echo '<option value="'.$employee['afm'].'" '.$selected.' >'.$employee['last_name'].' '.$employee['first_name'].'</option>';
						}
					?>
					</select>
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="form-group" id="since" style="<?php echo $since; ?>">
					<label>Από</label>
				   <input class="form-control required" name="date_starts" id="dpd1" size="16" type="text" value="<?php if(isset($_POST['date_starts'])) echo $_POST['date_starts']; ?>"/>
				</div> 
			</div> 
			
			<div class="col-md-2">
				<div class="form-group" id="untill" style="<?php echo $until; ?>">
					<label>Έως</label>
					<input class="form-control required" name="date_ends" id="dpd2" size="16" type="text" value="<?php if(isset($_POST['date_ends'])) echo $_POST['date_ends']; ?>"/>
				</div>  
			</div>  
			
			<div class="col-md-2">
				<div class="form-group">
					<label>Στατιστικά</label>
					<select name="stattype" class="form-control" id="stattype">
						<option value="all">Συνολικά Στατιστικά</option>
						<option value="day" <?php if(isset($_POST['stattype']) and trim($_POST['stattype']) == 'day') echo ' selected="selected"'; ?>>Στατιστικά Ημέρας</option>
						<option value="period" <?php if(isset($_POST['stattype']) and trim($_POST['stattype']) == 'period') echo ' selected="selected"'; ?>>Στατιστικά Περιόδου</option>
						<option value="single" <?php if(isset($_POST['stattype']) and trim($_POST['stattype']) == 'single') echo ' selected="selected"'; ?>>Άδειες Υπαλλήλου</option>
					</select>
				</div>  
			</div>
			
			<div class="col-md-2">
				<div class="form-group"> <!--
					<button type="reset" class="btn btn-default pull-right" style="margin-top:25px; margin-left:20px;">Καθαρισμός</button> -->
					<button type="submit" class="btn btn-primary pull-right" style="margin-top:25px;">Αναζήτηση</button>
				</div>  
			</div>
		</form>
	</div>
</div>
<?php if(isset($_POST['stattype'])  and trim($_POST['stattype']) == 'single'){ 
		
		$userstat =  get_user_details_by_afm( trim($_POST['userstat']));
?>

<div class="row">
	<div class="col-lg-12">
		<h4>Άδειες του/της 
		<?php 
			echo $userstat->first_name.' '.$userstat->last_name; 
			if( trim($_POST['date_starts'])!= '' and trim($_POST['date_ends'])!= '' ){
				echo ' | Περίοδος: '.printDate(trim($_POST['date_starts'])).' - '.printDate(trim($_POST['date_ends'])).' ';
			}
		?></h4>
		<div class="dataTable_wrapper">
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Ημερομηνία Αίτησης</th>
						<th>Είδος Άδειας</th>
						<th>Ημέρες Αδείας</th>
						<th>Έναρξη Άδειας</th>
						<th>Λήξη Άδειας</th>
						<th>Κατάσταση</th>     
					</tr>
				</thead>
				<tbody>
				<?php
					$my_leaves =  get_my_leaves( trim($_POST['userstat'])); //Φόρτωση αιτήσεων άδειας
					foreach($my_leaves as $leave){
						
						$class = 'info';
						if($leave['signature_by'] != 0 and $leave['status'] == 1)  $class = 'success';
						if($leave['signature_by'] != 0 and $leave['status'] == 0)  $class = 'danger';
						
						//This is canceled..
						if($leave['canceled'] == 1)  $class = 'warning';
						
						// Show only the approved ones
						if( $class != 'success') continue;
						
						//$period_start = date('Y-m-d', strtotime( trim($_POST['date_starts'])));
						$period_end = date('Y-m-d', strtotime( trim($_POST['date_ends'])));
						//$leave_start = date('Y-m-d', strtotime( $leave['date_starts']));
						$leave_end = date('Y-m-d',   strtotime( $leave['date_ends']));
						
						if ($period_end < $leave_end){ // Skip the ones older than the current
							continue;
						}
						
						echo "<tr class='$class'>";
						echo "<td>".printDate($leave['date_submitted'])."</td>";
						echo "<td>".get_leave_type($leave)."</td>";
						echo "<td>".$leave['num_leaves']."</td>";
						echo "<td>".printDate($leave['date_starts'])."</td>";
						echo "<td>".printDate($leave['date_ends'])."</td>";
						echo "<td>".get_leave_status($leave)."</td>";
						echo '</tr>';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php }elseif(isset($_POST['stattype'])  and trim($_POST['stattype']) == 'period'){ ?>
<div class="row">
	<div class="col-lg-12">
		<div class="dataTable_wrapper">
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Επώνυμο</th>
						<th>Όνομα</th>
						<th colspan="3">Ημέρες Αδειών</th>
						<th>Απών</th>     
					</tr>
					<tr>
						<th></th>
						<th></th>
							<th>Δικαιούμενες</th>
							<th>Παλαιές</th>
							<th>Υπόλοιπο</th>
						<th></th>      
					</tr>
				</thead>
				<tbody>
					<?php 
						$count_absents = 0;
						$employees_afms = array();
						foreach($employees as $employee)
							$employees_afms[$employee->afm] = array(
																'absend'	=> false,
															);
															
						$day_to_check_start = date('Y-m-d', strtotime($_POST['date_starts']));
						$day_to_check_end = date('Y-m-d', strtotime($_POST['date_ends']));
						$leaves = get_all_leaves();
						foreach($leaves as $leave){ 
							
							if($leave['signature_by'] != 0 and $leave['status'] == 1){ // Approved 
								
								// TODO: What about when recalled ?
								
								if(!$employees_afms[$leave['employee_afm']]['absend']){ // We have not found out if he is currently absent
									
									$leave_start = date('Y-m-d', strtotime( $leave['date_starts']));
									$leave_end = date('Y-m-d',   strtotime( $leave['date_ends']));
									
									if (($day_to_check_start > $leave_start) && ($day_to_check_end < $leave_end)){
										$employees_afms[$leave['employee_afm']]['absend'] = true;
										$count_absents++;
									}
								}
							}
							
						}
						
						foreach($employees as $employee){ 
							$leave_user_stats = get_leave_user_stats((object)$employee);
							$leave_details = get_analytics_data($leave_user_stats);
						?>
							<tr>
								<td><?php echo $employee['last_name']; ?></td>
								<td><?php echo $employee['first_name']; ?></td>
								<td><?php  echo $leave_details['current'][0].' / '.$leave_details['current'][1]; ?></td>
								<td><?php  echo $leave_details['past'][0].' / '.$leave_details['past'][1]; ?></td>
								<td><?php echo $leave_user_stats->remaining_leaves; ?></td>
								<td><?php echo ($employees_afms[$employee['afm']]['absend']? 'Ναι': '') ; ?></td>      
							</tr>
						<?php
						}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5" style="text-align:right;">Συνολικά Απόντες </th>
						<th><?php echo $count_absents; ?></th>      
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php }elseif(isset($_POST['stattype'])  and trim($_POST['stattype']) == 'day'){ ?>
<div class="row">
	<div class="col-lg-12">
		<div class="dataTable_wrapper">
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Επώνυμο</th>
						<th>Όνομα</th>
						<th colspan="3">Ημέρες Αδειών</th>
						<th>Απών</th>     
					</tr>
					<tr>
						<th></th>
						<th></th>
							<th>Δικαιούμενες</th>
							<th>Παλαιές</th>
							<th>Υπόλοιπο</th>
						<th></th>      
					</tr>
				</thead>
				<tbody>
					<?php 
						$count_absents = 0;
						$employees_afms = array();
						foreach($employees as $employee)
							$employees_afms[$employee->afm] = array(
																'absend'	=> false,
															);
															
						$day_to_check = date('Y-m-d', strtotime($_POST['date_starts']));
						$leaves = get_all_leaves();
						foreach($leaves as $leave){ 
							
							if($leave['signature_by'] != 0 and $leave['status'] == 1){ // Approved 
								
								// TODO: What about when recalled ?
								
								if(!$employees_afms[$leave['employee_afm']]['absend']){ // We have not found out if he is currently absent
									
									$leave_start = date('Y-m-d', strtotime( $leave['date_starts']));
									$leave_end = date('Y-m-d',   strtotime( $leave['date_ends']));
									
									if (($day_to_check > $leave_start) && ($day_to_check < $leave_end)){
										$employees_afms[$leave['employee_afm']]['absend'] = true;
										$count_absents++;
									}
								}
							}
							
						}
						
						foreach($employees as $employee){ 
							$leave_user_stats = get_leave_user_stats((object)$employee);
							$leave_details = get_analytics_data($leave_user_stats);
						?>
							<tr>
								<td><?php echo $employee['last_name']; ?></td>
								<td><?php echo $employee['first_name']; ?></td>
								<td><?php  echo $leave_details['current'][0].' / '.$leave_details['current'][1]; ?></td>
								<td><?php  echo $leave_details['past'][0].' / '.$leave_details['past'][1]; ?></td>
								<td><?php echo $leave_user_stats->remaining_leaves; ?></td>
								<td><?php echo ($employees_afms[$employee['afm']]['absend']? 'Ναι': '') ; ?></td>      
							</tr>
						<?php
						}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5" style="text-align:right;">Συνολικά Απόντες </th>
						<th><?php echo $count_absents; ?></th>      
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php }else{ ?>
<div class="row">
	<div class="col-lg-12">
		<div class="dataTable_wrapper">
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Επώνυμο</th>
						<th>Όνομα</th>
						<th colspan="3">Ημέρες Αδειών</th>
						<th colspan="5">Αιτήσεις</th>
						<th>Απών</th>     
					</tr>
					<tr>
						<th></th>
						<th></th>
							<th>Δικαιούμενες</th>
							<th>Παλαιές</th>
							<th>Υπόλοιπο</th>
							<th>Υποβολές</th>
							<th>Ακυρωμένες</th>
							<th>Εγκεκριμένες</th>
							<th>Απορριφθήσες</th>
							<th>Ανακληθείσες</th>
						<th></th>      
					</tr>
				</thead>
				<tbody>
					<?php 
			
						$employees_afms = array();
						foreach($employees as $employee)
							$employees_afms[$employee->afm] = array(
																'submitted' => 0,
																'canceled' 	=> 0,
																'approved' 	=> 0,
																'rejected' 	=> 0,
																'recalled' 	=> 0,
																'absend'	=> false,
															);
															
						$today = date('Y-m-d');
						$today = date('Y-m-d', strtotime($today));
						$leaves = get_all_leaves();
						foreach($leaves as $leave){ 
							
							$employees_afms[$leave['employee_afm']]['submitted']++;
							
							if($leave['canceled_by'] != 0 and $leave['canceled'] != 0)  	$employees_afms[$leave['employee_afm']]['recalled']++;
							elseif($leave['canceled'] != 0)  								$employees_afms[$leave['employee_afm']]['canceled']++;
							elseif($leave['signature_by'] != 0 and $leave['status'] == 1)  	$employees_afms[$leave['employee_afm']]['approved']++;
							elseif($leave['signature_by'] != 0 and $leave['status'] == 0) 	$employees_afms[$leave['employee_afm']]['rejected']++;
							
							if($leave['signature_by'] != 0 and $leave['status'] == 1){ // Approved 
								
								// TODO: What about when recalled ?
								
								if(!$employees_afms[$leave['employee_afm']]['absend']){ // We have not found out if he is currently absent
									
									$leave_start = date('Y-m-d', strtotime( $leave['date_starts']));
									$leave_end = date('Y-m-d',   strtotime( $leave['date_ends']));
									
									if (($today > $leave_start) && ($today < $leave_end)){
										$employees_afms[$leave['employee_afm']]['absend'] = true;
									}
								}
							}
							
						}
						
						foreach($employees as $employee){ 
							$leave_user_stats = get_leave_user_stats((object)$employee);
							$leave_details = get_analytics_data($leave_user_stats);
						?>
							<tr>
								<td><?php echo $employee['last_name']; ?></td>
								<td><?php echo $employee['first_name']; ?></td>
								<td><?php  echo $leave_details['current'][0].' / '.$leave_details['current'][1]; ?></td>
								<td><?php  echo $leave_details['past'][0].' / '.$leave_details['past'][1]; ?></td>
								<td><?php echo $leave_user_stats->remaining_leaves; ?></td>
								<td><?php echo $employees_afms[$employee['afm']]['submitted']; ?></td>
								<td><?php echo $employees_afms[$employee['afm']]['canceled']; ?></td>
								<td><?php echo $employees_afms[$employee['afm']]['approved']; ?></td>
								<td><?php echo $employees_afms[$employee['afm']]['rejected']; ?></td>
								<td><?php echo $employees_afms[$employee['afm']]['recalled']; ?></td>
								<td><?php echo ($employees_afms[$employee['afm']]['absend']? 'Ναι': '') ; ?></td>      
							</tr>
						<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php } ?>