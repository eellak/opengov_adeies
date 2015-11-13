$(document).ready(function(){
		
	$('#editform').submit(function(e){;
		var errorer = $('#errorer');
		errorer.fadeOut();
		var has_error = false;
		
		var call = $('input[name=approve_type]:checked', '#editform').val();
		var comment = $('#comments').val();
		
		if(call == 0 && comment == ''){
			errorer.fadeIn();
			has_error = true;
		}
		
		if(has_error)
			e.preventDefault();
	});
	
});