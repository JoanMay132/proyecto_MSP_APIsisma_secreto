window.onload = function() {
    $(".loader").fadeOut("slow");
    
  };

var in_rev = 1;
function addMaterial(data,budget = false)
{
   var fila = '<tr ondblclick=\'addMaterial('+ JSON.stringify(data)+','+budget+')\' id="row-'+in_rev+'" onchange="addMateriales_v2(this);" onclick="menu(this);">';
   if(budget){ fila += '<td width="10px"><button class="btn btn-sm btn-info" style="padding: 0px;" title="Agregar a materiales" data-toggle="tooltip"><small class="fa fa-arrow-left" style="font-size:15px;"></small></button></td>'; }
   fila += '<td><input type="text" name="material[]" class="form-control"></td>'+
                    '<td ><select name="origen[]" class="form-control">'+
                            '<option value=""></option>'+
                            '<option value="NACIONAL" >NACIONAL</option>'+
                            '<option value="IMPORTACION">IMPORTACIÓN</option>'+
                          '</select></td>'+
                    '<td ><select name="unidad[]" class="form-control"><option value=""></option>';
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        fila += '<option value=" '+element.pkunidad+'">'+element.nombre+'</option>';
                    }
                    fila += '</select></td>'+
                    '<td><input type="text" name="precio[]" class="form-control" onchange="moneda(this);"></td></tr>';

   $('.table-materiales').append(fila)

   in_rev++;
}

function addMateriales()
{
	var formData = new FormData($('#form-materiales')[0]);
    formData.append('industrial', 'true');
	$.ajax({
		type: "POST",
		data: formData,
		//dataType: "json",
		url: "Controller/Materiales.php",
	    processData: false,
  		contentType: false,
		success:function(respuesta){
            console.log(respuesta);
            $('#view-materiales').load(location.href + ' #display-materiales');
			var mensaje,tipo = '';
			if(('success' in respuesta) == true){
				mensaje = respuesta.success
				tipo = 'success';
				
			}else{
				mensaje = respuesta.error;
				tipo = 'error';
			}
			Swal.fire({
				position: 'center',
				icon: tipo,
				title: mensaje,
				showConfirmButton: true,
				
			  });
              

			}
	});
    return false;
}

function addMateriales_v2(data)
{
    const row = data;
    let descMaterial = row.querySelector('input[name="material[]"]') ? row.querySelector('input[name="material[]"]').value : row.querySelector('input[name="materialReg[]"]').value;
    let origen = row.querySelector('select[name="origen[]"]') ? row.querySelector('select[name="origen[]"]').value : row.querySelector('select[name="origenReg[]"]').value;
    let unidad = row.querySelector('select[name="unidad[]"]') ? row.querySelector('select[name="unidad[]"]').value : row.querySelector('select[name="unidadReg[]"]').value;
    let precio = row.querySelector('input[name="precio[]"]') ? row.querySelector('input[name="precio[]"]').value : row.querySelector('input[name="precioReg[]"]').value;
    let id = row.querySelector('input[name="pkmaterial[]"]') ? row.querySelector('input[name="pkmaterial[]"]').value : '';
    let sucursal = document.getElementById('sucursal').value;

    console.log(descMaterial);

    if(descMaterial == '' && origen == '' && unidad == '' && precio == ''){
        return false;
    }
	$.ajax({
		type: "POST",
		data: {"material":descMaterial,"origen":origen,"unidad":unidad,"precio":precio,"sucursal":sucursal,"id":id,"industrial":true},
		dataType: "json",
		url: "Controller/Materiales_v2.php",
        beforeSend: function () {
            // Muestra el ícono de carga
            $('#iconoCarga').show();
          },
		success:function(respuesta){
                if(('success' in respuesta) == true){
                    //Crear el elemento input para guardar el pkmaterial
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'pkmaterial[]';
                    input.value = respuesta.pkmaterial;
                    row.appendChild(input);

                    descMaterial.name = 'materialReg[]';
                    origen.name = 'origenReg[]';
                    unidad.name = 'unidadReg[]';
                    precio.name = 'precioReg[]';
                }else if(('error' in respuesta) == true){
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: respuesta.error,
                        showConfirmButton: true,
                        
                      });
                }         

			},
            complete: function () {
                // Oculta el ícono de carga
                $('#iconoCarga').hide();
              }
	});
    return false;
}


function CambioSucursal(data)
{
    let sucursal = data;

    location.href = "materialesind?suc="+sucursal;
}

function eliminarFila(button) {
    // Obtén la fila a la que pertenece el botón
    let fila = button;

    // Obtén el tbody que contiene la fila
    let tbody = fila.parentNode;

    // Elimina la fila del tbody
    tbody.removeChild(fila);
    
}

function menu(data){
    var id = data.id; //Se obtiene el ID de la fila
    //let mat = data.querySelector('input[type="hidden"]').value; //Obtengo el valor del hidden de la fila actual
    let branch = document.getElementById('sucursal').value;
    $("#"+id).on("contextmenu", function (e) {
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
        $('#delete').removeAttr('onclick'); //Se remueve el atributo
        $("#delete").on("click", function () {
            
            if(data.querySelector('input[type="hidden"]') == null){ 
                eliminarFila(data);
                menu.hide();
                return false;
            }
            let mat = data.querySelector('input[type="hidden"]').value;
            Swal.fire({
                title: '¿Confirma eliminar el regitro?',
                text: "No podra revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        data: {"pkmaterial":mat,"branch" : branch,"industrial":true},
                        url: "Controller/deleteMaterial.php",
                            success:function(respuesta){
                                  eliminarFila(data);
                                  menu.hide();
                            }
                        });
                    }
              })
            
        });
        

      
    });
    $("#delete").off("click");
}

//Add a la tabla de analisis de materiales
function add(button,data)
{

    let fila = button.parentNode.parentNode;

    let nombre =fila.querySelector('input[name="materialReg[]"]').value;
    let origen = fila.querySelector('select[name="origenReg[]"]').value;
    let unidad =fila.querySelector('select[name="unidadReg[]"]').value;
    let costo= fila.querySelector('input[name="precioReg[]"]').value;

   
    if(window.opener)
    {
        var ventana = window.opener;
        addMat(nombre,origen,unidad,costo,ventana,data);
    }

    return false;
}

function addMat(nombre,origen,unidad,costo,ventana,data)
{
    var fila = '<tr>'+
                    '<input type="hidden" name="cn[]" class="cnacional" value="'+origen+'">'+
                    '<td><button type="button" onclick="return deleteServ(this,undefined,\'materiales\');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button></td>'+
                    '<td><input type="text" name="nombre[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="'+escapeHTML(nombre)+'"></td>'+
                    '<td><input type="number" name="cantidad[]" onchange="Importe(this);" class="form-control form-control-sm no-bg input-form border-dark text-center" min="0.00" value="0" step="0.01" style="height: 20px;" ></td>'+
                    '<td>'+
                        '<select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidad[]">';
                        for (let index = 0; index < data.length; index++) {
                            const element = data[index];
                               let sel = element.pkunidad == JSON.parse(unidad) ? 'selected' : '';
                            fila += '<option '+sel+' value="'+element.pkunidad+'">'+element.nombre+'</option>';
                        }

                        fila += '</select>'+
                    '</td>'+
                    '<td><input type="text" name="costo[]" onchange="Importe(this); moneda(this);" value="'+costo+'" class="form-control form-control-sm text-center no-bg input-form border-dark" style="height: 20px;"></td>'+
                    '<td><input type="text" name="importe[]" value="$0.00" data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalMaterial" style="height: 20px;" readonly></td>'+
                                    
                '</tr>';

        let tbody =  ventana.document.getElementById('row-importeMaterial')

        tbody.insertAdjacentHTML('beforebegin',fila);
}


function escapeHTML(str) {
    var escapedStr = str.replace(/&/g, '&amp;')
                       .replace(/</g, '&lt;')
                       .replace(/>/g, '&gt;')
                       .replace(/"/g, '&quot;')
                       .replace(/'/g, '&#39;');
    return escapedStr;
  }
