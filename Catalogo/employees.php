<?php @session_start(); {$title = "- EMPLEADOS";}
include '../dependencias/php/head.php';
include '../dependencias/php/menu.php';
include '../controlador/Employee.php';
include '../controlador/Usuario.php';
include_once '../controlador/Sucursal.php';

$sucursal = (int) base64_decode($_GET['suc']);
#region Permisos
if (!$rol->getPermissionControl($_SESSION['controles'], Controls::empleado->value, $sucursal) && ($_SESSION['tipo_user'] != 'ROOT' && $_SESSION['tipo_user'] != 'ADMIN' )) {
  echo "NO TIENES NINGUN TIPO DE PERMISO PARA VER ESTA SECCIÓN EN LA SUCURSAL SELECCIONADA";
 return false;
}

$rol->getBranchInPermission($_SESSION['controles'], Controls::empleado->value);

$modifica = in_array(Operacion::modifica->value, $rol->getOperacion()) || ($_SESSION['tipo_user'] == 'ROOT' || $_SESSION['tipo_user'] == 'ADMIN' ) ? true : false;
#endregion

$obsuc = new Sucursal();
$obEmployee = new Employee();
$obuser = new Usuario();


?>
<style>
  .dropdown-menu {
    margin: 0 !important;
    padding: 0px !important;

  }

  .dropdown-menu li {
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-size: 13px;
    padding: unset;
    margin: 0;
  }

  .dropdown-menu li a {
    display: block;
    /* Hace que <a> sea un contenedor de bloque */
    width: 100%;
    /* Ocupa todo el ancho del elemento padre <li> */
    margin: 0;
    /* Elimina el margen si es necesario */
    padding: 8px;
    /* Opcional: Añade espacio interno si es necesario */
    text-decoration: none;
    /* Opcional: Elimina el subrayado del enlace */
  }

  .dropdown-menu li a:hover {
    background: steelblue;
    color: white;
  }

  .status-select {
    width: 105px;
    height: 22px;
    font-size: 11px;
    border: 1px solid transparent;
    color: #fff;
    font-weight: 600;
    padding: 0 6px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    user-select: none;
  }

  .status-select.status-active {
    background-color: #198754 !important;
    border-color: #146c43 !important;
  }

  .status-select.status-inactive {
    background-color: #dc3545 !important;
    border-color: #b02a37 !important;
  }
</style>
<div class="main-wrapper">
  <!-- ! Main -->
  <main class="main users chart-page">
    <div class="container">

      <h5 class="h5">EMPLEADOS</h5>
      <hr>
      <div class="row">
        <div class="col-2">
          <?php if($modifica){ ?>
          <a href="javascript:AddEmployee('addemployee?suc=<?php echo @$_GET['suc']; ?>')" style="border-radius:0px;height:auto" class="btn btn-primary txt-12"><i class="fa fa-plus"></i>Nuevo Empleado</a>
          <?php } ?>
        </div>
        <div class="col-3">
          <select id="sucursalView" class="form-control" style="height:25px;font-size:11px;padding:0px;width:200px" onchange="selecciona();">
            <option value="">Sucursal:</option>
            <?php foreach ($obsuc->GetDataAll() as $ressuc) {
              $selsuc = $sucursal == $ressuc['pksucursal'] ? 'selected' : '';
              if (in_array($ressuc['pksucursal'], $rol->getBranch())) {
            ?>
                <option <?php echo $selsuc; ?> value="<?php echo base64_encode($ressuc['pksucursal']); ?>"><?php echo $ressuc['nombre']; ?></option>
            <?php }
              } ?>
          </select>
        </div>
        <div class="col-6">
          <div class="row" style="float:right">
            <div class="col-4"><label class="label text-secondary txt-12" for="search" style="float:right">Buscar</label></div>
            <div class="col-8"><input type="search" id="search" class="form-control form-control-sm" /></div>

          </div>

        </div>
      </div><br>
      <div class="row">
        <div class="col-lg-12">
          <div class="users-table table-wrapper">
            <table class="posts-table">
              <thead>
                <tr class="users-table-info">
                  <th>Foto</th>
                  <th>Nombre</th>
                  <th>Curp</th>
                  <th>Nss</th>
                  <th>Status</th>
                  <th>Ultimo ingreso</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="employee">
                <?php

                foreach ($obEmployee->GetDataJoin($sucursal) as $employee) {
                  $dataH = $obEmployee->checkState($employee['pkempleado']);

                ?>
                  <tr>
                    <td>
                      <div class="pages-table-img">
                        <picture><img src="./../dependencias/img/avatar/avatar-illustrated-03.png" alt="User Name" style="width:50px;height:50px"></picture>
                      </div>
                    </td>
                    <td><?php echo $employee['nombre'] . " " . $employee['apellidos']; ?></td>
                    <td><?php echo $employee['curp']; ?></td>
                    <td><?php echo $employee['nss']; ?></td>

                    <td>
                      <?php $isActivo = ($dataH['baja'] === '0000-00-00'); ?>
                      <?php if ($modifica) { ?>
                        <select id="status-control-<?php echo base64_encode($employee['pkempleado']); ?>"
                          class="status-select form-control form-control-sm <?php echo $isActivo ? 'status-active' : 'status-inactive'; ?>"
                          style="height:24px;"
                          data-prev="<?php echo $isActivo ? '1' : '0'; ?>"
                          onchange="return updateEmployeeStatus(this,'<?php echo base64_encode($employee['pkempleado']); ?>','<?php echo base64_encode($employee['fksucursal']); ?>');">
                          <option value="1" <?php echo $isActivo ? 'selected' : ''; ?>>Activo</option>
                          <option value="0" <?php echo !$isActivo ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                      <?php } else { ?>
                        <span class="<?php echo $isActivo ? 'badge-success' : 'badge-danger'; ?>">
                          <?php echo $isActivo ? 'Activo' : 'Inactivo'; ?>
                        </span>
                      <?php } ?>
                    </td>
                    <td><?php echo $dataH['alta'] ?></td>
                    <td>
                      <button type="button" class="dropdown-toggle" id="menu" data-bs-toggle="dropdown" aria-expanded="false" style="background:none"></button>
                      <ul class="dropdown-menu">
                        <li><a href="##">Ver/Editar</a></li>
                        <?php
                        if (@$_SESSION['tipo_user'] === 'ADMIN' || @$_SESSION['tipo_user'] === 'ROOT') {
                          if ($employee['fkusuario'] == 0) { ?>
                            <li><a class="dropdown-item" href="javascript:AddEmployee('addUser?employee=<?php echo base64_encode($employee['pkempleado']) ?>')">Crear usuario</a></li>
                          <?php } else { ?>
                            <li><a href="javascript:user('addUser?user=<?php echo base64_encode($employee['pkusuario']) ?>')">Usuario</a></li>
                            <?php

                            //if ($employee['rol'] == 'NORMAL') { ?>
                              <li><a href="javascript:Permisos('permisos<?php echo '?user=' . base64_encode($employee['fkusuario']) . '&suc=' . base64_encode($employee['fksucursal']);  ?>')">Permisos</a></li>
                        <?php //}
                          }
                        } ?>
                      </ul>

                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<?php
include_once '../dependencias/php/footer.php';
?>
 <script type="text/javascript" src="../dependencias/js/Catalogo/Employee.js"></script>
<script>
  $(document).ready(function() {
    $("#search").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#employee tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

  });

 
</script>