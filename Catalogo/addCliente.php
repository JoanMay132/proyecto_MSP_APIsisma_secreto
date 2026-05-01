<?php @session_start(); {$title = " - ALTA CLIENTE";}
  include '../dependencias/php/head.php';
  include '../dependencias/php/menu.php';
  include_once '../controlador/Sucursal.php';
  include_once '../controlador/Estado.php';
  include_once '../controlador/Pais.php';

  $idsuc = null;
  if(isset($_GET['suc'])){
    $idsuc = $_GET['suc'] != '' ? $_GET['suc'] :''; 
  }

  $rol->listBranchInPermission($_SESSION['controles'],Operacion::modifica->value,Controls::clientes->value);
  ?>

  <div class="main-wrapper">

    <?php include '../dependencias/php/header.php'; ?>

  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
      <div class="container">

        <h5 class="h5">REGISTRO DE CLIENTE</h5>
        <div id="mensaje"></div>
        <form  id="form-cliente" enctype="multipart/form-data" onsubmit="return addCliente()">
        <div class="row">
        
            <div class="col-12 col-md-6">
            <div class="card">
                
                <div class="card-body">
                    <div class="form-group row">
                            <div class="col-4">
                                <label class="label text-secondary">Cotizar con Moneda: *</label>  
                            </div>
                            <div class="col-8">
                            <select class="form-control form-control-sm option" name="moneda" required>
                                    <option value="NACIONAL">Nacional</option>
                                    <option value="DOLAR">Dolares USA</option>
                                </select>
                            </div> 
                    </div>
                    <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Sucursal *</label>  
                            </div>
                            <div class="col-10">
                                <select class="form-control form-control-sm" name="sucursal">
                                    <?php 
                                        $obsuc = new Sucursal();
                                        foreach($obsuc->GetDataAll() as $data){
                                            $selec = $idsuc != null && base64_decode($idsuc) == $data['pksucursal'] ? 'selected' : '';
                                            if(in_array($data['pksucursal'],$rol->getBranch())){
                                    ?>
                                    <option <?php echo $selec; ?> value="<?php echo $data["pksucursal"]; ?>"><?php echo $data["nombre"]; ?></option>
                                    <?php } }?>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Cliente *</label>  
                            </div>
                            <div class="col-10">
                                <input type="text" class="form-control form-control-sm"  name="cliente" autocomplete="off" required>
                            </div> 
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Dirección</label>  
                            </div>
                            <div class="col-10">
                                <input type="text" class="form-control form-control-sm"  name="direccion" autocomplete="nope">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Estado *</label>  
                            </div>
                            <div class="col-10">
                                <select class="form-control form-control-sm" name="estado" required onchange="return loadmunicipio(this.value)">
                                    <option value="">Seleccione estado</option>
                                    <?php $obest = new Estado();
                                        foreach($obest->GetDataAll() as $estado){
                                    ?>
                                    <option value="<?php echo $estado['id_estado']; ?>"><?php echo $estado["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">Municipio *</label>  
                            </div>
                            <div class="col-10">
                            <select class="form-control form-control-sm" id="municipio" required name="municipio">
                                    <option value=""></option>

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
                                    <?php $obpais = new Pais();
                                        foreach($obpais->GetDataAll() as $pais){
                                    ?>
                                    <option value="<?php echo $pais['nombre']; ?>"><?php echo $pais["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div> 
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">
                                <label class="label text-secondary">RFC</label>  
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm"  name="rfc" autocomplete="off">
                            </div>
                            <div class="col-2">
                                <label class="label text-secondary">C.P</label>  
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm"  name="cp" autocomplete="off">
                            </div> 
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-7 row">
                                <div class="col-3">
                                    <label class="label text-secondary">Telefono</label></label>  
                                </div>
                                <div class="col-9">
                                    <textarea class="form-control form-control-sm"  name="tel" autocomplete="off" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-3">
                                        <label class="label text-secondary">Correo 1</label>  
                                    </div>
                                    <div class="col-9">
                                        <input type="email" class="form-control form-control-sm"  name="correo1" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="label text-secondary">Correo 2</label>  
                                    </div>
                                    <div class="col-9">
                                        <input type="email" class="form-control form-control-sm"  name="correo2" autocomplete="off">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <label class="label text-secondary">Imagen</label> 
                            </div>
                            <div class="col-10"> 
                                <input type="file" name="imagen" class="form-control" accept="image/*">  
                            </div>
                        </div>
                        
                        <div class="float-right" id="g-botones"><br>
                                <?php 
                                    $ref = $idsuc != null ? $idsuc : $_SESSION['sucursal'];
                                ?>
                                <a href="clientes?suc=<?php echo $ref; ?>" class="btn-sm btn-link" name="guardar" >Ir a la Lista</a>
                                
                                <button class="btn-sm btn-primary" name="guardar" id="btn-guardar">Guardar</button>
                        </div>
                  
                </div>
            </div>
            </div>
            <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    Departamentos
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm txt-12" width="100%">
                            <thead>
                                <th width="90%">Nombre</th>
                                <th width="10%">Acción</th>
                            </thead>
                            <tbody class="body-table table-depto">
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


<!-- Adding alert to prevent accidental navigation away from the page -->
<script>
document.addEventListener("click", () => {
    window.userInteracted = true;
});

window.addEventListener("beforeunload", function (event) {
    if (window.userInteracted) {
        event.preventDefault();
        event.returnValue = "";
    }
});
</script>    
