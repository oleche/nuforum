$(document).ready(function(){
	$(".number").mask('00000');
	
	function paso1ops(e){
		
	}
	
	postSt1 = {};
	
	$("input[name='optionsRadios']").click(function(){
	    if($('input:radio[name=optionsRadios]:checked').val() == "1"){
	        $("#inputWinners").show();
	        $("#inputWinners").val("");
	        //$('#select-table > .roomNumber').attr('enabled',false);
	    }else{
	    	$("#inputWinners").val("0.5");
	    	$("#inputWinners").hide();
	    }
	});
	
	$(".siguienteClick").click(function(e){
		var toNext = true;
		
		if ($(this).hasClass("paso1Next")){
			$campos = $("#paso1form").find("input[type=text]");
			console.log($campos);
			postSt1 = {};
			$campos.each(function() {
            	if (validateInputs($(this), ".validatorSt1") === false) {
            		toNext = false;
            	}else{
            		postST1[$(this).attr("id")] = $(this).val();
            	}
        	});
        	console.log(postST1);
			if (!toNext){
				$('.validatorSt1').show();
			}else{
				$.ajax({
		            type: 'POST',
		            url: 'post/step1.php',
		            data: postSt1,
		            //dataType: "json",
		            //contentType:"application/json",
		            success: function(data) {
		                console.log(data);
		            },
		            error: function(e, msj, xmlHttpReq) {
		            	console.log(e);
		                console.log(msj);
		                console.log(xmlHttpReq);
		            }
		         });
			}
		}
		
		if (toNext){
			$(".paso").hide();
			$("."+$(this).data("next")).show();
		}
	});
});
