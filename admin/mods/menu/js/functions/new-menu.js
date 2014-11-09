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
			$(".filled").fadeOut();
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
					var table = $("<table class='table table-hover linkable'></table>");
					var head = $("<tr></tr>");
					head.append("<th>Title</th>");
					head.append("<th>Category</th>");
					head.append("<th>Entry Type</th>");
					table.prepend(head);
					if (json.request.length > 0)
		                json.request.forEach(function(i){
		                	var line = $("<tr></tr>");
		                	line.append("<td>"+i.title+"</td>");
		                	line.append("<td>"+i.category_id.name+"</td>");
		                	line.append("<td>"+i.entry_type_id.name+"</td>");
		                	table.append(line);
		                });
	                else{
	                	$('.filled_content').html("<center><h1>Items not found</h1></center>");
	                }
	                var content = $("<div class='col-sm-12 renderPanelFix'></div>");
	                content.append("<legend>Available Items</legend>");
	                content.append(table);
	                $('.filled_content').html(content);
	            }else{
	            	$('.filled_content').html("<center><h1>Items not found</h1></center>");
	            }
	        },
	        error: function(e, msj, xmlHttpReq) {
	        	$('.filled_content').html("<center><h1>Items not found</h1></center>");
	        }
	    });
	});
});

function build_url(data){
	return "";
}
