function add(data,row,nuevo = false,load = false)
{
    var button_id = $(data).attr("data-fil");
    var fila = row;

    var servDesc = document.getElementById('descripcion-'+button_id);
    var servCosto= document.getElementById('costo-'+button_id);
    var servId= document.getElementById('pkservicio-'+button_id);
    var servItem= document.getElementById('item-'+button_id);

    if(window.opener)
    {
        //Asignamos variable a la ventana
        var venta = window.opener;
        let cantidad,descripcion,costo,pkservicio,item,subtotal;
        
        if(nuevo){
            //Nuevos servicios
            cantidad = venta.document.getElementById('cantidadNew-'+fila);
            descripcion = venta.document.getElementById('descNew-'+fila);
            costo= venta.document.getElementById('costoNew-'+fila);
            pkservicio= venta.document.getElementById('fkcatservNew-'+fila);
            item = venta.document.getElementById('itemNew-'+fila);
            subtotal = venta.document.getElementById('subtotalNew-'+fila);
        }else if(load)
        {
            //Servicios cargados
            cantidad = venta.document.getElementById('cantidadLoad-'+fila);
            descripcion = venta.document.getElementById('descLoad-'+fila);
            costo= venta.document.getElementById('costoLoad-'+fila);
            pkservicio= venta.document.getElementById('fkcatservLoad-'+fila);
            item = venta.document.getElementById('itemLoad-'+fila);
            subtotal = venta.document.getElementById('subtotalLoad-'+fila);
        }
        
        else{
            //Edicion de servicios
            cantidad = venta.document.getElementById('cantidad-'+fila);
            descripcion = venta.document.getElementById('descripcion-'+fila);
            costo= venta.document.getElementById('costo-'+fila);
            pkservicio= venta.document.getElementById('fkcatserv-'+fila);
            item = venta.document.getElementById('item-'+fila);
            subtotal = venta.document.getElementById('subtotal-'+fila);
        }

        //Limpia y asigna el nuevo valor
        descripcion.value = "";
        descripcion.value = servDesc.textContent.trim();
        costo.value = "";
        costo.value = servCosto.textContent.trim();
        if(pkservicio!== null){
            pkservicio.value = "";
            pkservicio.value = servId.value.trim();
        }
        
        if(item !== null){
            item.value = "";
            item.value = servItem.textContent.trim();
        }
        if(cantidad !== null){ cantidad.value = "";}
        if(subtotal !== null){ subtotal.value = "";}
        
        
    }

    
        window.close();
      
}

// let header =  document.getElementsByClassName('head-servicios');
// header = Array.from(header);

// header.forEach(data => {
//     data.addEventListener("click",()=>{
//         // Remover la clase "active" de todos los enlaces
//         data.forEach(l => l.classList.remove('active'));

//         // Agregar la clase "active" al enlace clicado
//         this.classList.add('active-serv');
//     });
// });


