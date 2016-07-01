<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Επεξεργασία Ημερών Αδείας Στελεχών</h3>
	</div>
</div>
  <?php 
	save_edit_user_leaves();
	print_messages();
?>
<div class="row"><div class="col-sm-12"><div id="errorer" class="alert alert-danger" style="display:none;">Συμπληρώστε τα απαραίτητα πεδία</div></div></div>
<div class="row">
	<div class="col-lg-12">
		<div class="">
		
		<form name="manageform" id="manageform" method="post" action="<?php echo URL; ?>/?p=leaves|manage">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Επιλογή Υπαλλήλου</label>
						<div class="form-group">
							<select name="user_list" class="form-control" id="user_list">
							<?php 
								$employees = get_employees(); //Φόρτωση υφισταμένων
								foreach($employees as $employee){
									$leaves = get_leave_user_stats((object)$employee);
									echo '<option value="'.$employee['id'].'" rel="'.$leaves->num_leaves.'#'.$leaves->past_leaves.'#'.$leaves->remaining_leaves.'">'.$employee['last_name'].' '.$employee['first_name'].'</option>';
								}
							?>
							</select>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="form-group">
						<label>Ημέρες Άδειας</label>
						<input class="form-control required" name="num_leaves" id="num_leaves" type="number" >
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Υπόλοιπο Περασμένου Έτους</label>
					   <input class="form-control required" name="past_leaves" id="past_leaves" type="number" >
					</div> 
				</div>  
				<div class="col-md-3">
					<div class="alert alert-info" style="margin-top: 10px;" id="showremaining">Υπόλοιπο Ημερών Άδειας: <strong></strong></div>
				</div>
			</div> 
			<div class="row">
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary pull-right">Υποβολή</button>
				</div>
			</div>
		</form>
	</div>
</div>