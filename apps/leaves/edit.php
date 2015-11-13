<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Επεξεργασία Αίτησης</h3>
	</div>
	<!-- /.col-lg-12 -->
</div>

<?php 
	$leave = get_employee_leave(trim($_GET['id'])) ;
	if(empty($leave)){ ?>
		<div class="row"><div class="col-sm-12"><div id="errorer" class="alert alert-danger">Η Αίτηση Δεν Εντοπίστηκε</div></div></div>
<?php
	} else{
		$leave_user = get_user_details_by_afm($leave->employee_afm);
	
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
								Ενέργειες
							</div>
							<div class="col-lg-12">
								<form id="editform" action="<?php echo URL; ?>/?p=leaves|applications&save" method="post">
									<div class="form-group">
										<label>Σχόλια</label><p class="form-control-static">
										<textarea name="comments" id="comments" rows="6" cols="25" placeholder="Συμπληρώστε το σε περίπτωση απόρριψης της αίτησης"></textarea></p>
									</div>
									<div class="form-group">
										<div id="errorer" class="alert alert-danger" style="display:none;">Συμπληρώστε Σχόλιο σε περίπτωση απόρριψης της αίτησης</div>
										<div class="radio">
											<label>
												<input type="radio" name="approve_type" class="approve_type" id="approve_type_accept" value="1" checked="checked">Έγκριση
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="approve_type" class="approve_type"  id="approve_type_deny" value="0">Απόρριψη
											</label>
										</div>
									</div>
									<div class="form-group">
										<p class="form-control-static">
											<input type="hidden" name="leave_id" value="<?php echo $leave->leave_id; ?>" />
											<button type="submit" class="btn btn-outline btn-success">Αποθήκευση</button>
										</p>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }  ?>
