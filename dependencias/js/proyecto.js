
/*$(document).ready(function() {
		    var refreshId =  setInterval( function(){
		    $('#table-service').load('#table-service');//actualizas el div
		   }, 2000 );

		   
		
});*/


//Inicio de pruebas

function addLevantamiento()
{
	var lev = $('#FormLevantamiento').serialize();
	console.log(lev);
	$.ajax({
		type: "POST",
		data: $('#FormLevantamiento').serialize(),
		url: "../pruebas/proceso/addLevantamiento.php",
		success:function(respuesta){
			respuesta = respuesta.trim();
			if(respuesta == 1){
				$('#tablenew').load(location.href + ' #table-service');
				Swal.fire({
					position: 'top-end',
					icon: 'success',
					title: 'Levantamiento creado exitosamente',
					showConfirmButton: false,
					timer: 1500
				  });
				  
				//$('#formAddproyecto')[0].reset();
			}else
			{
				alert("Error al guardar el proyecto, vuelva a intentarlo");
			}
		}
	});

	return false;
}


var inc = 1;
function addServiceLev(data)
{

	var fila = '<tr ondblclick=\'addServiceLev('+ JSON.stringify(data)+')\' id="newService'+inc+'">'+
	'<td style="width: 8%;"><input type="text" pattern="\\d+(\\.\\d+)?" name="pda[]"  class="form-control form-control-sm input-form" ></td>'+

	'<td style="width: 10%;"><input type="number" name="cantidad[]" class="form-control form-control-sm input-form" ></td>'+

	'<td style="width: 12%;"><select class="form-control form-control-sm input-form" name="unidad[] ><option value=""></option>';
	for(i = 0; i < data.length; i++){
		fila += '<option value="'+data[i]+'">'+data[i]+'</option>';
	}
	

	fila +='</select></td><td style="width: 50%">'+
	  '<textarea class="form-control form-control-sm input-form" rows="3" name="concepto[]"></textarea>'+
	'</td>'+
	'<td style="width: 10%;"><input type="text" class="form-control form-control-sm input-form" name="costo[]"></td>'+
	'<td style="width: 15%;"> <input type="text" name="dibujo[]" class="form-control form-control-sm input-form" value="" ></td>'+
	'<td><span onclick="return deleteService(this)" class="btn btn-danger btn_remove" id="'+inc+'">X</span></td>'+
 ' </tr>';

	$('#table-service').append(fila)

	inc++;

	return false;
}

function deleteService(data){
	var button_id = $(data).attr("id");
	var button_id2 = $(data).attr("data-id"); 
	$('#newService'+button_id+'').remove();
	$('#serviceReg'+button_id2+'').remove();

	var servicio = $("#pkservlevantamiento").val();
	$.ajax({
		type: "POST",
		data: {"serviciolev": servicio},
		url: "../pruebas/proceso/deleteLevantamiento.php",
		success:function(respuesta){
			respuesta = respuesta.trim();
			console.log(respuesta);
		}
	});	

}

function deleteServiceReg(data,servicio){

	var button_id2 = $(data).attr("data-id"); 
	$('#serviceReg'+button_id2+'').remove();
	$.ajax({
		type: "POST",
		data: {"serviciolev": servicio},
		url: "../pruebas/proceso/deleteLevantamiento.php",
		success:function(respuesta){
			respuesta = respuesta.trim();
			console.log(respuesta);
		}
	});	

}

//Fin de pruebas
function updateProyecto()
{
	$.ajax({
		type: "POST",
		data: $('#formUpdate').serialize(),
		url: "procesos/addProyecto.php",
		success:function(respuesta){
			respuesta = respuesta.trim();
			if(respuesta == 1){
				Swal.fire({
					position: 'top-end',
					icon: 'success',
					title: 'Proyecto Actualizado',
					showConfirmButton: false,
					timer: 1500
				  })
				

				//$('#formAddproyecto')[0].reset();
			}else
			{
				alert("Error al actualizar los datos");
			}
		}
	});

	return false;
}


function viewProject()
{
	$.ajax({
		type: "POST",
		data: $('#formBusqueda').serialize(),
		url: "vista.php",
		success:function(respuesta){
				$('#vista').html(respuesta);
		}
	});

	return false;
}


function deleteProject(idproyecto,nombre){

	Swal.fire({
	  title: "ELIMINAR",
	  text: nombre,
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, eliminar!'
	}).then((result) => {
 	 if (result.isConfirmed) {
  	$.ajax({
					type: "POST",
					data: "idproyecto="+idproyecto,
					url: "procesos/deleteProject.php",
					success:function(respuesta){
						respuesta = respuesta.trim();
						if(respuesta == 1){
							Swal.fire("","Se elimino con exito","success");
						}else{
							Swal.fire("","No se pudo eliminar","error");
						}
					}
				});

	  }
	})

}

