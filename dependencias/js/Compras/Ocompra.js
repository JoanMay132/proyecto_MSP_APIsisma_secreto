$(document).ready(function () {
    $(".loader").fadeOut("slow");
    // let men = $("#menu");
    // men.hide()
    let textarea = document.getElementsByClassName('scrollHidden');
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
function addServOcompra(data){
	var fila = '<tr style="max-height: 80px;"  ondblclick=\'addServOcompra('+ JSON.stringify(data)+')\' >'+
                            '<td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric"></td>'+
                            '<td valign="top">'+
                                '<select name="unidad[]" class="form-control form-control-sm">'+
                                    '<option value=""></option>';
                                    for (let index = 0; index < data.length; index++) {
                                        var option = data[index];
                                        fila += '<option value="'+option.pkunidad+'">'+option.nombre+'</option>';
                                        
                                    }
                               fila +=  '</select></td>'+
                               '<td valign="top"><input type="number" name="cant[]"  min="0.00" step="0.01" onblur="Subtotal(this); totales();" data-pre="cantidad" class="form-control form-control-sm text-center"  autocomplete="off"></td>'+                            
                               '<td valign="top"><input type="text" name="punit[]" data-pre="costo" onblur="Subtotal(this); totales();" onchange="window.moneda(this);"  class="form-control form-control-sm text-center"  autocomplete="off"></td>'+
                               '<td valign="top"><input type="text" name="importe[]" data-pre="subtotal"  onchange="window.moneda(this);" class="form-control form-control-sm text-center subtotal"  autocomplete="off"></td>'+
                               '<td valign="top"><textarea name="descripcion[]" id="descNew-'+in_rev+'" class="form-control form-control-sm cajas-texto" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll(\'descNew-'+in_rev+'\')" onblur="ocultarScroll(\'descNew-'+in_rev+'\')"></textarea></td>';

	$('.table-ocompra').append(fila);

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

    //Se actuliza la lista de requisiciones
    await $.ajax({
        type: "POST",
        data: {"sucursal": valor},
        url: "../compras/Cargas/Requisicion.php",
        dataType: "json",
        success:function(respuesta){
            for(i = 0; i < 1; i++){
                var opcionesSelect2 = document.getElementsByClassName('listRequisicion')[i];
                opcionesSelect2.options.length = 1;
                respuesta.forEach(function(dato) {
                    //let texto = dato[2] == '' ? '' : ' | '+dato[2];
                    var opcion2 = document.createElement('option');
                    opcion2.value = dato[0]; // Asigna el valor del dato a la opción
                    opcion2.text = dato[1]; // Asigna el texto visible de la opción
                    opcionesSelect2.appendChild(opcion2); // Agrega la opción al elemento <select>
                });
            }    
        }
    });

    //Se actuliza la lista de proveedores
    await $.ajax({
        type: "POST",
        data: {"sucursal": valor},
        url: "../Catalogo/Cargas/Proveedores.php",
        dataType: "json",
        success:function(respuesta){
            for(i = 0; i < 1; i++){
                var opcionesSelect2 = document.getElementsByClassName('listProveedor')[i];
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

async function Proveedor(data){
   await $.ajax({
    type: "POST",
    data: { "proveedor": data },
    url: "../Catalogo/Cargas/Proveedores.php",
    dataType: "json",
    success: function (respuesta) {
        
        //Se obtiene los Id a mostrar:
        let rfc = document.getElementById('rfc');
        let dir = document.getElementById('direccion');
        let contacto = document.getElementById('contacto');
        let telefono = document.getElementById('telefono');
        let correo = document.getElementById('correo');
        let nproveedor = document.getElementById('nproveedor');

        rfc.value = respuesta.rfc;
        dir.value = respuesta.direccion;
        contacto.value = respuesta.contacto;
        telefono.value = respuesta.telefono;
        correo.value = respuesta.correo;
        nproveedor.value = respuesta.nproveedor;
    }
});
}

//Consulta la requisicion
async function dataRequisicion(id) {
    //Se carga los servicios de la requisicion
    await $.ajax({
        type: "POST",
        data: { "id": id },
        url: "Cargas/servRequisicion.php",
        success: function (respuesta) {
            let fila0 = document.getElementById("serv-0");

            //Carga los servicios amtes de serv-0
            fila0.insertAdjacentHTML('beforebegin', respuesta);

            //tabla.innerHTML = respuesta;
            let area = document.querySelectorAll(".cajas-texto")
          area.forEach((elemento) => {
            elemento.style.height = `${elemento.scrollHeight}px`
          })
        
          return true;
        }
    });

    return false;
}

async function eliminarFila(button, reg = "") {
    // Obtén la fila a la que pertenece el botón
    let fila = button.parentNode.parentNode;

    const ruta = sub != "" ? "Controller/deleteServsubOrden.php" : "Controller/deleteServOrden.php";


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
                    data: { 'id': reg },
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

function setFolio() {
    
    //folioOt = "";
    let sucursal = document.getElementById('sucursal').value;
    let fecha = document.getElementById('fechaorden').value;
    $.ajax({
        type: "POST",
        data: { "setFolio": "true", "sucursal": sucursal,"fecha":fecha },
        url: "Controller/Ocompra.php",
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
            var form = document.getElementById('form-ocompra');
            var hidden = "<input type='hidden' name='ocompra' value='" + respuesta.pkocompra + "'>"

            //folioOt = respuesta.pkrevpreeliminar;
            const divContenedor = document.createElement('div');
            divContenedor.innerHTML = hidden;

            // Agregar el contenedor al formulario
            form.appendChild(divContenedor);

            $('#btnfolio').css({
                'pointer-events': 'none'
            });

            //Actualiza la tabla de la lista de ocompras
            if(window.opener)
                {
                    var display = window.opener;
                    display.$('#display-ocompra').load(display.location.href + ' .display-table');//actualizas el div
                    
                }
        }
    });

    return false;
}


function addOcompra(print = false) {
    var formData = new FormData($('#form-ocompra')[0]);
    $.ajax({
        type: "POST",
        data: formData,
        url: "Controller/Ocompra.php",
        dataType: 'json',
        processData: false,
        contentType: false,
        success: async function (respuesta) {
            console.log(respuesta);
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
                    Print(respuesta.ocompra,"ordencompra");
                }
                   //Actualiza la tabla de la lista de ocompras
            if(window.opener)
                {
                    var display = window.opener;
                    display.$('#display-ocompra').load(display.location.href + ' .display-table');//actualizas el div
                    
                }
                document.location.href = "eocompra?edit=" + respuesta.ocompra;
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

    const ruta = "Controller/deleteServOcompra.php";


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
        if(pre != null ){ 
            $("#eliminar").on("click",function () {
                eliminarFila(data, pre.value,branchSelec); 
            });
        }else{
            $("#eliminar").on("click", function () {
           
                eliminarFila(data);
            });
        } 
        $('#servicio').attr('onclick','javascript:servicios(\''+serv+"&suc="+branchSelec+"&tipo=revpreeliminar"+'\')'); 
        
        

      
    });
    $("#eliminar").off("click");
}

function Subtotal(row)
{
    let fila = row.parentNode.parentNode;
    let cant  = "",subtotal = "",costo = "";

        cant = fila.querySelector("[data-pre='cantidad']");
        subtotal = fila.querySelector("[data-pre='subtotal']");
        costo = fila.querySelector("[data-pre='costo']");
        if(costo.value == ""){
            costo.value = "$0.00";
        }
    costo = (costo.value.replace(/[$,]/g, ""));
    let total = (cant.value*costo);

    subtotal.value = formato(total);
    
}

function formato(numero)
{
    var numeroFormateado = numero.toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      return numeroFormateado;
}

//Suma de cantidades para calcular los totales
async function totales(){
    let inputSubtotal = document.getElementById('inputSubtotal');
    let inputTotal= document.getElementById('inputTotal');
    let subtotales = document.getElementsByClassName('subtotal');
    let subtotal = 0;
    for (let index = 0; index < subtotales.length; index++) {
         if(subtotales[index].value.replace(/[$,]/g, "") == ""){ continue}
        subtotal += parseFloat (subtotales[index].value.replace(/[$,]/g, "")); 
    }

    let tagSubtotal = document.getElementById('subtotal');
    tagSubtotal.textContent  = formato( subtotal);
    inputSubtotal.value = (subtotal);

    //Porcentaje - calculo de descuento
    let tagDescto= document.getElementById('descto');
    tagDescto = tagDescto.value == "" ? 0 : tagDescto.value ;
    let tagSubtotaldesc = document.getElementById('subtotaldesc');
    let porcentaje = (subtotal * parseFloat(tagDescto));
    tagSubtotaldesc.textContent = formato((subtotal - porcentaje)); 
    document.getElementById('tagDescto').textContent = formato(porcentaje);
    let total = (subtotal - porcentaje);

    //calculo de iva
    let iva = document.getElementById('iva'); //Se obtiene el valor del iva
    let tagIva = document.getElementById('tagIva'); //Se obtiene la etiqueta en donde se muestra el iva
    tagIva.textContent = formato( total * parseFloat("0."+iva.value));

    let tagTotal = document.getElementById('tagTotal'); //Se obtiene la etiqueta en donde se muestra el total
    let totales = (total + (total * parseFloat("0."+iva.value)));
    tagTotal.textContent = formato( totales );
    inputTotal.value = (totales);

    //Se muestra el valor en dolares
    // let tdolar = document.getElementById('tdolar');
    // let tagCambio= document.getElementById('cambio').value;
    // tdolar.textContent = formato( totales/parseFloat(tagCambio));
 

    // CalculaSubcotizacion();
}

//Carga los datos en el formulario de edición
// async function cargaEdit(data){

//         await Sucursal(data.sucursal);
//         document.getElementById('proveedor').value = data.proveedor;
//         document.getElementById('nrequisicion').value =  data.requisicion;
//         document.getElementById('listorden').value = data.orden;
//         document.getElementById('comprador').value = data.comprador;
//         document.getElementById('solicita').value = data.solicita;
//         document.getElementById('autoriza').value = data.autoriza;
// }

function Print(compra,name){
    let izquierda = Math.round((screen.width - 800) / 2);
    let arriba = Math.round((screen.height - 1000) / 2);

    window[name] ? window[name].focus() : window.open("print/ocompra?ocompra=" +compra , name, "width=800,height=1000,scrollbars=yes,left=" + izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");
}

function SentForm(form,print = false) {
       // Verificar si el formulario es válido
       if (form.checkValidity()) {
        // Si es válido, enviar el formulario
        //form.submit();
        addOcompra(print);
    } else {
        // Si no es válido, disparar los mensajes de validación
        form.reportValidity();
    }
}




document.getElementById("imprimir").addEventListener("click", function () {
    //$('#form-ocompra').attr('onsubmit', "return addOcompra(true);");
    let form = document.getElementById('form-ocompra');
    if (form) {
        SentForm(form,true);
    }
});

document.getElementById("guardar").addEventListener("click", function (event) {
    //console.log(event);
    //event.preventDefault();
    //$('#form-requisicion').attr('onsubmit', "return addRequisicion();");
    let form = document.getElementById('form-ocompra');

    if (form) {
        SentForm(form);
    } else {
        console.error("Formulario no encontrado");
    }
});


//Evento para sobrecarga de select en lista
const selectElement = document.getElementById('nrequisicion');

// Variable para rastrear la última opción seleccionada
let lastValue = selectElement.value;

// Manejar el evento 'change' para ejecutar la función
selectElement.addEventListener("change", async (event) => {
  const selectedValue = event.target.value;
  
  // Ejecutamos la función dataRequisicion solo si se ha seleccionado un valor válido
  if (selectedValue) {
    await dataRequisicion(selectedValue);
    lastValue = selectedValue; // Actualizamos lastValue solo si hay un valor válido
  }
});

// Manejar el evento 'mousedown' para permitir la selección repetida
selectElement.addEventListener("mousedown", (event) => {
  selectElement.value = ""; // Esto permite que se dispare el evento change
});



// // Manejar el clic en el documento para restablecer el valor si se hace clic fuera del select
 document.addEventListener("click", (event) => {
  if (event.target !== selectElement && !selectElement.value) {
    selectElement.value = lastValue; // Restauramos el valor anterior si no se selecciona nada
  }
});