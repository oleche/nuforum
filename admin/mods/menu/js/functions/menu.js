var table;

$(document).ready(function(){
	table = $('#menu_table').on( 'draw.dt', drawEvent ).DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": "menu/actions/listall_action.php",
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "menu_type_id.name" },
            { "data": "link_to" },
            { "data": null,
              "defaultContent": "<button class='btn btn-sm delete'><span class='glyphicon glyphicon-trash'></span></button>" }
        ],
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],
	});
	
	console.log(table);
	
	function drawEvent(){
		$(".delete").bind('click', function(){
			var data = table.row( $(this).parents('tr') ).data();
			var id = data['id'];
			var buttons = $("<button id='accept' class='btn' data-id='"+id+"'>OK</button> <button id='close' data-dismiss='modal' class='btn'>Close</button>");
			$('#myModal').find('.title').html("Delete Menu");
			$('#myModal').find('.sub_title').html("Are you sure you want to delete this?");
			$('#myModal').find('.message').html("This operation cannot be undone");
			$('#myModal').find('.button-space').html(buttons);
			$('#myModal').find('#accept').bind("click", deleteMenu);
			$('#myModal').modal('show');
		});	
	}
	
	function deleteMenu(){
		var id = $(this).data('id');
		$.ajax({
	        type: 'POST',
	        url: 'menu/actions/deletem_action.php',
	        data: {"id":id},
	        success: function(json) {
				if (json.code == 0){
					console.log(id);
					table.ajax.reload();
					table.draw();
					table.fnReloadAjax(table.fnSettings());
					$('#myModal').modal('hide');
	            }else{
	            	$('#myModal').find('.title').html("Ooops!!");
					$('#myModal').find('.sub_title').html("Something went wrong");
					$('#myModal').find('.message').html(json.message);
					$('#myModal').find('.button-space').html("<button id='close' data-dismiss='modal' class='btn'>Close</button>");
					$('#myModal').find('#accept').bind("click", deleteMenu);
					$('#myModal').modal('show');
	            }
	        },
	        error: function(e, msj, xmlHttpReq) {
	        	$('#myModal').find('.title').html("Ooops!!");
					$('#myModal').find('.sub_title').html("Something went wrong");
					$('#myModal').find('.message').html(msj);
					$('#myModal').find('.button-space').html("<button id='close' data-dismiss='modal' class='btn'>Close</button>");
					$('#myModal').find('#accept').bind("click", deleteMenu);
					$('#myModal').modal('show');
	        }
	    });
		
	}

	
});

