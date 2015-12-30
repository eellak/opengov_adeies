<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Οι Αιτήσεις μου</h3>
	</div>
</div>
 <?php 
	if(isset($_GET['cancel'])){
		save_cancel_application();
		print_messages(); 
	}
?>
<!-- /.row -->
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Πίνακας Καταχωρημένων Αιτήσεων
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<table class="table table-striped table-bordered table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Ημερομηνία Υποβολής</th>
								<th>Είδος Άδειας</th>
								<th>Αριθμός Ημερών</th>
								<th>Κατάσταση</th>   
								<th>Ενέργειες</th> 
							</tr>
						</thead>
						<tbody>
						<?php 
							$my_leaves =  get_my_leaves(); //Φόρτωση αιτήσεων άδειας
							foreach($my_leaves as $leave){
								
								$class = 'info';
								if($leave['signature_by'] != 0 and $leave['status'] == 1)  $class = 'success';
								if($leave['signature_by'] != 0 and $leave['status'] == 0)  $class = 'danger';
								
								//This is canceled..
								if($leave['canceled'] == 1)  $class = 'warning';
								
								echo "<tr class='$class'>";
								echo "<td>".$leave['date_submitted']."</td>";
								echo "<td>".get_leave_type($leave)."</td>";
								echo "<td>".$leave['num_leaves']."</td>";
								echo "<td>".get_leave_status($leave)."</td>";
								echo "<td><a href='".URL."/?p=leaves|single&id=".$leave['leave_id']."'><button type='button' class='btn btn-primary btn-circle'><i class='fa fa-eye'></i></button></a>&nbsp&nbsp";
								echo '<a href="'.URL.'/apps/leaves/files/'.$leave['filename'].'"><button type="button" class="btn btn-success btn-circle"><i class="fa fa-arrow-circle-down"></i></button></a>';
								
								if($class == 'info') // Not yer processed
									echo "&nbsp&nbsp<a href='".URL."/?p=leaves|myleaves&cancel=".$leave['leave_id']."'><button type='button' class='btn btn-danger btn-circle'><i class='fa fa-close'></i></button></a>";
									
								echo '</td>';
								echo '</tr>';
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
<!-- /.col-lg-6 -->
</div>
