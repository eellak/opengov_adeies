<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Η Αίτησή μου</h3>
	</div>
	<!-- /.col-lg-12 -->
</div>

<?php 
	$leave = get_leave(trim($_GET['id'])) ;
	if(empty($leave)){ ?>
		<div class="row"><div class="col-sm-12"><div id="errorer" class="alert alert-danger">Η Αίτηση Δεν Εντοπίστηκε</div></div></div>
<?php
	} else{
		global $leave_user;
		$leave_user = $user;
		
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
								Στοιχεία Άδειας
							</div>
							<div class="col-lg-12"> <!--
								<div class="form-group">
									<label>Σχόλια</label><p class="form-control-static">
									<textarea name="sxolia" id="sxolia" rows="6" cols="25" placeholder="Συμπληρώστε το σε περίπτωση απόρριψης της αίτησης"></textarea></p>
								</div>
								-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }  ?>