function addPermission(data){
    let form = document.getElementById('form-permisos');
    let fila = data.parentNode.parentNode;
    let sucursal = form.querySelector("[name='sucursal']").value;
    let usuario = form.querySelector("[name='usuario']").value;
    let control = fila.querySelector("[name='control[]']").value;
    let operacion = data.value;
    let permiso = '';
    let checked = data.checked;

   console.log(data.parentNode.querySelector("[name='permiso[]']") ?? '');

    if(!checked){
        permiso = data.parentNode.querySelector("[name='permiso[]']").value ?? '';
    }

    // const datas = {
    //     sucursal : sucursal,
    //     usuario : usuario,
    //     operacion : operacion
    // }

    // let dato = JSON.stringify(datas);

    $.ajax({
		type: "POST",
		data: {'sucursal': sucursal,'usuario':usuario,'operacion':operacion,"control":control,"checked" : checked,"permiso":permiso},
		url: "controller/Controles.php",
		// dataType: 'json',
		success:function(respuesta){
			
		}
	});
}