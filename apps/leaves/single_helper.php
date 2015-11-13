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
			<div class="form-group">
				<label>Γενική Διεύθυνση</label>
				<p class="form-control-static"><?php  ?></p>
			</div>
			 <div class="form-group">
				<label>Διεύθυνση</label>
				<p class="form-control-static"><?php  ?></p>
			</div>
			<div class="form-group">
				<label>Τμήμα</label>
				<p class="form-control-static"><?php  ?></p>
			</div>
			<div class="form-group">
				<label>Βαθμός</label>
				<p class="form-control-static"><?php ?></p>
			</div> 
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="panel panel-yellow">
		<div class="panel-heading">
			Στοιχεία Αδειών
		</div>
		<div class="col-lg-12">
			<div class="form-group">
				<label>Μέγιστος Αριθμός Αδειών</label>
				<p class="form-control-static"><?php  echo $leave_user->num_leaves; ?></p>
			</div>
			<div class="form-group">
				<label>Τρέχον Υπόλοιπο Αδειών</label>
				<p class="form-control-static"><?php  echo $leave_user->remaining_leaves; ?></p>
			</div>
			<div class="form-group">
				<label>Παλαιό Υπόλοιπο Αδειών</label>
				<p class="form-control-static"><?php  echo $leave_user->past_leaves; ?></p>
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
				<p class="form-control-static"><?php echo $leave->date_submitted; ?></p>
			</div>
			<div class="form-group">
				<label>Αιτούμενες Ημέρες</label>
				<p class="form-control-static"><?php echo $leave->num_leaves; ?></p>
			</div>
			<div class="form-group">
				<label>Ημερομηνία Έναρξης</label>
				<p class="form-control-static"><?php echo $leave->date_starts; ?></p>
			</div>
			<div class="form-group">
				<label>Ημερομηνία Λήξης</label>
				<p class="form-control-static"><?php echo $leave->date_ends; ?></p>
			</div>
		</div>
	</div>
</div>