window.onload = function() {
    $(".loader").fadeOut("slow");
    var textarea = document.getElementsByClassName('scrollHidden');
    for (let index = 0; index < textarea.length; index++) {
         textarea[index].style.overflow = 'hidden';

    }

    exchangeRate();
  };
  

function autoResize(textarea) {
    textarea.style.height = "30px"; // Restaura la altura automática
    // Ajusta la altura del textarea según el contenido
    textarea.style.height = Math.min(textarea.scrollHeight, 150) + "px";
}

var in_rev = 1;
function addServcot(data,view = '',tipo = []){
   var fila = '<tr style="max-height: 80px;" ondblclick=\'addServcot('+ JSON.stringify(data)+',"'+view+'",'+ JSON.stringify(tipo)+')\' id="serv-'+in_rev+'">'+
                           '<input type="hidden" name="fkcatservReg[]" id="fkcatservNew-'+in_rev+'">'+
                           '<td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm" name="pda" autocomplete="off"></td>'+
                           '<td valign="top"><input name="cant[]" min="0.00" step="0.01" id="cantidadNew-'+in_rev+'" data-pre="cantidad" onblur="Subtotal(this); totales();" type="number" class="form-control form-control-sm" autocomplete="off"></td>'+
                           '<td valign="top"><select name="unidad[]" class="form-control form-control-sm"><option value=""></option>';
                           for (let index = 0; index < data.length; index++) {
                               var option = data[index];
                               fila += '<option value="'+option.pkunidad+'">'+option.nombre+'</option>';
                               
                           }
                           fila += '</select></td>'+
                           '<td valign="top"><textarea oninput="autoResize(this)" id="descNew-'+in_rev+'" name="descripcion[]" class="form-control cajas-texto scrollHidden" autocomplete="off" style="resize:none;height:30px;" onclick="menu(this); return false;" data-servicio="servicios?row='+in_rev+'&new=true" onfocus="mostrarScroll(\'descNew-'+in_rev+'\')" onblur="ocultarScroll(\'descNew-'+in_rev+'\')" ></textarea></td>'+
                           '<td valign="top" >'+
                                '<select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px;">'+
                                    '<option value=""></option>';
                                for (let x = 0; x < tipo.length; x++) {
                                    
                                    fila += '<option value="'+tipo[x]+'">'+tipo[x]+'</option>';
                                    
                                }
                                fila += '</select>' +
                            '</td>'+
                           '<td valign="top"><input onchange="moneda(this);" id="costoNew-'+in_rev+'" data-pre="costo" onblur="Subtotal(this); totales();" name="costo[]" type="text"  class="form-control form-control-sm '+view+' " autocomplete="off"></td>'+
                           '<td valign="top"><input name="subtotal[]" readonly type="text" id="subtotalNew-'+in_rev+'" data-pre="subtotal" class="form-control form-control-sm subtotal '+view+'" autocomplete="off"></td>'+
                           '<td valign="top"><textarea id="" name="clave[]" class="form-control" autocomplete="off" style="resize:none;height:30px;" oninput="autoResize(this);" spellcheck="false"></textarea></td>'+
                            '<td valign="top"><input id="itemNew-'+in_rev+'"  name="item[]" type="text"  class="form-control form-control-sm" autocomplete="off"></td>'+
                           '</tr>';

   $('.table-cotizacion').append(fila)

   in_rev++;
}

var mon = [];
async function Sucursal(valor, data = {},em = "",responsable = ""){
   
    if(valor == ''){
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Seleccione sucursal',
            showConfirmButton: true,
            width:'auto',
            //timer: 1500,
          })
        return false;
    }

    
    
    //Se actuliza la lista de revisiones
     $.ajax({
        type: "POST",
        data: {"sucursal": valor},
        url: "Cargas/loadRevision.php",
        dataType: "json",
        success:function(respuesta){ 
            var opcionesSelect = document.getElementById('listRevision');
            opcionesSelect.options.length = 1;
            if(respuesta == null){return false;}
            respuesta.forEach(function(dato) {
                var opcion = document.createElement('option');
                opcion.value = dato[0]; // Asigna el valor del dato a la opción
                opcion.text = dato[1]; // Asigna el texto visible de la opción
                opcionesSelect.appendChild(opcion); // Agrega la opción al elemento <select>
                    if(data.revision === dato[0]){
                    opcionesSelect.value = dato[0];
                    }
                
              });     

              
        }
    });

    //Se actuliza la lista de clientes
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
                if(data.cliente === dato[0]){
                    opcionesSelect.value = dato[0];
                }
                mon.push({"pkcliente":dato[0],"moneda" : dato[2] }); //Asigna datos al arreglo
              }); 
              
             
        }
    });

    //Se actuliza la lista de empleado por sucursal
    $.ajax({
        type: "POST",
        data: {"sucursal": valor, "soloActivos": "1"},
        url: "Cargas/loadEmpleados.php",
        dataType: "json",
        success:function(respuesta){
            
                let opcionesSelect2 = Array.from(document.getElementsByClassName('listEmployee'));
                let tagCotizo = document.getElementById('cotizo') ?? null;
                let tagResponsable = document.getElementById('responsable') ?? null;

                opcionesSelect2.forEach((resp)=>{
                    resp.options.length = 1;
                    respuesta.forEach(function(dato)  {
                        var opcion2 = document.createElement('option');
                        opcion2.value = dato[0]; // Asigna el valor del dato a la opción
                        opcion2.text = dato[1]; // Asigna el texto visible de la opción
                        resp.appendChild(opcion2); // Agrega la opción al elemento <select>
                        if(em != ""){
                            if(tagCotizo != null){if(em === dato[0]) tagCotizo.value = dato[0];}
                         }
                        if(responsable != ""){
                            if(tagResponsable != null){if(responsable === dato[0]) tagResponsable.value = dato[0];}
                        }
                    });
                })
                
              
        }
    });
}

//Se actuliza la lista de usuarios del cliente
var user = [];
async function usercustomer(valor){
   user = []; // Vacia el arreglo
   $('#buttonUser').attr('onclick','javascript:popup(\'../Catalogo/addusercustomer?customer='+valor+'\')');
   $('#buttonArea').attr('onclick','javascript:Area(\'../Catalogo/adddeptocli?customer='+valor+'\')');
   await $.ajax({
       type: "POST",
       data: {"client": valor},
       url: "Cargas/loadUsercustomer.php",
       dataType: "json",
       success:function(respuesta){

        for(c = 0; c < 2; c++){
           var opcionesSelect = document.getElementsByClassName('listUsercustomer')[c];
           opcionesSelect.options.length = 1;

           respuesta.forEach(function(dato) {
               var opcion = document.createElement('option');
               opcion.value = dato[0]; // Asigna el valor del dato a la opción
               opcion.text = dato[1]; // Asigna el texto visible de la opción
               opcionesSelect.appendChild(opcion); // Agrega la opción al elemento
              /* if(selec === dato[0]){
                    opcionesSelect.value = dato[0];
               }*/

               //Agrega elementos al array;
               user.push({"pkusercli":dato[0],"nombre":dato[1],"depto" : dato[2],"cargo" : dato[3] }); //Asigna datos al arreglo
             });
        }
       }
   });

   //Carga los departamentos por cliente
   await $.ajax({
    type: "POST",
    data: {"cliente": valor},
    url: "Cargas/loadDepto.php",
    dataType: "json",
    success:function(respuesta){ 
        
        var opcionesSelect = document.getElementById('area'); //Obtiene el id del select
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

   return false;

}

//Se actuliza la lista de departamento por el cliente seleccionado
function viewDepto(cliente){
   if(cliente==='') { $('#deptouser').val(""); return false; }

   const id = cliente;
   const indice = user.findIndex(persona => persona.pkusercli === id);

   if (indice != -1) {
    $('#deptouser').val(user[indice]["cargo"]);
   }
}

//Consulta la revision preeliminar
function dataRevision(id)
{
    $.ajax({
        type: "POST",
        data: {"id": id},
        url: "Cargas/loadRevision.php",
        dataType: "json",
        success:async function(respuesta){
            //Obtenemos los id de las etiquetas
            //var depto= document.getElementById("deptouser");
            var clientes= document.getElementById("listClientes");
            let fecha = document.getElementById("fecha");
            let folio = document.getElementById("displayFolio");
            let inputFolio = document.getElementById("folio");
            const revision = respuesta.pkrevision;
            
            //Se asignan los valores
            //depto.value = respuesta.depto;
            clientes.value =  respuesta.cliente;
            fecha.value = respuesta.fecha;
            folio.textContent = respuesta.folio;
            inputFolio.value = respuesta.folio;

            //CONSULTA LOS USUARIOS DE LOS CLIENTES Y LOS ENLISTA
           await usercustomer(respuesta.cliente); 

            //Selecciona el elemento de la lista de usuarios
            //setTimeout(()=>{
                var listuser = document.getElementById("listUser");
                listuser.value = respuesta.solicito;
           // },300);

            //Se muestran los totales
            await Servicios(revision);

            await viewCambio(respuesta.cliente);
            await Cambio();
            //setTimeout(()=>{
            totales();
            //},500);
            

        }
    });
}

//Carga los servicios en la tabla
async function Servicios(rev){
    await $.ajax({
        type: "POST",
        data: {"id": rev},
        url: "Cargas/servicioLev.php",
        success:function(respuesta){
            let tabla = document.getElementById("serv-cotizacion");
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
//Asigna el folio
function setFolio(){

    var sucursal = document.getElementById('sucursal').value;
    let fecha = document.getElementById('fecha').value;
    let iva = document.getElementById('iva').value;
    $.ajax({
        type: "POST",
        data: {"setFolio": "true","sucursal":sucursal,"fecha":fecha,"iva":iva},
        url: "Controller/Cotizacion.php",
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
                 var form = document.getElementById('form-cotizacion');
                 var hidden = "<input type='hidden' name='cotizacion' value='"+respuesta.cotizacion+"'>"
                
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
                    display.$('#display-cotizacion').load(display.location.href + ' .display-table');//actualizas el div
                    
                }
        }
    });
}

//Registro de Cotizacion
function addCotizacion(rev = "",print=false,iva = false) {
    let btnSave = document.getElementById("printIva");
    let btnPrint = document.getElementById("guardar");
    btnSave.disabled = true;
    btnPrint.disabled = true;
   var formData = new FormData($('#form-cotizacion')[0]);
   $.ajax({
       type: "POST",
       data: formData,
       url: "Controller/Cotizacion.php",
       dataType: 'json',
       processData: false,
       contentType: false,
       success:async function(respuesta){
        
        if(('success' in respuesta) == true){
            if (print) {
                let moneda = document.getElementById('tagmoneda') ?? null;

                if(moneda != null && moneda.value === 'DOLAR'){
                    Print(respuesta.cotizacion,iva,"cotizacion","print_cotizacion",true);
                }else{
                    Print(respuesta.cotizacion,iva,"cotizacion","print_cotizacion");
                }
                
            }
            await Swal.fire({
                position: 'top-end',
                icon: 'success',
                text:"Datos Guardados",
                showConfirmButton: false,
                width:'auto',
                position:'center',
                timer: 1500,
            });     
        }
        else if('error_folio' in respuesta){
            btnSave.disabled = false;
            btnPrint.disabled = false;
            await Swal.fire({
                position: 'top-end',
                icon: 'error',
                text:respuesta.error_folio,
                showConfirmButton: false,
                width:'auto',
                position:'center',
                timer: 2500,
            }); return false;
        }
        else{
            btnSave.disabled = false;
            btnPrint.disabled = false;
            await Swal.fire({
                position: 'top-end',
                icon: 'error',
                text:respuesta.Error,
                showConfirmButton: false,
                width:'auto',
                position:'center',
                timer: 2500,
            }); return false;
        }    
           if(rev != "")
           {
               document.location.href = "ecotizacion?edit="+rev;  
           }
           else{

               document.location.href = "ecotizacion?edit="+respuesta.cotizacion;
           }
           
           if(window.opener)
               {
                   var display = window.opener;
                   display.$('#display-cotizacion').load(display.location.href + ' .display-table');//actualizas el div
                   
               }
               
       }
   });

   return false;
}


function menu(data,sub = ""){
    var id = data.id; //Se obtiene el ID del textArea
    var serv = $(data).attr("data-servicio"); //Se obtiene la data del servicio para redireccionar
    let fila = data.parentNode.parentNode;

    let branchSelec = document.getElementById('sucursal').value ?? '';

     let pre =  fila.querySelector("[name='pkservcotizacion[]']");

    $("#"+id).on("contextmenu", function (e) {
        //#region configuracion
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
        
        
        $('#presupuesto').removeAttr('onclick'); //Se remueve el atributo
        $('#presupuestoind').removeAttr('onclick'); //Se remueve el atributo
        $('#servicio').removeAttr('onclick'); //Se remueve el atributo
        $('#eliminar').removeAttr('onclick'); //Se remueve el atributo
        if(pre != null ){
           
            $('#presupuesto').attr('onclick','cargaPresupuesto("'+pre.value+'","'+id+'","'+sub+'")');
            $('#presupuestoind').attr('onclick','cargaPresupuestoInd("'+pre.value+'","'+id+'","'+sub+'")'); 
             
            $("#eliminar").on("click",function () {
                if(sub != ""){
                    eliminarFila(data,pre.value,'true',branchSelec);
                }else{ eliminarFila(data,pre.value,undefined,branchSelec); }
            });
        }else{
            $('#presupuesto').attr('onclick','NotificationPre()'); 
            $('#presupuestoInd').attr('onclick','NotificationPre()');
            $("#eliminar").on("click", function () {
           
                eliminarFila(data);
            });
        } 
        $('#servicio').attr('onclick','javascript:servicios(\''+serv+"&suc="+branchSelec+"&tipo=cotizacion"+'\')'); 
        
        

      
    });
    $("#eliminar").off("click");
}

async function eliminarFila(button,reg = "",sub = "",branch = '') {
    // Obtén la fila a la que pertenece el botón
    let fila = button.parentNode.parentNode;

    const ruta = sub != "" ? "Controller/deleteServsubCot.php" : "Controller/deleteServCotizacion.php";
    
    if(reg != ""){ // Verifica si viene de un registro
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
                    data: {'id': reg,"branch" : branch},
                    url: ruta,
                    dataType: 'json',
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
                        fila.remove();
                        totales();
                        return true;
                    }
                });
             
            }
           
          });
          return false;
    }
    fila.remove();
    totales();
    
}

function NotificationPre(){
    Swal.fire({
        position: 'center',
        icon: 'warning',
        text:"Para presupuestar este servicio hay que guardar los cambios!",
        showConfirmButton: true,
        width:'auto',
        //timer: 1500,
    });   
}


async function cargaPresupuesto(id,fila = "",sub = "")
{
    const suc = document.querySelector("[name='sucursal']").value;
    const cot = document.querySelector("[name='cotizacion']").value;
    const cli = document.querySelector("[name='cliente']").value;

    console.log(sub);

    await $.ajax({
        type: "POST",
        data: {accion: 'findServcot', valor:id, sub : sub},
        url: "../Presupuesto/Controller/Presupuesto",
        success:async function(respuesta){        
                  console.log(respuesta);
                if(respuesta != ''){
                    let idpre = respuesta.replace(/"/g,'');

                    //Abre la ventana modal
                    const url = "../Presupuesto/epresupuesto?edit="+idpre+"&fila="+fila;
                    windowPresupuesto(url);
                    return true;
                }else{
                    const url = `../Presupuesto/presupuesto?suc=${suc}&cot=${cot}&cli=${cli}&fila=${fila}&carga=true&sub=${sub}`;                    
                   windowPresupuesto(url);
                }
        }
    });
}

async function cargaPresupuestoInd(id,fila = "",sub = "")
{
    const suc = document.querySelector("[name='sucursal']").value;
    const cot = document.querySelector("[name='cotizacion']").value;
    const cli = document.querySelector("[name='cliente']").value;

    console.log(sub);

    await $.ajax({
        type: "POST",
        data: {accion: 'findServcot', valor:id, sub : sub},
        url: "../Presupuesto/Controller/Presupuestoind",
        success:async function(respuesta){        
                  console.log(respuesta);
                if(respuesta != ''){
                    let idpre = respuesta.replace(/"/g,'');

                    //Abre la ventana modal
                    const url = "../Presupuesto/epresupuestoind?edit="+idpre+"&fila="+fila;
                    windowPresupuesto(url);
                    return true;
                }else{
                    const url = `../Presupuesto/presupuestoind?suc=${suc}&cot=${cot}&cli=${cli}&fila=${fila}&carga=true&sub=${sub}`;                    
                   windowPresupuesto(url);
                }
        }
    });
}

//Función para abrir la ventana presupuesto
async function windowPresupuesto(URL){
                        const width = screen.availWidth;
                     const height = screen.availHeight;
                    window["PRESUPUESTO"] ? window["PRESUPUESTO"].focus() : window.open(URL, "PRESUPUESTO", `width=${width},height=${height},scrollbars=yes,left=200,addressbar=0,menubar=0,toolbar=0}`);
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
  function addFilaSubcotizacion(num = ""){
    var fila = '<div class="form-inline">'+
                    '<button type="button" onclick="addSubcotizacion(true,'+num+');" id="buttonSubcot" title="Agregar subcotizacion" class="btn btn-dark"  style="width:14px;height:14px;position:relative;padding:0px;border-radius:0px;font-size:10px;color:white"><span style="position:absolute;left:0px;right:0px;bottom:0px;top:0px;margin:0 auto;font-size:13px;color:white;" class="fa fa-plus"></span></button>'+
    '<input type="text" ondblclick="addFilaSubcotizacion('+num+');" name="sub[]" class="form-control form-control-sm txt-11" style="height:20px;font-size:11px;padding:2px;width:50px;">'+
    '<div class="price text-right" style="width:65px;background:white"><p  style="margin-top:3px" class="txt-11">$0.00</p></div>'+
'</div>';

$('.subcotizacion-add').append(fila)
  }

function CalculaContenido(){
    let totalContenido = CantContenido();
    /*
    let contenido = document.querySelectorAll("[name='contenidoReg[]']");
    
    contenido.forEach( elemento => {
            totalContenido +=parseFloat(elemento.value);
    });
    */
    let tagContenido = document.getElementById('cnacional');
    let pcnc = document.getElementById('pcns');

    let subtotal = document.getElementById('subtotal').textContent.replace(/[$,]/g, "");
    let totalPCNC = Math.floor((totalContenido/subtotal)*1000)/1000 ;

    pcnc.value = totalPCNC;
    tagContenido.value = formato(totalContenido);
}

function addSubcotizacion(nuevo = false, num = "",print = false, iva = false) {
    
           
            if(nuevo){
                 
                var formData = new FormData($('#form-cotizacion')[0]);
                formData.append('Newfolio',nuevo);
                if(num != ""){ 
                    num = parseInt(num);
                    num = num == 0 ? 1 :  num+1; //Asigna un numero al nombre de la cotizacion
                    formData.append('nombre',num);
                } 
                Swal.fire({
                    title: "Crear subcotización",
                    text: "¿Desea crear nuevo registro?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Crear"
                  }).then((result) => {
                    if(result.isConfirmed){
                        saveSubcotizacion(formData,true);
                    }
                  }); 
            }else { var formData = new FormData($('#form-subcotizacion')[0]); saveSubcotizacion(formData,false,print,iva);}

           
    return false;
}

function saveSubcotizacion(formData,nuevo = false,print = false,iva = false) {
   
        $.ajax({
            type: "POST",
            data:formData,
            url: "Controller/Subcotizacion.php",
            dataType: 'json',
            processData: false,
            contentType: false,
            success:async function(respuesta){
                console.log(respuesta);
                if(('success' in respuesta) == true){
                    if(!nuevo){
                        await Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            text:"Cambios Guardados",
                            showConfirmButton: false,
                            width:'auto',
                            position:'center',
                            timer: 1500,
                        });    
                    } 
                }else{
                    await Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text:respuesta.Error,
                        showConfirmButton: false,
                        width:'auto',
                        position:'center',
                        timer: 2500,
                    }); 

                    return false;

                }
                const ruta = "subcotizacion?cot="+respuesta.cotizacion;

                if(nuevo){
                    window["SUBCOTIZACION"] ? window["SUBCOTIZACION"].focus() : window.open(ruta,"SUBCOTIZACION","width=1090,height=700,scrollbars=yes,left=200,addressbar=0,menubar=0,toolbar=0" );
                }else{
                    if (print) {
                        let moneda = document.getElementById('tagmoneda') ?? null;

                        if(moneda != null && moneda.value === 'DOLAR'){
                                Print(respuesta.cotizacion,iva,"subcotizacion","print_subcotizacion",true);
                            }else{
                                Print(respuesta.cotizacion,iva,"subcotizacion","print_subcotizacion");
                            }
                        }
                    document.location.href = ruta;  
                }                  
            }
        });

        return false;
    
}

function SentForm(form) {
    form.oninvalid();
    form.onsubmit();
}

function setName(data,sub, branch){
    if(data != '')
    {
        $.ajax({
            type: "POST",
            data: {'id': sub,'name': data,'tipo':'cot','branch' : branch },
            url: "Controller/SetName.php",
            dataType: "json",
            success:function(respuesta){  
                if(('Error' in respuesta)){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text:respuesta.Error,
                        showConfirmButton: false,
                        width:'auto',
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

function Print(respuesta,iva,tipo,name,dolar = false){
                let izquierda = Math.round((screen.width - 800) / 2);
                let arriba = Math.round((screen.height - 1000) / 2);
                
                window[name] ? window[name].focus() : window.open("print/"+tipo+"?cotizacion=" + respuesta+"&iva="+iva+"&moneda="+dolar, name, "width=800,height=1000,scrollbars=yes,left=" + izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");
}

function Add(valor){
    $('#buttonUser').attr('onclick','javascript:popup(\'../Catalogo/addusercustomer?customer='+valor+'\')');
    $('#buttonArea').attr('onclick','javascript:Area(\'../Catalogo/adddeptocli?customer='+valor+'\')');
}


if(document.getElementById("guardar")){
    document.getElementById("guardar").addEventListener("click", function () {

        $('#form-cotizacion').attr('onsubmit', "return addCotizacion('',true);");
        let form = document.getElementById('form-cotizacion');
        SentForm(form);
    });
}

if(document.getElementById("printIva")){
    document.getElementById("printIva").addEventListener("click", function () {
        $('#form-cotizacion').attr('onsubmit', "return addCotizacion('',true,true);");
        let form = document.getElementById('form-cotizacion');
        SentForm(form);
    });
}

if(document.getElementById("printsub")){
    document.getElementById("printsub").addEventListener("click", function () {
        $('#form-subcotizacion').attr('onsubmit', "return addSubcotizacion(undefined,undefined,true);");
        let form = document.getElementById('form-subcotizacion');
        SentForm(form);
    });
}

if(document.getElementById("printsubIva")){
    document.getElementById("printsubIva").addEventListener("click", function () {
        $('#form-subcotizacion').attr('onsubmit', "return addSubcotizacion(undefined,undefined,true,true);");
        let form = document.getElementById('form-subcotizacion');
        SentForm(form);
    });
}

async function viewCambio(cliente){
    //if(cliente==='') { $('#tagmoneda').val(""); return false; }
 
    const id = cliente;
    const indice = mon.findIndex(clientes => clientes.pkcliente === id);
    if (indice != -1) {
     $('#tagmoneda').val(mon[indice]["moneda"]);
    }

    if(mon[indice]["moneda"] === ''){
        $('#tagmoneda').val("NACIONAL");
    }
 }


    document.getElementById("listClientes").addEventListener("change", async function () {
        let cliente = document.getElementById('listClientes').value;
        await viewCambio(cliente);
        await Cambio();
    });


    function exchangeRate(){
        const fechaActual = new Date();
        let fecha = fechaActual.getDate()+'-'+(fechaActual.getMonth()+1)+'-'+fechaActual.getFullYear();
        let cambio = document.getElementsByClassName('apiCambio')[0] ?? null;
        //fetch("https://mx.dolarapi.com/v1/cotizaciones/usd")
        fetch("https://sidofqa.segob.gob.mx/dof/sidof/indicadores/"+fecha)
        .then(response => response.json())
        .then(data => {
            if(cambio != null){
                cambio.value = parseFloat(data.ListaIndicadores[0].valor).toFixed(4);
                //data.fix;
               
            }
        })
        .catch(error => {
            console.error("Hubo un problema con la conexión o la API:", error);
            // Valor por defecto en caso de error
            if (cambio != null) {
                cambio.value = "19.0000"; // Valor por defecto
            }
        });
    }

    