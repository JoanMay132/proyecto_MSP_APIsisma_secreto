<?php @session_start(); {
  $title = " - CLIENTES";
}
include '../dependencias/php/head.php';
include '../dependencias/php/menu.php';
include_once '../controlador/Cliente.php';
include_once '../controlador/Sucursal.php';

$obsuc = new Sucursal();
$pksucursal = (int) base64_decode($_GET['suc']);

#region Permisos
if (!$rol->getPermissionControl($_SESSION['controles'], Controls::clientes->value, $pksucursal)) {
  echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
  return false;
}

$rol->getBranchInPermission($_SESSION['controles'], Controls::clientes->value);

$modifica = in_array(Operacion::modifica->value, $rol->getOperacion()) ? true : false;
#endregion

?>

<script>
  function popup(URL) {
    window.open(URL, "Alta Usuarios", "width=700,height=700,scrollbars=yes,left=800,addressbar=0,menubar=0,toolbar=0");
  }
</script>
<style>
  .form-control {
    border-radius: 0px !important;
  }

  #group-search {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;

  }
</style>

<div class="main-wrapper">
  <?php include '../dependencias/php/header.php'; ?>
  <!-- ! Main -->
  <main class="users chart-page" id="skip-target">
    <div class="container">

      <!-- <h5 class="h5">CLIENTES</h5> -->

      <div class="row">
        <div class="col-12 col-md-8">



          <div class="card" style="margin-top:10px">
            <div class="card-header" style="font-weight: bold;">
              CLIENTES
            </div>
            <div class="card-body">
              <div id="group-search">
                <input type="search" placeholder="Buscar cliente" onkeyup="filtro(this,'f-cliente');" class="form-control form-control-sm" style="font-size:11px;width:300px;height:25px">
                <select id="sucursalView" class="form-control" style="height:25px;font-size:11px;padding:0px;width:200px" onchange="selecciona();">
                  <option value=""></option>
                  <?php foreach ($obsuc->GetDataAll() as $ressuc) {
                    $selsuc = $pksucursal == $ressuc['pksucursal'] ? 'selected' : '';
                    if (in_array($ressuc['pksucursal'], $rol->getBranch())) {
                  ?>
                      <option <?php echo $selsuc; ?> value="<?php echo base64_encode($ressuc['pksucursal']); ?>"><?php echo $ressuc['nombre']; ?></option>
                  <?php }
                  } ?>
                </select>
                <?php if ($modifica) { ?>
                  <a href="addCliente?suc=<?php echo $_GET['suc']; ?>" class="btn btn-primary txt-12" style="border-radius:0px;height:auto"><i class="fa fa-plus fa-lg"></i>Alta Cliente</a>
                <?php } ?>
              </div>
              <div style="overflow:auto;height: 65vh;background:white;">
                <table class="table-hover table-stripped display compact table-bordered" id="table" style="width: 100%;">
                  <thead>
                    <tr>
                      <th width="80%">Nombre</th>
                      <?php if ($modifica) { ?>
                        <th>Accion</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody id="clientes">
                    <?php $obcli = new Cliente();
                    foreach ($obcli->GetDataAll($pksucursal) as $data) {
                    ?>
                      <tr>
                        <td id="f-cliente"><?php echo $data["nombre"]; ?></td>

                        <?php if ($modifica) { ?>
                          <td>
                            <a href="editCustomer?customer=<?php echo $data['pkcliente'] ?>" class="btn btn-warning" style="width:20px;height:20px;position:relative;padding:0"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-edit"></i></a>
                            <a href="javascript:popup('addusercustomer?customer=<?php echo base64_encode($data['pkcliente']); ?>&name=<?php echo htmlspecialchars($data['nombre']) ?>')" class="btn btn-info" style="width:20px;height:20px;position:relative;padding:0"><i class="fa fa-user-plus" style="position:absolute;left:0;right:0;top:0;bottom:0;width:8px;height:8px;margin:0"></i></a>
                          </td>
                        <?php } ?>
                      </tr>


                    <?php } ?>


                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>



    </div>
  </main>
  <?php
  include_once '../dependencias/php/footer.php';
  ?>

  <script type="text/javascript">
    function filtro(data, columna) {
      var value = $(data).val().toLowerCase();
      $("#clientes tr").each(function() {
        let found = false;
        $(this).find('#' + columna).each(function() {
          if ($(this).text().toLowerCase().indexOf(value) > -1) {
            found = true;
            return false;
          }
        });
        $(this).toggle(found);
      });
    }

    function selecciona() {
      let suc = document.getElementById('sucursalView').value;
      location.href = "clientes?suc=" + suc;

    }
  </script>