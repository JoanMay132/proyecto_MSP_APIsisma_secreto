window.onload = function() {
    $(".loader").fadeOut("slow");
    
  };

var in_rev = 1;
function addMObra(budget = false)
{
   var fila = '<tr ondblclick=\'addMObra('+budget+')\' id="row-'+in_rev+'" onclick="menu(this);">';
                    if(budget){ fila += '<td width="10px"><button class="btn btn-sm btn-info" style="padding: 0px;" title="Agregar a materiales" data-toggle="tooltip"><small class="fa fa-arrow-left" style="font-size:15px;"></small></button></td>'; }
                    fila += '<td><input type="text" name="descripcion[]" class="form-control"></td>'+
               
                    '<td ><select name="origen[]" class="form-control">'+
                            '<option value=""></option>'+
                            '<option value="NACIONAL" >NACIONAL</option>'+
                            '<option value="IMPORTACION">IMPORTACIÓN</option>'+
                          '</select></td>'+

                    '<td><input type="text" name="precio[]" class="form-control" onchange="moneda(this);"></td></tr>';

   $('.table-MObra').append(fila)

   in_rev++;
}

function addMObras()
{
	var formData = new FormData($('#form-MObra')[0]);
    formData.append('industrial', 'true');
	$.ajax({
		type: "POST",
		data: formData,
		dataType: "json",
		url: "Controller/MObra.php",
		processData: false,
  		contentType: false,
		success:function(respuesta){
            $('#view-MObra').load(location.href + ' #display-MObra');
			var mensaje,tipo = '';
			if(('success' in respuesta) == true){
				mensaje = respuesta.success
				tipo = 'success';
				
			}else{
				mensaje = respuesta.error;
				tipo = 'error';
			}
			Swal.fire({
				position: 'top-end',
				icon: tipo,
				title: mensaje,
				showConfirmButton: true,
				
			  });
              

			}
	});
    return false;
}


function CambioSucursal(data)
{
    let sucursal = data;

    location.href = "mobra?suc="+sucursal;
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
            let mobra = data.querySelector('input[type="hidden"]').value;
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
                        data: {"operario":mobra,"branch":branch, "industrial":true},
                        url: "Controller/deleteMObra.php",
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

//Add a la tabla de analisis de mano de obra
function add(button,data)
{

    let fila = button.parentNode.parentNode;

    let nombre = fila.querySelector('input[name="descripcionReg[]"]').value;
    let origen = fila.querySelector('select[name="origenReg[]"]').value;
    let costo= fila.querySelector('input[name="precioReg[]"]').value;
   
    if(window.opener)
    {
        var ventana = window.opener;
        addMat(nombre,origen,costo,ventana,data,18);
    }

    return false;
}

function addMat(nombre,origen,costo,ventana,data,unidad = 0)
{
    var fila = '<tr>'+
                    '<input type="hidden" name="cnObra[]" class="cnacional" value="'+origen+'">'+
                    '<td><button type="button" onclick="return deleteServ(this,undefined,\'manoobra\');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button></td>'+
                    '<td><input type="text" name="descripcionObra[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="'+nombre+'"></td>'+
                    '<td><input type="number" name="cantidadObra[]" onchange="ImporteObra(this);" class="form-control form-control-sm no-bg input-form border-dark text-center totalHora" min="0" value="0" step="0.01" style="height: 20px;" ></td>'+
                    '<td>'+
                        '<select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidadObra[]">';
                        for (let index = 0; index < data.length; index++) {
                            const element = data[index];
                               let sel = element.pkunidad == JSON.parse(unidad) ? 'selected' : '';
                            fila += '<option '+sel+' value="'+element.pkunidad+'">'+element.nombre+'</option>';
                        }
                        fila += '</select>'+
                    '</td>'+
                    '<td><input type="text" name="costoObra[]" onchange="ImporteObra(this); moneda(this);" value="'+costo+'" class="form-control form-control-sm text-center no-bg input-form border-dark" style="height: 20px;"></td>'+
                    '<td><input type="text" name="importeObra[]" value="$0.00" data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalObra" style="height: 20px;" readonly></td>'+
                                    
                '</tr>';

        let tbody =  ventana.document.getElementById('row-totalHora')

        tbody.insertAdjacentHTML('beforebegin',fila);
}