function addUser()
{
    
    $.ajax({
		type: "POST",
		data: $('#form-user').serialize(),
		dataType: "json",
		url: "controller/User.php",
		success:function(respuesta){
            if(('Success' in respuesta) == true){
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: respuesta.Success,
                    showConfirmButton: false,
                    timer: 1500,
                  })
            }else{
                 display = "<strong>Revise los siguientes errores:</strong> <br>";
                Object.entries(respuesta).forEach(function([clave, valor]) {
					display += "<div style='font-size:14px'><strong>"+clave + ": </strong>" + valor+"</div><br>";
				  });

                  Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    html:display,
                    showConfirmButton: true,
                    width:'100%',
                    position:'center',
                    //timer: 1500,
                  })
            }
		}
	});

	return false;
}

function updateNombre()
{ 
    $.ajax({
		type: "POST",
		data: $('#form-updatenombre').serialize(),
		dataType: "json",
		url: "controller/User.php",
		success:function(respuesta){
            if(('Success' in respuesta)){
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: respuesta.Success,
                    showConfirmButton: false,
                    timer: 1500
                  });
            }else{
                 display = "<strong>Revise los siguientes errores:</strong> <br>";
                Object.entries(respuesta).forEach(function([clave, valor]) {
					display += "<div style='font-size:14px'><strong>"+clave + ": </strong>" + valor+"</div><br>";
				  });

                  Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    html:display,
                    showConfirmButton: true,
                    width:'100%',
                    position:'center'
                    //timer: 1500,
                  })
            }
		}
	});

	return false;
}

function updatePassword()
{   
    $.ajax({
		type: "POST",
		data: $('#form-updatepassword').serialize(),
		dataType: "json",
		url: "controller/User.php",
		success:function(respuesta){
            if(('Success' in respuesta) == true){
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: respuesta.Success,
                    showConfirmButton: false,
                    timer: 1500,
                  })
            }else{
                 display = "<strong>Revise los siguientes errores:</strong> <br>";
                Object.entries(respuesta).forEach(function([clave, valor]) {
					display += "<div style='font-size:14px'><strong>"+clave + ": </strong>" + valor+"</div><br>";
				  });

                  Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    html:display,
                    showConfirmButton: true,
                    width:'100%',
                    position:'center',
                    //timer: 1500,
                  })
            }
		}
	});

	return false;
}

function updateCorreo()
{   
    $.ajax({
		type: "POST",
		data: $('#form-correo').serialize(),
		dataType: "json",
		url: "controller/User.php",
		success:function(respuesta){
            if(('Success' in respuesta) == true){
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: respuesta.Success,
                    showConfirmButton: false,
                    timer: 1500
                  });
            }else{
                 display = "<strong>Revise los siguientes errores:</strong> <br>";
                Object.entries(respuesta).forEach(function([clave, valor]) {
					display += "<div style='font-size:14px'><strong>"+clave + ": </strong>" + valor+"</div><br>";
				  });

                  Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    html:display,
                    showConfirmButton: true,
                    width:'100%',
                    position:'center'
                    //timer: 1500,
                  })
            }
		}
	});

  return false;
}

  function updateRol()
{   
    $.ajax({
		type: "POST",
		data: $('#form-rol').serialize(),
		dataType: "json",
		url: "controller/User.php",
		success:function(respuesta){
            if(('Success' in respuesta) == true){
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: respuesta.Success,
                    showConfirmButton: false,
                    timer: 1500,
                  })
            }else{
                 display = "<strong>Revise los siguientes errores:</strong> <br>";
                Object.entries(respuesta).forEach(function([clave, valor]) {
					display += "<div style='font-size:14px'><strong>"+clave + ": </strong>" + valor+"</div><br>";
				  });

                  Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    html:display,
                    showConfirmButton: true,
                    width:'100%',
                    position:'center',
                    //timer: 1500,
                  })
            }
		}
	});

	return false;
}



function Valida(user)
{
	$.ajax({
		type: "POST",
		data: {"user": user},
		//dataType: "json",
		url: "controller/userval.php",
		success:function(respuesta){
            $('#valid_user').html(respuesta);
		}
	});

	return false;
}


function validaPass()
{
    csna= $('#contrasena1').val();
    pass1= $('#valida_pass1');
    pass1= $('#valida_pass1').html("");

    if(csna != ''){
        if(csna.length < 8){
            pass1.html("<span style='color:red'>La constraseña debe tener como minimo 8 caracteres</span>")
            return false;
        }else{
            pass1.html("<span style='color:green'>Contraseña valida</span>");
            return true;
        }
    }else{
        pass1.html("<span style='color:red'>El campo contraseña es obligatorio</span>");
        return false;
    }

}

function validaPass2()
{
    csna= $('#contrasena1').val();
    csna2= $('#contrasena2').val();
    pass2= $('#valida_pass2');
    pass2= $('#valida_pass2').html("");

    if(csna != csna2 ){
        pass2.html("<span style='color:red'>Error: La contraseña no coincide</span>");
        return false
    }

    return true;

}

function validarCorreo(correo) {
    $('#valida_correo').html("");
    
    if(correo == '') return false;
        // Expresión regular para validar el formato del correo electrónico
        var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        // Verificar si el correo cumple con el formato
        if (regexCorreo.test(correo)) {
        return true;
        } else {
        $('#valida_correo').html("<span style='color:red'>El correo no es valido</span>");
        return false;
        }
    
  }