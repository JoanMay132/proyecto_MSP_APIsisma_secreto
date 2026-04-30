<?php @session_start(); {
    $title = '- ALTA ENTREGA';
} //TITULO DE LA PAGINA
include '../dependencias/php/head.php';

spl_autoload_register(function ($class) {
    include_once "../controlador/" . $class . ".php";
});
$idEntrega = (int) base64_decode($_GET['edit']);
if (!filter_var($idEntrega, FILTER_VALIDATE_INT)) {
    echo "LA URL NO ES VALIDA :(";
    return false;
}
$popper = true;

$obsuc = new Sucursal();
$obunidad = new Unidad();
$obcli = new Cliente();
$oEntrega = new Entrega();

//Consultamos la entrega
$resEnt =  $oEntrega->GetData($idEntrega);

foreach ($obunidad->GetDataAll() as $value) {
    $data[] = array("pkunidad" => $value['pkunidad'], "nombre" => $value['nombre']);
}

$rol->getPermissionControl($_SESSION['controles'], Controls::entregas->value, $resEnt['fksucursal']); //Se verifica las operaciones
$modifica = in_array(Operacion::modifica->value, $rol->getOperacion()) ? true : false;
?>
<style>
    body::-webkit-scrollbar {
        width: 0px;
        /* Ancho de la barra de desplazamiento */
        height: 8px;
    }

    td:focus-within {
        border: 1.7px solid #b9ddea;
    }

    .form-control {
        border-radius: unset;
        height: 20px;
        font-size: 11px;
        padding: 2px;
    }

    .form-group.row,
    .form-group {
        padding: 0px !important;
        margin-top: unset !important;
        margin-bottom: -3px !important;

        /* border:1px solid black; */
    }

    .row {
        padding: unset !important;
        margin-top: unset !important;
        margin-bottom: unset !important;
    }

    .top-8 {
        margin-top: -8px;
    }

    .top-3 {
        margin-top: -3px;
    }

    input[type="radio"] {
        transform: scale(0.6) !important;
    }

    table .form-control {
        height: 30px;
    }
</style>


<div class="main-wrapper">

    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target" style="padding:15px;margin-left:0;margin-right:0;margin-bottom:0px">

        <form id="form-entrega" enctype="multipart/form-data" onsubmit="return addEntrega();">
            <div class="row">
                <input type="hidden" name="entrega" value="<?php echo base64_encode($resEnt['pkentrega']); ?>">
                <div class="col-12 col-sm-5">
                    <div class="form-group row">
                        <label for="listClientes" class="txt-11 text-secondary col-3 col-sm-3">CLIENTE</label>
                        <select name="cliente" id="listClientes" class="form-control txt-12 col-9 col-sm-9" onchange="return usercustomer(this.value);">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="listUsercustomer" class="txt-11 text-secondary col-3 col-sm-3">SOLICITO</label>
                        <select name="solicito" id="listUsercustomer" onchange="return viewDepto(this.value);" class="form-control form-control-sm col-8 col-sm-8 listUsercustomer">
                            <option></option>
                        </select>
                        <div class="col-1 col-sm-1 text-center" style="padding:0">
                            <button type="button" id="buttonUser" title="Agregar usuario" data-toggle="tooltip" class="btn btn-outline-secondary" style="width:20px;height:20px;position:relative;padding:0;margin-left:5px;border:none" onclick=""><i style="position:absolute;left:0;right:0;top:0;bottom:0;margin:0 auto;font-size:15px" class="fa fa-user-plus"></i></button>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label for="deptouser" class="txt-11 text-secondary col-3 col-sm-3">DEPARTAMENTO</label>
                        <input type="text" name="depto" id="deptouser" class="form-control form-control-sm col-9 col-sm-9" autocomplete="off" value="<?php echo $resEnt['depto']; ?>">
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="form-group row">
                        <label for="sucursal" class="txt-11 text-secondary col-3 col-sm-3">SUCURSAL</label>
                        <select onchange="return Sucursal(this.value);" name="sucursal" id="sucursal" class="form-control form-control-sm col-9 col-sm-9">
                            <option selected value="<?php echo base64_encode($resEnt['fksucursal']); ?>"><?php echo $resEnt['nombre'] ?></option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="fecha" class="txt-11 text-secondary col-3 col-sm-3">FECHA</label>
                        <input type="date" name="fecha" id="fecha" required class="form-control form-control-sm col-9 col-sm-9" value="<?php echo $resEnt['fecha']; ?>">
                    </div>
                    <div class="form-group row">
                        <label for="listorden" class="txt-11 text-secondary col-3 col-sm-3">O.T.:</label>
                        <select name="orden" id="listorden"  style="color:red;font-weight:bold" class="form-control form-control-sm col-3 col-sm-3">
                            <option value=""></option>
                        </select>
                        <label for="cotizacion" class="txt-11 text-secondary col-3 col-sm-3">COTIZACION</label>
                        <select name="cotizacion" id="cotizacion" class="form-control form-control-sm col-3 col-sm-3">
                            <option value=""></option>

                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="form-group row" style="margin-left:15px;">
                        <?php if ($modifica) { ?>
                            <button class="btn btn-info btn-sm" style="border-radius:0px;" name="imprimir" id="imprimir"><i class="fa fa-print fa-lg"></i><br> Imprimir</button>


                            <button class="btn btn-primary btn-sm" style="border-radius:0px;" name="guardar" id="guardar"><i class="fa fa-save fa-lg"></i><br>Guardar</button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-info btn-sm" style="border-radius:0px;" onclick="return Print('<?php echo base64_encode($resEnt['pkentrega']); ?>');"><i class="fa fa-print fa-lg"></i><br> Imprimir</button>
                        <?php } ?>
                    </div>
                    <div class="row form-inline" style="font-size:12px;margin-top:8px !important">
                        <div class="col-7"><span>Imp. con evidencia:</span></div>
                        <div class="col-2 custom-control custom-radio">
                            <input type="radio" id="radioSi" name="printEvi" class="custom-control-input">
                            <label class="custom-control-label" for="radioSi">Si</label>
                        </div>
                        <div class="col-2 custom-control custom-radio">
                            <input type="radio" id="radioNo" name="printEvi" checked class="custom-control-input">
                            <label class="custom-control-label" for="radioNo">No</label>
                        </div>
                        <!-- <div class="col-3"><input class="form-control form-control-sm" type="radio" name="printEvi">Si</div>
                    <div class="col-3"><input class="form-control form-control-sm"  type="radio" name="printEvi">No</div> -->
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-12" style="padding:2px">
                    <div style="height: 65vh;background:white;border:2px solid grey" class="scroll table-responsive">
                        <table class=" table-bordered table-ot" width="100%" id="table" style="background-color: white;font-size:11px; border-collapse:collapse;">
                            <thead style="position: sticky;top:0;z-index: 10;" class="table-info">
                                <tr>
                                    <th width="5%">PDA</th>
                                    <th width="5%">CANT.</th>
                                    <th width="5%">UNIDAD</th>
                                    <th width="40%">DESCRIPCIÓN</th>

                                </tr>
                            </thead>
                            <tbody class="text-secondary body-table" id="serv-ot">
                                <?php $cont = 0;
                                foreach ($oEntrega->GetDataAllServ($resEnt['pkentrega']) as $rowServ) {  ?>
                                    <tr style="max-height: 80px;">
                                        <input type="hidden" name="pkserv[]" value="<?php echo base64_encode($rowServ['pkserventrega']); ?>">
                                        <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control" autocomplete="off" inputmode="numeric" value="<?php echo $rowServ['pda']; ?>"></td>
                                        <td valign="top"><input type="number" name="cant[]" min="0.00" step="0.01" value="<?php echo $rowServ['cantidad']; ?>" class="form-control  " autocomplete="off"></td>
                                        <td valign="top">
                                            <select name="unidad[]" class="form-control">
                                                <option value=""></option>
                                                <?php foreach ($data as $unidad) {
                                                    $selec = $rowServ['unidad'] == $unidad['pkunidad'] ? 'selected' : '';
                                                ?>
                                                    <option <?php echo $selec; ?> value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td valign="top"><textarea name="descripcion[]" id="desc-<?php echo $cont ?>" class="form-control cajas-texto scrollHidden" autocomplete="off" style="resize:none;padding:3px" onclick="menu(this); return false;" oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('desc-<?php echo $cont; ?>')" onblur="ocultarScroll('desc-<?php echo $cont; ?>')"><?php echo $rowServ['descripcion']; ?></textarea></td>

                                    </tr>
                                <?php $cont++;
                                } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 ">
                    <center> <label class="txt-11 " for="observaciones">REQUERIMIENTOS ESPECIALES / OBSERVACIONES</label></center>
                    <textarea class="form-control top-8 scrollHidden" rows="3" name="observaciones"><?php echo $resEnt['observaciones']; ?></textarea>

                </div>

                <div class="col-12 col-sm-4">
                    <div class="row">
                        <label for="entrego" class="txt-11 col-6">EVIDENCIA: <a style="color:cornflowerblue" href="javascript:popup('<?php echo substr($resEnt['evidencia'], 3); ?>');"><span class="fa fa-eye fa-lg"></span> Ver</a></label>
                        <input type="file" name="evidencia" class="form-control col-5">
                        <input type="hidden" name="evidencia_bd" value="<?php echo $resEnt['evidencia']; ?>">
                    </div>
                    <div class="row">
                        <label for="entrego" class="txt-11 col-6">ENTREGO POR MSP:</label>
                        <select id="entrego" name="entrego" class="form-control listEmployee col-5">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="recicibio" class="txt-11 col-6">RECIBIO POR EL CLIENTE:</label>
                        <select id="recibio" name="recibio" class="form-control col-5 listUsercustomer">
                            <option value=""></option>
                        </select>
                    </div>

                </div>

            </div>


        </form>
    </main>
    <?php if ($modifica) { ?>
        <div id="menu" class="context-menu">
            <ul>
                <li><a id="eliminar"><i class="fa fa-trash-o fa-lg" style="color:red;margin-right:10px"></i>ELIMINAR REGISTRO</a></li>
            </ul>
        </div>
    <?php } ?>

    <?php
    include_once '../dependencias/php/footer.php';

    $dato = array(
        "orden" => base64_encode($resEnt["fkorden"]),
        "cliente" => base64_encode($resEnt["fkcliente"])
    );
    ?>

    <script type="text/javascript" src="../dependencias/js/Trazabilidad/Entrega.js"></script>
    <script>
        $(async function() {
            //$('[data-toggle="tooltip"]').tooltip();

            //Carga la sucursal
            let sucursalSelect = document.getElementById('sucursal').value;
            await Sucursal(sucursalSelect, <?php echo json_encode($dato); ?>);
            await usercustomer("<?php echo base64_encode($resEnt['fkcliente']); ?>");

            let solicito = document.getElementById('listUsercustomer');
            solicito.value = "<?php echo base64_encode($resEnt['fksolicito']); ?>";

            let entrego = document.getElementById('entrego');
            entrego.value = "<?php echo base64_encode($resEnt['fkentrego']); ?>";

            let recibio = document.getElementById('recibio');
            recibio.value = "<?php echo base64_encode($resEnt['fkrecibe']); ?>";
            let cotizacion = document.getElementById('cotizacion');
            cotizacion.value = "<?php echo base64_encode($resEnt['fkcotizacion']); ?>";

            let area = document.querySelectorAll(".cajas-texto");
            area.forEach((elemento) => {
                elemento.style.height = Math.min(elemento.scrollHeight, 150) + "px";
            });


        });

        function popup(URL) {
            window.open(URL, "", "width=400,height=400,scrollbars=yes,left=800,addressbar=0,menubar=0,toolbar=0");
        }

        document.getElementById('listorden').addEventListener("click", (event) => {
            event.stopPropagation();
            opcionSeleccionada = document.getElementById('listorden').value;
            document.getElementById('listorden').addEventListener("click", (event) => {
                let id = opcionSeleccionada;
                dataOrden(id, '<?php echo base64_encode($resEnt['pkentrega']); ?>');
            });

        });
    </script>