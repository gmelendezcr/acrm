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
