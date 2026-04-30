function Subtotal(row,load = false)
{
    let fila = row.parentNode.parentNode;
    let cant  = "",subtotal = "",costo = "";

        cant = fila.querySelector("[data-pre='cantidad']");
        subtotal = fila.querySelector("[data-pre='subtotal']");
        costo = fila.querySelector("[data-pre='costo']");
        if(costo.value == ""){
            costo.value = "$0.00";
        }
    costo = (costo.value.replace(/[$,]/g, ""));
    let total = (cant.value*costo);

    subtotal.value = formato(total);
    
}

function CantContenido() { //Operacion para calcular las cantidad x el valor del contenido original
    let total = 0;
    let tagcantidad = document.querySelectorAll("[name='cantReg[]'], [name='cant[]']");
    tagcantidad.forEach((data) =>{
            let cantidad = data.value;
           let fila =  data.parentNode.parentNode;
            
            let contenido = fila.querySelector("[name='contenidoReg[]']")?.value ?? fila.querySelector("[name='contenido[]']")?.value;
            total += (cantidad * contenido);
    });
    return total;
}

function formato(numero)
{
    var numeroFormateado = numero.toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      return numeroFormateado;
}

//Suma de cantidades para calcular los totales
async function totales(){
    let inputSubtotal = document.getElementById('inputSubtotal');
    let inputTotal= document.getElementById('inputTotal');
    let subtotales = document.getElementsByClassName('subtotal');
    let subtotal = 0;
    for (let index = 0; index < subtotales.length; index++) {
         if(subtotales[index].value.replace(/[$,]/g, "") == ""){ continue}
        subtotal += parseFloat (subtotales[index].value.replace(/[$,]/g, "")); 
    }

    let tagSubtotal = document.getElementById('subtotal');
    tagSubtotal.textContent  = formato( subtotal);
    inputSubtotal.value = (subtotal);

    //Porcentaje - calculo de descuento
    let tagDescto= document.getElementById('descto');
    tagDescto = tagDescto.value == "" ? 0 : tagDescto.value ;
    let tagSubtotaldesc = document.getElementById('subtotaldesc');
    let porcentaje = (subtotal * parseFloat(tagDescto));
    tagSubtotaldesc.textContent = formato((subtotal - porcentaje)); 
    document.getElementById('tagDescto').textContent = formato(porcentaje);
    let total = (subtotal - porcentaje);

    //calculo de iva
    let iva = document.getElementById('iva'); //Se obtiene el valor del iva
    let tagIva = document.getElementById('tagIva'); //Se obtiene la etiqueta en donde se muestra el iva
    tagIva.textContent = formato( total * parseFloat("0."+iva.value));

    let tagTotal = document.getElementById('tagTotal'); //Se obtiene la etiqueta en donde se muestra el total
    let totales = (total + (total * parseFloat("0."+iva.value)));
    tagTotal.textContent = formato( totales );
    inputTotal.value = (totales);

    //Se muestra el valor en dolares
    let tdolar = document.getElementById('tdolar');
    let tagCambio= document.getElementById('cambio').value;
    tdolar.textContent = formato( totales/parseFloat(tagCambio));
 

    CalculaSubcotizacion();
}

//Función para mostrar y ocultar las etiquetas de cambio (Nacional o Dolar)
async function Cambio()
{
    let tagMoneda = document.getElementById('tagmoneda').value;
    let tagDolar = document.getElementsByClassName('dolar');
    
    if(tagMoneda == "DOLAR")
    {
        for(i= 0; i < tagDolar.length; i++){
            tagDolar[i].style.display = 'unset'; //Las muestra
        }

    }
    else{
        for(i= 0; i < tagDolar.length; i++){
            tagDolar[i].style.display = 'none'; // Las oculta
        }
    }
}


async function CalculaSubcotizacion() //Clcula el total y la diferencia en la cotización por subcotizaciones creadas
{
    let tagTotal = document.getElementById('tagTotal');
    if(tagTotal){ tagTotal = tagTotal.textContent.replace(/[$,]/g, "")}
    let tagTotalSubcotizacion = document.getElementById('totalSubcotizacion');
    if(tagTotalSubcotizacion){ tagTotalSubcotizacion = tagTotalSubcotizacion.textContent.replace(/[$,]/g, "")}
    let tagDiferencia = document.getElementById('diferencia');
    let diferencia = 0;

    diferencia = (tagTotalSubcotizacion - tagTotal);

    if(tagDiferencia){
        tagDiferencia.textContent = formato(diferencia);
    }
}