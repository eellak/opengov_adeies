$(document).ready(function(){
			 
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	
	
	$('#dpd1').datetimepicker({	format: 'YYYY-MM-DD' });
	$('#dpd2').datetimepicker({	format: 'YYYY-MM-DD' });
	
	
	
	
});