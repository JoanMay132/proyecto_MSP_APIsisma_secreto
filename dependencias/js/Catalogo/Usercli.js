var in_user = 1;
function addusercustomer(data)
{
    
	var fila = '<tr ondblclick=\'addusercustomer('+ JSON.stringify(data)+')\' id="newusercustomer-'+in_user+'" >'+
	'<td><input type="text" name="titulo[]" class="form-control" placeholder="Vacio" autocomplete="off"></td>'+
	'<td><input type="text" name="nombre[]" class="form-control" placeholder="Vacio" autocomplete="nope"></td>'+
	'<td><select class="form-control" name="deptocli[]"><option value=""></option>';
    for (let index = 0; index < data.length; index++) {
        var option = data[index];
        fila += '<option value="'+option.pkdeptocli+'">'+option.nombre+'</option>';
        
    }
 
     fila += '</select></td>'+
	'<td><input type="text" name="puesto[]" class="form-control" placeholder="Vacio" autocomplete="off"></td>'+
	'<td><button onclick="return deleteusercustomer(this)" id="'+in_user+'" class="btn btn-info" style="width:20px;height:20px;position:relative;padding:0"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash"></i></button></td></tr>';

	$('.table-usercustomer').append(fila)

	in_user++;

	return false;
}

function deleteusercustomer(data){
	var button_id = $(data).attr("id");

	$('#newusercustomer-'+button_id+'').remove();

}

function deleteusercustomerReg(data,user){
	var button_id = $(data).attr("id");

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
				data: {"user": user},
				url: "controller/delUsercustomer.php",
				success:function(respuesta){
					$('#newusercustomerReg-'+button_id+'').remove();
					refreshUser();
				}
			});
			}
	  })
return false;
}

function addUser()
{
	var formData = new FormData($('#form-usercustom')[0]);
	$.ajax({
		type: "POST",
		data: formData,
		dataType: "json",
		url: "controller/addUserCustom.php",
		processData: false,
  		contentType: false,
		success:async function(respuesta){
			if(('success' in respuesta) == true){
                await Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: respuesta.success,
                    showConfirmButton: false,
                    timer: 1500,
                  });   
            }			
			if(window.opener){
				refreshUser();
			}
			
			$('#display-user').load(location.href + ' .table');//actualizas el div
		}
	});


	return false;
}

function refreshUser()
{
  //Obtiene el valor del select seleccionado
  let ventana = window.opener; 
  let valor = ventana.document.getElementById('listClientes').value ?? null;
  if(valor){
	ventana.user = [];
  }

  $.ajax({
    type: "POST",
    data: {"client": valor},
    url: "../Trazabilidad/Cargas/loadUsercustomer.php",
    dataType: "json",
    success:function(respuesta){ 
		if(respuesta == null){return false;}
        let opcionesSelect = ventana.document.getElementsByClassName('listUsercustomer'); //Obtiene el id del select}
		opcionesSelect = Array.from(opcionesSelect);
		opcionesSelect.forEach((selec) =>{
			selec.options.length = 1; //Crea la primera opción vacia
        
        respuesta.forEach(function(dato) {
            var opcion = document.createElement('option');
            opcion.value = dato[0]; // Asigna el valor del dato a la opción
            opcion.text = dato[1]; // Asigna el texto visible de la opción
            selec.appendChild(opcion); // Agrega la opción al elemento <select>
			if(window.opener && ventana.user){
				ventana.user.push({"pkusercli":dato[0],"nombre":dato[1],"depto" : dato[2],"cargo" : dato[3] });
			}
			
          }); 
		});
            
    }
});
}
