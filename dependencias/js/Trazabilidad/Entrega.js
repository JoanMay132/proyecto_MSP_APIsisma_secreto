window.onload = function () {
    $(".loader").fadeOut("slow");
    var textarea = document.getElementsByClassName('scrollHidden');
    for (let index = 0; index < textarea.length; index++) {
         textarea[index].style.overflow = 'hidden';

    }
    var men = $("#menu");
    men.hide();

};
function setFolio() {
    folioOt = "";
    var sucursal = document.getElementById('sucursal').value;
    $.ajax({
        type: "POST",
        data: { "setFolio": "true", "sucursal": sucursal },
        url: "Controller/Orden.php",
        dataType: "json",
        success: function (respuesta) {
            if (respuesta.error) {
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: 'Seleccione sucursal',
                    showConfirmButton: true,
                    width: 'auto',
                    //timer: 1500,
                })

                return false;
            }
            $('#displayFolio').text(respuesta.folio);
            var form = document.getElementById('form-orden');
            var hidden = "<input type='hidden' name='orden' value='" + respuesta.pkorden + "'>"

            folioOt = respuesta.pkrevpreeliminar;
            const divContenedor = document.createElement('div');
            divContenedor.innerHTML = hidden;

            // Agregar el contenedor al formulario
            form.appendChild(divContenedor);

            $('#btnfolio').css({
                'pointer-events': 'none'
            });

            /* //Actualiza la tabla de la lista de ordenes
            if(window.opener)
            {
                var display = window.opener;
                display.$('#display-revpreeliminar').load(display.location.href + ' .display-table');//actualizas el div
                
            }*/
        }
    });

    return false;
}

var in_rev = 1;
function addServentrega(data) {
    var fila = '<tr style="max-height: 80px;" ondblclick=\'addServentrega(' + JSON.stringify(data) +')\' id="serv-' + in_rev + '">' +
        '<td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm " name="pda" autocomplete="off"></td>' +
        '<td valign="top"><input name="cant[]" id="cantidadNew-' + in_rev + '" data-pre="cantidad"  type="number" min="0.00" step="0.01" class="form-control form-control-sm" autocomplete="off"></td>' +
        '<td valign="top"><select name="unidad[]" class="form-control form-control-sm"><option value=""></option>';
    for (let index = 0; index < data.length; index++) {
        var option = data[index];
        fila += '<option value="' + option.pkunidad + '">' + option.nombre + '</option>';

    }
    fila += '</select></td>' +
        '<td valign="top"><textarea oninput="autoResize(this)" id="descNew-' + in_rev + '" name="descripcion[]" class="form-control cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:30px;" onclick="menu(this); return false;" onfocus="mostrarScroll(\'descNew-' + in_rev + '\')" onblur="ocultarScroll(\'descNew-' + in_rev + '\')" ></textarea></td>' +
        '</tr>';

    $('.table-ent').append(fila)

    in_rev++;
}

function autoResize(textarea) {
    textarea.style.height = "30px"; // Restaura la altura automática
    // Ajusta la altura del textarea según el contenido
    textarea.style.height = Math.min(textarea.scrollHeight, 150) + "px";
}

async function Sucursal(valor, data = {}, em = "") {

    if (valor == '') {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Seleccione sucursal',
            showConfirmButton: true,
            width: 'auto',
            //timer: 1500,
        })
        return false;
    }

    //Se actuliza la lista de ordenes
    $.ajax({
        type: "POST",
        data: { "sucursal": valor },
        url: "Cargas/loadOrden.php",
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
                if (data.orden === dato[0]) {
                    opcionesSelect.value = dato[0];
                }
            });
        }
    });

    //Consulta y enlista las cotizaciones
    $.ajax({
        type: "POST",
        data: { "sucursal": valor },
        url: "Cargas/loadCotizacion.php",
        dataType: "json",
        success: function (respuesta) {
            var opcionesSelect = document.getElementById('cotizacion');
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

    //Se actuliza la lista de clientes
    $.ajax({
        type: "POST",
        data: { "sucursal": valor },
        url: "Cargas/loadClientes.php",
        dataType: "json",
        success: function (respuesta) {
            var opcionesSelect = document.getElementById('listClientes');
            opcionesSelect.options.length = 1;
            if (respuesta == null) { return false; }
            respuesta.forEach(function (dato) {
                var opcion = document.createElement('option');
                opcion.value = dato[0]; // Asigna el valor del dato a la opción
                opcion.text = dato[1]; // Asigna el texto visible de la opción
                opcionesSelect.appendChild(opcion); // Agrega la opción al elemento <select>
                if (data.cliente === dato[0]) {
                    opcionesSelect.value = dato[0];
                }
            });


        }
    });

    //Se actuliza la lista de empleado por sucursal
    await $.ajax({
        type: "POST",
        data: { "sucursal": valor, "soloActivos": "1" },
        url: "Cargas/loadEmpleados.php",
        dataType: "json",
        success: function (respuesta) {
            var opcionesSelect2 = document.getElementsByClassName('listEmployee');
            opcionesSelect2 = Array.from(opcionesSelect2);
            opcionesSelect2.forEach((data) => {
                data.options.length = 1;
                respuesta.forEach(function (dato) {
                    var opcion2 = document.createElement('option');
                    opcion2.value = dato[0];
                    opcion2.text = dato[1];
                    data.appendChild(opcion2);
                });
            });
        }
    });
}

//Se actuliza la lista de usuarios del cliente
var user = [];
async function usercustomer(valor) {
    user = []; // Vacia el arreglo
    let opcionesSelect = document.getElementsByClassName('listUsercustomer');
    opcionesSelect = Array.from(opcionesSelect);

    
    if(valor === 'MA=='){opcionesSelect[0].selectedIndex = 0;return false; }
    $('#buttonUser').attr('onclick', 'javascript:popup(\'../Catalogo/addusercustomer?customer=' + valor + '\')');
    await $.ajax({
        type: "POST",
        data: { "client": valor },
        url: "Cargas/loadUsercustomer.php",
        dataType: "json",
        success: function (respuesta) {
            opcionesSelect.forEach((data) => {
                data.options.length = 1;
                respuesta.forEach(function (dato) {
                    var opcion = document.createElement('option');
                    opcion.value = dato[0]; // Asigna el valor del dato a la opción
                    opcion.text = dato[1]; // Asigna el texto visible de la opción
                    data.appendChild(opcion); // Agrega la opción al elemento
                    //Agrega elementos al array;
                    user.push({ "pkusercli": dato[0], "nombre": dato[1], "depto": dato[2], "cargo": dato[3] }); //Asigna datos al arreglo
                });
            });
        }
    });


    return false;

}

//Se actuliza la lista de departamento por el cliente seleccionado
async function viewDepto(cliente) {
    if (cliente === '') { $('#deptouser').val(""); return false; }

    const id = cliente;
    const indice = user.findIndex(persona => persona.pkusercli === id);
    $('#deptouser').val("");
    if (indice != -1) {
        $('#deptouser').val(user[indice]["depto"]);
    }

}

//Consulta la orden
async function dataOrden(id, ot = "") {
    $.ajax({
        type: "POST",
        data: { "id": id },
        url: "Cargas/loadOrden.php",
        dataType: "json",
        success: async function (respuesta) {
            //Obtenemos los id de las etiquetas
            var clientes = document.getElementById("listClientes");
            let cotizacion = document.getElementById("cotizacion");
            let fecha = document.getElementById("fecha");
            const orden = respuesta.pkorden;
            //Se asignan los valores
            clientes.value = '';
            //fecha.value = '';
            cotizacion.value = respuesta.fkcotizacion;
            clientes.value = respuesta.cliente;
            //fecha.value = respuesta.fecha;
            console.log(respuesta.fkcotizacion);
            //CONSULTA LOS USUARIOS DE LOS CLIENTES Y LOS ENLISTA
            await usercustomer(respuesta.cliente);

            //Selecciona el elemento de la lista de usuarios
            var listuser = document.getElementById("listUsercustomer");
            listuser.value = respuesta.solicito;

            await viewDepto(respuesta.solicito);

            await Servicios(orden, ot);
        }
    });
}

//Consulta la cotizacion y obtiene la O.T. relacionada
async function dataCotizacion(id) {
    if (id === '') {
        return false;
    }

    const sucursal = document.getElementById("sucursal").value;
    if (sucursal === '') {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Seleccione sucursal',
            showConfirmButton: true,
            width: 'auto'
        });
        return false;
    }

    $.ajax({
        type: "POST",
        data: { "id": id, "sucursal": sucursal },
        url: "Cargas/loadOrdenByCotizacion.php",
        dataType: "json",
        success: async function (respuesta) {
            if (respuesta.error) {
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: respuesta.error,
                    showConfirmButton: true,
                    width: 'auto'
                });
                return false;
            }

            document.getElementById("listorden").value = respuesta.orden;
            await dataOrden(respuesta.orden);
        }
    });
}

//Carga los servicios en la tabla
async function Servicios(rev, ot = "") {
    await $.ajax({
        type: "POST",
        data: { "id": rev, "entrega": ot },
        url: "Cargas/servicioOrden.php",
        success: function (respuesta) {

            let tabla = document.querySelector("#table tbody");
            tabla.innerHTML = respuesta;
            let area = document.querySelectorAll(".cajas-texto")
            area.forEach((elemento) => {
                 elemento.style.height = `${elemento.scrollHeight}px`
                // let altura = elemento.scrollHeight;
                // elemento.style.height = altura > 80 ? "80px" : `${altura}px`;
            })

            return true;
        }

    });

    return false;
}

//Registro de Entrega
function addEntrega(print = false) {
    let btnSave = document.getElementById("imprimir");
    let btnPrint = document.getElementById("guardar");
    btnSave.disabled = true;
    btnPrint.disabled = true;
    var formData = new FormData($('#form-entrega')[0]);
    $.ajax({
        type: "POST",
        data: formData,
        url: "Controller/Entrega.php",
        dataType: 'json',
        processData: false,
        contentType: false,
        success: async function (respuesta) {
            if (('success' in respuesta) == true) {
                if (print) {
                    Print(respuesta.success);
                }
                await Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    text: "Datos Guardados",
                    showConfirmButton: false,
                    width: 'auto',
                    position: 'center',
                    timer: 1500,
                });

                document.location.href = "eentrega?edit=" + respuesta.success;
            }else if(respuesta.error){
                btnSave.disabled = false;
                btnPrint.disabled = false;
                await Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: respuesta.error,
                    showConfirmButton: false,
                    width: '400',
                    position: 'center',
                    timer: 2000,
                });
            } else {
                await Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: "Error al guardar los datos, contacte con el administrador",
                    showConfirmButton: false,
                    width: 'auto',
                    position: 'center',
                    timer: 1500,
                });
            }
            
            if(window.opener)
                {
                    var display = window.opener;
                    display.$('#display-entrega').load(display.location.href + ' .display-table');//actualizas el div
                    
                }

        }
    });

    return false;
}


function menu(data) {
    var id = data.id; //Se obtiene el ID del textArea
    let fila = data.parentNode.parentNode;

    let branchSelec = document.getElementById('sucursal').value ?? '';
    let pre = fila.querySelector("[name='pkserv[]']");

    //#region Configuracion
    $("#" + id).on("contextmenu", function (e) {
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
        //#endregion
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

async function eliminarFila(button, reg = "",branch = "") {
    // Obtén la fila a la que pertenece el botón
    let fila = button.parentNode.parentNode;

    const ruta = "Controller/deleteServEntrega.php";


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
                    data: { 'id': reg, 'branch' : branch},
                    url: ruta,
                    dataType: 'json',
                    success: function (respuesta) {
                        if(respuesta.Error){
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


//Muestra y oculta el scroll
function mostrarScroll(id) {
    var textarea = document.getElementById(id);
    textarea.style.overflow = 'auto'; // Muestra la barra de desplazamiento
}

function ocultarScroll(id) {
    var textarea = document.getElementById(id);
    textarea.style.overflow = 'hidden'; // Oculta la barra de desplazamiento al perder el foco
}

//Agregar elementos de la subcotizacion
function addFilaSuborden(num = "") {
    var fila = '<div class="form-inline">' +
        '<button type="button" onclick="addSuborden(true,' + num + ');" title="Agregar suborden" class="btn btn-dark"  style="width:13px;height:13px;position:relative;padding:0px;border:none;border-radius:0px"><span style="position:absolute;left:0px;right:0px;bottom:0px;top:0px;margin:0 auto;font-size:13px;color:white;" class="fa fa-plus"></span></button>' +
        '<input type="text" ondblclick="addFilaSuborden(' + num + ');" name="sub[]" class="form-control form-control-sm txt-11" style="height:20px;font-size:11px;padding:3px;width:80px;">' +
        '</div>';

    $('.suborden-add').append(fila)
}

function SentForm(form) {
    form.oninvalid();
    form.onsubmit();
}

function Print(entrega){
    let izquierda = Math.round((screen.width - 800) / 2);
    let arriba = Math.round((screen.height - 1000) / 2);

    let evidencia = document.getElementById('radioSi') ?? '';
    if(evidencia != '' && evidencia.checked){
        window["print_entrega"] ? window["print_entrega"].focus() : window.open("print/entrega_evi?entrega=" + entrega, "print_entrega", "width=800,height=1000,scrollbars=yes,left=" + izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");
        return;
    }

    window["print_entrega"] ? window["print_entrega"].focus() : window.open("print/entrega?entrega=" + entrega, "print_entrega", "width=800,height=1000,scrollbars=yes,left=" + izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");
}


document.getElementById("imprimir").addEventListener("click", async function () {
    await $('#form-entrega').attr('onsubmit', "return addEntrega(true);");
    let form = document.getElementById('form-entrega');
    SentForm(form);
});

document.getElementById("guardar").addEventListener("click", function () {
    $('#form-entrega').attr('onsubmit', "return addEntrega();");
    let form = document.getElementById('form-entrega');
    SentForm(form);
});