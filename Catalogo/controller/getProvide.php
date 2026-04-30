<?php 
include_once '../../controlador/conexion.php';
include_once '../../controlador/Proveedor.php';
  
$pksucursal = (int) base64_decode(@$_GET['suc']);
  ?>
<table class="table-hover table-stripped display compact table-bordered" id="table" style="width: 100%;" id="table">
                <thead>
                    <tr>
                        <th width="80%">Nombre</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody id="proveedor">
                    <?php $obprov = new Proveedor();
                    foreach ($obprov->GetDataAll($pksucursal) as $data) {
                    ?>
                    <tr>
                        <td id="f-proveedor"><?php echo $data["nombre"]; ?></td>
                        <td>
                            <a href="javascript:popup('addprovider?provider=<?php echo base64_encode($data['pkproveedor']); ?>')" class="btn btn-warning" style="width:20px;height:20px;position:relative;padding:0"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-edit"></i></a> 
                            <a onclick="return deleteprovider('<?php echo base64_encode($data['pkproveedor']); ?>');" class="btn btn-danger" style="width:20px;height:20px;position:relative;padding:0"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto;color:white" class="fa fa-trash-o"></i></a> 
                        </td>
                    </tr>

                    <?php } ?>


                </tbody>
            </table>

            <!-- <script type="text/javascript">
        $(document).ready(function () {
            $('#table').DataTable({
                paging: false,
                ordering: true,
                info: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json',
                },
            });
        
        });    
    </script> -->
