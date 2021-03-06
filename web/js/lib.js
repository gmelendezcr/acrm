$( document ).ready( function () {
	$.validator.addMethod("valueNotEquals", function(value, element, arg){
    	return arg != value;
	});
	$.validator.addMethod(
    	"fecha_",
    	function(value, element) {
        	return value.match(/^\d\d?\-\d\d?\-\d\d\d\d$/);
    	},
    	"Formato incorrecto, ejemplo: 20-10-2016"
	);

	$( "#reporte_info_general" ).validate({
		rules: {
			centrosEducativo: { valueNotEquals: "0" },
		},
		messages: {
	    	centrosEducativo: { valueNotEquals: "Por favor seleccione un centro educativo" },
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
	
	$( "#reporte_actividad_usuario" ).validate( {
		rules: {
			fechaIni: {
				required: true,
        		fecha_: true
    		},
			fechaFin: {
				required: true,
	    		fecha_: true
			},
		},
		messages: {
	    	fechaIni: {
    			required: "La fecha es requerida",
    		},
    		fechaFin: {
				required: "La fecha es requerida",
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
	
	$("#form_reporte_x_anio" ).validate({
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
	
	$("#form_validar" ).validate( {
		rules: {
    		codigo: "required",
			nombre: "required",
			direccion: "required",
			total_alumnos: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			municipio: { valueNotEquals: "0" },
			jornada: { valueNotEquals: "0" },
			tamanno: { valueNotEquals: "0" },
			modalidades: { valueNotEquals: "0" },
			zonasCE: { valueNotEquals: "0" },
			totalDocentesMasculinos: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			totalDocentesFemeninos: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			}
		},
		messages: {
			codigo: "Código requerido",
			nombre: "El nombre es requerido",
			direccion: "La dirección es requerida",
			total_alumnos: {
				required: "El total de alumnos es requerido",
				number: "Debe ser un número",
				maxlength:"Límite superado"
			},
			municipio: { valueNotEquals: "Por favor seleccione un municipio" },
			jornada: { valueNotEquals: "Por favor seleccione una jornada" },
			tamanno: { valueNotEquals: "Por favor seleccione un tamaño" },
			modalidades: { valueNotEquals: "Por favor seleccione una modalidad" },
			zonasCE: { valueNotEquals: "Por favor seleccione una zona" },
			totalDocentesMasculinos: {
				required: "La cantidad de docentes es requerido",
				number: "Debe ser un número",
				maxlength:"Límite superado"
			},
			totalDocentesFemeninos: {
				required: "La cantidad de docentes es requerido",
				number: "Debe ser un número",
				maxlength:"Límite superado"
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
	
	$( "#form_validar_editar" ).validate( {
		rules: {
			["CentroEducativoType[codCentroEducativo]"]: "required",
			["CentroEducativoType[nbrCentroEducativo]"]: "required",
			["CentroEducativoType[direccionCentroEducativo]"]: "required",
			["CentroEducativoType[totalAlumnos]"]: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			["CentroEducativoType[totalDocentesMasculinos]"]: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			["CentroEducativoType[totalDocentesFemeninos]"]: {
				required: true,
				minlength: 1,
				maxlength: 15,
				number: true,
			},
			["CentroEducativoType[idMunicipio]"]: { valueNotEquals: "0" },
			["CentroEducativoType[idJornadaCentroEducativo]"]: { valueNotEquals: "0" },
			["CentroEducativoType[idTamannoCentroEducativo]"]: { valueNotEquals: "0" }
		},
		messages: {
	    	["CentroEducativoType[codCentroEducativo]"]: "Código requerido",
			["CentroEducativoType[nbrCentroEducativo]"]: "El nombre es requerido",
			["CentroEducativoType[direccionCentroEducativo]"]: "La dirección es requerida",
			["CentroEducativoType[totalAlumnos]"]: {
				required: "El total de alumnos es requerido",
				number: "Debe ser un número",
				maxlength:"Límite superado"
			},
			["CentroEducativoType[totalDocentesMasculinos]"]: {
				required: "El total de docentes es requerido",
				number: "Debe ser un número",
				maxlength:"Límite superado"
			},
			["CentroEducativoType[totalDocentesFemeninos]"]: {
				required: "El total de docentes es requerido",
				number: "Debe ser un número",
				maxlength:"Límite superado"
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
	
	$("#form_validar_cuota_agregar" ).validate( {
    	rules: {
			grado: { valueNotEquals: "valor0" },
			matricula: {
				required: true,
				minlength: 1,
				maxlength: 10,
				number: true,
			},
			cuota: {
				required: true,
				minlength: 1,
				maxlength: 10,
				number: true,
			},
			cantidadCuotas: {
				required: true,
				minlength: 1,
				maxlength: 3,
				number: true,
				min:10,
				max:12
			},
	    	anno: {
        		required: true,
        		date: true,
        		maxlength: 4,
    		}
		},
		messages: {
			grado: { valueNotEquals: "Por favor seleccione un grado" },
			matricula: {
				required: "La matrícula es requerido",
				number: "Debe ser un número",
				maxlength: "Límite de caracteres superado"
			},
			cuota: {
				required: "La colegiatura es requerida",
				number: "Debe ser un número",
				maxlength: "Límite de caracteres superado"
			},
			cantidadCuotas: {
				required: "La cantidad de colegiatura es requerida",
				number: "Debe ser un número",
				maxlength: "Límite de caracteres superado",
				min:"Digite la cantidad de colegiaturas, se permite entre 10 y 12 colegiaturas",
				max:"Solo se permite colegiaturas entre 10 y 12 colegiaturas."
			},
			anno: {
				required: "El año es requerido",
				date: "Debe ser ejem: 2016",
				maxlength: "Límite de caracteres superado"
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
	
	$('#form_validar_cuota_editar').validate({
    	rules: {
    		["CuotaAnualPorGradoEscolarPorCentroEducativoType[matricula]"]: {
				required: true,
				minlength: 1,
				maxlength: 10,
				number: true,
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[monto]"]: {
				required: true,
				minlength: 1,
				maxlength: 10,
				number: true,
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[cantidadCuotas]"]: {
				required: true,
				minlength: 1,
				maxlength: 3,
				number: true,
				min:10,
				max:12
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[anno]"]: {
    			required: true,
    			date: true
			}
    	},
    	messages: {	
    		["CuotaAnualPorGradoEscolarPorCentroEducativoType[matricula]"]: {
				required: "La matrícula es requerido",
				number: "Debe ser un número",
				maxlength: "Límite de caracteres superado"
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[monto]"]: {
				required: "La colegiatura es requerida",
				number: "Debe ser un número",
				maxlength: "Límite de caracteres superado"
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[cantidadCuotas]"]: {
				required: "La cantidad de colegiatura es requerida",
				number:"Debe ser un número",
				maxlength:"Límite de caracteres superado",
				min:"Digite la cantidad de colegiaturas, se permite entre 10 y 12 colegiaturas",
				max:"Solo se permite colegiaturas entre 10 y 12 colegiaturas."
			},
			["CuotaAnualPorGradoEscolarPorCentroEducativoType[anno]"]: {
				required: "El año es requerido",
				date: "Debe ser ejem: 2016",
				maxlength: "Límite de caracteres superado"
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
	            required: "Digite su cuenta de correo electrónico",
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
	
	$( "#form_cambio_clave" ).validate( {
		rules: {
	        ["fos_user_change_password_form[current_password]"]:{
	            required: true,
	            minlength: 4,
	        },
	        ["fos_user_change_password_form[plainPassword][first]"]:{
	            required: true,
	            minlength: 4
	        },
	        ["fos_user_change_password_form[plainPassword][second]"]:{
	            required: true,
	            minlength: 4,
	            equalTo : "#fos_user_change_password_form_plainPassword_first"
	        },
	        
	    },
	    messages: {
	        ["fos_user_change_password_form[current_password]"]: {
	            required: "Por favor digite su clave de acceso actual",
	            minlength: "La clave debe superar los 4 caracteres"
	        },
        	["fos_user_change_password_form[plainPassword][first]"]: {
	            required: "Por favor digite su nueva clave de acceso",
	            minlength: "La clave debe superar los 4 caracteres",
	        },
	        ["fos_user_change_password_form[plainPassword][second]"]: {
	            required: "Por favor repita su nueva clave de acceso",
	            minlength: "La clave debe superar los 4 caracteres",
	            equalTo:"Las claves no coinciden, por favor verificar"
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
	
	$( "#form_dig_corregir" ).validate( {
		rules: {
			centrosEducativos: { valueNotEquals: "0" },
			formularios: { valueNotEquals: "0" },
			fechaAplicacion: {
				required: true,
	    		fecha_: true
			},
			lugarAplicacion: "required",
		},
		messages: {
	    	centrosEducativos: { valueNotEquals: "Por favor seleccione un centro educativo" },
	    	formularios: { valueNotEquals: "Por favor seleccione un formulario" },
    		fechaAplicacion: {
				required: "La fecha es requerida",
    		},
    		lugarAplicacion: "Digite el lugar",
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
	$( "#AnioFormulario" ).validate( {
		rules: {
			anno: "required",
			formularios: { valueNotEquals: "0" },
		},
		messages: {
    		anno: "Digite el año",
    		formularios: { valueNotEquals: "Por favor seleccione un formulario" },
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

$(function () {
	$('[data-toggle="tooltip"]').tooltip()
});

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

function marcarRevisar(control,idFormularioPorCentroEducativoRevisar,idPregunta,idOpcionRespuesta){
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
	    	}
    	}
	});
}

$("#warning-alert").fadeTo(3000, 6000).slideUp(6000, function(){
	$("#warning-alert").slideUp(6000);
});