<?php @session_start(); {$title = "- REVISIONES";}
  include '../dependencias/php/head.php';
  include '../controlador/Sucursal.php';
  include '../controlador/Revpreeliminar.php';
  include '../class/Fecha.php';

  $popper = true;
  $obsuc = new Sucursal();
  $oRev = new Revpreeliminar();
  $nameControl = Controls::revpreeliminar->value;

  $idSuc =(int) base64_decode($_GET['suc']??null);
  if(!filter_var($idSuc,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}
  $anio = isset($_GET['anio']) ? $_GET['anio'] :  date("Y");

#region Permsisos
  if(!$rol->getPermissionControl($_SESSION['controles'],$nameControl,$idSuc)){
      echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
      return false;
  }

  $rol->getBranchInPermission($_SESSION['controles'],$nameControl);

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
    #revision tr td {
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
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px;">
                <label class="txt-12 text-secondary">Revision</label>
                <input type="text" id="rev" onkeyup="filtro(this,'f-revision');" class="form-control form-control-sm text-center form-text">
            </div>

            <div class="form-group col-12 col-sm-3 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Cliente</label>
                <input type="text" class="form-control form-control-sm text-center form-text" onkeyup="filtro(this,'f-cliente');">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Sucursal</label>
                <select id="sucursalView" onchange="selecciona();" class="form-control text-center form-text">
                  <?php foreach($obsuc->GetDataAll() as $ressuc){ 
                      $selSuc = $idSuc == $ressuc['pksucursal'] ?'selected':'';

                      if(in_array($ressuc['pksucursal'],$rol->getBranch())){
                    ?>
                  <option <?php echo $selSuc; ?> value="<?php echo base64_encode($ressuc['pksucursal']); ?>"><?php echo $ressuc['nombre']; ?></option>
                  <?php } } ?>
                </select>
              
            </div>
            <div class="form-group col-12 col-sm-1 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Año</label>
                <select class="form-control text-center form-text" id="anio" onchange="return selecciona();" style="width:60px">
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
                <a href="javascript:ventana1('addrevpreeliminar')" style="border-radius:0px;margin-top:5px" class="btn btn-primary btn-sm"><i class="fa fa-plus fa-lg"></i>Nuevo</a>
                <?php } ?>
              </div>
        </div>
        <div class="row" style="margin-top:-15px;">
            <div class="col-12">
                <div style="overflow:auto;height: 83vh;margin-bottom:-58px;background:white" id="display-revpreeliminar" class="contenido">
                    <table class="table-stripped table-hover display-table"  width="100%" id="table" style="background-color: white;font-size:11px;border-collapse: collapse;table-layout: fixed;min-width:700px">
                        <thead style="background:white;position: sticky;top:0;z-index: 10;" >
                          <tr style="border:1px solid #DFDFDF;">
                            <th width="10%">REV</th>
                            <th width="10%">FECHA</th>
                            <th width="40%">CLIENTE</th>
                            <th width="40%">ELABORO</th>
                          </tr>
                        </thead>
                        <tbody class="body-table" id="revision">
                          <?php foreach($oRev->GetDataJoin($idSuc,$anio) as $result){ ?>
                          <tr style="font-weight:500" onclick="return sel(this);" ondblclick="javascript:ventana1('editrevpreeliminar?edit=<?php echo base64_encode($result['pkrevpreeliminar']); ?>')" >
                            <td style="border-right:1px solid #DFDFDF" id="f-revision"><p id="folio" style="margin-left:3px;padding:3px"><?php echo $result["folio"]; ?></p></td>
                            <td style="border-right:1px solid #DFDFDF"><p style="margin-left:3px"><?php if($result["fecha"] != '0000-00-00') echo Fecha::convertir($result["fecha"]); ?></p></td>
                            <td style="border-right:1px solid #DFDFDF" id="f-cliente"><p style="margin-left:3px"><?php echo $result["ncliente"]; ?></p></td>
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
    $("#revision tr").each(function() {
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

    function ventana1(URL){ 
      const ventanaAncho = screen.width;
        const ventanaAlto = screen.height;

        let izquierda =Math.round((ventanaAncho - 1000) / 2);
        let arriba = Math.round((ventanaAlto - 700) / 2);

        window["REVISION"] ? window["REVISION"].focus() : window.open(URL,"REVISION","width=1000,height=700,scrollbars=yes,left="+izquierda+",top="+arriba+",addressbar=0,menubar=0,toolbar=0" );

    }
   
//     document.getElementById('sucursalView').addEventListener("click",(event)=>{
  
//   event.stopPropagation();
//   opcionSeleccionada = document.getElementById('sucursalView').value;
//   document.getElementById('sucursalView').addEventListener("click",(event)=>{
//     let sucursal = opcionSeleccionada;
//     location.href = "revpreeliminar?suc="+sucursal;
//   });
  
// });

function selecciona(){
  let suc =  document.getElementById('sucursalView').value;
  let anio = document.getElementById('anio').value;
    location.href = "revpreeliminar?suc="+suc+"&anio="+anio;
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