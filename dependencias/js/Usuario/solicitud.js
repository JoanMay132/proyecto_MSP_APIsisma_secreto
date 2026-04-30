function updateStatus(solicitud,status)
{
	$.ajax({
		type: "POST",
		data: {"status":status,"solicitud":solicitud},
		url: "Controller/solicitud.php",
		dataType: 'json',
		success:async function(respuesta){
				if(respuesta.success){
                    $('.table-responsive').load(location.href + ' .display-table');//actualizas el div
                }else{
                    await Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text: respuesta.error,
                        showConfirmButton: false,
                        width: 'auto',
                        position: 'center',
                        timer: 1800,
                    });
                }
		}
	});


	return false;
}

function deleteSesion(solicitud)
{
    Swal.fire({
        title: "¿Desea eliminar la sesión?",
        text: "¡Cuando el usuario intente acceder, se enviara de nuevo la solicitud!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
      }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                data: {"solicitud":solicitud},
                url: "Controller/deleteSesion.php",
                //dataType: 'json',
                success:async function(respuesta){
                        if(respuesta == 1){
                        
                            Swal.fire({
                                title: "Eliminado",
                                text: "Se ha eliminado la sesión.",
                                icon: "success"
                              });
                            $('.table-responsive').load(location.href + ' .display-table');//actualizas el div
                         }
                }
            });
          
        }
      });
	


	return false;
}


function updateprivileges(){
    const path = window.location.href;
    let ruta = path.includes('main') || path.includes('main.php') ? "Usuario/permisosroot.php" : "../Usuario/permisosroot.php"
    $.ajax({
		type: "POST",
		data: {"refresh":true},
		url: ruta,
		dataType: 'json',
		success:function(respuesta){
				if(respuesta.success){
                    
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        html: respuesta.success,
                        showConfirmButton: false,
                        width: 'auto',
                        position: 'center',
                        timer: 3500,
                    });
                }
		}
	});
    return false;
}