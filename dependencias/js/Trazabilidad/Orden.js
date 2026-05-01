window.onload = function () {
    $(".loader").fadeOut("slow");
    
    var men = $("#menu");
        men.hide();
    
    var textarea = document.getElementsByClassName('scrollHidden');
    for (let index = 0; index < textarea.length; index++) {
         textarea[index].style.overflow = 'hidden';

    }

};
function setFolio() {
    
    folioOt = "";
    var sucursal = document.getElementById('sucursal').value;
    let fecha = document.getElementById('fecha').value;
    $.ajax({
        type: "POST",
        data: { "setFolio": "true", "sucursal": sucursal,"fecha":fecha },
        url: "Controller/Orden.php",
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

             //Actualiza la tabla de la lista de ordenes
            if(window.opener)
            {
                var display = window.opener;
                display.$('#display-ordenes').load(display.location.href + ' .display-table');//actualizas el div
                
            }
        }
    });

    return false;
}

function autoResize(textarea) {
    textarea.style.height = "30px"; // Restaura la altura automática
    // Ajusta la altura del textarea según el contenido
    textarea.style.height = Math.min(textarea.scrollHeight, 150) + "px";
}

var in_rev = 1;
function addServot(data,tipo = []) {
    var fila = '<tr style="max-height: 80px;" ondblclick=\'addServot(' + JSON.stringify(data) + ','+ JSON.stringify(tipo)+')\' id="serv-' + in_rev + '">' +
        '<td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center" name="pda" autocomplete="off"></td>' +
        '<td valign="top"><input name="cant[]" id="cantidadNew-' + in_rev + '" data-pre="cantidad"  type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center" autocomplete="off"></td>' +
        '<td valign="top"><select name="unidad[]" class="form-control form-control-sm text-center"><option value=""></option>';
    for (let index = 0; index < data.length; index++) {
        var option = data[index];
        fila += '<option value="' + option.pkunidad + '">' + option.nombre + '</option>';

    }
    fila += '</select></td>' +
        '<td valign="top"><textarea oninput="autoResize(this)" id="descNew-' + in_rev + '" name="descripcion[]" class="form-control cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:30px;" onclick="menu(this); return false;" onfocus="mostrarScroll(\'descNew-' + in_rev + '\')" onblur="ocultarScroll(\'descNew-' + in_rev + '\')" ></textarea></td>' +
        '<td valign="top" >' +
            '<select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">' +
            '<option value=""></option>';
                                for (let x = 0; x < tipo.length; x++) {
                                    
                                    fila += '<option value="'+tipo[x]+'">'+tipo[x]+'</option>';
                                    
                                }
                                fila += '</select>' +
        '</td>' +
        '<td valign="top"><textarea id="" name="dibujo[]" class="form-control text-center" autocomplete="off" oninput="autoResize(this);" spellcheck="false" style="resize:none;height:30px;"></textarea></td>' +
        '</tr>';

    $('.table-ot').append(fila)

    in_rev++;
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



    //Se actuliza la lista de cotizaciones
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
                if (data.cotizacion === dato[0]) {
                    opcionesSelect.value = dato[0];
                }
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
    $('#buttonUser').attr('onclick', 'javascript:popup(\'../Catalogo/addusercustomer?customer=' + valor + '\')');
    await $.ajax({
        type: "POST",
        data: { "client": valor },
        url: "Cargas/loadUsercustomer.php",
        dataType: "json",
        success: function (respuesta) {


            var opcionesSelect = document.getElementsByClassName('listUsercustomer')[0];
            opcionesSelect.options.length = 1;

            respuesta.forEach(function (dato) {
                var opcion = document.createElement('option');
                opcion.value = dato[0]; // Asigna el valor del dato a la opción
                opcion.text = dato[1]; // Asigna el texto visible de la opción
                opcionesSelect.appendChild(opcion); // Agrega la opción al elemento
                //Agrega elementos al array;
                user.push({ "pkusercli": dato[0], "nombre": dato[1], "depto": dato[2], "cargo": dato[3] }); //Asigna datos al arreglo
            });
        }
    });


    return false;

}

//Se actuliza la lista de departamento por el cliente seleccionado
function viewDepto(cliente) {
    if (cliente === '') { $('#deptouser').val(""); return false; }

    const id = cliente;
    const indice = user.findIndex(persona => persona.pkusercli === id);
    $('#deptouser').val("");
    if (indice != -1) {
        $('#deptouser').val(user[indice]["depto"]);
    }

}

//Consulta la cotizacion
async function dataCotizacion(id, ot = "") {
    $.ajax({
        type: "POST",
        data: { "id": id },
        url: "Cargas/loadCotizacion.php",
        dataType: "json",
        success: async function (respuesta) {
            //Obtenemos los id de las etiquetas
            var clientes = document.getElementById("listClientes");
            let fecha = document.getElementById("fecha");
            const cotizacion = respuesta.pkcotizacion;

            //Se asignan los valores
            clientes.value = '';
            //fecha.value = '';

            clientes.value = respuesta.cliente;
            //fecha.value = respuesta.fecha;

            //CONSULTA LOS USUARIOS DE LOS CLIENTES Y LOS ENLISTA
            await usercustomer(respuesta.cliente);

            //Selecciona el elemento de la lista de usuarios

            var listuser = document.getElementById("listUsercustomer");
            listuser.value = respuesta.solicito;

            viewDepto(respuesta.solicito);

            await Servicios(cotizacion, ot);
        }
    });
}

//Carga los servicios en la tabla
async function Servicios(rev, ot = "") {
    await $.ajax({
        type: "POST",
        data: { "id": rev, "ot": ot },
        url: "Cargas/servicioCot.php",
        success: function (respuesta) {
            let tabla = document.querySelector("#table tbody");
            tabla.innerHTML = respuesta;
            let area = document.querySelectorAll(".cajas-texto")
            area.forEach((elemento) => {
                elemento.style.height = `${elemento.scrollHeight}px`
            })

            return true;
        }

    });

    return false;
}

//Registro de Orden
function addOrden(print = false) {
    let btnSave = document.getElementById("imprimir");
    let btnPrint = document.getElementById("guardar");
    btnSave.disabled = true;
    btnPrint.disabled = true;
    var formData = new FormData($('#form-orden')[0]);
    $.ajax({
        type: "POST",
        data: formData,
        url: "Controller/Orden.php",
        dataType: 'json',
        processData: false,
        contentType: false,
        success: async function (respuesta) {
            if (('success' in respuesta) == true) {
                if (print) {
                    Print(respuesta.orden,"orden","print_orden");
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
                    //Actualiza la lista de ordenes
                    if(window.opener)
                        {
                            var display = window.opener;
                            display.$('#display-ordenes').load(display.location.href + ' .display-table');//actualizas el div
                            
                        }
                document.location.href = "eorden?edit=" + respuesta.orden;
            } else {
                btnSave.disabled = false;
                btnPrint.disabled = false;
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


function menu(data, sub = "") {
    var id = data.id; //Se obtiene el ID del textArea
    let fila = data.parentNode.parentNode;

    let branchSelec = document.getElementById('sucursal').value ?? '';

    let pre = fila.querySelector("[name='pkserv[]']");

    $("#" + id).on("contextmenu", function (e) {
        //#region Configuracion
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
                if (sub != "") {
                    eliminarFila(data, pre.value, 'true',branchSelec);
                } else { eliminarFila(data, pre.value,undefined,branchSelec); }
            });
        } else {
            $("#eliminar").on("click", function () {

                eliminarFila(data);
            });
        }
    });
    $("#eliminar").off("click");
}

async function eliminarFila(button, reg = "", sub = "", branch = '') {
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

function addSuborden(nuevo = false, num = "",print = false) {
    if (nuevo) {

        var formData = new FormData($('#form-orden')[0]);
        formData.append('Newfolio', nuevo);
        if (num != "") {
            num = parseInt(num);
            num = num == 0 ? 1 : num + 1; //Asigna un numero al nombre de la cotizacion
            formData.append('nombre', num);
        }
        Swal.fire({
            title: "Crear suborden",
            text: "¿Desea crear nuevo registro?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Crear"
        }).then((result) => {
            if (result.isConfirmed) {
                saveSuborden(formData, true);
            }
        });
    } else { var formData = new FormData($('#form-suborden')[0]); saveSuborden(formData,false,print); }


    return false;
}

function saveSuborden(formData, nuevo = false,print = false) {
    
    $.ajax({
        type: "POST",
        data: formData,
        url: "Controller/Suborden.php",
        dataType: 'json',
        processData: false,
        contentType: false,
        success: async function (respuesta) {
            if (('success' in respuesta) == true) {
                if (!nuevo) {
                    await Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        text: "Cambios Guardados",
                        showConfirmButton: false,
                        width: 'auto',
                        position: 'center',
                        timer: 1500,
                    });
                }
            }else{
                await Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: respuesta.error,
                    showConfirmButton: false,
                    width: '300px',
                    position: 'center',
                    timer: 2000,
                });

                return false;
            }
            const ruta = "suborden?ot=" + respuesta.orden;

            if (nuevo) {
                window["SUBORDEN"] ? window["SUBORDEN"].focus() : window.open(ruta, "SUBORDEN", "width=1050,height=550,scrollbars=yes,left=200,addressbar=0,menubar=0,toolbar=0" );
            } else {
                if (print) {
                    Print(respuesta.orden,"suborden","print_suborden");
                }
                document.location.href = ruta;
            }
        }
    });

    return false;

}

function setName(data, sub,branch) {
    if (data != '') {
        $.ajax({
            type: "POST",
            data: { 'id': sub, 'name': data,'tipo':'ot','branch' : branch },
            url: "Controller/SetName.php",
            dataType: "json",
            success: function (respuesta) {
                if(('Error' in respuesta)){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text:respuesta.Error,
                        showConfirmButton: false,
                        width:'400',
                        position:'center',
                        timer: 2500,
                    }); 

                return false;
                }
            }
        });
    }

    return false;
}
function SentForm(form) {
    form.oninvalid();
    form.onsubmit();
}

function Print(orden,tipo,name){
    let izquierda = Math.round((screen.width - 800) / 2);
    let arriba = Math.round((screen.height - 1000) / 2);

    window[name] ? window[name].focus() : window.open("print/"+tipo+"?orden=" + orden, name, "width=800,height=1000,scrollbars=yes,left=" + izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");
}

if(document.getElementById("imprimir")){
    document.getElementById("imprimir").addEventListener("click", function () {
        $('#form-orden').attr('onsubmit', "return addOrden(true);");
        let form = document.getElementById('form-orden');
        SentForm(form);
    });
}

if(document.getElementById("guardar")){
    document.getElementById("guardar").addEventListener("click", function () {
        $('#form-orden').attr('onsubmit', "return addOrden();");
        let form = document.getElementById('form-orden');
        SentForm(form);
    });
}

if(document.getElementById("printSuborden")){
    document.getElementById("printSuborden").addEventListener("click", function () {
        $('#form-suborden').attr('onsubmit', "return addSuborden(undefined,undefined,true);");
        let form = document.getElementById('form-suborden');
        SentForm(form);
    });
}

if(document.getElementById("guardarSuborden")){
    document.getElementById("guardarSuborden").addEventListener("click", function () {
        $('#form-suborden').attr('onsubmit', "return addSuborden();");
        let form = document.getElementById('form-suborden');
        SentForm(form);
    });
}