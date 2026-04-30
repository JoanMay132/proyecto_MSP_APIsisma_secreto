<?php @session_start(); {$title = "- USUARIO DEL CLIENTE"; }
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
  
  $data = [];
  $popper = true;
  $usercli = new Usercli();
  $obdepto = new Deptocli();

  foreach ($obdepto->GetDataAll($pkcliente) as $value) {
    $data[] = array("pkdeptocli" =>$value['pkdeptocli'],"nombre" =>$value['nombre']);
  }

 
  ?>

  <div class="main-wrapper">
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
        <div class="container">
            <h5 class="h5">USUARIOS DEL CLIENTE</h5><span class="blockquote-footer"><?php if(isset($_GET['name'])){ echo htmlspecialchars_decode($_GET['name']); } ?></span>
            

            
        </div>
        <div class="row" style="margin:0">
        <div class="">
                    <div class="card-body" style="margin:0px;padding:3px">
                        <form id="form-usercustom" onsubmit="return addUser();">
                        <input type="hidden" name="fkcliente" value="<?php echo base64_encode($pkcliente); ?>">
                        <div class="card" id="display-user" style="padding: 10px;">
                            <table class="table table-responsive compact" id="table" style="width:100%">
                                <thead>
                                <tr >
                                    <th width="8%">Titulo</th>
                                    <th width="62%">Nombre</th>
                                    <th width="20%">Departamento</th>
                                    <th width="10%">Puesto</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="body-table table-usercustomer">
                                <?php 
                                $conteo = 0;
                                foreach($usercli->GetDataAll($pkcliente) as $result){ ?>
                                
                                    <tr ondblclick='addusercustomer(<?php echo json_encode($data); ?>)' id="newusercustomerReg-<?php echo $conteo ;?>">
                                    
                                    <input type="hidden" name="user[]" value="<?php echo base64_encode($result['pkusercli']); ?>">
                                    <td><input type="text" name="tituloReg[]" class="form-control" placeholder="Vacio" autocomplete="off" value="<?php echo $result['titulo']; ?>"></td>
                                    
                                    <td>
                                        <input type="text" name="nombreReg[]" class="form-control" placeholder="Vacio" autocomplete="nope" value="<?php echo $result['nombre']; ?>">
                                        <p style="display:none"><?php echo $result['nombre']; ?></p>
                                    </td>
                                    <td>
                                        <select class="form-control" name="deptocliReg[]">
                                            <option value=""></option>
                                            <?php 
                                                foreach($data as $deptoReg){
                                                    $select = $result['fkdeptocli'] == $deptoReg['pkdeptocli'] ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $deptoReg['pkdeptocli'] ?>" <?php echo $select; ?>><?php echo $deptoReg["nombre"] ?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="puestoReg[]" class="form-control" placeholder="Vacio" autocomplete="off" value="<?php echo $result['cargo']; ?>">
                                    </td>
                                    <td>
                                        <button class="btn btn-info" style="width:20px;height:20px;position:relative;padding:0" id="<?php echo $conteo ;?>" onclick="return deleteusercustomerReg(this,'<?php echo base64_encode($result['pkusercli']) ;?>')"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                               <?php $conteo++; } ?>

                                <tr ondblclick='addusercustomer(<?php echo json_encode($data); ?>)' id="newusercustomer-0">
                                    <td><input type="text" name="titulo[]" class="form-control" placeholder="Vacio" autocomplete="off">
                                </td>
                                    <td>
                                        <input type="text" name="nombre[]" class="form-control" placeholder="Vacio" autocomplete="nope">
                                        
                                    </td>
                                    <td>
                                        <select class="form-control" name="deptocli[]">
                                            <option value=""></option>
                                            <?php 
                                                foreach($data as $depto){
                                            ?>
                                            <option value="<?php echo $depto['pkdeptocli'] ?>"><?php echo $depto["nombre"] ?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="puesto[]" class="form-control" placeholder="Vacio" autocomplete="off">
                                    </td>
                                    <td>
                                        <button class="btn btn-info" style="width:20px;height:20px;position:relative;padding:0" id="0" onclick="return deleteusercustomer(this)"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash"></i></button>
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
<script type="text/javascript" src="../dependencias/js/Catalogo/Usercli.js"></script>
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
                info: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json',
                },
            });
        });
       
        
        
    </script>


    