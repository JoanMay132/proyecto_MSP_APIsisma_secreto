$(document).ready(function () {
    let men = $("#menu");
    men.hide();

    var textarea = document.getElementsByClassName('scrollHidden');
    for (let index = 0; index < textarea.length; index++) {
         textarea[index].style.overflow = 'hidden';

    }
 });
 function autoResize(textarea) {
    textarea.style.height = "30px"; // Restaura la altura automática
    // Ajusta la altura del textarea según el contenido
    textarea.style.height = Math.min(textarea.scrollHeight, 150) + "px";
}

//  function desactivar(){
//     $('.disabled').attr('disabled','on');
//     $('.btn').attr('disabled','on');
//     var tabla = document.getElementById('table');

//    tabla.style.opacity = '0.6'; // Reducir la opacidad para dar un aspecto desactivado
//    tabla.style.pointerEvents = 'none';
//  }

var in_rev = 1;
function addServrequisicion(data){
	var fila = '<tr style="max-height: 80px;"  ondblclick=\'addServrequisicion('+ JSON.stringify(data)+')\' id="serv-'+in_rev+'">'+
                            '<td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric"></td>'+
                            '<td valign="top"><input type="number" name="cant[]"  min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off"></td>'+
                            '<td valign="top">'+
                                '<select name="unidad[]" class="form-control form-control-sm">'+
                                    '<option value=""></option>';
                                    for (let index = 0; index < data.length; index++) {
                                        var option = data[index];
                                        fila += '<option value="'+option.pkunidad+'">'+option.nombre+'</option>';
                                        
                                    }
                               fila +=  '</select></td>'+
                            '<td valign="top"><input type="text" name="nparte[]"  class="form-control form-control-sm text-center"  autocomplete="off"></td>'+
                            '<td valign="top"><textarea name="descripcion[]" id="descNew-'+in_rev+'" class="form-control form-control-sm" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll("descNew-'+in_rev+'")" onblur="ocultarScroll("descNew-'+in_rev+'")"></textarea></td></tr>';

	$('.table-requisicion').append(fila)

	in_rev++;

}

// function Activo()
// {
//     var activo =  $('.disabled').removeAttr('disabled');
//     $('.btn').removeAttr('disabled');
//    var tabla = document.getElementById('table');
//    tabla.style.opacity = ''; // Reducir la opacidad para dar un aspecto desactivado
//    tabla.style.pointerEvents = '';
//    activo.css({
//        'cursor': 'unset',
//    });
// }


async function Sucursal(valor){
    if(valor == ''){
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Seleccione sucursal',
            showConfirmButton: true,
            width:'auto',
            //timer: 1500,
          })
        //desactivar();
        return false;
    }

    //Activo();


   //Se actuliza la lista de ordenes
   await $.ajax({
    type: "POST",
    data: { "sucursal": valor },
    url: "../Trazabilidad/Cargas/loadOrden.php",
    dataType: "json",
    success: function (respuesta) {
        var opcionesSelect = document.getElementById('listorden');
        opcionesSelect.options.length = 1;
        if (respuesta == null) { return false; }
        respuesta.forEach(function (dato) {
            var opcion = document.createElement('option');
            opcion.value = dato[0]; // Asigna el valor del dato a la opción
            opcion.text = dato[1]; // Asigna el texto visible de la opción
            opcionesSelect.appendChild(opcion); // Agrega la opción al elemento <select>
        });
    }
});

    //Se actuliza la lista de empleado por sucursal
    await $.ajax({
        type: "POST",
        data: {"sucursal": valor},
        url: "../Trazabilidad/Cargas/loadEmpleados.php",
        dataType: "json",
        success:function(respuesta){
            for(i = 0; i < 3; i++){
                var opcionesSelect2 = document.getElementsByClassName('listEmployee')[i];
                opcionesSelect2.options.length = 1;
                respuesta.forEach(function(dato) {
                    var opcion2 = document.createElement('option');
                    opcion2.value = dato[0]; // Asigna el valor del dato a la opción
                    opcion2.text = dato[1]; // Asigna el texto visible de la opción
                    opcionesSelect2.appendChild(opcion2); // Agrega la opción al elemento <select>
                });
            }    
        }
    });
}


function setFolio() {
    
    //folioOt = "";
    let sucursal = document.getElementById('sucursal').value;
    let fecha = document.getElementById('fecha').value;
    $.ajax({
        type: "POST",
        data: { "setFolio": "true", "sucursal": sucursal,"fecha":fecha },
        url: "Controller/Requisicion.php",
        dataType: "json",
        success: function (respuesta) {
            if (respuesta.error) {
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: respuesta.error,
                    showConfirmButton: true,
                    width: '500',
                    //timer: 1500,
                })

                return false;
            }
            $('#displayFolio').text(respuesta.folio);
            var form = document.getElementById('form-requisicion');
            var hidden = "<input type='hidden' name='requisicion' value='" + respuesta.pkrequisicion + "'>"

            //folioOt = respuesta.pkrevpreeliminar;
            const divContenedor = document.createElement('div');
            divContenedor.innerHTML = hidden;

            // Agregar el contenedor al formulario
            form.appendChild(divContenedor);

            $('#btnfolio').css({
                'pointer-events': 'none'
            });

             //Actualiza la tabla de la lista de requisiciones
             if(window.opener)
                {
                    var display = window.opener;
                    display.$('#display-requisiciones').load(display.location.href + ' .display-table');//actualizas el div
                    
                }
        }
    });

    return false;
}


function addRequisicion(print = false) {
    var formData = new FormData($('#form-requisicion')[0]);
    $.ajax({
        type: "POST",
        data: formData,
        url: "Controller/Requisicion.php",
        dataType: 'json',
        processData: false,
        contentType: false,
        success: async function (respuesta) {
            
            if (('success' in respuesta) == true) {
                
                await Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    text: "Datos Guardados",
                    showConfirmButton: false,
                    width: 'auto',
                    position: 'center',
                    timer: 1500,
                });
                if (print) {
                    Print(respuesta.requisicion,"print_requisicion");
                }
                    //Actualiza la lista de ordenes
                    if(window.opener)
                        {
                            var display = window.opener;
                            display.$('#display-requisiciones').load(display.location.href + ' .display-table');//actualizas el div
                            
                        }
                document.location.href = "erequisicion?edit=" + respuesta.requisicion;
            } else {
                await Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: respuesta.error,
                    showConfirmButton: false,
                    width: '300px',
                    position: 'center',
                    timer: 2000,
                });
            }
           

        }
    });

    return false;
}



//Muestra y oculta el scroll
function mostrarScroll(id) {
    var textarea = document.getElementById(id);
    textarea.style.overflow = 'auto'; // Muestra la barra de desplazamiento
}

function ocultarScroll(id) {
    var textarea = document.getElementById(id);
    textarea.style.overflow = 'hidden'; // Oculta la barra de desplazamiento al perder el foco
}
async function eliminarFila(button, reg = "", branch = '') {
    // Obtén la fila a la que pertenece el botón
    let fila = button.parentNode.parentNode;

    const ruta = "Controller/deleteServRequisicion.php";


    if (reg != "") { // Verifica si viene de un registro
        await Swal.fire({
            title: "Con firma eliminar el registro?",
            text: "No podra revertir esta acción!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Eliminar!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    data: { 'id': reg,"branch" : branch },
                    url: ruta,
                    dataType: 'json',
                    success: function (respuesta) {
                        if(('Error' in respuesta)){
                            Swal.fire({
                                position: "top-end",
                                icon: "error",
                                title: respuesta.Error,
                                showConfirmButton: false,
                                timer: 2000
                              });
                              return false;
                            } 
                        fila.remove();
                        return true;
                    }
                });

            }

        });
        return false;
    }
    fila.remove();
}

function menu(data){
    var id = data.id; //Se obtiene el ID del textArea
    var serv = $(data).attr("data-servicio"); //Se obtiene la data del servicio para redireccionar
    let fila = data.parentNode.parentNode;

    let branchSelec = document.getElementById('sucursal').value ?? '';

     let pre =  fila.querySelector("[name='pkserv[]']");

    $("#"+id).on("contextmenu", function (e) {
        e.preventDefault();
        var menu = $("#menu");
        
        menu.css({
            top: e.pageY + "px",
            left: e.pageX + "px"
        });

        menu.show();

        $(document).on("click", function () {
            menu.hide();
            $(document).off("click");
        });

        menu.on("click", function (e) {
            e.stopPropagation();
        });

        $('#eliminar').removeAttr('onclick'); //Se remueve el atributo
        if (pre != null) {
            $("#eliminar").on("click", function () {
                eliminarFila(data, pre.value,branchSelec); 
            });
        } else {
            $("#eliminar").on("click", function () {

                eliminarFila(data);
            });
        }  
      
    });
    $("#eliminar").off("click");
}


function SentForm(form,print = false) {
       // Verificar si el formulario es válido
       if (form.checkValidity()) {
        // Si es válido, enviar el formulario
        //form.submit();
        addRequisicion(print);
    } else {
        // Si no es válido, disparar los mensajes de validación
        form.reportValidity();
    }
}

function Print(req,name){
    let izquierda = Math.round((screen.width - 800) / 2);
    let arriba = Math.round((screen.height - 1000) / 2);

    window[name] ? window[name].focus() : window.open("print/requisicion?requisicion=" + req, name, "width=800,height=1000,scrollbars=yes,left=" + izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");
}

document.getElementById("imprimir").addEventListener("click", function () {
    //$('#form-requisicion').attr('onsubmit', "return addRequisicion(true);");
    let form = document.getElementById('form-requisicion');
    if (form) {
        SentForm(form,true);
    } 
});

document.getElementById("guardar").addEventListener("click", function (event) {
    //console.log(event);
    //event.preventDefault();
    //$('#form-requisicion').attr('onsubmit', "return addRequisicion();");
    let form = document.getElementById('form-requisicion');

    if (form) {
        SentForm(form);
    } else {
        console.error("Formulario no encontrado");
    }
});