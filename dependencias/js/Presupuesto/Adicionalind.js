window.onload = function() {
    $(".loader").fadeOut("slow");
    
  };

var in_rev = 1;
function addAdicional(data,budget = false)
{
   var fila = '<tr ondblclick=\'addAdicional('+ JSON.stringify(data)+')\' id="row-'+in_rev+'" onclick="menu(this);">';
   if(budget){ fila += '<td width="10px"><button class="btn btn-sm btn-info" style="padding: 0px;" title="Agregar a Presupuesto" data-toggle="tooltip"><small class="fa fa-arrow-left" style="font-size:15px;"></small></button></td>'; }
                   fila +=  '<td><input type="text" name="adicional[]" class="form-control"></td>'+
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

   $('.table-adicionales').append(fila)

   in_rev++;
}

function addAdicionales()
{
	var formData = new FormData($('#form-adicional')[0]);
    formData.append('industrial', 'true');
	$.ajax({
		type: "POST",
		data: formData,
		dataType: "json",
		url: "Controller/Adicional.php",
		processData: false,
  		contentType: false,
		success:function(respuesta){
            $('#view-adicionales').load(location.href + ' #display-adicionales');
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


function CambioSucursal(data)
{
    let sucursal = data;

    location.href = "adicionalesind?suc="+sucursal;
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
                        data: {"adicional":mat,"branch" : branch, "industrial":true},
                        url: "Controller/deleteAdicional.php",
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

//Add a la tabla de analisis de Adicional
function add(button,data)
{

    let fila = button.parentNode.parentNode;

    let nombre = fila.querySelector('input[name="adicionalReg[]"]').value;
    let origen = fila.querySelector('select[name="origenReg[]"]').value;
    let costo= fila.querySelector('input[name="precioReg[]"]').value;
    let unidad= fila.querySelector('select[name="unidadReg[]"]').value;
   
    if(window.opener)
    {
        var ventana = window.opener;
        addMat(nombre,origen,costo,ventana,data,unidad);
    }

    return false;
}

function addMat(nombre,origen,costo,ventana,data,unidad = 0)
{
    var fila = '<tr>'+
                    '<input type="hidden" name="cnAdicional[]" class="cnacional" value="'+origen+'">'+
                    '<td><button type="button" onclick="return deleteServ(this,undefined,\'servicios\');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button></td>'+
                    '<td><input type="text" name="descripcionAdicional[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="'+escapeHTML(nombre)+'"></td>'+
                    '<td><input type="number" name="cantidadAdicional[]" onchange="ImporteAdicional(this);" class="form-control form-control-sm no-bg input-form border-dark text-center" min="0" value="0" step="0.01" style="height: 20px;" ></td>'+
                    '<td>'+
                        '<select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidadAdicional[]">';
                        for (let index = 0; index < data.length; index++) {
                            const element = data[index];
                               let sel = element.pkunidad == JSON.parse(unidad) ? 'selected' : '';
                            fila += '<option '+sel+' value="'+element.pkunidad+'">'+element.nombre+'</option>';
                        }

                        fila += '</select>'+
                    '</td>'+
                    '<td><input type="text" name="costoAdicional[]" onchange="ImporteAdicional(this); moneda(this);" value="'+costo+'" class="form-control form-control-sm text-center no-bg input-form border-dark" style="height: 20px;"></td>'+
                    '<td><input type="text" name="importeAdicional[]" value="$0.00" data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalAdicional" style="height: 20px;" readonly></td>'+
                                    
                '</tr>';

        let tbody =  ventana.document.getElementById('row-importeAdicional')

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
