<?php @session_start(); {$title = '- COTIZACIONES';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  include '../controlador/Sucursal.php';
  include '../controlador/Cotizacion.php';
  include '../class/Fecha.php';

  $popper = true;
  $obsuc = new Sucursal();
  $oCot = new Cotizacion();
  $totalCot = 0;

  $idSuc = base64_decode($_GET['suc']??null);

  $anio = isset($_GET['anio']) ? $_GET['anio'] :  date("Y");
  
#region Permisos
  if(!$rol->getPermissionControl($_SESSION['controles'],Controls::cotizacion->value,$idSuc)){
    echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
    return false;
}
$rol->getBranchInPermission($_SESSION['controles'],Controls::cotizacion->value);
$modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
#endregion
   ?>
<style>
  .form-control{
        border-radius: 0px !important;
      }
    .form-text{
      height:25px;
      margin-top:-10px;
      font-size:11px;
      padding:0px
    }
    #cotizacion tr td {
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
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0;margin-bottom:0px">   
        <div class="row" style="margin-top:-10px;">            
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Cotización</label>
                <input type="text" id="cot" onkeyup="filtro(this,'f-cotizacion');" class="form-control form-control-sm text-center form-text" >
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Cliente</label>
                <input type="text" class="form-control form-control-sm text-center form-text" onkeyup="filtro(this,'f-cliente');">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Depto.</label>
                <input type="text" class="form-control form-control-sm text-center form-text" onkeyup="filtro(this,'f-depto');">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Cotizó.</label>
                <input type="text" class="form-control form-control-sm text-center form-text" onkeyup="filtro(this,'f-cotizo');">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Sucursal</label>
                <select id="sucursalView" onchange="selecciona();" class="form-control text-center form-text">
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
                <select class="form-control text-center form-text" id="anio" onchange="selecciona();">
                <?php
                   for($i = date("Y"); $i >= ($anio-30); $i-- ){
                    $selAnio = $anio == $i ?"selected":"";
                      echo "<option ".$selAnio." value=".$i.">".$i."</option>";
                   }
                   ?>
                </select>
              
            </div>
            <div class="form-group col-12 col-sm-1" style="padding:0px">
              <?php if($modifica){ ?>
                <a href="javascript:ventana1('addcotizacion','ALTA COTIZACION')" class="btn btn-primary btn-sm" style="border-radius:0px;margin-top:5px"><i class="fa fa-plus fa-lg"></i>Nuevo</a>
                <?php } ?>
            </div>
        </div>
        <div class="row" style="margin-top:-15px;">
            <div class="col-12">
                <div style="overflow:auto;height: 83vh;margin-bottom:-58px;background-color: white;border:1px solid grey" id="display-cotizacion" class="contenido">
                  
                    <table class="table-hover display-table" width="100%" id="table" style="background-color: white;font-size:11px;border-collapse: collapse;table-layout: fixed;min-width:900px">
                        <thead style="background:white;position: sticky;top:0;z-index: 10;" >
                          <tr style="border:1px solid #DFDFDF;">
                            <th style="width:8%">COTIZACION</th>
                            <th style="width:8%">FECHA</th>
                            <th style="width:30%">CLIENTE</th>
                            <th style="width:10%">DEPTO.</th>
                            <th style="width:24%">COTIZO</th>
                            <th style="width:10%">OC / PO</th>
                            <th style="width:10%">FACTURA</th>
                          </tr>
                        </thead>
                        <tbody class="body-table" id="cotizacion" >
                          <?php foreach($oCot->GetDataJoin($idSuc,$anio) as $result){ ?>
                          <tr style="font-weight:500" onclick="return sel(this);" ondblclick="javascript:ventana1('ecotizacion?edit=<?php echo base64_encode($result['pkcotizacion']); ?>','E-COTIZACION')" >
                            <td id="f-cotizacion" style="border-right:1px solid #DFDFDF"><p id="folio" style="margin-left:3px;padding:3px"><?php echo $result["folio"]; ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php if($result["fecha"] != '0000-00-00') echo Fecha::convertir($result["fecha"]); ?></p></td>
                            <td id="f-cliente" style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["ncliente"]; ?></p></td>
                            <td id="f-depto" style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["ndepto"]; ?></p></td>
                            <td id="f-cotizo" style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["nempleado"]." ".$result["apellidos"]; ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["ocompra"]; ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php echo $result["factura"]; ?></p></td>
                          </tr>
                            <?php $totalCot ++; }?>
                        </tbody>
                    </table>
                   
                </div>
                
            </div>
            <p style="margin-top:60px;margin-left:15px;font-size:13px">Total: <span id="total-display"><?php echo $totalCot; ?></span></p>
        </div> 
        
    </main>

<?php
  include_once '../dependencias/php/footer.php';
?>
<script>
  function filtro(data,columna){
    let conteo = 0;
    var value = $(data).val().toLowerCase();
    $("#cotizacion tr").each(function() {
      let found = false;
      $(this).find('#'+columna).each(function(){
          if($(this).text().toLowerCase().indexOf(value) > -1){
            conteo++;
            found = true;
            return false;
          }
         
      });
      $(this).toggle(found);
      document.getElementById('total-display').innerText = conteo;
    });
  }

    function ventana1(URL,name = ""){ 
      const ventanaAncho = screen.width;
        const ventanaAlto = screen.height;

        let izquierda =Math.round((ventanaAncho - 1090) / 2);
        let arriba = Math.round((ventanaAlto - 700) / 2);
        window[name] ? window[name].focus() : window.open(URL,name,"width=1090,height=700,scrollbars=yes,left="+izquierda+",top="+arriba+",addressbar=0,menubar=0,toolbar=0" );

    }


// document.getElementById('sucursalView').addEventListener("click",(event)=>{
  
//   event.stopPropagation();
//   opcionSeleccionada = document.getElementById('sucursalView').value;
//   document.getElementById('sucursalView').addEventListener("click",(event)=>{
//     let sucursal = opcionSeleccionada;
//     location.href = "cotizacion?suc="+sucursal;
//   });
  
// });

function selecciona(){
  let suc =  document.getElementById('sucursalView').value;
  let anio = document.getElementById('anio').value;

    location.href = "cotizacion?suc="+suc+"&anio="+anio;
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