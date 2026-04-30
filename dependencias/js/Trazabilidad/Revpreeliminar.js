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

 function desactivar(){
    $('.disabled').attr('disabled','on');
    $('.btn').attr('disabled','on');
    var tabla = document.getElementById('table');

   tabla.style.opacity = '0.6'; // Reducir la opacidad para dar un aspecto desactivado
   tabla.style.pointerEvents = 'none';
 }

var in_rev = 1;
function addServpreeliminar(data,view = '',tipo = [])
{
	var fila = '<tr style="max-height: 80px;" ondblclick=\'addServpreeliminar('+ JSON.stringify(data)+',"'+view+'",'+ JSON.stringify(tipo)+')\' id="serv-'+in_rev+'">'+
                            '<td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm"  autocomplete="off"></td>'+
                            '<td valign="top"><input name="cant[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm"  autocomplete="off"></td>'+
                            '<td valign="top"><select name="unidad[]" class="form-control form-control-sm"><option value=""></option>';
                            for (let index = 0; index < data.length; index++) {
                                var option = data[index];
                                fila += '<option value="'+option.pkunidad+'">'+option.nombre+'</option>';
                                
                            }
                            fila += '</select></td>'+
                            '<td valign="top"><textarea onclick="menu(this); return false;" oninput="autoResize(this)" id="descNew-'+in_rev+'" name="descripcion[]" class="form-control" autocomplete="off" style="resize:none;height:30px;" onfocus="mostrarScroll(\'descNew-' + in_rev + '\')" onblur="ocultarScroll(\'descNew-' + in_rev + '\')" data-servicio=\'servicios?row='+in_rev+'&new=true\' data-serv="'+in_rev+'" ></textarea></td>'+
                            '<td valign="top"><input onchange="moneda(this);" id="costoNew-'+in_rev+'"  name="costo[]" type="text"  class="form-control form-control-sm '+view+'" autocomplete="off"><input type="hidden" id="itemNew-'+in_rev+'" name="item[]" ></td>'+
                            '<td valign="top" >' +
                                '<select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px;">' +
                                '<option value=""></option>';
                                for (let x = 0; x < tipo.length; x++) {
                                    
                                    fila += '<option value="'+tipo[x]+'">'+tipo[x]+'</option>';
                                    
                                }
                                // '<option value="TITULO">TITULO</option>' +
                                // '<option value="CONVENCIONAL">CONVENCIONAL</option>' +
                                // '<option value="MANUFACTURA API">MANUFACTURA API</option>' +
                                // '<option value="MAQUINADO DE CONEXIONES">MAQUINADO DE CONEXIONES</option>' +
                                // '<option value="MAQUINADO DE SELLO">MAQUINADO DE SELLO</option>' +
                                // '<option value="REVESTIMIENTO">REVESTIMIENTO</option>' +
                                fila += '</select>' +
                            '</td>' +
                        '</tr>';

	$('.table-revpreeliminar').append(fila)

	in_rev++;

}

function Activo()
{
    var activo =  $('.disabled').removeAttr('disabled');
    $('.btn').removeAttr('disabled');
   var tabla = document.getElementById('table');
   tabla.style.opacity = ''; // Reducir la opacidad para dar un aspecto desactivado
   tabla.style.pointerEvents = '';
   activo.css({
       'cursor': 'unset',
   });
}


function Sucursal(valor){
    if(valor == ''){
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Seleccione sucursal',
            showConfirmButton: true,
            width:'auto',
            //timer: 1500,
          })
        desactivar();
        return false;
    }

    Activo();


    //Se actuliza la lista de clientes por sucursal
    $.ajax({
        type: "POST",
        data: {"sucursal": valor},
        url: "Cargas/loadClientes.php",
        dataType: "json",
        success:function(respuesta){ 
            var opcionesSelect = document.getElementById('listClientes');
            opcionesSelect.options.length = 1;
            if(respuesta == null){return false;}
            respuesta.forEach(function(dato) {
                var opcion = document.createElement('option');
                opcion.value = dato[0]; // Asigna el valor del dato a la opción
                opcion.text = dato[1]; // Asigna el texto visible de la opción
                opcionesSelect.appendChild(opcion); // Agrega la opción al elemento <select>
              });     
        }
    });

    //Se actuliza la lista de empleado por sucursal
    $.ajax({
        type: "POST",
        data: {"sucursal": valor},
        url: "Cargas/loadEmpleados.php",
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


function listaEmpleado(valor,sventas,scalidad,sproduccion)
{
    $.ajax({
        type: "POST",
        data: {"sucursal": valor},
        url: "Cargas/loadEmpleados.php",
        dataType: "json",
        success:function(respuesta){
            
                var ventas = document.getElementById('ventas');
                var calidad = document.getElementById('calidad');
                //var manufactura = document.getElementById('manufactura');
                var produccion = document.getElementById('produccion');
                ventas.length = 1;calidad.length = 1; /*manufactura.length = 1; */produccion.length = 1;
                
                respuesta.forEach(function(dato) {
                    var opcion = document.createElement('option');
                    opcion.value = dato[0]; // Asigna el valor del dato a la opción
                    opcion.text = dato[1]; // Asigna el texto visible de la opción
                    ventas.appendChild(opcion); // Agrega la opción al elemento <select>
                    if(sventas === dato[0])
                    {
                        ventas.value = dato[0];
                    }
                });

                respuesta.forEach(function(dato) {
                    var opcion = document.createElement('option');
                    opcion.value = dato[0]; // Asigna el valor del dato a la opción
                    opcion.text = dato[1]; // Asigna el texto visible de la opción
                    calidad.appendChild(opcion); // Agrega la opción al elemento <select>
                    if(scalidad === dato[0])
                    {
                        calidad.value = dato[0];
                    }
                });

                // respuesta.forEach(function(dato) {
                //     var opcion = document.createElement('option');
                //     opcion.value = dato[0]; // Asigna el valor del dato a la opción
                //     opcion.text = dato[1]; // Asigna el texto visible de la opción
                //     manufactura.appendChild(opcion); // Agrega la opción al elemento <select>
                //     if(smanufactura === dato[0])
                //     {
                //         manufactura.value = dato[0];
                //     }
                // });

                respuesta.forEach(function(dato) {
                    var opcion = document.createElement('option');
                    opcion.value = dato[0]; // Asigna el valor del dato a la opción
                    opcion.text = dato[1]; // Asigna el texto visible de la opción
                    produccion.appendChild(opcion); // Agrega la opción al elemento <select>
                    if(sproduccion === dato[0])
                    {
                        produccion.value = dato[0];
                    }
                });
               
        }
    });
}


//Se actuliza la lista de usuarios del cliente
var user = [];
function usercustomer(valor){
    user = []; // Vacia el arreglo
    $('#buttonUser').attr('onclick','javascript:popup(\'../Catalogo/addusercustomer?customer='+valor+'\')');
    $('#buttonArea').attr('onclick','javascript:Area(\'../Catalogo/adddeptocli?customer='+valor+'\')');
    $.ajax({
        type: "POST",
        data: {"client": valor},
        url: "Cargas/loadUsercustomer.php",
        dataType: "json",
        success:function(respuesta){
            var opcionesSelect = document.getElementById('listUsercustomer');
            opcionesSelect.options.length = 1;

            respuesta.forEach(function(dato) {
                var opcion = document.createElement('option');
                opcion.value = dato[0]; // Asigna el valor del dato a la opción
                opcion.text = dato[1]; // Asigna el texto visible de la opción
                opcionesSelect.appendChild(opcion); // Agrega la opción al elemento

                //Agrega elementos al array;
                user.push({"pkusercli":dato[0],"nombre":dato[1],"depto" : dato[2]  }); //Asigna datos al arreglo
              });
        }
    });

}

function Loadusercustomer(valor,suser){
    user = []; // Vacia el arreglo
    $('#buttonUser').attr('onclick','javascript:popup(\'../Catalogo/addusercustomer?customer='+valor+'\')');
    $('#buttonArea').attr('onclick','javascript:Area(\'../Catalogo/adddeptocli?customer='+valor+'\')');
    $.ajax({
        type: "POST",
        data: {"client": valor},
        url: "Cargas/loadUsercustomer.php",
        dataType: "json",
        success:function(respuesta){
            var opcionesSelect = document.getElementById('listUsercustomer');
            opcionesSelect.options.length = 1;

            respuesta.forEach(function(dato) {
                var opcion = document.createElement('option');
                opcion.value = dato[0]; // Asigna el valor del dato a la opción
                opcion.text = dato[1]; // Asigna el texto visible de la opción
                opcionesSelect.appendChild(opcion); // Agrega la opción al elemento

                if(suser === dato[0])
                {
                    opcionesSelect .value = dato[0];
                }

                //Agrega elementos al array;
                user.push({"pkusercli":dato[0],"nombre":dato[1],"depto" : dato[2]  }); //Asigna datos al arreglo
              });
        }
    });

}

//Se actuliza la lista de departamento por el cliente seleccionado
function viewDepto(cliente){
    if(cliente==='') { $('#deptouser').val(""); return false; }

    const id = cliente;
    const indice = user.findIndex(persona => persona.pkusercli === id);

    $('#deptouser').val(user[indice]["depto"]);
}

//Elimina los servicios
function delServ(data){
	var button_id = $(data).attr("data-serv");

	$('#serv-'+button_id+'').remove();

}

//Elimina los servicios
function delServReg(data,id,branch = ''){
    var button_id = $(data).attr("data-servReg");
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
				data: {"servicio":id,"branch" : branch},
                dataType: 'json',
				url: "Controller/deleteservrevicion.php",
					success:function(respuesta){
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
                          $('#servReg-'+button_id+'').remove();
					}
				});
			}
	  })
	
    
	


}

//Asigna el folio
var folioRev = "";
function setFolio(){
    folioRev = "";
    var sucursal = document.getElementById('sucursal').value;
    let fecha = document.getElementById('fecha').value;
    $.ajax({
        type: "POST",
        data: {"setFolio": "true","sucursal":sucursal,"fecha":fecha},
        url: "Controller/Revpreeliminar.php",
        dataType: "json",
        success:function(respuesta){
            if(('Error_de_folio' in respuesta) == true){
                display = "<div style='font-size:14px'>" + respuesta.Error_de_folio+"</div>";
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    html:display,
                    showConfirmButton: true,
                    width:'auto',
                    position:'center'
                    //timer: 1500,
                  });
                  return false;
                }else if(respuesta.error){
                   
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
                 var form = document.getElementById('form-revpreeliminar');
                 var hidden = "<input type='hidden' name='revpreeliminar' value='"+respuesta.revision+"'>"
                
                 folioRev = respuesta.revision;
                 const divContenedor = document.createElement('div');
                divContenedor.innerHTML = hidden;

                // Agregar el contenedor al formulario
                form.appendChild(divContenedor);
                 
                $('#btnfolio').css({
                    'pointer-events': 'none'
                });

                if(window.opener)
                {
                    var display = window.opener;
                    display.$('#display-revpreeliminar').load(display.location.href + ' .display-table');//actualizas el div
                    
                }
        }
    });
}


//Registro de revición preeliminar
function addRevpreeliminar(print = false){
    let btnSave = document.getElementById("imprimir");
    let btnPrint = document.getElementById("guardar");
    btnSave.disabled = true;
    btnPrint.disabled = true;
    var formData = new FormData($('#form-revpreeliminar')[0]);
	$.ajax({
		type: "POST",
		data: formData,
		url: "Controller/Revpreeliminar.php",
		dataType: 'json',
		processData: false,
  		contentType: false,
        success:function(respuesta){
            if(('Error_de_folio' in respuesta) == true){
        btnSave.disabled = false;
        btnPrint.disabled = false;
            display = "<div style='font-size:14px'>" + respuesta.Error_de_folio+"</div>";
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                html:display,
                showConfirmButton: true,
                width:'auto',
                position:'center'
                //timer: 1500,
              });
              return false;
            }
            if (print) {

                onlyImpression(respuesta.revision);

            }
            document.location.href = "editrevpreeliminar?edit="+respuesta.revision;   
            if(window.opener)
                {
                    var display = window.opener;
                    display.$('#display-revpreeliminar').load(display.location.href + ' .display-table');//actualizas el div
                    
                }
                
        }
    });

    return false;
}

function onlyImpression(id){
                let izquierda = Math.round((screen.width - 800) / 2);
                let arriba = Math.round((screen.height - 1000) / 2);

                window["print_revision"] ? window["print_revision"].focus() : window.open("print/revision?revision=" + id, "print_revision", "width=800,height=1000,scrollbars=yes,left=" + izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");
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

function menu(data){
    var id = data.id; //Se obtiene el ID del textArea
    var serv = $(data).attr("data-servicio"); //Se obtiene la data del servicio para redireccionar
    let fila = data.parentNode.parentNode;

    let branchSelec = document.getElementById('sucursal').value ?? '';

     let pre =  fila.querySelector("[name='pkservrevicionReg[]']");

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

        $('#servicio').removeAttr('onclick'); //Se remueve el atributo
        $('#eliminar').removeAttr('onclick'); //Se remueve el atributo
        if(pre != null ){ 
            $("#eliminar").on("click",function () {
                delServReg(data,pre.value,branchSelec);
            });
        }else{
            $("#eliminar").on("click", function () {
           
                delServ(data);
            });
        } 
        $('#servicio').attr('onclick','javascript:servicios(\''+serv+"&suc="+branchSelec+"&tipo=revpreeliminar"+'\')'); 
        
        

      
    });
    $("#eliminar").off("click");
}

function menuTexto(data,menu){
    
    const textarea = data;
    const contextMenu = document.getElementById(menu);
    // Mostrar el menú contextual
    textarea.addEventListener('contextmenu', (e) => {
        e.preventDefault(); // Evitar el menú contextual por defecto
        contextMenu.style.display = 'block';
        contextMenu.style.left = `${e.pageX}px`;
        contextMenu.style.top = `${e.pageY}px`;
    });

    document.addEventListener('click', function hideMenu(e) {
        // Solo ocultar si no se hace clic dentro del menú
        if (!contextMenu.contains(e.target)) {
            contextMenu.style.display = 'none';
          document.removeEventListener('click', hideMenu); // Eliminar el manejador después de ocultar
        }
      });

}

function Aggregation(textarea,clase){
   
    let textArea = document.getElementById(textarea);
    let check = document.getElementsByClassName(clase);

    textArea.value = "";
    check = Array.from(check);
    check.forEach((data) =>{
        
        if(data.checked){
            textArea.value += data.value+'\n'; 
        }
    });

    return false;



}


function SentForm(form) {
    form.oninvalid();
    form.onsubmit();
}

document.getElementById("imprimir").addEventListener("click", function () {
    $('#form-revpreeliminar').attr('onsubmit', "return addRevpreeliminar(true);");
    let form = document.getElementById('form-revpreeliminar');
    SentForm(form);
});

document.getElementById("guardar").addEventListener("click", function () {
    $('#form-revpreeliminar').attr('onsubmit', "return addRevpreeliminar();");
    let form = document.getElementById('form-revpreeliminar');
    SentForm(form);
});