$(document).ready(function(){
		
	$('#editform').submit(function(e){;
		var errorer = $('#errorer');
		errorer.fadeOut();
		var has_error = false;
		
		var comment = $('#comments').val();
		
		if(comment == ''){
			errorer.fadeIn();
			has_error = true;
		}
		
		if(has_error)
			e.preventDefault();
	});
	
});