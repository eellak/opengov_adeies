<div class="row">	
	<div class="col-lg-12">
		<h3 class="page-title">Η Αίτησή μου</h3>
	</div>
	<!-- /.col-lg-12 -->
</div>

<?php 
	$leave = get_leave(trim($_GET['id'])) ; //Φόρτωση άδειας βάσει ID
	if(empty($leave)){ ?>
		<div class="row"><div class="col-sm-12"><div id="errorer" class="alert alert-danger">Η Αίτηση Δεν Εντοπίστηκε</div></div></div>
<?php
	} else{
		global $leave_user;
		$leave_user = get_leave_user($leave->employee_afm); //Φόρτωση υφισταμένου βάσει ΑΦΜ
		
		$class = 'info';
		$title = 'Η αίτηση βρίσκεται σε στάδιο αξιολόγησης';
		if($leave->signature_by != 0 and $leave->status == 1){  
			$title = 'Η αίτηση εγκρίθηκε';
			$class = 'success';
		}
		if($leave->signature_by != 0 and $leave->status == 0){  
			$title = 'Η αίτηση απορρίφθηκε';
			$class = 'danger';
		}
		
		if($leave->canceled == 1){  
			if($leave->canceled_by != ''){
				$title = 'Ανακλήθηκαν '.$leave->canceled_days.' απο '.$leave->num_leaves.' ημέρες της άδειας.';
				$class = 'warning';
			}else{
				$title = 'Η αίτηση ακυρώθηκε';
				$class = 'warning';
			}
		}
?>
	
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-<?php echo $class; ?>">
			<div class="panel-heading"><?php echo $title; ?></div>
			<div class="panel-body">
				<div class="row">
					<?php include('single_helper.php'); ?>
					<div class="col-lg-3">
						<div class="panel panel-green">
							<div class="panel-heading">
								Στοιχεία Άδειας
							</div>
							<div class="col-lg-12"> 
								<div class="form-group">
									<label class="<?php echo $class; ?>"><?php echo $title; ?></label>
								</div>
							<?php
								if($leave->signature_by != 0){  
									if($leave->canceled == 1){ ?>
										<div class="form-group">
											<label>Ανακλήθηκε απο</label>
											<?php $user_leave_signed = get_leave_user($leave->canceled_by); ?>
											<p class="form-control-static"><?php echo  $user_leave_signed->last_name.' '.$user_leave_signed->first_name; ?></p>
										</div>
										<div class="form-group">
											<label>Ημερομηνία Επεξεργασίας</label>
											<p class="form-control-static"><?php echo printDate($leave->canceled_date); ?></p>
									</div>
								<?php
									}else{
								?>
									<div class="form-group">
										<label>Επεξεργάστηκε απο</label>
										<?php $user_leave_signed = get_leave_user($leave->signature_by); ?>
										<p class="form-control-static"><?php echo  $user_leave_signed->last_name.' '.$user_leave_signed->first_name; ?></p>
									</div>
									<div class="form-group">
										<label>Ημερομηνία Επεξεργασίας</label>
										<p class="form-control-static"><?php echo printDate($leave->signature_date); ?></p>
									</div>
								<?php
									}
									
									if($leave->status == 0 or $leave->canceled == 1){
								?>
									<div class="form-group">
										<label>Σχόλιο</label>
										<p class="form-control-static"><?php echo $leave->comments; ?></p>
									</div>
								<?php
									}
								}
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }  ?>
