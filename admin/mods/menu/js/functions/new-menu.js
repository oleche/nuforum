$(document).ready(function(){
	$("#type").change(function(){
		istable = $(this).find(':selected').data('istable');
		if (istable == 0){
			$("#linktogrp").hide();
			$(".filled").fadeOut();
			$("#link").show();
			table = null;
		}else{
			$("#linktogrp").show();
			$("#link").hide();
			table = $(this).find(':selected').data('table');
		}
	});
	
	$("#linkto").click(function(){
		$(".filled").fadeIn();
		$.ajax({
	        type: 'POST',
	        url: 'actions/linkto_action.php',
	        data: {setlist: table},
	        success: function(json) {
				if (json.code == 0){
	                
	            }else{
	            	
	            }
	        },
	        error: function(e, msj, xmlHttpReq) {
	        	
	        }
	    });
	});
});
