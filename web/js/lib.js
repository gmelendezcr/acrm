/*
--------------------------------------------------------------------------------
Inicia valición de formularios
--------------------------------------------------------------------------------
*/
$( document ).ready( function () {
  $.validator.addMethod("valueNotEquals", function(value, element, arg){
    return arg != value;
  });
  
  
$('#form_publico').validate({
        rules: {
            criterio: {
                minlength: 4,
                required: true
            },
        },
        messages: {
		  criterio: {
				required: "El nombre del centro educativo es requerido para la búsqueda",
				minlength: "El nombre del centro educativo debe contener más de tres caracteres"
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
})
  
  
  
  
  
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
		errorElement: "em",
		errorPlacement: function ( error, element ) {
		  // Add the `help-block` class to the error element
		  error.addClass( "help-block" );

		  if ( element.prop( "type" ) === "checkbox" ) {
		    error.insertAfter( element.parent( "label" ) );
		  } else {
		    //error.insertAfter( element );
		  }
		},
		highlight: function ( element, errorClass, validClass ) {
		  $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
		}
	});
	
	//editar
	
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
		errorElement: "em",
		errorPlacement: function ( error, element ) {
		  // Add the `help-block` class to the error element
		  error.addClass( "help-block" );

		  if ( element.prop( "type" ) === "checkbox" ) {
		    error.insertAfter( element.parent( "label" ) );
		  } else {
		    error.insertAfter( element );
		  }
		},
		highlight: function ( element, errorClass, validClass ) {
		  $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
		}
	});
	
	
	//Cuotas
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
		errorElement: "em",
		errorPlacement: function ( error, element ) {
		  // Add the `help-block` class to the error element
		  error.addClass( "help-block" );

		  if ( element.prop( "type" ) === "checkbox" ) {
		    error.insertAfter( element.parent( "label" ) );
		  } else {
		    //error.insertAfter( element );
		  }
		},
		highlight: function ( element, errorClass, validClass ) {
		  $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
		}
	});
	
	
	
	
	
	
});

//--->Fin


function checkForm(form){
    re = /^\w+$/;
    
//usuario[password]
//usuario[passwordConfirmation]

    if(form.usuario[password].value != "" && form.usuario[password].value == form.usuario[passwordConfirmation].value) {
      if(form.pwd1.value.length < 6) {
        alert("Error: La contraseña debe contener al menos seis caracteres!");
        form.usuario[password].focus();
        return false;
      }
     
      re = /[0-9]/;
      if(!re.test(form.usuario[password].value)) {
        alert("Error: la contraseña debe contener al menos un número (0-9)!");
        form.usuario[password].focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(form.usuario[password].value)) {
        alert("Error: la contraseña debe contener al menos una letra minúscula (a-z)!");
        form.usuario[password].focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(form.usuario[password].value)) {
        alert("Error: la contraseña debe contener al menos una letra mayúscula (A-Z)!");
        form.usuario[password].focus();
        return false;
      }
    } else {
      alert("Error: Por favor, compruebe que ha introducido y confirmado su contraseña!");
      form.usuario[password].focus();
      return false;
    }

  
    return true;
  }
  
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})



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
      }
      else{
        //control.removeClass('label-success');
      }
    }
  });
}


$("#warning-alert").fadeTo(3000, 6000).slideUp(6000, function(){
    $("#warning-alert").slideUp(6000);
});


	/*function upload_archivo(){//Funcion encargada de enviar el archivo via AJAX
				$(".upload-msg").text('Cargando...');
				var inputFile = document.getElementById("archivo");
				var idCEDU = document.getElementById("archivo");
				var file = inputFile.files[0];
				var idCEDU = idCEDU;
				var data = new FormData();
				data.append('archivo',file);
				
				/*jQuery.each($('#fileToUpload')[0].files, function(i, file) {
					data.append('file'+i, file);
				});*/
							
		/*		$.ajax({
					url: "upload.php",        // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data: data, 			  // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					success: function(data)   // A function to be called if request succeeds
					{
						$(".upload-msg").html(data);
						window.setTimeout(function() {
						$(".alert-dismissible").fadeTo(500, 0).slideUp(500, function(){
						$(this).remove();
						});	}, 5000);
					}
				});
				
			}*/
	
	$(function(){
        $("input[name='archivo']").on("change", function(){
            var formData = new FormData($("#formulario")[0]);
            $.ajax({
                url: appSf + '/lista-centros-educativos/archivo-cuota-cargar',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos)
                {
                    $("#respuesta").html(datos);
                }
            });
        });
     });


  