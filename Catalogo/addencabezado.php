<?php @session_start(); {
    $title = "- ENCABEZADOS";
}
include_once '../dependencias/php/head.php';
include_once '../dependencias/php/menu.php';
//include '../controlador/Employee.php';
include_once '../controlador/Usuario.php';
include_once '../controlador/Sucursal.php';
include_once '../controlador/Controles.php';

//$sucursal = (int) base64_decode($_GET['suc']);
#region Permisos
// if (!$rol->getPermissionControl($_SESSION['controles'], Controls::empleado->value, $sucursal) && ($_SESSION['tipo_user'] != 'ROOT' && $_SESSION['tipo_user'] != 'ADMIN' )) {
//   echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
//  return false;
// }

// $rol->getBranchInPermission($_SESSION['controles'], Controls::empleado->value);

// $modifica = in_array(Operacion::modifica->value, $rol->getOperacion()) || ($_SESSION['tipo_user'] == 'ROOT' || $_SESSION['tipo_user'] == 'ADMIN' ) ? true : false;
#endregion

$obsuc = new Sucursal();
//$obEmployee = new Employee();
$obuser = new Usuario();
$oCont = new Controles();


?>
<style>
    table {
        font-size: 11px;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .header-table,
    .content-table,
    .table-header-title {
        width: 100%;
        /* border: 1px solid black; */
    }

    #img-h1 {
        width: 15%;
    }

    .header-table td {
        border: none;
        padding: 5px;
    }

    .header {
        text-align: right;
    }

    .contact-info {
        text-align: center;
        width: 50%;
        max-width: 45%;
    }
</style>
<div class="main-wrapper">
    <!-- ! Main -->
    <main class="main users chart-page">
        <div class="container">

            <h5 class="h5">Nuevo encabezado</h5>
            <hr>
            <section>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                Vista Previa <i class="fa fa-eye"></i>
                            </div>
                            <div class="card-body">
                                <table class="header-table">
                                    <tr style="text-align:right">
                                        <td colspan="3" style="color:#33BEFF">
                                            <strong id="texto1">Texto 1 (vacio)</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="img-h1"><img src="../dependencias/img/logo-msp.png" style="width:200px;height:100px;position:relative;top:-20px"></td>
                                        <td class="contact-info" valign="top">
                                            <strong id="display-titulodesc">Titulo de la descripción (vacio)</strong><br>
                                            <div id="descripcion">
                                                Descripción (vacio)
                                            </div>
                                            <!-- <a href="mailto:ventas01@mspetroleros.com">ventas01@mspetroleros.com</a> -->
                                        </td>
                                        <td class="header">
                                            <div style="margin-right:0px;position:relative;width:130%;margin-top:-55px;right:30%">
                                                <strong id="texto2">Texto 2 (vacio)</strong><br>
                                                <strong id="texto3">Texto 3 (vacio)</strong><br>
                                                <strong id="texto4">Texto 4 (vacio)</strong><br>
                                            </div>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <section>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                Edición <i class="fa fa-edit"></i>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="row">
                                        <label class="col-3 col-sm-3 col-md-2 label label">Sucursal</label>
                                        <select id="sucursalView" class="col-12 col-9 col-sm-3 col-md-3 form-control form-control-sm">
                                            <option value=""></option>
                                            <?php foreach ($obsuc->GetDataAll() as $ressuc) {
                                                $selsuc = $sucursal == $ressuc['pksucursal'] ? 'selected' : '';
                                                //if (in_array($ressuc['pksucursal'], $rol->getBranch())) {
                                            ?>
                                                <option <?php echo $selsuc; ?> value="<?php echo base64_encode($ressuc['pksucursal']); ?>"><?php echo $ressuc['nombre']; ?></option>
                                            <?php }
                                            //} 
                                            ?>
                                        </select>
                                        <label class="col-3 col-sm-3 col-md-2 label label">Control</label>
                                        <select id="sucursalView" class="col-12 col-9 col-sm-3 col-md-3 form-control form-control-sm">
                                            <option value=""></option>
                                            <?php foreach ($oCont->getControls() as $ressuc) {
                                                // $selsuc = $sucursal == $ressuc['pksucursal'] ? 'selected' : '';
                                                //if (in_array($ressuc['pksucursal'], $rol->getBranch())) {
                                            ?>
                                                <option <?php echo $selsuc; ?> value="<?php echo base64_encode($ressuc['pkcontrol']); ?>"><?php echo $ressuc['nombre']; ?></option>
                                            <?php }
                                            //} 
                                            ?>
                                        </select>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <label class="col-3 col-sm-3 col-md-2 label">Texto 1</label>
                                        <input id="ftexto1" type="text" class="col-9 col-sm-3 col-md-3 form-control form-control-sm" name="texto1" oninput="Preview();">
                                        <label class="col-3 col-sm-3 col-md-2 label">Texto 2</label>
                                        <input id="ftexto2" type="text" class="col-9 col-sm-3 form-control form-control-sm col-md-3" name="texto2" oninput="Preview();">
                                    </div>
                                    <div class="row">
                                        <label class="col-3 col-sm-3 col-md-2 label">Texto 3</label>
                                        <input id="ftexto3" type="text" class="col-9 col-sm-3 col-md-3 form-control form-control-sm" name="texto1" oninput="Preview();">
                                        <label class="col-3 col-sm-3 col-md-2 label">Texto 4</label>
                                        <input id="ftexto4" type="text" class="col-9 col-sm-3 form-control form-control-sm col-md-3" name="texto2" oninput="Preview();">
                                    </div>
                                    <div class="row">
                                        <label class="col-12 label">Titulo de la descripción</label>
                                        <input type="text" class="col-12 form-control form-control-sm" id="titulodesc" oninput="Preview();" name="titulodesc">
                                        <label class="col-12 label">Descipción</label>
                                        <textarea class="col-12 form-control" id="fdescripcion" oninput="Preview();" rows="8" name="descripcion"></textarea>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


        </div>
    </main>
</div>

<?php
include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Catalogo/Headers.js"></script>