$(document).ready(function(){
	$("#type").change(function(){
		istable = $(this).find(':selected').data('istable');
		if (istable == 0){
			$("#linkto").hide();
			$(".filled").fadeOut();
			$("#link").show();
		}else{
			$("#linkto").show();
			$("#link").hide();
		}
	});
	
	$("#linkto").click(function(){
		$(".filled").fadeIn();
		var type = $("#type").val();
		
	});
});
