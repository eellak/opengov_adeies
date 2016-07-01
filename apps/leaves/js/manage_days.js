$(document).ready(function(){

	 $("#user_list").change(function(){ 
		var leavedata = $('option:selected', this).attr('rel');
		var leaves = leavedata.split("#");
		$('#num_leaves').val(leaves[0]);
		$('#past_leaves').val(leaves[1]);
		$('#showremaining').html('Υπόλοιπο Ημερών Άδειας: <strong>'+leaves[2]+'</strong>');
	}); 
	
	$('#manageform').submit(function(e){;
		var errorer = $('#errorer');
		errorer.fadeOut();
		var has_error = false;
		var num_leaves = $('#num_leaves').val();
		var past_leaves = $('#past_leaves').val();
		if( num_leaves == 0 || num_leaves == '' || num_leaves < 0){
			errorer.fadeIn();
			has_error = true;
		}
		
		if( past_leaves == '' || num_leaves < 0){
			errorer.fadeIn();
			has_error = true;
		}
		
		if(has_error)
			e.preventDefault();
	});
	
	$( "#num_leaves" ).keyup(function() {
		update_remaining()
	});
	
	$( "#past_leaves" ).keyup(function() {
		update_remaining()
	});
	
	function update_remaining(){
		var num_leaves = $('#num_leaves').val();
		var past_leaves = $('#past_leaves').val();
		var remaining = parseInt(num_leaves) + parseInt(past_leaves);
		$('#showremaining').html('Υπόλοιπο Ημερών Άδειας: <strong>'+remaining+'</strong>');
	}
	
});