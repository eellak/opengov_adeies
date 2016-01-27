$(document).ready(function(){
			 
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	
	$('#dpd1').datepicker({	format: 'yyyy-mm-dd' });
	$('#dpd2').datepicker({	format: 'yyyy-mm-dd' });
	
		var checkin = $('#dpd1').datepicker({
		  onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		  }
		}).on('changeDate', function(ev) {
			if (ev.date.valueOf() > checkout.date.valueOf()) {
				var newDate = new Date(ev.date)
				newDate.setDate(newDate.getDate() + 1);
				checkout.setValue(newDate);
			}
			checkin.hide();
			$('#dpd2')[0].focus();
		}).data('datepicker');
		
		var checkout = $('#dpd2').datepicker({
			onRender: function(date) {
				return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
			}
		}).on('changeDate', function(ev) {
			checkout.hide();
		}).data('datepicker');
	
	/*
	$('#statform').submit(function(e){;
		
		var errored = $('#errorer');
		var has_error = false;
		
		$('.required').each(function(){
			if($(this).val() !=''){}else{
				 has_error = true;
			} 
		});
		
		if( has_error) errored.html('Συμπληρώστε τα απαραίτητα πεδία').fadeIn();
		
		if(has_error)
			e.preventDefault();
	}); */
	
});