function OpenWindows(URL, name) {
  // Verificar si la ventana ya está abierta
  if (window[name]) {
    // La ventana ya está abierta, enfocarla
    window[name].focus();
  } else {
    // La ventana no está abierta, abrir una nueva
    window.open(URL, name, "width=600,height=600,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0");
  }

  return false;
}


//Convierte los input tipo text en valores porcentuales
function Porcentaje() {
  var inputs = document.getElementsByClassName('porcentaje');

  // Recorre todos los elementos y agrega el evento 'input'
  for (var i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('change', function () {
      // Obtén el valor del input actual
      let inputValue = this.value;

      // Elimina caracteres no numéricos
      inputValue = inputValue.replace(/[^0-9]/g, '');

      // Añade el símbolo de porcentaje al final
      inputValue = inputValue + '%';

      inputValue = inputValue === "%" ? "0%" : inputValue;
      // Asigna el valor formateado de nuevo al input actual
      this.value = inputValue;
    });
  }

}

Porcentaje();

async function Importe(data, del = false) {
  let row = data.parentNode.parentNode;
  let cantidad = row.querySelector('input[name="cantidad[]"]').value;
  let costo = row.querySelector('input[name="costo[]"]').value;
  let tagimporte = row.querySelector('input[name="importe[]"]');
  let iTotal = 0; //Varable para sumar los totales del importe

  //calcula el importe por pieza
  costo = parseFloat(costo.replace(/[$,]/g, ""));
  tagimporte.value = formato(cantidad * costo);

  //calcula el importe total
  let tagImportePieza = document.getElementsByClassName('importeTotalMaterial'); //Por cada elemento
  let tagImporteTotal = document.getElementById('importeTotalMaterial'); //De presupuesto
  for (let index = 0; index < tagImportePieza.length; index++) {
    iTotal += parseFloat(tagImportePieza[index].value.replace(/[$,]/g, ""));

    console.log(iTotal);
  }

  //let delet = 0;
  // if (del) {
  //   delet = tagimporte.value.replace(/[$,]/g, "");
  // }

  tagImporteTotal.textContent = formato(iTotal);

  ImporteTotal();

}

async function ImporteObra(data, del = false) {
  
    let row = data.parentNode.parentNode;
    let cantidad = row.querySelector('input[name="cantidadObra[]"]').value;
    let costo = row.querySelector('input[name="costoObra[]"]').value;
    let tagimporte = row.querySelector('input[name="importeObra[]"]');
  

  //let iTotal = 0; //Varable para sumar los totales del importe

  // let delet = 0;
  // if (del) {
  //   delet = tagimporte.value.replace(/[$,]/g, "");
  // }

  //calcula el importe por pieza
  costo = parseFloat(costo.replace(/[$,]/g, ""));
  tagimporte.value = formato((cantidad * costo));

  //Calcula el total de horas
  let tagTotalHora = document.getElementsByClassName('totalHora');
  tagTotalHora = Array.from(tagTotalHora);

  let resHora = 0; 
  resHora = tagTotalHora.reduce(function (acumulador, valor) { //Suma el total de horas
    let horas = parseFloat(valor.value) || 0;  
    return acumulador + horas;
  }, 0);

  document.getElementById('canthora').value = parseFloat(resHora);

  let tagSumaTotal = document.getElementById('sumaTotal');
  tagSumaTotal.textContent = formato(tiempoExtra()); //Llama al metodo tiempoExtra


  //tagImporteTotal.textContent = formato(iTotal + tiempoExtra());   
  ImporteHtaEq();

}

async function ImporteMaquinaria(data, del = false) {
  let row = data.parentNode.parentNode;
  let cantidad = row.querySelector('input[name="cantidadMaquin[]"]').value;
  let costo = row.querySelector('input[name="costoMaquin[]"]').value;
  let tagimporte = row.querySelector('input[name="importeMaquin[]"]');

  let iTotal = 0; //Varable para sumar los totales del importe

  //calcula el importe por pieza
  costo = parseFloat(costo.replace(/[$,]/g, ""));
  tagimporte.value = formato(cantidad * costo);

  //calcula el importe total
  let tagImportePieza = document.getElementsByClassName('importeTotalMaquin'); //Por cada elemento
  let tagImporteTotal = document.getElementById('importeTotalMaquin'); //De presupuesto
  for (let index = 0; index < tagImportePieza.length; index++) {
    iTotal += parseFloat(tagImportePieza[index].value.replace(/[$,]/g, ""));
  }

  // let delet = 0;
  // if (del) {
  //   delet = tagimporte.value.replace(/[$,]/g, "");
  // }

  tagImporteTotal.textContent = formato(iTotal);

  ImporteTotal();

}

async function ImporteAdicional(data, del = false) {
  let row = data.parentNode.parentNode;
  let cantidad = row.querySelector('input[name="cantidadAdicional[]"]').value;
  let costo = row.querySelector('input[name="costoAdicional[]"]').value;
  let tagimporte = row.querySelector('input[name="importeAdicional[]"]');

  let iTotal = 0; //Varable para sumar los totales del importe

  //calcula el importe por pieza
  costo = parseFloat(costo.replace(/[$,]/g, ""));
  tagimporte.value = formato(cantidad * costo);

  //calcula el importe total
  let tagImportePieza = document.getElementsByClassName('importeTotalAdicional'); //Por cada elemento
  let tagImporteTotal = document.getElementById('importeTotalAdicional'); //De presupuesto
  for (let index = 0; index < tagImportePieza.length; index++) {
    iTotal += parseFloat(tagImportePieza[index].value.replace(/[$,]/g, ""));
  }

  // let delet = 0;
  // if (del) {
  //   delet = tagimporte.value.replace(/[$,]/g, "");
  // }

  tagImporteTotal.textContent = formato(iTotal);

  ImporteTotal();

}

function tiempoExtra() {
  //suma tiempo extra
  let tExtra = document.getElementById('tExtra').value;
  let costoExtra = parseFloat(document.getElementById('costoExtra').value.replace(/[$,]/g, ""));
  let iTotal = 0; //Varable para sumar los totales del importe
  let totalExtra = (tExtra * costoExtra);
  document.getElementById('totalExtra').textContent = formato(totalExtra);

  //calcula el importe total
  let tagImportePieza = document.getElementsByClassName('importeTotalObra'); //Por cada elemento
  let tagImporteTotal = document.getElementById('importeTotalObra'); //De presupuesto
  for (let index = 0; index < tagImportePieza.length; index++) {
    iTotal += parseFloat(tagImportePieza[index].value.replace(/[$,]/g, ""));
  }
  tagImporteTotal.textContent = formato(iTotal + totalExtra);
  ImporteTotal(); //Cuando se llame al metodo tiempo extra, este llamara a Importe Totals

  return iTotal; //Para la suma
}

//Calcula el total de todos los importes
function ImporteTotal() {
  let tagImporte = document.getElementsByClassName('importeTotal');
  let total = 0;
  for (let index = 0; index < tagImporte.length; index++) {
    total += parseFloat(tagImporte[index].innerText.replace(/[$,]/g, ""));
  }

  let tagDirectos = document.getElementById('cDirectos');
  let tagIndirectos = document.getElementById('cindirectos');
  let tagIindirecto = parseFloat(document.getElementById('iindirectos').value.replace("%", ""));
  let tagSuma = document.getElementById('suma');
  let tagFinanciamiento = document.getElementById('financiamiento');
  let tagIfinanciamiento = parseFloat(document.getElementById('ifinanciamiento').value.replace("%", ""));
  let tagSubtotal = document.getElementById('subtotal');
  let tagUtilidad = document.getElementById('utilidad');
  let tagIutilidad = parseFloat(document.getElementById('iutilidad').value.replace('%', ""));
  let tagTotalAll = document.getElementById('totalAll');
  let tagTotalUnitario = document.getElementById('totalUnitario');

  tagDirectos.textContent = formato(total);

  //Calcula indirecto
  let totalIndirecto = total * (tagIindirecto / 100)
  tagIndirectos.textContent = formato(totalIndirecto);
  let totalSuma = (total + totalIndirecto);
  tagSuma.textContent = formato((totalSuma)); //Sumas

  //calcula financiamiento
  let totalFinanciamiento = totalSuma * (tagIfinanciamiento / 100);
  tagFinanciamiento.textContent = formato(totalFinanciamiento);

  let totalSubtotal = totalSuma + totalFinanciamiento;
  tagSubtotal.textContent = formato(totalSubtotal);

  //Calcula utilidad bruta
  let totalUtilidad = totalSubtotal * (tagIutilidad / 100);
  tagUtilidad.textContent = formato(totalUtilidad);

  //Muestra el total de todo
  tagTotalAll.textContent = formato((totalSubtotal + totalUtilidad));
  tagTotalUnitario.value = formato((totalSubtotal + totalUtilidad));

  CNacional();
  Operaciones();

}

//contenido importado
function Importado() {
  let tagCNacional = document.getElementsByClassName('cnacional');
  let arregloDeElementos = Array.from(tagCNacional); //Convierte de html a arreglo jscript

  let elementosFiltrados = arregloDeElementos.filter(function (elemento) { //Filtra los elementos de importacion
    return elemento.value.includes("IMPORTACION");
  });

  let totalCnc = 0
  for (let index = 0; index < elementosFiltrados.length; index++) {
    let element = elementosFiltrados[index].parentNode;
    let tagImporte = element.querySelector('[data-cnc="importe"]');

    if (tagImporte) { // Verificar si se encontró un elemento con el selector 'input[name="importe[]"]'
      totalCnc += parseFloat(tagImporte.value.replace(/[$,]/g, ""));
    }
  }

  return totalCnc;

}

function CNacional() {
  let tagCNacional = document.getElementById('cnacional');
  let tagPCNC = document.getElementById('pcnc');

  let valorImportado = Importado();
  let valorTotal = parseFloat(document.getElementById('totalAll').innerText.replace(/[$,]/g, ""));
  let valorCnc = (valorTotal - valorImportado);
  let pcnc = (valorCnc / valorTotal);

  tagCNacional.value = formato(valorCnc);
  tagPCNC.textContent = !isNaN(pcnc) ? Math.floor(pcnc * 1000) / 1000 : "0.00";
}

function Operaciones() {
  let costo = parseFloat(document.getElementById('totalAll').innerText.replace(/[$,]/g, "")); //Costo total
  let inputMul = document.getElementById('iMul').value;
  let tagMul = document.getElementById('cpieza');
  let inputDiv = document.getElementById('iDiv').value;
  let tagDiv = document.getElementById('cdiv');



  let resulMul = (inputMul * costo);
  tagMul.value = formato(resulMul);

  let resulDiv = inputDiv != 0 ? (costo / inputDiv) : 0;
  tagDiv.value = formato(resulDiv);

}

function ImporteHtaEq() {
  let totalMobra = document.getElementById('importeTotalObra');//Total de la mano de obra
  totalMobra = totalMobra.innerText.replace(/[$,]/g, "");

  //Calcula el importe total de la HtasMenor
  let inputHtamenor = parseFloat(document.getElementById('inputHtamenor').value.replace('%', ""));
  let importeTotalHtamenor = totalMobra * (inputHtamenor / 100);
  let tagTotalHtamenor = document.getElementById('totalHtamenor');
  tagTotalHtamenor.textContent = formato(importeTotalHtamenor);

  //Calcula el importe de Equipo de seguridad
  let inputEquipo = parseFloat(document.getElementById('inputEquipo').value.replace('%', ""));
  let importeTotalEquipo = totalMobra * (inputEquipo / 100);
  let tagTotalEquipo = document.getElementById('totalEquipo');
  tagTotalEquipo.textContent = formato(importeTotalEquipo);

  ImporteTotal();
}

function formato(numero) {
  var numeroFormateado = numero.toLocaleString('es-MX', {
    style: 'currency',
    currency: 'MXN',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });

  return numeroFormateado;
}

async function Sucursal(valor, cot = "") {

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
  await $.ajax({
    type: "POST",
    data: { "sucursal": valor },
    url: "Cargas/Cotizacion.php",
    dataType: "json",
    success: async function (respuesta) {
      let opcionesSelect = document.getElementById('listCotizacion');
      opcionesSelect.options.length = 1;
      if (respuesta == null) { return false; }
      await respuesta.forEach(function (dato) {
        var opcion = document.createElement('option');
        opcion.value = dato[0]; // Asigna el valor del dato a la opción
        opcion.text = dato[1]; // Asigna el texto visible de la opción
        opcionesSelect.appendChild(opcion); // Agrega la opción al elemento <select>

        //Cuando se consulta la cotización en el modo edit
        
        /*
            if(data.revision === dato[0]){
            opcionesSelect.value = dato[0];
            }*/
      });
      if (cot != "") {
        console.log(cot);
        opcionesSelect.value = cot; 
      }
    }
  });
  await Cliente(valor);
}

async function Cliente(valor, cli = "") {
  //Se actuliza la lista de clientes
  await $.ajax({
    type: "POST",
    data: { "sucursal": valor },
    url: "../Trazabilidad/Cargas/loadClientes.php",
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
        if (cli != "") {
          if (cli === dato[0]) { opcionesSelect.value = dato[0]; }
        }
        /*if(data.cliente === dato[0]){
            opcionesSelect.value = dato[0];
        }*/
      });
    }
  });
}

function usercustomer(valor) {
  let solicita = document.getElementById('solicita');
  solicita.value = "";
  $.ajax({
    type: "POST",
    data: { "user": valor },
    url: "Cargas/Usercustomer.php",
    dataType: "json",
    success: function (respuesta) {

      solicita.value = respuesta.titulo + ". " + respuesta.nombre
    }
  });
}

function dataCotizacion(valor) {
  $.ajax({
    type: "POST",
    data: { "id": valor },
    url: "Cargas/Cotizacion.php",
    dataType: "json",
    success: function (respuesta) {
      //Obtenemos los id de las etiquetas
      let clientes = document.getElementById("listClientes");
      let fecha = document.getElementById("fecha");
      let folio = document.getElementById("displayFolio");
      let inputFolio = document.getElementById("inputFolio");
      //Se asignan los valores
      if(clientes){
        clientes.value = respuesta.cliente;
      }
      
      fecha.value = respuesta.fecha;
      folio.textContent = respuesta.folio;
      inputFolio.value = respuesta.folio;

      usercustomer(respuesta.solicito);
      //Abre la lista de servicios
      OpenWindows("Cargas/Serviciosind.php?id=" + valor + "", "SERVICIOS")
    }
  });

}
function dataCotizacion2(valor) {
      OpenWindows("Cargas/Servicios2.php?id=" + valor + "", "SERVICIOS")
}

//Registro de Presupuesto
function addPresupuesto(ed = "") {
    let btnSave = document.getElementById("btnSave");
    let btnPrint = document.getElementById("btnPrint");
    btnSave.disabled = true;
    btnPrint.disabled = true;
  var formData = new FormData($('#form-presupuesto')[0]);
  $.ajax({
    type: "POST",
    data: formData,
    url: "Controller/Presupuestoind.php",
    dataType: 'json',
    processData: false,
    contentType: false,
    success: async function (respuesta) {
      if (('Success' in respuesta) == true) {
        await Swal.fire({
          position: 'top-end',
          icon: 'success',
          text: respuesta.Success,
          showConfirmButton: false,
          width: 'auto',
          position: 'center',
          timer: 1500,
        });
      }
      else if((respuesta.Error)){
          btnSave.disabled = false;
           btnPrint.disabled = false;
        await Swal.fire({
          icon: 'error',
          text: respuesta.Error,
          showConfirmButton: false,
          width: '450',
          position: 'center',
          timer: 2000,
        });

        return false;
      }
      if (ed != "") {
        document.location.href = "epresupuestoind?edit=" + ed;
      }
      else {

        document.location.href = "epresupuestoind?edit=" + respuesta.Presupuesto;
      }

      if (window.opener) {
        var display = window.opener;
        display.$('#display-listpresupuesto').load(display.location.href + ' .display-table');//actualizas el div

      }

    }
  });

  return false;
}

//Registro de Predefinido
function addPredefinido(ed = "") {
  let btnSave = document.getElementById("btnSave");
    let btnPrint = document.getElementById("btnPrint");
    btnSave.disabled = true;
    btnPrint.disabled = true;
  var formData = new FormData($('#form-presupuesto')[0]);
  $.ajax({
    type: "POST",
    data: formData,
    url: "Controller/Predefinido.php",
    dataType: 'json',
    processData: false,
    contentType: false,
    success: async function (respuesta) {
      if (('Success' in respuesta) == true) {
        await Swal.fire({
          position: 'top-end',
          icon: 'success',
          text: respuesta.Success,
          showConfirmButton: false,
          width: 'auto',
          position: 'center',
          timer: 1500,
        });
      }
      else if((respuesta.Error)){
        btnSave.disabled = false;
        btnPrint.disabled = false;
        await Swal.fire({
          icon: 'error',
          text: respuesta.Error,
          showConfirmButton: false,
          width: '450',
          position: 'center',
          timer: 2000,
        });

        return false;
      }
      if (ed != "") {
        document.location.href = "epredefinido?edit=" + ed;
      }
      else {
        document.location.href = "epredefinido?edit=" + respuesta.Presupuesto;
      }

      if (window.opener) {
        var display = window.opener;
        display.$('#display-listpresupuesto').load(display.location.href + ' .display-table');//actualizas el div

      }

    }
  });

  return false;
}

async function Carga(suc, cot, cli, cursor = "") {
  await Sucursal(suc, cot);

  await Cliente(suc, cli);

  ImporteHtaEq();
  //Verifica si se abre de cotizaciones y la ventana de presupuestos
  if (window.opener && (window.opener.location.href.includes("ecotizacion") || window.opener.location.href.includes("subcotizacion")) && window.location.href.includes("presupuesto")) {
    const ventana = window.opener;
    let servicio = "";
    let pda = "";
    let fila = "";
    let iserv = "";


    //Elementos de la ventana emergente
    let folio = ventana.document.getElementById('folio').value;
    let fecha = ventana.document.getElementById('fecha').value;
    let solicito = ventana.document.querySelector("[data-id='solicito']");
    solicito = solicito.options[solicito.selectedIndex].textContent; //Obtenemos el indice del elemento seleccionado

    if (cursor != '') {
      fila = ventana.document.getElementById(cursor).parentNode.parentNode; //Se obtiene la fila del cursor

      servicio = ventana.document.getElementById(cursor).textContent;
      pda = fila.querySelector("[name='pdaReg[]']").value;
      iserv = fila.querySelector("[name='pkservcotizacion[]']").value;

    }

    //Elementos de la ventana presupuesto
    let folioPre = document.getElementById('inputFolio');
    let solicitoPre = document.getElementById('solicita');
    let folioPreDisplay = document.getElementById('displayFolio');
    let servicioPre = document.getElementById('inputServicio');
    let pdaPre = document.getElementById('pda');
    let iservicioPre = document.getElementById('servicio');
    let fechaPre = document.getElementById('fecha');

    //Se asignan los valores
    folioPre.value = folioPre.value == 0 ? folio : folioPre.value;
    folioPreDisplay.textContent = folioPreDisplay.textContent == 0 ? folio : folioPreDisplay.textContent
    solicitoPre.value = solicitoPre.value === "" ? solicito : solicitoPre.value;
    servicioPre.textContent = servicioPre.textContent === "" ? servicio : servicioPre.textContent;
    pdaPre.value = pdaPre.value === "" ? pda : pdaPre.value;
    iservicioPre.value = iservicioPre.value === "" ? iserv : iservicioPre.value;
    fechaPre.value = fechaPre.value === "" ? fecha : fechaPre.value;

  }

}

//Elimina los servicios
async function deleteServ(id, data = "", tipo = "") {

  let tipos = ['materiales', 'manoobra', 'maquinaria', 'servicios'];
  let fila = id.parentNode.parentNode;
  const index = tipos.indexOf(tipo);
  const branch = document.getElementById('sucursal').value ?? "";
  if (index >= 0) {
    if (data == "") {
      await fila.remove();
      await handleTipo(index, id);
      return false;
    } else {
      tipo = tipos[index];
    }
  } else { return false; }

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
        data: { 'data': data, 'tipo': tipo, "branch": branch },
        url: "Controller/deleteServicioind.php",
        success: async function (respuesta) {
          if(respuesta == ''){
            return false;
          }
          fila.remove();
          await handleTipo(index, id);
          let form = document.getElementById('form-presupuesto');
          form.onsubmit();
        }
      });
    }
  });


  return false;

  async function handleTipo(index, id) {
    switch (index) {
      case 0: await Importe(id, true); break;
      case 1: await ImporteObra(id, true); break;
      case 2: await ImporteMaquinaria(id, true); break;
      case 3: await ImporteAdicional(id, true); break;
    }
  }
}

//Elimina los servicios
async function deleteServPredefinido(id, data = "", tipo = "") {

  let tipos = ['materiales', 'manoobra', 'maquinaria', 'servicios'];
  let fila = id.parentNode.parentNode;
  const index = tipos.indexOf(tipo);
  const branch = document.getElementById('sucursal').value ?? "";
  if (index >= 0) {
    if (data == "") {
      await fila.remove();
      await handleTipo(index, id);
      return false;
    } else {
      tipo = tipos[index];
    }
  } else { return false; }

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
        data: { 'data': data, 'tipo': tipo, "branch": branch },
        url: "Controller/deleteServicioPredefinido.php",
        success: async function (respuesta) {
          if(respuesta == ''){
            return false;
          }
          fila.remove();
          await handleTipo(index, id);
          let form = document.getElementById('form-presupuesto');
          form.onsubmit();
        }
      });
    }
  });
  
  return false;

  async function handleTipo(index, id) {
    switch (index) {
      case 0: await Importe(id, true); break;
      case 1: await ImporteObra(id, true); break;
      case 2: await ImporteMaquinaria(id, true); break;
      case 3: await ImporteAdicional(id, true); break;
    }
  }
}

//Evento cuando se cierre la ventana, asigna el costo al servicio de la cotizacion
window.addEventListener('beforeunload', function (event) {
  if (window.opener && (window.opener.location.href.includes("ecotizacion" || "subcotizacion") || window.opener.location.href.includes("subcotizacion"))) {
    if (window.location.href.includes("fila")) {
      // Crear un nuevo objeto URLSearchParams con la cadena de consulta de la URL actual
      let params = new URLSearchParams(window.location.search);

      // Obtener el valor de 'fila'
      let filaValue = params.get('fila');
      filaValue = window.opener.document.getElementById(filaValue);
      let fila = filaValue.parentNode.parentNode;
      let costo = fila.querySelector("[name='costoReg[]']");
      let ContenidoServ = fila.querySelector("[name='contenidoReg[]']");
      //let ContenidoCot =parseFloat(tagContenidoCot.value.replace(/[$,]/g, ""));

      let importeTotalPre = document.getElementById('totalUnitario');
      let contenidonc = parseFloat(document.getElementById('cnacional').value.replace(/[$,]/g, ""));

      ContenidoServ.value = contenidonc;
      if (importeTotalPre.value != '$0.00') {
        costo.value = importeTotalPre.value;

        window.opener.Subtotal(costo);
        window.opener.totales();
        window.opener.CalculaContenido();
      }

    }
  }

  //event.preventDefault(); 
  event.returnValue = '';
});

document.getElementById("sucursal").addEventListener("change", function () {
  Sucursal(this.value);
});


document.getElementById("listCotizacion").addEventListener("change", function () {
  dataCotizacion(this.value);
});



function formulaM2(){
  const espesor = document.getElementById('F-espesor').value ?? 0;
  const factor = document.getElementById('F-factor').value ?? 0;
  const resultado = document.getElementById('F-km') ?? null;

  const calculo = espesor * factor;
  resultado.value = calculo.toFixed(2);

  return false;
}


function formulaPeso(value){
    const fila = value.parentNode.parentNode;
    
    const ancho = fila.querySelector("[name='ancho[]']").value ?? 0;
    const long = fila.querySelector("[name='long[]']").value ?? 0;
    const kilom2 = fila.querySelector("[name='kilom2[]']").value ?? 0;
    const peso = fila.querySelector("[name='peso[]']") ?? 0;
    
    let num = (ancho*long)*kilom2;
    let redondeado = parseFloat(num.toFixed(2)); // 5.68
    peso.value = redondeado ;

    return false;

}


function formulaPMaterial(value){
  const fila = value.parentNode.parentNode;
 
  const od = fila.querySelector("[name='od[]']").value ?? 0;
  const id = fila.querySelector("[name='idmat[]']").value ?? 0;
  const longmaterial = fila.querySelector("[name='longmaterial[]']").value ?? 0;
  const pmaterial = fila.querySelector("[name='pmaterial[]']") ?? 0;

    let num =((((od*25.4)*(od*25.4))-((id*25.4)*(id*25.4)))*(longmaterial*25.4))/162200
    let redondeado = parseFloat(num.toFixed(2)); // 5.68
    pmaterial.value = redondeado ;

    return false;
}

document.getElementById('form-presupuesto').addEventListener('keydown', function(event) {
  if (event.key === 'Enter') {
    event.preventDefault(); // Evita el comportamiento predeterminado
  }
});