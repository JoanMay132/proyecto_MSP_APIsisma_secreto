
function loadmunicipio(estado){

    $.ajax({
        type: "POST",
        data: {"estado": estado},
        url: "../dependencias/php/municipios.php",
        dataType: "json",
        success:function(respuesta){
            var opcionesSelect = document.getElementById('municipio');
            opcionesSelect.options.length = 0;

            respuesta.forEach(function(dato) {
                var opcion = document.createElement('option');
                opcion.value = dato[0]; // Asigna el valor del dato a la opción
                opcion.text = dato[1]; // Asigna el texto visible de la opción
                opcionesSelect.appendChild(opcion); // Agrega la opción al elemento <select>
              });
              
              
        }
    });
}


function loadestado(municipio, estado){
    $.ajax({
        type: "POST",
        data: {"municipio": municipio,"estado": estado},
        url: "../dependencias/php/estados.php",
        dataType: "json",
        success:function(respuesta){
            var opcionesSelect = document.getElementById('localidad');
            opcionesSelect.options.length = 0;

            respuesta.forEach(function(dato) {
                var opcion = document.createElement('option');
                opcion.value = dato[0]; // Asigna el valor del dato a la opción
                opcion.text = dato[1]; // Asigna el texto visible de la opción
                opcionesSelect.appendChild(opcion); // Agrega la opción al elemento <select>
              });
              
              
        }
    });
}

/*
function loadServicios(cursor){ //Para los servicios que van de cotizacion a presupuesto
  
    let servicio = cursor.textContent;
    let fila = cursor.parentNode.parentNode;
    let pda = fila.querySelector("[name='pdaReg[]']").value;
    
    let data = {
        servicio: servicio,
        pda : pda
    };

    return data;

}*/

