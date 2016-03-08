<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Υποβολή Νέας Αίτησης</h3>
	</div>
</div>
 <?php 
	save_new_application();
	print_messages();
	$remainin_leaves = get_remaining_leaves();
?>
<div class="row"><div class="col-sm-12"><div id="errorer" class="alert alert-danger" style="display:none;">Συμπληρώστε τα απαραίτητα πεδία</div></div></div>
<div class="row">
	<div class="col-lg-12">
		<div class="">
		
		<form name="leaveform" id="leaveform" method="post" action="<?php echo URL; ?>/?p=leaves|new">
			<div class="row">
				<div class="col-md-2">
					<div id="remaining" data-leaves="<?php echo $remainin_leaves; //Εμφάνιση υπολοίπου ημερών άδειας ?>" ></div>
					<div class="alert alert-info" style="margin-top: 10px;">Υπόλοιπο Ημερών Άδειας: <strong><?php echo $remainin_leaves; ?></strong></div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label>Αριθμός Ημερών Άδειας</label>
						<input class="form-control required" name="num_leaves" id="num_leaves" type="number" min="1" max="<?php echo $remainin_leaves; ?>">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label>Είδος Άδειας</label>
						<div class="radio">
							<label>
								<input type="radio" name="leave_type" id="leave_type_regular" value="0" checked="checked">Κανονική
							</label>
						</div>
						<?php /*
						<div class="radio">
							<label>
								<input type="radio" name="leave_type" id="leave_type_scholar" value="1">Σχολική
							</label>
						</div> */ ?>
						<?php if(get_user_is('director')){ //Αν ο χρήστης έχει αυξημένα δικαιώματα ?>
							<div class="radio">
								<label>
									<input type="radio" name="leave_type" id="leave_type_tel" value="2">Τηλεφωνική, για: 
								</label>
							</div>
							</div>
							<div class="form-group">
								<select name="user_tel" class="form-control" id="user_tel">
								<?php 
									$employees = get_employees(); //Φόρτωση υφισταμένων
									foreach($employees as $employee){
										echo '<option value="'.$employee['afm'].'" rel="'.get_remaining_leaves_for_user($employee['id']).'">'.$employee['last_name'].' '.$employee['first_name'].'</option>';
									}
								?>
								</select>
								<div id="showremaining"></div>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Ημερομηνία Έναρξης</label>
					   <input class="form-control required" name="date_starts" id="dpd1" size="16" type="text" value="ΕΕΕΕ/ΜΜ/ΗΗ"/>
					</div> 
				</div> 
				<div class="col-md-3">
					<div class="form-group">
						<label>Ημερομηνία Λήξης</label>
						<input class="form-control required" name="date_ends" id="dpd2" size="16" type="text" value="ΕΕΕΕ/ΜΜ/ΗΗ"/>
					</div>  
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