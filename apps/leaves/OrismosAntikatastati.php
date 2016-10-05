<head>

 <script>
 //Build the days based of weekends
/* $nonWorkingDays = array();
foreach($iDate = 0; $iDate < 365; $iDate++){
    $date = strtotime('today +'.$iDate.' day');
    if(date('w', $date) == 0 || date('w', $date) == 6){
        $nonWorkingDays = date('Y-m-d', $date);
    }
}

//Add the holidays
$nonWorkingDays[] = '2011-12-25';
$nonWorkingDays[] = '2012-01-01';

//Determine the date of delivery
$daysToDelivery = 6;
$deliveryDate = time();
while($daysToDelivery > 0){
    $deliveryDate = time() + (24*60*60);
    if(!in_array(date('Y-m-d', $deliveryDate), $nonWorkingDays)){
        $daysToDelivery--;
    }
} */
 
 // check if a value neeedle is in array haystack
 /* function inArray(needle,haystack)
{
    var count=haystack.length;
    for(var i=0;i<count;i++)
    {
        if(getTime(haystack[i])==needle){return true;}
    }
    return false;
}
 
 
 
 addBusinessDays = function (startingDate, daysToAdjust) {
    var DateReturn,
        businessDaysLeft,
		tmpDate,
		iDate;
	var nonWorkingDays = [];
	
	tmpDate= new Date(startingDate.valueOf());
	for (iDate = 0; iDate < 365; iDate++) {
		tmpDate.setDate( tmpDate.getDate() + iDate );
		 if(tmpDate.getDay()==0 || tmpDate.getDay()==6 ){
			 nonWorkingDays.push(tmpDate); 
		 }
	} 
    //Add the holidays
	nonWorkingDays.push('2016-12-25');
	nonWorkingDays.push('2017-12-01');
	
	//Determine the date of return
	businessDaysLeft=daysToAdjust;
	DateReturn= new Date(startingDate.valueOf());
	while( businessDaysLeft>0 ){
		DateReturn.setDate( DateReturn.getDate() + 1 );
		if ( inArray(DateReturn,nonWorkingDays)) {
			 businessDaysLeft--;
		}
	}
	return DateReturn;
	 */
	
    // short-circuit no work; make direction assignment simpler
    /* if (daysToAdjust === 0) {
        return startingDate;
    } */
    // direction = daysToAdjust > 0 ? 1 : -1;

    // Move the date in the correct direction
    // but only count business days toward movement
    //businessDaysLeft = Math.abs(daysToAdjust);
    //while (businessDaysLeft) {
    //    newDate.setDate(newDate.getDate() + direction);
    //    isWeekend = newDate.getDay() in {0: 'Sunday', 6: 'Saturday'};
    //    if (!isWeekend) {
    //        businessDaysLeft--;
    //    }
    //}
	
	
	/* while( businessDaysLeft>0 ){
    var tmp = new Date();
    tmp.setDate( tmp.getDate() + counter++ );
    switch( tmp.getDay() ){
            case 0: case 6: break;// sunday & saturday
            default:
                businessDaysLeft--;
            }; 
}
	
	
    return newDate; */



  /* function OnSelectionDateApo (select) {
            var DateSelected = select.options[select.selectedIndex];
			localStorage.setItem("DateSelectedApo", DateSelected.value);
            var url = 'OrganismosSelection.php?EpilogiGenDnsis='+GenDnsiSelected.value;
            window.location.href=url;
            //alert ("The selected option is " + DnsiSelected.value);
            } */
</script>
    
</head>


<div class="row">
	<div class="col-lg-12">
		<h3 class="page-title">Ορισμός Αντικαταστάτη Προϊσταμένου</h3>
	</div>
</div>
  <?php 
	if ( $_GET['pause']==1) { // ΠΑΥΣΗ ΑΝΤΙΚΑΤΑΣΤΑΤΗ 
		if (isset($_GET['id'])) {
			PausiEnergouAntikatastati($_GET['id']);
		}
	}
	save_edit_antikatastatis_proistamenos();
	print_messages();
?>
<div class="row"><div class="col-sm-12"><div id="errorer" class="alert alert-danger" style="display:none;">Συμπληρώστε τα απαραίτητα πεδία</div></div></div>
<div class="row">
	<div class="col-lg-12">
		<div class="">
		
		<form name="Antikatastatisform" id="Antikatastatisform" method="post" action="<?php echo URL; ?>/?p=leaves|OrismosAntikatastati&pause=0&id=">
			<input  name="dieuthinsi_id"   type="hidden" value="<?php echo get_user_dieuthinsi() ?>" />
			<input  name="dieuthintis_afm"   type="hidden" value="<?php echo get_user_afm() ?>" />
			<div class="row">
				<div class="panel-heading">
					<h4> Τρέχων Αντικαταστάτης</h4>
				</div>
				<div class="dataTable_wrapper">
					<table class="table table-striped table-bordered table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Αντικαταστάτης Α</th>
								<th>Αντικαταστάτης Β</th>
								<th>από</th>
								<th>εώς</th>
								<th>Παύση Αντικαταστάτη</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$antikatastates=get_my_trexon_antikatastates();
							foreach($antikatastates as $antikatastati){
								echo "<tr>";
								if (!is_null($antikatastati['antikat_afm_a']))
									echo "<td>".afm_to_name($antikatastati['antikat_afm_a'])."</td>";
								else
									echo "<td> </td>";
								if ( !is_null($antikatastati['antikat_afm_b']) )
									echo "<td>".afm_to_name($antikatastati['antikat_afm_b'])."</td>";
								else
									echo "<td> </td>";
								echo "<td>".$antikatastati['antikatastasi_apo']."</td>";
								echo "<td>".$antikatastati['antikatastasi_eos']."</td>";
								echo "<td><a href='".URL."/?p=leaves|OrismosAntikatastati&pause=1&id=".$antikatastati['primkey']."'><button type='button' class='btn btn-danger btn-circle'><i class='fa fa-close'></i></button></a></td>";
								echo '</tr>';
							}
						?>
						</tbody>
					</table>
				</div> 
							
				<div class="panel-heading">
					<h4> Ορισμός Νέων Αντικαταστατών</h4>
				</div>		
				<div class="col-md-3">
					<div class="form-group">
						<label>Αντικαταστάτης Α</label>
						<div class="form-group">
							<select name="Antikatastatis1_afm" class="form-control" id="user_list">
							<?php 
								$employees = get_employeesProistamenous(); //Φόρτωση Προϊσταμένων Τμήματος
								echo '<option value=0 selected="SELECTED" > <ΕΠΕΛΕΞΕ ΠΡΟΪΣΤΑΜΕΝΟ> </option>';
								foreach($employees as $employee){
									// $leaves = get_leave_user_stats((object)$employee);
									echo '<option value="'.$employee['afm'].'">'.$employee['last_name'].' '.$employee['first_name'].'</option>';
								}
							?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Αντικαταστάτης Β</label>
						<div class="form-group">
							<select name="Antikatastatis2_afm" class="form-control" id="user_list">
							<?php 
								$employees = get_employeesProistamenous(); //Φόρτωση Προϊσταμένων Τμήματος
								echo '<option value=0 selected="SELECTED" > <ΕΠΕΛΕΞΕ ΠΡΟΪΣΤΑΜΕΝΟ> </option>';
								foreach($employees as $employee){
									// $leaves = get_leave_user_stats((object)$employee);
									echo '<option value="'.$employee['afm'].'">'.$employee['last_name'].' '.$employee['first_name'].'</option>';
								}
							?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Από</label>
						<input  class="form-control required" name="AntikatastatisApo" id="dpd1" type="date" size="16" />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Εώς</label>
						<input type="date" class="form-control required" name="AntikatastatisEws" id="dpd2" value="ΕΕΕΕ/ΜΜ/ΗΗ" >
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