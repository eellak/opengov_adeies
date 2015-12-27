<div class="row">
	<?php 
		if(get_user_is('director')){  //Εμφάνιση στατιστικών αδειών σε χρήστη με αυξημένα δικαιώματα
		$count_new = 0;
		$count_ready = 0;
		$my_leaves =  get_my_employees_leaves();
		foreach($my_leaves as $leave){
			if($leave['signature_by'] != 0) 
				$count_ready++;
			else
				$count_new++;
		}
	?>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-yellow">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-inbox fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo $count_new; ?></div>
							<div>Αιτήσεις προς Επεξεργασία</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-green">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-list-alt fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo $count_ready; ?></div>
							<div>Επεξεργασμένες Αιτήσεις</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-calendar fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo get_remaining_leaves(); ?></div>
						<div>Υπόλοιπο Ημερών Άδειας</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		Εφαρμογή Διαχείρισης Αδειών
	</div>
	<div class="panel-body">
		<p>Χρησιμοποιώντας το Σύστημα Διαχείρισης Αδειών της Περιφέρειας Δυτικής Μακεδονίας, έχετε τη δυνατότητα να αξιολογήσετε
		τις αιτήσεις των υπαλλήλων της διεύθυνσής σας, να καταχωρήσετε νέα αίτηση άδειας, να ελέγξετε την πορεία της καταχωρημένης αίτησής σας, να λάβετε αντίγραφο της αίτησής σας σε μορφή PDF καθώς και να έχετε μια επισκόπιση όλων των αιτήσεων που έχετε υποβάλλει ως τώρα.</p>
	</div>
</div>