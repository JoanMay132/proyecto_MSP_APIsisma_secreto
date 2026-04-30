function addEmployee()
{
	
	var formData = new FormData($('#form-employee')[0]);
	$.ajax({
		type: "POST",
		data: formData,
		url: "controller/addEmployee.php",
		dataType: 'json',
		processData: false,
  		contentType: false,
		success:async function(respuesta){
			var mensaje = $('#mensaje');
			//var display = '';
			//respuesta = respuesta.trim();
			
				if(respuesta.Mensaje == "Guardado Exitoso"){
					// display = '<div class="alert alert-success alert-dismissible fade show" role="alert">'+
					// '<strong>Bien! </strong>'+respuesta.Mensaje+
					// '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					// '<span aria-hidden="true">&times;</span>'+
					// '</button></div>';
					await Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: respuesta.Mensaje,
						showConfirmButton: false,
						timer: 1500,
					  });

                    location.href = 'addUser.php?employee='+respuesta.pkemployee;
				}
				else if((respuesta.Error)){
					await Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: respuesta.Error,
						showConfirmButton: false,
						timer: 2000,
					  });
				}
				
			else{
				//respuesta = JSON.parse(respuesta);
				display = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+
				'<strong>Error! </strong>Revise los siguientes errores:<br>';
				Object.entries(respuesta).forEach(function([clave, valor]) {
					display += "<strong>"+clave + ": </strong>" + valor+"<br>";
				  });
				display += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
				  '<span aria-hidden="true">&times;</span>'+
				'</button></div>';
			}

			
			mensaje.html(display);	
		}
	});


	return false;
}



function deleteEmployee(data,depto){

	var button_id2 = $(data).attr("id"); 
	$('#deptoReg-'+button_id2+'').remove();
	$.ajax({
		type: "POST",
		data: {"pkdepto": depto},
		url: "controller/deleteDepto.php",
		success:function(respuesta){
			respuesta = respuesta.trim();

		}
	});	

}

function selecciona(){
	let suc =  document.getElementById('sucursalView').value;  
	  location.href = "employees?suc="+suc;
  
  }

  function AddEmployee(URL) {
    window.open(URL, "Alta Empleados", "width=800,height=600,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0");
  }

  function user(URL) {
    window.open(URL, "Dato usuario", "width=600,height=600,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0");
  }

  function Permisos(URL) {
    const ventanaAncho = screen.width;
    const ventanaAlto = screen.height;

    let izquierda = Math.round((ventanaAncho - 500) / 2);
    let arriba = Math.round((ventanaAlto - 550) / 2);
    window["PERMISOS"] ? window["PERMISOS"].focus() : window.open(URL, "PERMISOS", "width=500,height=550,scrollbars=yes,left=" + izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");
  }