<?php @session_start(); {$title = '- OT POR CONCEPTO';} //TITULO DE LA PAGINA
  include '../dependencias/php/head.php';
  include '../controlador/Sucursal.php';
  include '../controlador/Orden.php';
  include '../class/Fecha.php';

  $popper = true;
  $obsuc = new Sucursal();
  $oOt = new Orden();
  $idSuc = base64_decode($_GET['suc']);
  $anio = isset($_GET['anio']) ? $_GET['anio'] :  date("Y");

#region Permisos
    if(!$rol->getPermissionControl($_SESSION['controles'],Controls::ot->value,$idSuc)){
      echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
      return false;
  }
  
  $rol->getBranchInPermission($_SESSION['controles'],Controls::ot->value);
  
  $modifica = in_array(Operacion::modifica->value,$rol->getOperacion()) ? true : false;
  #endregion
   ?>

<style>
/* label,input[type="text"],table,select{ 
        font-family: Tahoma !important;
        
      }*/
.form-control {
    border-radius: 0px !important;
}

#orden tr td {

    white-space: nowrap;
    /* Evita que el texto se divida en varias líneas */
    overflow: hidden;
    /* Oculta el texto que sobrepasa el ancho del contenedor */
    text-overflow: ellipsis;
    /* Agrega puntos suspensivos al final del texto recortado */
    box-sizing: border-box;
    cursor: default;
    font-size: 11px;
}
</style>

<div class="main-wrapper">

    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0">

        <div class="row" style="margin-top:-10px;">

            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">No. de orden</label>
                <input type="text" id="rev" onkeyup="filtro(this,'f-orden');"
                    class="form-control form-control-sm text-center"
                    style="height:25px;margin-top:-10px;font-size:11px">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Cliente</label>
                <input type="text" onkeyup="filtro(this,'f-cliente');" class="form-control form-control-sm text-center"
                    style="height:25px;margin-top:-10px;font-size:11px">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Concepto</label>
                <input type="text" onkeyup="filtro(this,'f-concepto');" class="form-control form-control-sm text-center"
                    style="height:25px;margin-top:-10px;font-size:11px">
            </div>
            <div class="form-group col-12 col-sm-2 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Sucursal</label>
                <select id="sucursalView" class="form-control text-center"
                    style="height:25px;margin-top:-10px;font-size:11px;padding:0px">
                    <?php foreach($obsuc->GetDataAll() as $ressuc){
                      $selsuc = $idSuc == $ressuc['pksucursal'] ? 'selected' : '';
                        if(in_array($ressuc['pksucursal'],$rol->getBranch())){
                    ?>
                    <option <?php echo $selsuc; ?> value="<?php echo base64_encode($ressuc['pksucursal']); ?>">
                        <?php echo $ressuc['nombre']; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="form-group col-12 col-sm-1 text-center" style="margin-top:-10px">
                <label class="txt-12 text-secondary">Año</label>
                <select class="form-control text-center" onchange="selecciona();" id="anio"
                    style="height:25px;margin-top:-10px;font-size:11px;padding:0px">
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
                <a href="javascript:ventana1('addorden','ALTA ORDEN')" class="btn btn-primary btn-sm"
                    style="border-radius:0px;margin-top:5px"><i class="fa fa-plus fa-lg"></i>Nuevo</a>
                <?php }?>
            </div>
        </div>
        <div class="row" style="margin-top:-15px;">
            <div class="col-12">
                <div style="overflow:auto;height: 83vh;margin-bottom:-58px;background:white;border:1px solid grey"
                    id="display-ordenes" class="contenido">
                    <table class="table-stripped table-hover display-table" width="100%" id="table"
                        style="font-size:11px;border-collapse: collapse;table-layout: fixed;min-width:900px">
                        <thead style="background:white;position: sticky;top:0;z-index: 10;">
                            <tr style="border:1px solid #DFDFDF;">
                                <th width="7%">ORDEN</th>
                                <th width="8%">FECHA</th>
                                <th width="10%">CLIENTE</th>
                                <th width="40%">CONCEPTO</th>
                                <th width="10%">TIPO TRABAJO</th>
                            </tr>
                        </thead>
                        <tbody class="body-table" id="orden">
                            <?php foreach($oOt->Concepto($idSuc,$anio) as $result){ ?>
                            <tr style="font-weight:500" onclick="return sel(this);"
                                ondblclick="javascript:ventana1('eorden?edit=<?php echo base64_encode($result['pkorden']); ?>','E-ORDEN')">
                                <td class="f-orden" style="border-right:1px solid #DFDFDF">
                                    <?php echo $result["folio"]; ?></td>
                                <td style="border-right:1px solid #DFDFDF">
                                    <p style="margin-left:3px">
                                        <?php if($result["fecha"] != '0000-00-00') echo Fecha::convertir($result["fecha"]); ?>
                                    </p>
                                </td>
                                <td class="f-cliente" style="border-right:1px solid #DFDFDF">
                                    <p style="margin-left:3px"><?php echo $result["nombre"]; ?></p>
                                </td>
                                <td style="border-right:1px solid #DFDFDF;" class="f-concepto">
                                    <p style="margin-left:3px"><?php echo $result["descripcion"]; ?></p>
                                </td>
                                <td style="border-right:1px solid #DFDFDF;">
                                    <p style="margin-left:3px"><?php echo $result["tipotrabajo"]; ?></p>
                                </td>
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
    let timer;

    function filtro(data, columna) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const value = $(data).val().toLowerCase();
            $("#orden tr").each(function() {
                const texto = $(this).find('.' + columna).text().toLowerCase();
                $(this).toggle(texto.indexOf(value) > -1);
            });
        }, 250); // solo filtra cuando el usuario se detiene
    }

    function ventana1(URL, name = "") {
        const ventanaAncho = screen.width;
        const ventanaAlto = screen.height;

        let izquierda = Math.round((ventanaAncho - 1050) / 2);
        let arriba = Math.round((ventanaAlto - 550) / 2);
        window[name] ? window[name].focus() : window.open(URL, name, "width=1050,height=550,scrollbars=yes,left=" +
            izquierda + ",top=" + arriba + ",addressbar=0,menubar=0,toolbar=0");

    }


    document.getElementById('sucursalView').addEventListener("click", (event) => {

        event.stopPropagation();
        opcionSeleccionada = document.getElementById('sucursalView').value;
        document.getElementById('sucursalView').addEventListener("click", (event) => {
            let sucursal = opcionSeleccionada;
            location.href = "conceptoorden?suc=" + sucursal;
        });

    });

    function selecciona() {
        let suc = document.getElementById('sucursalView').value;
        let anio = document.getElementById('anio').value;

        location.href = "conceptoorden?suc=" + suc + "&anio=" + anio;

    }
    var anterior = null;

    function sel(data) {

        if (anterior != null) {
            anterior.style.background = "unset";
            anterior.style.color = "unset";
        }

        data.style.background = "black";
        data.style.color = "white";

        anterior = data;

        return false;
    }
    </script>