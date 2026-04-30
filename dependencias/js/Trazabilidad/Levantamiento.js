function autoResize(textarea) {
    textarea.style.height = "30px"; // Restaura la altura automática
    // Ajusta la altura del textarea según el contenido
    textarea.style.height = Math.min(textarea.scrollHeight, 150) + "px";
}

var in_rev = 1;
function addServlev(data)
{
   var fila = '<tr style="max-height: 80px;" ondblclick=\'addServlev('+ JSON.stringify(data)+')\' id="serv-'+in_rev+'">'+
                           '<input type="hidden" name="fkcatservReg[]" id="fkcatservNew-'+in_rev+'">'+
                           '<td><input name="pda[]" type="number" min="1.0" step="0.01" class="form-control form-control-sm" name="pda" autocomplete="off"></td>'+
                           '<td><input name="cant[]" type="number" min="1" class="form-control form-control-sm" name="pda" autocomplete="off"></td>'+
                           '<td><select name="unidad[]" class="form-control form-control-sm"><option value=""></option>';
                           for (let index = 0; index < data.length; index++) {
                               var option = data[index];
                               fila += '<option value="'+option.pkunidad+'">'+option.nombre+'</option>';
                               
                           }
                           fila += '</select></td>'+
                           '<td><textarea oninput="autoResize(this)" id="descNew-'+in_rev+'" name="descripcion[]" class="form-control" autocomplete="off" style="resize:none;height:30px;" onclick="menu(this); return false;" data-servicio="servicios?row='+in_rev+'&new=true" ></textarea></td>'+
                           '<td><input onchange="moneda(this);" id="costoNew-'+in_rev+'"  name="costo[]" type="text"  class="form-control form-control-sm" autocomplete="off"></td>'+
                           '</tr>';

   $('.table-levantamiento').append(fila)

   in_rev++;
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
            
                var opcionesSelect2 = document.getElementsByClassName('listEmployee')[0];
                opcionesSelect2.options.length = 1;
                respuesta.forEach(function(dato) {
                    var opcion2 = document.createElement('option');
                    opcion2.value = dato[0]; // Asigna el valor del dato a la opción
                    opcion2.text = dato[1]; // Asigna el texto visible de la opción
                    opcionesSelect2.appendChild(opcion2); // Agrega la opción al elemento <select>
                });
              
        }
    });
}

//Se actuliza la lista de usuarios del cliente
var user = [];
function usercustomer(valor){
   user = []; // Vacia el arreglo
   $('#buttonUser').attr('onclick','javascript:popup(\'../Catalogo/addusercustomer?customer='+valor+'\')');
   $.ajax({
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

               //Agrega elementos al array;
               user.push({"pkusercli":dato[0],"nombre":dato[1],"depto" : dato[2]  }); //Asigna datos al arreglo
             });
        }
       }
   });

   return false;

}



//Se actuliza la lista de departamento por el cliente seleccionado
function viewDepto(cliente){
   if(cliente==='') { $('#deptouser').val(""); return false; }

   const id = cliente;
   const indice = user.findIndex(persona => persona.pkusercli === id);

   $('#deptouser').val(user[indice]["depto"]);
}

/*
//Elimina los servicios
function delServ(data){
   var button_id = $(data).attr("data-serv");
   $('#serv-'+button_id+'').remove();
}
*/

//Consulta la revision preeliminar
function dataRevision(id)
{
    $.ajax({
        type: "POST",
        data: {"id": id},
        url: "Cargas/loadRevision.php",
        dataType: "json",
        success:function(respuesta){
            //Obtenemos los id de las etiquetas
            var depto= document.getElementById("deptouser");
            var clientes= document.getElementById("listClientes");
            let fecha = document.getElementById("fecha");
            let folio = document.getElementById("displayFolio");
            const revision = respuesta.pkrevision;
            
            //Se asignan los valores
            depto.value = respuesta.depto;
            clientes.value =  respuesta.cliente;
            fecha.value = respuesta.fecha;
            folio.textContent = respuesta.folio;

            //CONSULTA LOS USUARIOS DE LOS CLIENTES Y LOS ENLISTA
           usercustomer(respuesta.cliente); 

            //Selecciona el elemento de la lista de usuarios
            setTimeout(()=>{
                var listuser = document.getElementById("listUser");
                listuser.value = respuesta.solicito;
            },300);

            //Se muestra los servicios correspondientes a la revision
            Servicios(revision);
            

        }
    });
}

//Carga los servicios en la tabla
function Servicios(rev){
    $.ajax({
        type: "POST",
        data: {"id": rev},
        url: "Cargas/servicioLev.php",
        success:function(respuesta){
            let tabla = document.getElementById("serv-levantamiento");
            let fila0 = document.getElementById("serv-0");

            //Carga los servicios amtes de serv-0
            fila0.insertAdjacentHTML('beforebegin', respuesta);

            //tabla.innerHTML = respuesta;
            let area = document.querySelectorAll(".cajas-texto")
          area.forEach((elemento) => {
            elemento.style.height = `${elemento.scrollHeight}px`
          })
        
        }
    
    });
}

function eliminarFila(button) {
    // Obtén la fila a la que pertenece el botón
    let fila = button.parentNode.parentNode;
    
    // Obtén el tbody que contiene la fila
    let tbody = fila.parentNode;

    // Elimina la fila del tbody
    tbody.removeChild(fila);
    
}


//Registro de levantamiento

/*
function addRevpreeliminar(rev = ""){
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
           display = "<div style='font-size:14px'>" + respuesta.Error_de_folio+"</div>";
           Swal.fire({
               position: 'top-end',
               icon: 'error',
               html:display,
               showConfirmButton: true,
               width:'auto',
               position:'center',
               //timer: 1500,
             });
           }
           
           else if(rev != "")
           {
               
               document.location.href = "editrevpreeliminar?edit="+rev;
               
           }
           else{

               document.location.href = "editrevpreeliminar?edit="+folioRev;
           }

           if(window.opener)
               {
                   var display = window.opener;
                   display.$('#display-revpreeliminar').load(display.location.href + ' .display-table');//actualizas el div
                   
               }
               
       }
   });

   return false;
}
*/

function menu(data){
    var id = data.id; //Se obtiene el ID del textArea
    var serv = $(data).attr("data-servicio"); //Se obtiene la data del servicio para redireccionar
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
        $('#servicio').attr('onclick','javascript:servicios(\''+serv+'\')'); //Se le asigna un nuevo atributo
        //$('#eliminar').attr('onclick','eliminarFila('+id+')'); //Se le asigna un nuevo atributo
        $("#eliminar").on("click", function () {
           
            eliminarFila(data);
        });
        

      
    });
    $("#eliminar").off("click");
}

