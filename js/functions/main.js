$(document).ready(function(){
	$(".knob").knob();
	
	$('#myTab a:last').tab('show');
	
	$.post("post/main.php",{dummy:1},function(){
		
	});
	
	/*$.ajax({
		type: 'GET',
		url: "https://graph.facebook.com/search?q=platform&type=page&access_token="+$("#at_mq").val(),
		crossDomain: true,
        dataType: "json",
        contentType:"application/json",
        
		success: function(json){
        	console.log(json);
        	json.data.forEach(function(x,y){
        		$.ajax({
					type: 'GET',
					url: "https://graph.facebook.com/"+x.id+"/picture?redirect=false&type=small&access_token="+$("#at_mq").val(),
					crossDomain: true,
			        dataType: "json",
			        contentType:"application/json",
			        
					success: function(json){
			        	console.log(json);
			        	
			        },
			        error: function(e, m, x){
			        	console.log(e);
			        	
			        } 
				});
        	});
        },
        error: function(e, m, x){
        	console.log(e);
        	
        } 
	});*/
	
	$("#crearQ").click(function(){
		$(".menus").fadeOut();
		$(".loader").fadeIn();
		$(".operation").html("");
		$.ajax({
            type: 'POST',
            url: "post/crear.php",
            xhrFields: {
				withCredentials: true
			},
            contentType:"application/json",
            success: function(data){
                $(".operation").fadeIn();
                $(".operation").html(data);
                $(".loader").fadeOut();
            },
            error: function(e, m, x){
            	$(".loader").fadeOut();
            	$(".operation").append("<center><h1>No hay contenido para mostrar</h1></center>");
            	$(".operation").fadeIn();
            	
            } 
        });
	});
});


var validators = {
	IsSelected : function(string) {
		return (string == "-1");
	},
	IsDate : function(string){
		var regex = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
		return !regex.test(string);
	},
    IsEmail : function(string) {
        var regex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/;
        return !regex.test(string.toLowerCase());
    },
    IsCleanSpanishString : function(string) {
        var regex = /[^a-zA-Z áéíóúñÁÉÍÓÚÑ]/;;
        return regex.test(string);  
    },
    IsCleanInteger : function(string){
        var regex = /[^0-9]/;
        return regex.test(string);  
    },
    IsPhone : function(string) {
        var regex = /\d{4}-\d{4}/;
        return !regex.test(string);  
    },
    IsPlacaGT : function(string) {
        var regex = /^P-[0-9]{3}[a-zA-Z]{3}$/;
        return !regex.test(string);  
    },
    IsNitGT: function(string){
        var regex = /\d-\d{1}$/;
        return !regex.test(string);  
    },
    IsDpiGT: function(string){
        var regex = /^\d{4} \d{5} \d{4}$/;
        return !regex.test(string);  
    },
    IsAlpha: function(string){
        var regex = /^[A-Za-z0-9]*$/;
        return !regex.test(string);  
    },
    IsCCard: function(string){
        var regex = /\d-\d{4}-\d{4}$/;
        return !(regex.test(string) && this.luhnVal(parseInt(string.replace(/\s-/))));  
    },
    luhnVal: function(a,b,c,d,e) {
        for(d = +a[b = a.length-1], e=0; b--;)
        c = +a[b], d += ++e % 2 ? 2 * c % 10 + (c > 4) : c;
        return !(d%10);
    },
    isFechaV: function(string){
        var regex = /^(1[0-2]|0[1-9])\/([0-9][0-9])$/;
        var d = new Date();
        if (regex.test(string) == true){
            var m = d.getMonth() + 1;
            var y = d.getFullYear().toString().substr(-2);
            var fv = string.split("/");
            if (y == fv[1]){
                if (fv[0] < m){
                    return true;
                } else {
                    return false;
                }
            } else if (y < fv[1]){
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
};

function validateInputs($this, validator) {
	if(typeof(validator)==='undefined') validator = "";
	var campo = $(validator).html();
	var alert_text = "";
    var p = true;
    if (($this.val() !== "" && $this.val() != null)){
        if ($this.hasClass('valEmail')){
            p = !validators.IsEmail($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Formato de correo electrónico inválido";
            }
        }
        if ($this.hasClass('valPhone')){
            p = !validators.IsPhone($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Formato de teléfono inválido";
            }
        }
        if ($this.hasClass('selectLinea')){
            p = !validators.IsSelected($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "No hay línea seleccionada";
            }
        }
        if ($this.hasClass('hasSexo')){
            p = !validators.IsSelected($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "No hay género seleccionado";
            }
        }
        if ($this.hasClass('hasEstadCivil')){
            p = !validators.IsSelected($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "No hay estado civil seleccionado";
            }
        }
        if ($this.hasClass('inputMarca')){
            p = !validators.IsSelected($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "No hay marca seleccionada";
            }
        } 
        if ($this.hasClass('valFecha')){
            p = !validators.IsDate($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Formato de fecha incorrecto (DD/MM/AAAA)";
            }
        }
        if ($this.hasClass('valSPString')){
            p = !validators.IsCleanSpanishString($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Formato de texto incorrecto";
            }
        }
        if ($this.hasClass('valCleanInt')){
            p = !validators.IsCleanInteger($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Valor númerico incorrecto";
            }
        }
        if ($this.hasClass('valAlpha')){
            p = !validators.IsAlpha($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Formato de texto inválido";
            }
        }
        if ($this.hasClass('valNitGT')){
            p = !validators.IsNitGT($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Nit ingresado no es correcto";
            }
        }
        if ($this.hasClass('valDpiGT')){
            p = !validators.IsDpiGT($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "DPI ingresado no es correcto";
            }
        }
        if ($this.hasClass('valPlacaGT')){
            p = !validators.IsPlacaGT($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Placa ingresada no es correcta";
            }
        }
        if ($this.hasClass('valCCard')){
            p = !validators.IsCCard($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Formato de tarjeta de crédito inválido";
            }
        }
        if ($this.hasClass('valFechaV')){
            p = !validators.isFechaV($this.val());
            if (!p){
            	if (alert_text != "") alert_text = alert_text + ", ";
            	alert_text = alert_text + "Formato de fecha inválido";
            }
        }
    } else {
        p = false;
        if (campo.indexOf("Debe llenar los campos requeridos") == -1)
        	alert_text = "Debe llenar los campos requeridos";
    }
	
    if (!p){
    	
    	if ((campo != "") && (alert_text != "")) 
    		campo = campo + ", ";
    	campo = campo + alert_text;
    	$(validator).html(campo);
        return p;
    }

}
