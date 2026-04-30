<?php @session_start(); {$title = "- CLIENTE";}
  include '../dependencias/php/head.php';
  include '../dependencias/php/menu.php';
  include_once '../controlador/Cliente.php';
  include_once '../controlador/Sucursal.php';
  include_once '../controlador/Estado.php';
  include_once '../controlador/Pais.php';
  include_once '../controlador/Municipio.php';
  include_once '../controlador/Deptocli.php';
  

  //Se recibe la URL y se valida
  $pkcliente = filter_var($_GET['customer'],FILTER_VALIDATE_INT);

  if(!$pkcliente){
    echo "ERROR AL MOSTRAR EL CONTENIDO, LA URL NO ES VALIDA";
    return false;
  }

  //Creacion de objetos
  $obcli = new Cliente();
  $obsuc = new Sucursal();
  $obest = new Estado();
  $obpais = new Pais();
  $obmuni = new Municipio();
  $deptocli = new Deptocli();

  //Consulta de datos por cliente
  $row = $obcli->GetData($pkcliente);


  ?>

  <div class="main-wrapper">

    <?php include '../dependencias/php/header.php'; ?>

  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
      <div class="container">
        <h5 class="h5">MODIFICACION DE CLIENTE</h5>
        <div id="mensaje"></div>
        <form  id="form-cliente" enctype="multipart/form-data" onsubmit="return editCustomer()">
        <div class="row">
        
            <div class="col-12 col-md-6">
            <div class="card">
                
                <div class="card-body">
                    <input type="hidden" name="pkcliente" value=<?php echo $pkcliente; ?>>
                    <div class="form-group row">
                        <?php $nacional = $row['moneda'] == 'NACIONAL' ? 'selected' : '';
                              $dolares = $row['moneda'] == 'DOLAR' ? 'selected' : '';
                         ?>
                            <div class="col-4">
                                <label class="label text-secondary">Cotizar con Moneda: *</label>  
                            </div>
                            <div class="col-8">
                            <select class="form-control form-control-sm option" name="moneda" required>
                                    <option <?php echo $nacional; ?> value="NACIONAL">Nacional</option>
                                    <option <?php echo $dolares; ?> value="DOLAR">Dolares USA</option>
                                </select>
                            </div> 
                    </div>
                    <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Sucursal *</label>  
                            </div>
                            <div class="col-10">
                                <select class="form-control form-control-sm" name="sucursal">
                                    <option selected value="<?php echo $row["fksucursal"]; ?>" ><?php echo $row["namesuc"]; ?></option>
                                  
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Cliente *</label>  
                            </div>
                            <div class="col-10">
                                <input type="text" class="form-control form-control-sm"  name="cliente" autocomplete="off" required value="<?php echo $row['nombre']?>">
                            </div> 
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Dirección</label>  
                            </div>
                            <div class="col-10">
                                <input type="text" class="form-control form-control-sm"  name="direccion" autocomplete="nope" value="<?php echo $row['direccion']?>">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Estado *</label>  
                            </div>
                            <div class="col-10">
                                <select class="form-control form-control-sm" name="estado" required onchange="return loadmunicipio(this.value)">
                                    <option value="">Seleccione estado</option>
                                    <?php 
                                        foreach($obest->GetDataAll() as $estado){
                                        $selec_sucursal = $estado['id_estado'] == $row['fkestado'] ? "selected" : "";
                                    ?>
                                    <option value="<?php echo $estado['id_estado']; ?>"  <?php echo $selec_sucursal ?> ><?php echo $estado["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Municipio *</label>  
                            </div>
                            <div class="col-10">
                            <select class="form-control form-control-sm" id="municipio" required  name="municipio">
                                    <?php 
                                        foreach($obmuni->GetDataAll($row['fkestado']) as $municipio){ 
                                            $selec_muni = $municipio['id_municipio'] == $row['fkmunicipio'] ? "selected" : "";
                                            ?>
                                    <option value="<?php echo $municipio['id_municipio'] ?>" <?php echo $selec_muni ?>><?php echo $municipio['nombre'] ?></option>
                                    
                                    <?php }?>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Pais *</label>  
                            </div>
                            <div class="col-10">
                            <select class="form-control form-control-sm option" name="pais" required>
                                    <option value="">Seleccione pais</option>
                                    <?php 
                                        foreach($obpais->GetDataAll() as $pais){
                                            $selec_pais = $pais['nombre'] == $row['pais'] ? "selected" : "";
                                    ?>
                                    <option value="<?php echo $pais['nombre']; ?>" <?php echo $selec_pais; ?>><?php echo $pais["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">RFC</label>  
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm"  name="rfc" autocomplete="off" value="<?php echo $row['rfc']; ?>">
                            </div>
                            <div class="col-2">
                                <label class="label text-secondary">C.P</label>  
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm"  name="cp" autocomplete="off" value="<?php echo $row['cp']; ?>">
                            </div> 
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-7 row">
                                <div class="col-3">
                                    <label class="label text-secondary">Telefono</label></label>  
                                </div>
                                <div class="col-9">
                                    <textarea class="form-control form-control-sm"  name="tel" autocomplete="off" rows="3"><?php echo $row['telefono']; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-3">
                                        <label class="label text-secondary">Correo 1</label>  
                                    </div>
                                    <div class="col-9">
                                        <input type="email" class="form-control form-control-sm"  name="correo1" autocomplete="off" value="<?php echo $row['correo1']; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="label text-secondary">Correo 2</label>  
                                    </div>
                                    <div class="col-9">
                                        <input type="email" class="form-control form-control-sm"  name="correo2" autocomplete="off" value="<?php echo $row['correo2']; ?>">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <label class="label text-secondary">Imagen</label> 
                            </div>
                            <div class="col-10"> 
                                <input type="file" name="imagen" class="form-control" accept="image/*" >  
                                <input type="hidden" name="img" value="<?php echo $row['imagen']; ?>">
                            </div>
                        </div>
                        <div class="float-right" id="g-botones"><br>
                                <a href="clientes?suc=<?php echo base64_encode($row['fksucursal']); ?>" class="btn-sm btn-link" name="guardar" >Ir a la Lista</a>
                                <button class="btn-sm btn-primary" name="guardar" id="btn-guardar">Actualizar</button>
                        </div>
                  
                </div>
            </div>
            </div>
            <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    Departamentos
                </div>
                <div class="card-body" id="table-view-depto">
                    <table class="table table-bordered table-sm txt-12" width="100%" id="table-depto">
                            <thead>
                                <th width="90%">Nombre</th>
                                <th width="10%">Acción</th>
                            </thead>
                            <tbody class="body-table table-depto">
                                <?php 
                                $conteo = 0;
                                foreach ($deptocli->GetDataAll($row['pkcliente']) as $depto) { ?>
                                   <input type="hidden" name="pkdeptocli[]" value="<?php echo $depto['pkdeptocli'] ?>">
                                <tr ondblclick='adddepto()' id="deptoReg-<?php echo $conteo; ?>">
                                    <td><input type="text" name="ndeptoReg[]" class="form-control" placeholder="Introduzca el nombre" autocomplete="off" value="<?php echo $depto['nombre'] ?>"></td>

                                    <td style="text-align: center;">
                                        <button class="btn btn-danger " style="width:20px;height:20px;position:relative;padding:0" id="<?php echo $conteo; ?>" onclick="return deleteDeptoReg(this,'<?php echo base64_encode($depto['pkdeptocli']); ?>')"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash"></i></button>
                                    </td>
                                </tr>

                               <?php $conteo++; } ?>
                                <tr ondblclick='adddepto()' id="newdepto-0">
                                    <td><input type="text" name="ndepto[]" class="form-control" placeholder="Introduzca el nombre" autocomplete="off" ></td>

                                    <td style="text-align: center;">
                                    <button class="btn btn-danger " style="width:20px;height:20px;position:relative;padding:0" id="0" onclick="return deletedepto(this)"><i style="position:absolute;left:0;right:0;top:0;bottom:0;width:15px;height:15px;margin:auto" class="fa fa-trash"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                    </table>	
                    
                </div>
            </div>

            </div>
           
        </div>
        </form>                             
        
      </div>
    </main>
    <script src="../dependencias/js/cargas.js" type="text/javascript"></script>
    <script src="../dependencias/js/Catalogo/Cliente.js" type="text/javascript"></script>
<?php
  include_once '../dependencias/php/footer.php';
?>

<script type="text/javascript">
        $(document).ready(function () {
            $('#table').DataTable({
                paging: false,
                ordering: true,
                info: true,
            });

        });
    </script>


