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
})
