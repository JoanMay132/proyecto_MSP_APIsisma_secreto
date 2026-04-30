async function Carga(valor,unidades,tipo = ""){
    await cargaMateriales(valor,unidades,tipo);
    await cargaMobra(valor,unidades,tipo);
    await cargaMaquinaria(valor,unidades,tipo);
    await cargaServicios(valor,unidades,tipo);
}

async function cargaMateriales(valor,unidades,tipo){

    $.ajax({
        type: "POST",
        data: { "presupuesto": valor,"tipo" : tipo },
        url: "Cargas/loadMateriales.php",
        dataType: "json",
        success: async function (respuesta) {
            if(window.opener) {
                let ventana = window.opener;
            respuesta.forEach(async data => {
                    await addMat(data.nombre,data.contenido,data.unidad,data.cantidad,data.costounit,data.importe,ventana,unidades);
            });
              //calcula el importe total
              let iTotal = 0; //Varable para sumar los totales del importe
            let tagImportePieza = ventana.document.getElementsByClassName('importeTotalMaterial'); //Por cada elemento
            let tagImporteTotal = ventana.document.getElementById('importeTotalMaterial'); //De presupuesto
            for (let index = 0; index < tagImportePieza.length; index++) {
                iTotal += parseFloat(tagImportePieza[index].value.replace(/[$,]/g, ""));
            }

                tagImporteTotal.textContent = ventana.formato(iTotal);
                        ventana.ImporteTotal();
        }
        }
    });

    return false;
}

async function cargaMobra(valor,unidades,tipo){
    $.ajax({
        type: "POST",
        data: { "presupuesto": valor,"tipo" : tipo  },
        url: "Cargas/loadMobra.php",
        dataType: "json",
        success: async function (respuesta) {
            if(window.opener) {
                let ventana = window.opener;
                respuesta.forEach(data => {
                    addMobra(data.nombre,data.contenido,data.unidad,data.cantidad,data.costounit,data.importe,ventana,unidades);
                });
                
                await cargaExtra(valor,ventana,tipo);
                ventana.ImporteHtaEq(); 
               
            }
        }
    });

    return false;
}

async function cargaExtra(valor,ventana,tipo){
    await $.ajax({
        type: "POST",
        data: { "presupuesto": valor,"tipo" : tipo  },
        url: "Cargas/loadPresupuesto.php",
        dataType: "json",
        success: function (respuesta) {
            //Calcula el total de horas
            let tagTotalHora = ventana.document.getElementsByClassName('totalHora');
            tagTotalHora = Array.from(tagTotalHora);

            let resHora = 0; 
            resHora = tagTotalHora.reduce(function (acumulador, valor) { //Suma el total de horas
                return acumulador + parseFloat(valor.value);
            }, 0);

                ventana.document.getElementById('tExtra').value = respuesta.textra;
                ventana.document.getElementById('costoExtra').value = ventana.formato(respuesta.costoextra);  

                ventana.document.getElementById('canthora').value = parseFloat(resHora);

                let tagSumaTotal = ventana.document.getElementById('sumaTotal');
                tagSumaTotal.textContent = ventana.formato(ventana.tiempoExtra()); //Llama al metodo tiempoExtra
                 
        }
    });

}

async function cargaMaquinaria(valor,unidades,tipo){
    $.ajax({
        type: "POST",
        data: { "presupuesto": valor,"tipo" : tipo  },
        url: "Cargas/loadMaquinaria.php",
        dataType: "json",
        success: async function (respuesta) {
            if(window.opener) {
                let ventana = window.opener;
            respuesta.forEach(async data => {
                    await addMaquinaria(data.nombre,data.contenido,data.unidad,data.cantidad,data.costounit,data.importe,ventana,unidades);
            });
            let iTotal = 0; //Varable para sumar los totales del importe
          
            //calcula el importe total
            let tagImportePieza = ventana.document.getElementsByClassName('importeTotalMaquin'); //Por cada elemento
            let tagImporteTotal = ventana.document.getElementById('importeTotalMaquin'); //De presupuesto
            for (let index = 0; index < tagImportePieza.length; index++) {
              iTotal += parseFloat(tagImportePieza[index].value.replace(/[$,]/g, ""));
            }
            tagImporteTotal.textContent = ventana.formato(iTotal);
          
            ventana.ImporteTotal();
        }
        }
    });

    return false;
}

async function cargaServicios(valor,unidades,tipo){
    $.ajax({
        type: "POST",
        data: { "presupuesto": valor, "tipo" : tipo  },
        url: "Cargas/loadServicios.php",
        dataType: "json",
        success: async function (respuesta) {
            if(window.opener) {
                    let ventana = window.opener;
                respuesta.forEach(async data => {
                        await addServicios(data.nombre,data.contenido,data.unidad,data.cantidad,data.costounit,data.importe,ventana,unidades);
                });
                let iTotal = 0; //Varable para sumar los totales del importe

                //calcula el importe total
                let tagImportePieza = ventana.document.getElementsByClassName('importeTotalAdicional'); //Por cada elemento
                let tagImporteTotal = ventana.document.getElementById('importeTotalAdicional'); //De presupuesto
                for (let index = 0; index < tagImportePieza.length; index++) {
                    iTotal += parseFloat(tagImportePieza[index].value.replace(/[$,]/g, ""));
                }

                tagImporteTotal.textContent = ventana.formato(iTotal);

                ventana.ImporteTotal();
            }
        }
    });

    return false;
}

async function addMat(nombre,origen,unidad,cantidad,costo,importe,ventana,data)
{
    var fila = '<tr>'+
                    '<input type="hidden" name="cn[]" class="cnacional" value="'+origen+'">'+
                    '<td><button type="button" onclick="return deleteServ(this,undefined,\'materiales\');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button></td>'+
                    '<td><input type="text" name="nombre[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="'+nombre+'"></td>'+
                    '<td><input type="number" name="cantidad[]" onchange="Importe(this);" value="'+cantidad+'" class="form-control form-control-sm no-bg input-form border-dark text-center" min="0" step="0.1" style="height: 20px;" ></td>'+
                    '<td>'+
                        '<select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidad[]">';
                        for (let index = 0; index < data.length; index++) {
                            const element = data[index];
                               let sel = element.pkunidad == JSON.parse(unidad) ? 'selected' : '';
                            fila += '<option '+sel+' value="'+element.pkunidad+'">'+element.nombre+'</option>';
                        }

                        fila += '</select>'+
                    '</td>'+
                    '<td><input type="text" name="costo[]" onchange="Importe(this); moneda(this);" value="$'+costo+'" class="form-control form-control-sm text-center no-bg input-form border-dark" style="height: 20px;"></td>'+
                    '<td><input type="text" name="importe[]"  value="$'+importe+'"  data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalMaterial" style="height: 20px;" readonly></td>'+
                                    
                '</tr>';

        let tbody =  ventana.document.getElementById('row-importeMaterial')

        tbody.insertAdjacentHTML('beforebegin',fila);
}

async function addMaquinaria(nombre,origen,unidad,cantidad,costo,importe,ventana,data)
{
    var fila = '<tr>'+
                    '<input type="hidden" name="cnMaquin[]" class="cnacional" value="'+origen+'">'+
                    '<td><button type="button" onclick="return deleteServ(this,undefined,\'maquinaria\');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button></td>'+
                    '<td><input type="text" name="descripcionMaquin[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="'+nombre+'"></td>'+
                    '<td><input type="number" name="cantidadMaquin[]" value="'+cantidad+'" onchange="ImporteMaquinaria(this);" class="form-control form-control-sm no-bg input-form border-dark text-center" min="0" step="0.1" style="height: 20px;" ></td>'+
                    '<td>'+
                        '<select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidadMaquin[]">';
                        for (let index = 0; index < data.length; index++) {
                            const element = data[index];
                               let sel = element.pkunidad == JSON.parse(unidad) ? 'selected' : '';
                            fila += '<option '+sel+' value="'+element.pkunidad+'">'+element.nombre+'</option>';
                        }

                        fila += '</select>'+
                    '</td>'+
                    '<td><input type="text" name="costoMaquin[]" onchange="ImporteMaquinaria(this); moneda(this);" value="$'+costo+'" class="form-control form-control-sm text-center no-bg input-form border-dark" style="height: 20px;"></td>'+
                    '<td><input type="text" name="importeMaquin[]" value="$'+importe+'"  data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalMaquin" style="height: 20px;" readonly></td>'+
                                    
                '</tr>';

        let tbody =  ventana.document.getElementById('row-importeMaquin')

        tbody.insertAdjacentHTML('beforebegin',fila);
}

async function addMobra(nombre,origen,unidad,cantidad,costo,importe,ventana,data){
    var fila = '<tr>'+
    '<input type="hidden" name="cnObra[]" class="cnacional" value="'+origen+'">'+
    '<td><button type="button" onclick="return deleteServ(this,undefined,\'manoobra\');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button></td>'+
    '<td><input type="text" name="descripcionObra[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="'+nombre+'"></td>'+
    '<td><input type="number" name="cantidadObra[]" value="'+cantidad+'" onchange="ImporteObra(this);" class="form-control form-control-sm no-bg input-form border-dark text-center totalHora" min="0" step="0.1" style="height: 20px;" ></td>'+
    '<td>'+
        '<select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidadObra[]">';
        for (let index = 0; index < data.length; index++) {
            const element = data[index];
               let sel = element.pkunidad == JSON.parse(unidad) ? 'selected' : '';
            fila += '<option '+sel+' value="'+element.pkunidad+'">'+element.nombre+'</option>';
        }
        fila += '</select>'+
    '</td>'+
    '<td><input type="text" name="costoObra[]" onchange="ImporteObra(this); moneda(this);" value="$'+costo+'" class="form-control form-control-sm text-center no-bg input-form border-dark" style="height: 20px;"></td>'+
    '<td><input type="text" name="importeObra[]" value="$'+importe+'"  data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalObra" style="height: 20px;" readonly></td>'+
                    
'</tr>';

let tbody =  ventana.document.getElementById('row-totalHora')

tbody.insertAdjacentHTML('beforebegin',fila);
}

async function addServicios(nombre,origen,unidad,cantidad,costo,importe,ventana,data)
{
    let  fila = '<tr>'+
    '<input type="hidden" name="cnAdicional[]" class="cnacional" value="'+origen+'">'+
    '<td><button type="button" onclick="return deleteServ(this,undefined,\'servicios\');" class="btn btn-sm btn-outline-danger" style="height:20px;padding-top:0px"><span class="fa fa-trash" style="margin-top:-20px"></span></button></td>'+
    '<td><input type="text" name="descripcionAdicional[]" class="form-control form-control-sm  no-bg input-form border-dark" style="height: 20px;" value="'+nombre+'"></td>'+
    '<td><input type="number" name="cantidadAdicional[]" value="'+cantidad+'" onchange="ImporteAdicional(this);" class="form-control form-control-sm no-bg input-form border-dark text-center" min="0" step="0.1" style="height: 20px;" ></td>'+
    '<td>'+
        '<select class="form-control form-control-sm no-bg text-center border-dark input-form" style="height:20px;padding:0px" name="unidadAdicional[]">';
        for (let index = 0; index < data.length; index++) {
            const element = data[index];
               let sel = element.pkunidad == JSON.parse(unidad) ? 'selected' : '';
            fila += '<option '+sel+' value="'+element.pkunidad+'">'+element.nombre+'</option>';
        }

        fila += '</select>'+
    '</td>'+
    '<td><input type="text" name="costoAdicional[]" onchange="ImporteAdicional(this); moneda(this);" value="$'+costo+'" class="form-control form-control-sm text-center no-bg input-form border-dark" style="height: 20px;"></td>'+
    '<td><input type="text" name="importeAdicional[]" value="$'+importe+'" data-cnc="importe" class="form-control form-control-sm text-center no-bg input-form border-dark importeTotalAdicional" style="height: 20px;" readonly></td>'+
                    
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
