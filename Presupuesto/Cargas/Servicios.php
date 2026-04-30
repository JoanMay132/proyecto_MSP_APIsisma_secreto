<script src="../../dependencias/js/jquery-3.6.0.min.js"></script>
<?php
        //TITULO DE LA PAGINA
        $title = '- SERVICIOS';

    include '../../controlador/conexion.php';
    spl_autoload_register(function ($class) {
        include_once "../../controlador/" . $class . ".php";
    });
    
    $oCot = new Cotizacion();
    $idcotizacion =(int) base64_decode($_GET['id']);
    if(!filter_var($idcotizacion,FILTER_VALIDATE_INT)){ echo "URL INVALIDA"; return false;}

    ?>
 <style>
     #draggable {
         width: 100%;
         height: 99%;
     }

     .modal-body {
         overflow: auto;
         height: 90vh;
     }
    
     table{
        border-collapse: collapse;
        font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
     }
     td {
        
        padding:0px;
        font-size: 14px;
     }
     tr{
        height:auto;
     }
     thead th{
        background:#A9D0F5;
        font-weight: bold;
        color:#424242;
     }
     textarea{
        padding:0px;
        width:100%;
        font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        padding:5px;
        color:#565656;
     }

 </style>
 <!-- VENTANA MODAL MATERIALES-->
 <div id="draggable">
     <form id="form-maquinaria" onsubmit="return addMaquinarias();">

         <div class="modal-body" id="view-maquinarias" style="width:100%;padding:0px">
                <div id="display-maquinarias">
             <table class="table table-sm table-bordered txt-11" width="100%" >
                <thead>
                    <th>DESCRIPCIÓN</th>
                </thead>
                 <tbody class="body-table table-maquinarias" >
                    <?php
                        foreach($oCot->GetDataAllServ($idcotizacion) as $row){ ?>
                                <tr class="fila">
                                    <input type="hidden" name="servicio" value="<?php echo base64_encode($row['pkservcot']); ?>">
                                    <input type="hidden" name="pda" value="<?php echo $row['pda']; ?>">
                                    <td><textarea name="descripcion" class="descripcion" rows="6"><?php echo $row['descripcion'];?></textarea></td>
                                </tr>

                       <?php } ?>
                

                 </tbody>
             </table>

            </div>

         </div>

     </form>
 </div>

<script>
    
    if(window.opener){
            let ventana = window.opener; //obtiene la ventana

            let inputServicio = ventana.document.getElementById("inputServicio");
            let id = ventana.document.getElementById("servicio");
            let pda = ventana.document.getElementById("pda");
            let filas=document.getElementsByClassName('fila');
            
            //Limpieza de variables
            inputServicio.value = "";
            id.value = "";
            pda.value = "";

            filas = Array.from(filas);
            filas.forEach(function(fila) {
                fila.addEventListener('dblclick', async function() {
                    // Encuentra la celda de descripción dentro de la fila actual
                    var descripcionCelda = fila.querySelector('.descripcion');
                    let serv =  fila.querySelector('[name="servicio"]').value;
                    let thisPda =  fila.querySelector('[name="pda"]').value;

                    // Obtiene el texto de la celda de descripción
                    var text = descripcionCelda.value;

                    await $.ajax({
                        type: "POST",
                        data: {accion: 'findServcot', valor:serv},
                        url: "../Controller/Presupuesto",
                        success:function(respuesta){ 
                                if(respuesta != ''){
                                    let idpre = respuesta.replace(/"/g,'');
                                    ventana.location.href = "../epresupuesto?edit="+idpre; 
                                    return true;
                                }
                        }
                    });
                    //Se asignan los valores
                    inputServicio.value = text;
                    id.value = serv;
                    pda.value = thisPda;
                    window.close();

                });
            });
            
    }
</script>

