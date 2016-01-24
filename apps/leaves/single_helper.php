<div class="col-lg-3">
	<div class="panel panel-primary">
		<div class="panel-heading">
			Στοιχεία Υπαλλήλου
		</div>
		<div class="col-lg-12">
			<div class="form-group">
				<label>Επώνυμο</label>
				<p class="form-control-static"><?php echo $leave_user->last_name; ?></p>
			</div>
			<div class="form-group">
				<label>Όνομα</label>
				<p class="form-control-static"><?php echo $leave_user->first_name; ?></p>
			</div>
			<?php if($class == 'info') { ?>
				<div class="alert alert-info" style="margin-top: 10px;">
					Η αίτηση δεν έχει επεξεργαστεί ακόμα. Οι ημέρες της άδειας δεν συνυπολογίζονται στο υπόλοιπο αδειών.
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php
	$leave_user_stats = get_leave_user_stats($leave_user);
	$leave_details = get_analytics_data($leave_user_stats);
?>
<div class="col-lg-3">
	<div class="panel panel-yellow">
		<div class="panel-heading">
			Στοιχεία Αδειών
		</div>
		<div class="col-lg-12">
			<div class="form-group">
				<label>Υπόλοιπο Ημερών Άδειας</label>
				<p class="form-control-static"><?php  echo $leave_user_stats->remaining_leaves; ?></p>
			</div>
			<div class="form-group">
				<label>Ημέρες Αδειών</label>
				<p class="form-control-static"><?php  echo $leave_details['current'][0].' / '.$leave_details['current'][1]; ?></p>
			</div>
			
			<div class="form-group">
				<label>Παλαιό Υπόλοιπο Αδειών</label>
				<p class="form-control-static"><?php  echo $leave_details['past'][0].' / '.$leave_details['past'][1]; ?></p>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="panel panel-red">
		<div class="panel-heading">
			Τρέχουσα Άδεια
		</div>
		<div class="col-lg-12">
			<div class="form-group">
				<label>Είδος Άδειας</label>
				<p class="form-control-static"><?php echo get_leave_type(array($leave)); ?></p>
			</div>
			<div class="form-group">
				<label>Ημερομηνία Υποβολής</label>
				<p class="form-control-static"><?php echo printDate($leave->date_submitted); ?></p>
			</div>
			<div class="form-group">
				<label>Αιτούμενες Ημέρες</label>
				<p class="form-control-static"><?php echo $leave->num_leaves; ?></p>
			</div>
			<div class="form-group">
				<label>Ημερομηνία Έναρξης</label>
				<p class="form-control-static"><?php echo printDate($leave->date_starts); ?></p>
			</div>
			<div class="form-group">
				<label>Ημερομηνία Λήξης</label>
				<p class="form-control-static"><?php echo printDate($leave->date_ends); ?></p>
			</div>
		</div>
	</div>
</div>