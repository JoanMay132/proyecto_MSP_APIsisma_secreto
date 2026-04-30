function addDepto()
{
    $.ajax({
		type: "POST",
		data: $('#form-deptocli').serialize(),
		dataType: "json",
		url: "controller/Deptocli.php",
		success:function(respuesta){
            if(('Success' in respuesta) == true){
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: respuesta.Success,
                    showConfirmButton: false,
                    timer: 1500,
                  });
                   //Actualiza la lista de los departamentos
                   refreshDepto();

                   $('#display-depto').load(location.href + ' .table');//actualizas el div
                   
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

var in_rev = 1;
function addDeptocli(){
    let fila = '<tr ondblclick="return addDeptocli();">'+
                '<td><input type="text" name="nombre[]" class="form-control" placeholder="Vacio" autocomplete="nope"></td>'+
         

    '<td><button class="btn btn-info" style="width:20px;height:20px;position:relative;padding:0" id="'+in_rev+'" onclick="return eliminarFila(this);"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash-o"></i></button></td>'+
    '</tr>';

   $('.table-deptocli').append(fila)

   in_rev++;
}

function eliminarFila(button) {
    // Obtén la fila a la que pertenece el botón
    let fila = button.parentNode.parentNode;
    
    // Obtén el tbody que contiene la fila
    let tbody = fila.parentNode;

    // Elimina la fila del tbody
    tbody.removeChild(fila);
    
}

function EliminarFilaReg(fila,depto) {
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
                data: {"pkdepto": depto},
                url: "controller/deleteDepto.php",
                success:function(respuesta){
                    respuesta = respuesta.trim();
                    
                    eliminarFila(fila);
                    refreshDepto(); //Actualiza la lista de los departamentos
                }
            });	
			}
	  })
}

function refreshDepto()
{
  //Obtiene el valor del select seleccionado
  let ventana = window.opener; 
  let valor = ventana.document.getElementById('listClientes').value ?? null;

  $.ajax({
    type: "POST",
    data: {"cliente": valor},
    url: "../Trazabilidad/Cargas/loadDepto.php",
    dataType: "json",
    success:function(respuesta){ 
        var opcionesSelect = ventana.document.getElementById('area'); //Obtiene el id del select
        opcionesSelect.options.length = 1; //Crea la primera opción vacia
        if(respuesta == null){return false;}
        respuesta.forEach(function(dato) {
            var opcion = document.createElement('option');
            opcion.value = dato[0]; // Asigna el valor del dato a la opción
            opcion.text = dato[1]; // Asigna el texto visible de la opción
            opcionesSelect.appendChild(opcion); // Agrega la opción al elemento <select>
          });     
    }
});
}