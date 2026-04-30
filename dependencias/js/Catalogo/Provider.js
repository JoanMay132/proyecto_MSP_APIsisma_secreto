function addProvider()
{
	var formData = new FormData($('#form-provider')[0]);

	$.ajax({
		type: "POST",
		data: formData,
		dataType: "json",
		url: "controller/addProvider.php",
		processData: false,
  		contentType: false,
		success:function(respuesta){
			var mensaje,tipo = '';
			if(('Success' in respuesta) == true && (respuesta.Success.includes('Guardado') || respuesta.Success.includes('actualizados'))){
				mensaje = respuesta.Success
				tipo = 'success';
				if (window.opener) {
						window.opener.actualizarGetProvide(respuesta.sucursal);
				  }
				
			}else if(('Error' in respuesta) == true){
				mensaje = respuesta.Error;
				tipo = 'error';
			}
			else{
				mensaje = respuesta.Campos_requeridos;
				tipo = 'error';
			}
			Swal.fire({
				position: 'top-end',
				icon: tipo,
				title: mensaje,
				showConfirmButton: true,
				
			  })
			}
	});


	return false;
}

function deleteprovider(provider)
{
	Swal.fire({
		title: '¿Confirma eliminar el regitro?',
		text: "No podra revertir esta acción!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
	  }).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				type: "POST",
				data: {"provider":provider},
				url: "controller/deleteProvider.php",
					success:function(respuesta){
						$('#table-provide').load('controller/getProvide.php');
						Swal.fire(
							'Eliminado!',
							'El registro se ha eliminado.',
							'success'
						  )	
					}
				});
			}
	  })


	return false;
}

function selecciona(sucursal) {
	
	$('#table-provide').load('controller/getProvide?suc='+sucursal);
	
	
	let urlActual = window.location.href;
	let newUrl = urlActual.replace(/suc=.*/,"suc="+sucursal);

	window.history.replaceState({},"",newUrl);

	
  }

  function filtro(data, columna) {
	var value = $(data).val().toLowerCase();
	$("#proveedor tr").each(function() {
	  let found = false;
	  $(this).find('#' + columna).each(function() {
		if ($(this).text().toLowerCase().indexOf(value) > -1) {
		  found = true;
		  return false;
		}
	  });
	  $(this).toggle(found);
	});
  }