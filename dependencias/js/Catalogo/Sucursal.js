$(document).ready(function () {
	$('#table').DataTable({
		 paging: false,
		 ordering: true,
		 info: true,
	 });

	 getSucursal();
 });
 
function addSucursal()
{
	$.ajax({
		type: "POST",
		data: $('#form-sucursal').serialize(),
		url: "controller/addSucursal.php",
		success:function(respuesta){
			respuesta = respuesta.trim();
			if(respuesta == 1){
				getSucursal();
			}else
			{
				alert("Error al crear la sucursal, vuelva a intentarlo");
			}
		}
	});


	return false;
}

function getSucursal(){
	$.ajax({
		type: "POST",
		dataType:'json',
		url: "controller/getSucursal.php",
		success:function(data){
			$('#table').DataTable().clear();

			// Agregar los nuevos datos a la tabla
			$('#table').DataTable().rows.add(data);

			// Dibujar la tabla con los nuevos datos
			$('#table').DataTable().draw();

		}
	});


	return false;
}