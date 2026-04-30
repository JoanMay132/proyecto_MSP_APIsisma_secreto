<?php @session_start(); {$title = "PROVEEDORES";}
include '../dependencias/php/head.php';
include '../dependencias/php/menu.php';
include_once '../controlador/Sucursal.php';

$obsuc = new Sucursal();
$pksucursal = (int) base64_decode(@$_GET['suc']);

#region Permisos
if (!$rol->getPermissionControl($_SESSION['controles'], Controls::proveedores->value, $pksucursal)) {
  echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
  return false;
}

$rol->getBranchInPermission($_SESSION['controles'], Controls::proveedores->value);

$modifica = in_array(Operacion::modifica->value, $rol->getOperacion()) ? true : false;
#endregion

?>

<script>
  function popup(URL) {
    window.open(URL, "Alta proveedor", "width=800,height=400,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0");
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
  <main class="main users chart-page" id="skip-target">
    <div class="container">

      <h5 class="h5">PROVEEDORES</h5>

      <div class="row">
        <div class="col-12 col-md-8">
        <div class="card" style="margin-top:10px">
          <div class="card-body">
              <div id="group-search">
                
                <input type="search" placeholder="Buscar proveedor" onkeyup="filtro(this,'f-proveedor');" class="form-control form-control-sm" style="font-size:11px;width:300px;height:25px">
                <select id="sucursalView" class="form-control" style="height:25px;font-size:11px;padding:0px;width:200px" onchange="selecciona(this.value);">
                  <option value=""></option>
                  <?php foreach ($obsuc->GetDataAll() as $ressuc) {
                    $selsuc = $pksucursal == $ressuc['pksucursal'] ? 'selected' : '';
                    if (in_array($ressuc['pksucursal'], $rol->getBranch())) {
                  ?>
                      <option <?php echo $selsuc; ?> value="<?php echo base64_encode($ressuc['pksucursal']); ?>"><?php echo $ressuc['nombre']; ?></option>
                  <?php }
                  } ?>
                </select>
                <a class="btn btn-primary txt-12" style="border-radius:0px;height:auto" href="javascript:popup('addprovider')"><i class="fa fa-plus fa-lg"></i>Alta proveedor</a>
              </div>
              <div id="table-provide">

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
  <script type="text/javascript" src="../dependencias/js/Catalogo/Provider.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#table-provide').load('controller/getProvide?suc=<?php echo $_GET['suc']; ?>.php');
    });

    function actualizarGetProvide(sucursal) {
      $('#table-provide').load('controller/getProvide?suc='+sucursal);
    }

  </script>