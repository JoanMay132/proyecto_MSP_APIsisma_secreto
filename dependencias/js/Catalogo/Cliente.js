function addCliente()
{
	
	var formData = new FormData($('#form-cliente')[0]);
	$.ajax({
		type: "POST",
		data: formData,
		url: "controller/addCliente.php",
		dataType: 'json',
		processData: false,
  		contentType: false,
		success:function(respuesta){
			
			var mensaje = $('#mensaje');
			var display = '';
			//respuesta = respuesta.trim();
			
				if(respuesta.Mensaje == "Guardado Exitoso"){
					display = '<div class="alert alert-success alert-dismissible fade show" role="alert">'+
					'<strong>Bien! </strong>'+respuesta.Mensaje+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					'<span aria-hidden="true">&times;</span>'+
					'</button></div>';

					// Crea el nuevo elemento
					var nuevoElemento = $('<a href="addCliente" class="btn-sm btn-link" name="guardar">Nuevo cliente</a>');
					var edit = $('<a href="editCustomer?customer='+respuesta.pkcliente+'" class="btn-sm btn-link" >Modificar</a>');

					// Encuentra el botón existente
					var botonExistente = $('#btn-guardar');

					// Inserta el nuevo elemento antes del botón existente
					nuevoElemento.insertBefore(botonExistente);
					edit.insertBefore(botonExistente);
					$('#btn-guardar').attr('disabled', true);
				}
				
			else{
				//respuesta = JSON.parse(respuesta);
				display = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+
				'<strong>Error! </strong>Revise los siguientes errorres<br>';
				Object.entries(respuesta).forEach(function([clave, valor]) {
					display += clave + ": " + valor+"\n";
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

function editCustomer()
{
	var formData = new FormData($('#form-cliente')[0]);
	$.ajax({
		type: "POST",
		data: formData,
		url: "controller/editCustomer.php",
		dataType: 'json',
		processData: false,
  		contentType: false,
		success:function(respuesta){
			
			var mensaje = $('#mensaje');
			var display = '';
			//respuesta = respuesta.trim();
			
				if(respuesta.Mensaje == "Guardado Exitoso"){
					display = '<div class="alert alert-success alert-dismissible fade show" role="alert">'+
					'<strong>Bien! </strong>'+respuesta.Mensaje+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					'<span aria-hidden="true">&times;</span>'+
					'</button></div>';

					$('#table-view-depto').load(location.href + ' #table-depto');
				}
				
			else{
				//respuesta = JSON.parse(respuesta);
				display = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+
				'<strong>Error! </strong>Revise los siguientes errorres<br>';
				Object.entries(respuesta).forEach(function([clave, valor]) {
					display += clave + ": " + valor+"\n";
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

var in_depto = 1;
function adddepto()
{

	var fila = '<tr ondblclick="adddepto()" id="newdepto-'+in_depto+'">'+
				'<td><input type="text" name="ndepto[]" autocomplete="off" class="form-control" placeholder="Introduzca el nombre"></td>'+
				'<td style="text-align: center;">'+
				'<button class="btn btn-danger " style="width:20px;height:20px;position:relative;padding:0" id="'+in_depto+'" onclick="return deletedepto(this)"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash"></i></button></td></tr>';

	$('.table-depto').append(fila)

	in_depto++;

	return false;
}

function deletedepto(data){
	var button_id = $(data).attr("id");

	$('#newdepto-'+button_id+'').remove();

}
function deleteDeptoReg(data,depto){

	var button_id2 = $(data).attr("id"); 
	$('#deptoReg-'+button_id2+'').remove();
	$.ajax({
		type: "POST",
		data: {"pkdepto": depto},
		url: "controller/deleteDepto.php",
		success:function(respuesta){
			respuesta = respuesta.trim();
			console.log(respuesta);
		}
	});	

}
