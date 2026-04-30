<?php {$title = "DEPARTAMENTOS"; }
  include '../dependencias/php/head.php';
  include_once '../controlador/Usercli.php';
  include_once '../controlador/Deptocli.php';

  //Se recibe la URL y se valida
  $pkcliente = (int) base64_decode($_GET['customer']);
  $pkcliente = filter_var($pkcliente,FILTER_VALIDATE_INT);

  if(!$pkcliente){
    echo "ERROR AL MOSTRAR EL CONTENIDO, LA URL NO ES VALIDA";
    return false;
  }
  
  $popper = true;
  $obdepto = new Deptocli();

 
  ?>

  <div class="main-wrapper">
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
        <div class="container">
            <h5 class="h5">DEPARTAMENTOS/AREA DEL CLIENTE</h5>   
        </div>
        <div class="row" style="margin:0">
        <div class="col-12">
                    <div class="card-body">
                        <form id="form-deptocli" onsubmit="return addDepto();">
                        <input type="hidden" name="fkcliente" value="<?php echo base64_encode($pkcliente); ?>">
                        <div class="card" style="padding: 10px;" id="display-depto">
                            <table class="table table-responsive table-stripped compact" id="table" style="width:100%">
                                <thead>
                                <tr>
                                    <th width="100%">Departamento/Area</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="body-table table-deptocli">
                                <?php 
                                $conteo = 0;
                                foreach($obdepto->GetDataAll($pkcliente) as $result){ ?>
                                
                                    <tr ondblclick="return addDeptocli();" id="deptoReg-<?php echo $conteo ;?>">
                                    
                                    <input type="hidden" name="pkdeptocli[]" value="<?php echo base64_encode($result['pkdeptocli']); ?>">
                                    <td><input type="text" name="nombreReg[]" class="form-control" placeholder="Vacio" autocomplete="off" value="<?php echo $result['nombre']; ?>"></td>

                                    <td>
                                        <button type="button" class="btn btn-info" style="width:20px;height:20px;position:relative;padding:0" onclick="return EliminarFilaReg(this,'<?php echo base64_encode($result['pkdeptocli']) ;?>');"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                               <?php $conteo++; } ?>

                                <tr ondblclick="addDeptocli();">
                                    <td>
                                        <input type="text" name="nombre[]" class="form-control" placeholder="Vacio" autocomplete="off">
                                        
                                    </td>
                                    <td>
                                        <button class="btn btn-info" style="width:20px;height:20px;position:relative;padding:0" id="0" onclick="return eliminarFila(this)"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                                

                                </tbody>
                            </table>
                            
                        </div>
                        <button class="btn btn-primary btn-sm btn-block">Guardar</button>
                        </form>
                    </div>
                </div>
        </div>

    </main>
<?php
  include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Catalogo/Deptocli.js"></script>
<script type="text/javascript">
        /* window.onbeforeunload = function() {
            // Función que se ejecutará al cerrar la página
            addUser();
            //return null; // Esta línea es necesaria para que funcione en algunos navegadores antiguos
        };*/
        $(document).ready(function () {
            $('#table').DataTable({
                paging: false,
                ordering: true,
                info: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json',
                },
            });
        });
       
        
        
    </script>


    