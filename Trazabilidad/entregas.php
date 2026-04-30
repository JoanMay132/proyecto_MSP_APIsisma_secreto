<?php @session_start(); {$title = '- ENTREGAS';} //TITULO DE LA PAGINA
  include_once '../dependencias/php/head.php';
  include_once '../controlador/Sucursal.php';
  include_once '../controlador/Entrega.php';

  include_once '../class/Fecha.php';
  $popper = true;

  $obsuc = new Sucursal();
  $oEntrega = new Entrega();

  $idSuc = base64_decode($_GET['suc']);

  $anio = isset($_GET['anio']) ? $_GET['anio'] :  date("Y");

  #region Permisos
  if(!$rol->getPermissionControl($_SESSION['controles'],Controls::entregas->value,$idSuc)){
    echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
    return false;
}

$rol->getBranchInPermission($_SESSION['controles'],Controls::entregas->value);

$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
#endregion
   ?>

   <style>
      /* label,input[type="text"],table,select{ 
        font-family: Tahoma !important;
        
      }*/
      .form-control{
        border-radius: 0px !important;
      }
      #orden tr td {
    
    white-space: nowrap; /* Evita que el texto se divida en varias líneas */
    overflow: hidden; /* Oculta el texto que sobrepasa el ancho del contenedor */
    text-overflow: ellipsis; /* Agrega puntos suspensivos al final del texto recortado */
    box-sizing: border-box;
    cursor:default; 
    font-size: 11px;
}
   </style>

  <div class="main-wrapper">
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0">
      
        <div class="row" style="margin-top:-10px;">
            
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">No. de orden</label>
                <input type="text" id="rev" onkeyup="filtro(this,'f-orden');" class="form-control form-control-sm text-center" style="height:25px;margin-top:-10px;font-size:11px">
            </div>

            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Cotizacion</label>
                <input type="text" onkeyup="filtro(this,'f-cotizacion');" class="form-control form-control-sm text-center" style="height:25px;margin-top:-10px;font-size:11px">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Cliente</label>
                <input type="text" onkeyup="filtro(this,'f-cliente');" class="form-control form-control-sm text-center" style="height:25px;margin-top:-10px;font-size:11px">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Sucursal</label>
                <select id="sucursalView" onchange="selecciona();" class="form-control text-center" style="height:25px;margin-top:-10px;font-size:11px;padding:0px">
                  <?php foreach($obsuc->GetDataAll() as $ressuc){
                      $selsuc = $idSuc == $ressuc['pksucursal'] ? 'selected' : '';
                      if(in_array($ressuc['pksucursal'],$rol->getBranch())){
                    ?>
                      <option <?php echo $selsuc; ?>  value="<?php echo base64_encode($ressuc['pksucursal']); ?>"><?php echo $ressuc['nombre']; ?></option>
                  <?php } } ?>
                </select>
            </div>
            <div class="form-group col-12 col-sm-1 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Año</label>
                <select class="form-control text-center" onchange="selecciona();" id="anio" style="height:25px;margin-top:-10px;font-size:11px;padding:0px">
                <?php
                   for($i = date("Y"); $i >= ($anio-30); $i-- ){
                    $selAnio = $anio == $i ?"selected":"";
                      echo "<option ".$selAnio." value=".$i.">".$i."</option>";
                   }
                   ?>
                </select>
              
            </div>
            <div class="form-group col-12 col-sm-1">
              <?php if($modifica){ ?>
                <a href="javascript:ventana1('addentrega','ALTA ENTREGA')" class="btn btn-primary btn-sm" style="border-radius:0px;margin-top:5px"><i class="fa fa-plus fa-lg"></i>Nuevo</a>
                <?php } ?>
              </div>
        </div>
        <div class="row" style="margin-top:-15px;">
            <div class="col-12">
                <div style="overflow:auto;height: 83vh;margin-bottom:-58px;background:white;border:1px solid grey" id="display-entrega" class="contenido">
                    <table class="table-stripped table-hover display-table"  width="100%" id="table" style="font-size:11px;border-collapse: collapse;table-layout: fixed;min-width:800px">
                        <thead style="background:white;position: sticky;top:0;z-index: 10;" >
                          <tr style="border:1px solid #DFDFDF;">
                            <th style="width:8%">ORDENES</th>
                            <th style="width:8%">COTIZACION</th>
                            <th style="width:10%">FECHA</th>
                            <th style="width:40%">CLIENTE</th>
                            <th style="width:34%">ENTREGO</th>
                          </tr>
                        </thead>
                        <tbody class="body-table" id="entrega">
                          <?php foreach($oEntrega->GetDataJoin($idSuc,$anio) as $result){ ?>
                          <tr style="font-weight:500" onclick="return sel(this);" ondblclick="javascript:ventana1('eentrega?edit=<?php echo base64_encode($result['pkentrega']); ?>','E-ENTREGA')" >
                            <td id="f-orden" style="border-right:1px solid #DFDFDF"><?php echo $result["folio"]; ?></td>
                            <td id="f-cotizacion" style="border-right:1px solid #DFDFDF"><p id="folio" style="margin-left:3px;padding:3px"><?php echo $result["folioCot"]; ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php if($result["fecha"] != '0000-00-00') echo Fecha::convertir($result["fecha"]); ?></p></td>
                            <td id="f-cliente" style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["ncliente"]; ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["nempleado"]." ".$result["apellidos"]; ?></p></td>
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
<script>
  function filtro(data,columna){
    var value = $(data).val().toLowerCase();
    $("#entrega tr").each(function() {
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

    function ventana1(URL,name = ""){ 
        const ventanaAncho = screen.width;
        const ventanaAlto = screen.height;

        let izquierda =Math.round((ventanaAncho - 1050) / 2);
        let arriba = Math.round((ventanaAlto - 550) / 2);
        window[name] ? window[name].focus() : window.open(URL,name,"width=1050,height=550,scrollbars=yes,left="+izquierda+",top="+arriba+",addressbar=0,menubar=0,toolbar=0" );

    }


// document.getElementById('sucursalView').addEventListener("click",(event)=>{
  
//   event.stopPropagation();
//   opcionSeleccionada = document.getElementById('sucursalView').value;
//   document.getElementById('sucursalView').addEventListener("click",(event)=>{
//     let sucursal = opcionSeleccionada;
//     location.href = "ordenes?suc="+sucursal;
//   });
  
// });

function selecciona(){
  let suc =  document.getElementById('sucursalView').value;
  let anio = document.getElementById('anio').value;
    location.href = "entregas?suc="+suc+"&anio="+anio;
}

var anterior = null;
function sel(data){

    if(anterior != null){
        anterior.style.background = "unset";
        anterior.style.color= "unset";
    }

    data.style.background = "black";
    data.style.color= "white";

    anterior = data;

    return false;
}
   
</script>