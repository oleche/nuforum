$(document).ready(function(){
	$("#type").change(function(){
		istable = $(this).find(':selected').data('istable');
		$("#link").val("");
		$(".selected-text").html("Select from list");
		if (istable == 0){
			$("#linktogrp").hide();
			$(".filled").fadeOut();
			$("#link").show();
			tabledb = null;
		}else{
			$("#linktogrp").show();
			$("#link").hide();
			$(".filled").fadeOut();
			tabledb = $(this).find(':selected').data('table');
		}
	});
	
	$("#parent-select").click(function(){
		$(".filled").fadeIn();
		$.ajax({
	        type: 'POST',
	        url: 'actions/parent_action.php',
	        data: {setmenu: ''},
	        success: function(json) {
	        	if (json.code == 0){
	        	}
	        },
	        error: function(e, msj, xmlHttpReq) {
	        	$('.filled_content').html("<center><h1>Items not found</h1></center>");
	        }
	    });
	});
	
	$("#linkto").click(function(){
		$(".filled").fadeIn();
		$.ajax({
	        type: 'POST',
	        url: 'actions/linkto_action.php',
	        data: {setlist: tabledb},
	        success: function(json) {
				if (json.code == 0){
					var table = $("<table class='table table-hover linkable'></table>");
					var head = $("<tr></tr>");
					head.append("<th>Title</th>");
					head.append("<th>Category</th>");
					head.append("<th>Entry Type</th>");
					head.append("<th>Preview</th>");
					table.prepend(head);
					if (json.request.length > 0)
		                json.request.forEach(function(i){
		                	console.log(tabledb);
		                	if (tabledb == "category"){
		                		var url = i.url;
			                	var line = $("<tr data-link='"+url+"' class='linkable-click'></tr>");
			                	line.append("<td>"+i.name+"</td>");
			                	line.append("<td>--</td>");
			                	line.append("<td>Category</td>");
			                	line.append("<td><a href='"+url+"' target='_blank'>Preview</a></td>");
			                	table.append(line);
		                	}else{
			                	var url = i.entry_type_id.url+"?id="+i.id;
			                	var line = $("<tr data-link='"+url+"' class='linkable-click'></tr>");
			                	line.append("<td>"+i.title+"</td>");
			                	line.append("<td>"+i.category_id.name+"</td>");
			                	line.append("<td>"+i.entry_type_id.name+"</td>");
			                	line.append("<td><a href='"+url+"' target='_blank'>Preview</a></td>");
			                	table.append(line);
		                	}
		                });
	                else{
	                	$('.filled_content').html("<center><h1>Items not found</h1></center>");
	                }
	                var content = $("<div class='col-sm-12 renderPanelFix'></div>");
	                content.append("<legend>Available Items</legend>");
	                content.append(table);
	                $('.filled_content').html(content);
	                $("tr.linkable-click").click(function(){
	                	var link = $(this).data("link");
	                	$("#link").val(link);
	                	$(".selected-text").html(link);
	                });
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