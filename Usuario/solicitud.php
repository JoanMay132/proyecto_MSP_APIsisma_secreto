<?php @session_start(); {$title = "- SESIONES"; }
  include_once '../dependencias/php/head.php';
  include_once '../dependencias/php/menu.php';
  include_once '../controlador/Sucursal.php';
  include_once '../class/sesion.php';

  if($_SESSION['tipo_user'] != 'ADMIN' && $_SESSION['tipo_user'] != 'ROOT'){
    header('Location:../main');
    exit;
 }

  $oSesion = new Sesion();
  $obsuc = new Sucursal();

  $sucursal = (int) base64_decode($_GET['suc']);
  $status = trim($_GET['status']);

  $colorPendiente = $status === 'pendiente' ? 'background-color:lightblue;' : 'background-color: #e2e6ea;'; 
  $colorRechazado = $status === 'rechazado' ? 'background-color:lightblue;' : 'background-color: #e2e6ea;';
  $colorAceptado = $status === 'aceptada' ? 'background-color:lightblue;' : 'background-color: #e2e6ea;';

 $contador = 1;

  ?>
   <style>
    #sub-menu{
        display: flex;
        flex-wrap: nowrap;
    }
        .custom-link {
            font-size: 12px;
            padding: 10px 20px;
            margin-right: 10px;
            border-radius: 25px;
            /* background-color:lightblue; */
            text-decoration: none;
            color: #333;
            /* border: 1px solid #dee2e6; */
            transition: background-color 0.3s, color 0.3s;
        }
        
        .custom-link:hover {
            background-color: lightblue;
            color: #000;
        }
        
    </style>
<div class="container mt-5">
    <h5 class="h5">SOLICITUDES DE INICIO DE SESIÓN</h5><hr>
    <div class="row mb-3">
        <div class="col-6 col-sm-3">
          <select id="sucursalView" class="form-control" style="height:25px;font-size:11px;padding:0px;width:200px" onchange="selecciona();">
            <option value="">Sucursal:</option>
            <?php foreach ($obsuc->GetDataAll() as $ressuc) {
              $selsuc = $sucursal == $ressuc['pksucursal'] ? 'selected' : '';
              //if (in_array($ressuc['pksucursal'], $rol->getBranch())) {
            ?>
                <option <?php echo $selsuc; ?> value="<?php echo base64_encode($ressuc['pksucursal']); ?>"><?php echo $ressuc['nombre']; ?></option>
            <?php }
              //} ?>
          </select>
        </div>
        <div class="col-6 col-sm-3">
          <div class="row">
            <div class="col-8"><input type="search" id="search" placeholder="Buscar" class="form-control form-control-sm" style="height:25px;font-size:11px;padding:3px;width:200px" /></div>

          </div>

        </div>
        <div class="col-12 col-sm-6">
          
            <div   id="sub-menu">
                <a href="<?php echo 'solicitud?suc='.$_GET['suc'].'&status=pendiente'; ?>" style="<?php echo $colorPendiente; ?>" class="custom-link" >Pendientes</a>
                <a href="<?php echo 'solicitud?suc='.$_GET['suc'].'&status=aceptada'; ?>" style="<?php echo $colorAceptado; ?>" class="custom-link" >Aprobados</a>
                <a href="<?php echo 'solicitud?suc='.$_GET['suc'].'&status=rechazado'; ?>" style="<?php echo $colorRechazado; ?>" class="custom-link" >Rechazados</a>
            </div>
        </div>
      </div>
    
    <div class="table-responsive">
        <table class="table table-hover display-table" style="background:white;font-size:13px;">
            <thead >
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Navegador</th>
                    <th>Equipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="solicitud">
               
                 <?php foreach ($oSesion->GetData($sucursal,$status) as $value) { ?>
                    <tr>
                        <td><?php echo $contador; ?></td>
                        <td><?php echo $value['usuario']; ?></td>
                        <td><?php echo $value['nombre']; ?></td>
                        <td><?php echo $value['apellidos']; ?></td>
                        <td><?php echo $value['user_agent']; ?></td>
                        <td><?php echo $value['hostname']; ?></td>
                        <?php if($value['status'] === 'pendiente'){ ?>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="return updateStatus('<?php echo base64_encode($value['pksesion']); ?>','aceptada');"><i class="fa fa-check-circle-o" style="font-size:18px;"></i>Aprobar</button>
                            <button class="btn btn-danger btn-sm" onclick="return updateStatus('<?php echo base64_encode($value['pksesion']); ?>','rechazado');"><i class="fa fa-ban" style="font-size:18px;"></i>Rechazar</button>
                        </td>
                        <?php }else if($value['status'] === 'aceptada'){ ?>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="return deleteSesion('<?php echo base64_encode($value['pksesion']); ?>');"><i class="fa fa-trash-o" style="font-size:18px;"></i>Eliminar sesión</button>
                        </td>
                        <?php }else if($value['status'] === 'rechazado'){ ?>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="return updateStatus('<?php echo base64_encode($value['pksesion']); ?>','aceptada');"><i class="fa fa-check-circle-o" style="font-size:18px;"></i>Aprobar</button>
                        </td>
                        <?php  } ?>                            
                    </tr>
                <?php $contador++; } ?>
                

            </tbody>
        </table>
    </div>
</div>
<?php
include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Usuario/solicitud.js"></script>
<script>
    $(document).ready(function() {
    $("#search").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#solicitud tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

  });
function selecciona(){
	let suc =  document.getElementById('sucursalView').value;  
	  location.href = "solicitud?suc="+suc+'&status=<?php echo $status; ?>';
  
  }
</script>