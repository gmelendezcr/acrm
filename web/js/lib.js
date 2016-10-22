$( document ).ready( function () {
	$.validator.addMethod("valueNotEquals", function(value, element, arg){
    	return arg != value;
	});
	
	/*--------------------------------------------------------------------------------
	Validación de reportes
	--------------------------------------------------------------------------------*/
	//Cuantitativo cualitativo
	$( "#form_reporte_cuantitativo_cualitativo" ).validate( {
		rules: {
			anno: {
    			required: true,
        		date: true
    		},
    		centrosEducativo: { valueNotEquals: "0" },
    		t_reporte: { valueNotEquals: "0" },
    		formato: { valueNotEquals: "0" },
		},
		messages: {
	    	anno: {
    			required:"El año es requerido",
        		date:"Formato incorrecto ejem: 20016",
    		},
			centrosEducativo: { valueNotEquals: "Por favor seleccione un centro educativo" },
			t_reporte: { valueNotEquals: "Por favor seleccione un tipo de reporte" },
			formato: { valueNotEquals: "Por favor seleccione un formato de salida" },
		},
		highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	
	$( "#form_reporte_x_anio" ).validate( {
		rules: {
			anno: {
    			required: true,
        		date: true
    		},
    		centrosEducativo: { valueNotEquals: "0" },
    		t_reporte: { valueNotEquals: "0" },
    		formato: { valueNotEquals: "0" },
    		estado: { valueNotEquals: "0" },
		},
		messages: {
	    	anno: {
    			required: "El año es requerido",
        		date: "Formato incorrecto ejem: 20016",
    		},
			centrosEducativo: { valueNotEquals: "Por favor seleccione un centro educativo" },
			t_reporte: { valueNotEquals: "Por favor seleccione un tipo de reporte" },
			formato: { valueNotEquals: "Por favor seleccione un formato de salida" },
			estado: { valueNotEquals:"Por favor seleccione el estado" },
		},
		highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	$( "#form_valida_rango_anio" ).validate( {
		rules: {
			anno: {
    			required: true,
        		date: true
    		},
    		anno_rango: {
    			required: true,
        		date: true
    		},
    		t_reporte: { valueNotEquals: "0" },
    		formato: { valueNotEquals: "0" },
		},
		messages: {
	    	anno: {
    			required:"El año es requerido",
        		date: "Formato incorrecto ejem: 2016",
    		},
    		anno_rango: {
    			required:"El año es requerido",
        		date: "Formato incorrecto ejem: 2016",
    		},
			t_reporte: { valueNotEquals: "Por favor seleccione un tipo de reporte" },
			formato: { valueNotEquals: "Por favor seleccione un formato de salida" },
		},
		highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	$.validator.addMethod(
    "fecha_",
    function(value, element) {
        // put your own logic here, this is just a (crappy) example
        return value.match(/^\d\d?\-\d\d?\-\d\d\d\d$/);
    },
    "Formato incorrecto, forma correcta 20-10-2016"
);
	
	$( "#rep_cedu_estado_actual" ).validate( {
		rules: {
			estado_acred: { valueNotEquals: "0" },
    		fecha: {
    			required: true,
        		fecha_: true
    		},
		},
		messages: {
	    	estado_acred: { valueNotEquals: "Por favor seleccione un estado de salida" },
    		fecha: {
    			required:"La fecha es requerida",
        		date: "Formato incorrecto ejem: 20-10-2016",
    		},
		},
		highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	
	
	
	/*--------------------------------------------------------------------------------
	Validación para centros educativos
	--------------------------------------------------------------------------------*/
	//Registro
	$( "#form_validar" ).validate( {
		rules: {
	    	codigo: "required",
			nombre: "required",
			total_alumnos: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			municipio: { valueNotEquals: "0" },
			jornada: { valueNotEquals: "0" },
			tamanno: { valueNotEquals: "0" }
		},
		messages: {
	    codigo: "Código requerido",
		  nombre: "El nombre es requerido",
		  total_alumnos: {
				required: "El total de alumnos es requerido",
				number: "Debe ser un número"
			},
			municipio: { valueNotEquals: "Por favor seleccione un municipio" },
			jornada: { valueNotEquals: "Por favor seleccione una jornada" },
			tamanno: { valueNotEquals: "Por favor seleccione un tamaño" }
		},
		 highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	//Editar
	$( "#form_validar_editar" ).validate( {
		rules: {
			["CentroEducativoType[codCentroEducativo]"]: "required",
			["CentroEducativoType[nbrCentroEducativo]"]: "required",
			["CentroEducativoType[totalAlumnos]"]: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			["CentroEducativoType[idMunicipio]"]: { valueNotEquals: "null" },
			["CentroEducativoType[idJornadaCentroEducativo]"]: { valueNotEquals: "null" },
			["CentroEducativoType[idTamannoCentroEducativo]"]: { valueNotEquals: "null" }
		},
		messages: {
	    	["CentroEducativoType[codCentroEducativo]"]: "Código requerido",
			["CentroEducativoType[nbrCentroEducativo]"]: "El nombre es requerido",
			["CentroEducativoType[totalAlumnos]"]: {
				required: "El total de alumnos es requerido",
				number: "Debe ser un número"
			},
			["CentroEducativoType[idMunicipio]"]: { valueNotEquals: "Por favor seleccione un municipio" },
			["CentroEducativoType[idJornadaCentroEducativo]"]: { valueNotEquals: "Por favor seleccione una jornada" },
			["CentroEducativoType[idTamannoCentroEducativo]"]: { valueNotEquals: "Por favor seleccione un tamaño" }
		},
		highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	/*--------------------------------------------------------------------------------
	Validación para cuotas
	--------------------------------------------------------------------------------*/	
	//Registro
	$( "#form_validar_cuota_agregar" ).validate( {
	    rules: {
			grado: { valueNotEquals: "0" },
			matricula: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			cuota: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
	    	anno: {
        		required: true,
        		date: true
    		}
		},
		messages: {
			grado: { valueNotEquals: "Por favor seleccione un grado" },
			matricula: {
				required: "La matrícula es requerido",
				number: "Debe ser un número"
			},
			cuota: {
				required: "La cuota es requerida",
				number: "Debe ser un número"
			},
			anno: {
				required: "El año es requerido",
				date: "Debe ser ejem: 2016"
			},
		},
		highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	//Editar
	$('#form_validar_cuota_editar').validate({
    	rules: {
    		["CuotaAnualPorGradoEscolarPorCentroEducativoType[matricula]"]: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[monto]"]: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[anno]"]: {
    			required: true,
    			date: true
			}
    	},
    	messages: {	
    		["CuotaAnualPorGradoEscolarPorCentroEducativoType[matricula]"]: {
				required: "La matrícula es requerida",
				number: "Debe ser un número"
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[monto]"]: {
				required: "La cuota es requerida",
				number: "Debe ser un número"
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[anno]"]: {
				required: "El año es requerido",
				date: "Debe ser ejem: 2016"
			}
		},
    	highlight: function(element) {
        	$(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
    	},
    	unhighlight: function(element) {
        	$(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
    	},
    	errorElement: 'span',
    	errorClass: 'help-block',
    	errorPlacement: function(error, element) {
        	if(element.parent('.input-group').length) {
            	error.insertAfter(element.parent());
        	} else {
            	error.insertAfter(element);
        	}
    	}
	});
	
	/*--------------------------------------------------------------------------------
	Validación de usuarios
	--------------------------------------------------------------------------------*/
	//Registro editar
	$( "#form_registro_usuario" ).validate( {
		rules: {
    		["usuario[username]"]: {
	            required: true,
	            minlength: 4,
	        },
	        ["usuario[nombres]"]: {
	            required: true,
	        },
	        ["usuario[email]"]: {
	            required: true,
	            email: true
	        },
	        ["usuario[password]"]:{
	            required: true,
	            minlength: 4,
	        },
	        ["usuario[passwordConfirmation]"]:{
	            required: true,
	            minlength: 4,
	            equalTo : "#usuario_password"
	        },
	        ["usuario[roles][]"]:{
	            required: true,
	        },
	    },
	    messages: {
        	["usuario[username]"]: {
	            required: "Nombre de usuario es requerido",
	            minlength: "El nombre de usuarios debe superar los 4 caracteres"
	        },
	        ["usuario[nombres]"]: {
	            required: "Su nombre es requerido",
	        },
	        ["usuario[email]"]: {
	            required: "Digite su cuenta de correo electrónico, esta le permitira recuperar su clave.",
	            email: "Digite una cuenta de correo válida"
	        },
	        ["usuario[password]"]: {
	            required: "Por favor digite su clave de acceso",
	            minlength: "La clave debe superar los 4 caracteres"
	        },
        	["usuario[passwordConfirmation]"]: {
	            required: "Por favor repita su clave de acceso",
	            minlength: "La clave debe superar los 4 caracteres",
	            equalTo:"Las claves no coinciden, por favor verificar"
	        },
	        ["usuario[roles][]"]:{
	            required: "Por favor seleccione un rol",
	        },
	    },
	    highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error').removeClass( "has-success" );
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').addClass( "has-success" ).removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	
});

/*--------------------------------------------------------------------------------
Msj informativo title
--------------------------------------------------------------------------------*/
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
});

/*--------------------------------------------------------------------------------
Validad formulario Digitar / corregir formulario
--------------------------------------------------------------------------------*/
function digitarCorregirCargarFormulario(){
	if($('#centrosEducativos').val()=='' || $('#formularios').val()=='' || $('#lugarAplicacion').val()=='' ||  $('#fechaAplicacion').val()==''){
    	return false;
	}
	return true;
}

var appSf='/app_dev.php';
function cargarDepartamentos(comboDepartamento,selected,comboMunicipio,municipioHidden){
	$.ajax({
    	method: 'GET',
    	url: appSf + '/departamento/comboDepartamento',
    	success: function(html){
    		comboDepartamento.html(html);
    		comboDepartamento.val(selected);
    		if(!comboMunicipio.val() && (municipioHidden==null || municipioHidden.val())){
        		cargarMunicipios(comboDepartamento.val(),comboMunicipio,(municipioHidden?municipioHidden.val():null));
    		}
    	}
	});
}

function cargarMunicipios(idDepartamento,comboMunicipio,selected){
	$.ajax({
    	method: 'POST',
    	url: appSf + '/municipio/comboMunicipio',
    	data: {
    		'idDepartamento': idDepartamento,
    		'idMunicipio': selected
		},
    	success: function(html){
    		comboMunicipio.html(html);
    		comboMunicipio.val(selected);
    	}
	});
}

function marcarRevisar(control,idFormularioPorCentroEducativoRevisar,idPregunta,idOpcionRespuesta=null){
	$.ajax({
    	method: 'POST',
    	url: appSf + '/formularioPorCentro/respuestaRevisar',
    	data: {
    		'idFormularioPorCentroEducativoRevisar': idFormularioPorCentroEducativoRevisar,
    		'idPregunta': idPregunta,
    		'idOpcionRespuesta': idOpcionRespuesta
    	},
    	success: function(html){
	    	if(html=='S'){
	        	control.removeClass('label-success');
	        	control.addClass('label-warning');
	    	}else if(html=='N'){
	        	control.addClass('label-success');
	        	control.removeClass('label-warning');
	    	}else{
	        	//control.removeClass('label-success');
	    	}
    	}
	});
}

/*--------------------------------------------------------------------------------
Advertencia
--------------------------------------------------------------------------------*/
$("#warning-alert").fadeTo(3000, 6000).slideUp(6000, function(){
	$("#warning-alert").slideUp(6000);
});

/*--------------------------------------------------------------------------------
Cargar de archivos
--------------------------------------------------------------------------------*/	
$(function(){
    $("input[name='archivo']").on("change", function(){
        var formData = new FormData($("#formulario")[0]);
        $.ajax({
            url: appSf + '/lista-centros-educativos/archivo-cuota-cargar',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(datos){
            	$("#respuesta").html(datos);
            }
        });
    });
});



  
  