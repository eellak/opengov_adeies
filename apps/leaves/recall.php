<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Ανάκληση Αίτησης</h3>
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
?>
	
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-danger">
			<div class="panel-heading">Προσοχή η ενέργεια αυτή δεν μπορεί να αναιρεθεί!</div>
			<div class="panel-body">
				<div class="row">
					<?php include('single_helper.php'); ?>
					<div class="col-lg-3">
						<div class="panel panel-green">
							<div class="panel-heading">
								Ενέργειες
							</div>
							<div class="col-lg-12">
								<form id="editform" action="<?php echo URL; ?>/?p=leaves|applications&recall" method="post">
									<div class="form-group">
										<label>Σχόλιο</label><p class="form-control-static">
										<textarea name="comments" id="comments" rows="6" cols="30" placeholder="Συμπληρώστε τους λόγους ανάκλησης της αίτησης"></textarea></p>
									</div>
									<div class="form-group">
										<div id="errorer" class="alert alert-danger" style="display:none;">Συμπληρώστε τους λόγους ανάκλησης της αίτησης</div>
										<label>Ημέρες ανάκλησης</label>
										<select name="days_canceled" class="form-control" id="days_canceled">
										<?php 
											$num = $leave->num_leaves; 
											while($num > 0){
												echo '<option value="'.$num.'">'.$num.'</option>';
												$num--;
											}
										?>
										</select>
									</div>
									<div class="form-group">
										<p class="form-control-static">
											<input type="hidden" name="leave_id" value="<?php echo $leave->leave_id; ?>" />
											<button type="submit" class="btn btn-outline btn-success">Ανάκληση</button>
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
