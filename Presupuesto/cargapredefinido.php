<?php @session_start(); {$title = '- CARGA PREDEFINIDO';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  include '../controlador/Sucursal.php';
  include '../controlador/Unidad.php';
  include '../controlador/Predefinido.php';
  include '../class/Fecha.php';
  $popper = true;

  $obsuc = new Sucursal();
  $oPre = new Predefinido();
  $obunidad = new Unidad();

  $idSuc = base64_decode( $_GET['suc']);
  $anio = isset($_GET['anio']) ? $_GET['anio'] :  date("Y");

  foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" => $value['pkunidad'], "nombre" => $value['nombre']);
}

  #region Permisos
  if(!$rol->getPermissionControl($_SESSION['controles'],Controls::analisiscosto->value,$idSuc)){
    echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
    return false;
}

$rol->getBranchInPermission($_SESSION['controles'],Controls::analisiscosto->value);

$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
$viewCosto = !in_array(Operacion::costo->value,$rol->getOperacion()) ? 'no-cost' : '';
#endregion

   ?>
   <style>
    #presupuesto tr td {
    white-space: nowrap; /* Evita que el texto se divida en varias líneas */
    overflow: hidden; /* Oculta el texto que sobrepasa el ancho del contenedor */
    text-overflow: ellipsis; /* Agrega puntos suspensivos al final del texto recortado */
    box-sizing: border-box;
    cursor:default; 
    font-size: 12px;
}


   </style>
  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0">
      
        <div class="row" style="margin-top:-10px;">
          
            <div class="form-group col-6 col-sm-7 text-center" style="padding:2px;margin-top:-10px">
                <label class="txt-12 text-secondary">DESCRIPCION</label>
                <input type="text" id="icliente" onkeyup="filtro(this,'f-descripcion');" class="form-control form-control-sm txt-12 text-center" style="height:25px;margin-top:-10px;font-size:11px">
            </div>
            <div class="form-group col-6 col-sm-2 text-center" style="padding:2px;margin-top:-10px">
                <label class="txt-12 text-secondary">SUCURSAL <i class="fa fa-house"></i></label>
                <select id="sucursalView" class="form-control txt-12 text-center" style="height:25px;margin-top:-10px;font-size:11px;padding:0px">
                  <?php foreach($obsuc->GetDataAll() as $ressuc){
                     $selsuc = $idSuc == $ressuc['pksucursal'] ? 'selected' : '';
                     if(in_array($ressuc['pksucursal'],$rol->getBranch())){
                    ?>
                  <option <?php echo  $selsuc; ?>  value="<?php echo base64_encode($ressuc['pksucursal']); ?>"><?php echo $ressuc['nombre']; ?></option>
                  <?php } } ?>
                </select>
              
            </div>
        </div>
        <div class="row" style="margin-top:-15px;">
            <div class="col-12 col-sm-12">
                <div style="overflow:auto;height: 83vh;margin-bottom:-58px;background:white" id="display-listpresupuesto">
                  
                    <table class="table-stripped table-hover display-table"  width="100%" id="table" style="font-family:'Calibri';background-color: white;font-size:13px;user-select: none;border-collapse: collapse;table-layout: fixed;">
                        <thead style="background:white;position: sticky;top:0;z-index: 10;" >
                        <tr style="border:1px solid #DFDFDF;">
                            <th>DESCRIPCION</th>
                            <th width="10%">PRECIO</th>
                          </tr>
                        </thead>
                        <tbody class="text-secondary body-table" id="presupuesto">
                          <?php foreach($oPre->GetDataList($idSuc) as $result){?>                        
                          <tr style="font-family:'Calibri';font-size:13px;color:#1C1C1C;" onclick="return sel(this);" ondblclick='Carga("<?php echo base64_encode($result["pkpresupuesto"]); ?>",<?php echo json_encode($data); ?>,"true")' >
                            <td id="f-descripcion" style="border-right:1px solid #DFDFDF;padding:2px"><p style="margin-left:3px"><?php echo $result["servicio"]; ?></p></td>

                            <td style="border-right:1px solid #DFDFDF"><p class="text-center <?php echo $viewCosto; ?>" style="margin-left:3px;background:#088A29;border-radius:3px;color:white;padding:1px;font-weight:bold">$<?php echo number_format($result["total"],2,".",","); ?></p></td>
                          </tr>

                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </main>

<?php
  include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Presupuesto/Cargapresupuesto.js"></script>
<script>
 function filtro(data,columna){
    var value = $(data).val().toLowerCase();
    $("#presupuesto tr").each(function() {
      let found = false;
      $(this).find('#'+columna).each(function(){
          if($(this).text().toLowerCase().indexOf(value) > -1){
            found = true;
            return false;
          }
      });
      $(this).toggle(found);
    });
  }


document.getElementById('sucursalView').addEventListener("click",(event)=>{
  
  event.stopPropagation();
  opcionSeleccionada = document.getElementById('sucursalView').value;
  document.getElementById('sucursalView').addEventListener("click",(event)=>{
    let sucursal = opcionSeleccionada;
    location.href = "cargapredefinido?suc="+sucursal;
  });
  
});



var anterior = null;
function sel(data){

    if(anterior != null){
        anterior.style.background = "unset";
        anterior.style.color= "#1C1C1C";
    }

    data.style.background = "#404040";
    data.style.color= "white";

    anterior = data;

    return false;
} 
</script>