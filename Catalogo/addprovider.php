<?php @session_start(); {$title = "- PROVEEDOR";}
  include '../dependencias/php/head.php';
  include_once '../controlador/Sucursal.php';
  include_once '../controlador/Proveedor.php';

  $popper = true;
  $obsuc=new Sucursal();
  $obprov = new Proveedor(); 

  $rol->listBranchInPermission($_SESSION['controles'],Operacion::modifica->value,Controls::proveedores->value);

  if(isset($_GET['provider']) && !empty($_GET['provider'])){
    $pkprovider = (int) base64_decode($_GET['provider']);
    if(!filter_var($pkprovider,FILTER_VALIDATE_INT)){
        echo "ERROR, URL NO VALIDA";
        return false;
    } 

    $result  = $obprov->GetData($pkprovider);
}
  ?>
  

  <div class="main-wrapper">
    <!-- ! Main -->
    

        
        <div class="row" style="margin:0">
            <div class="col-12">
            <div class="card" >
                <div class="card-header">
                   <strong>Alta proveedor</strong> 
                </div>
                    <div class="card-body">
                        <form id="form-provider" onsubmit="return addProvider();">
                        <?php 
                        
                        if(isset($_GET['provider']) && !empty($_GET['provider'])){

                             ?>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label class="label text-secondary">Sucursal *</label>
                                <select class="form-control form-control-sm txt-12" name="sucursal">
                                    <option  value="<?php echo base64_encode($result['pksucursal']);?>"><?php echo $result['nsucursal'];?></option>
                               
                                </select>
                            </div>
                        </div>
                                <div class="row">
                                    <input type="hidden" name="pkprovider" value="<?php echo $_GET['provider'] ?>">
                                    <div class="col-12 col-sm-3">
                                            <label class="label text-secondary">Empresa *</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="empresa"><?php echo $result['nombre']; ?></textarea>
                                    </div>
                                    <div class="col-12 col-sm-3">
                                            <label class="label text-secondary">Telefono</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="telefono"><?php echo $result['telefono']; ?></textarea>
                                    </div>
                                    <div class="col-12 col-sm-3">
                                            <label class="label text-secondary">Correo</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="correo"><?php echo $result['correo']; ?></textarea>
                                    </div>
                                    <div class="col-12 col-sm-3">
                                            <label class="label text-secondary">Contacto</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="contacto"><?php echo $result['contacto']; ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">Dirección</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="direccion"><?php echo $result['direccion']; ?></textarea>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">Ciudad</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="ciudad"><?php echo $result['ciudad']; ?></textarea>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">Datos bancarios</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="datbancario"><?php echo $result['datbancario']; ?></textarea>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">Num. Proveedor</label>
                                            <input type="text" class="form-control form-control-sm"  name="nproveedor" value="<?php echo $result['nproveedor']; ?>">
                                    </div>
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">RFC</label>
                                            <input class="form-control form-control-sm"  type="Text" name="rfc" value="<?php echo $result['rfc']; ?>">
                                    </div>
                                    <div class="col-12 col-sm-4"><br>
                                        <button class="btn btn-primary btn-sm float-right">Guardar</button>
                                    </div>
                                    
                                </div>
                        <?php } else{ ?>
                            <div class="row">
                            <div class="col-12 col-sm-6">
                                <label class="label text-secondary">Sucursal *</label>
                                <select class="form-control form-control-sm txt-12" name="sucursal" required>
                                    <option value=""></option>
                                    <?php foreach($obsuc->GetDataAll() as $sucursal){
                                            if(in_array($sucursal['pksucursal'],$rol->getBranch())){
                                        ?>
                                    <option value="<?php echo base64_encode($sucursal['pksucursal']);?>"><?php echo $sucursal['nombre'];?></option>
                                    <?php } }?>
                                </select>
                            </div>
                        </div>
                                <div class="row">
                                    <div class="col-12 col-sm-3">
                                            <label class="label text-secondary">Empresa *</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="empresa"></textarea>
                                    </div>
                                    <div class="col-12 col-sm-3">
                                            <label class="label text-secondary">Telefono</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="telefono"></textarea>
                                    </div>
                                    <div class="col-12 col-sm-3">
                                            <label class="label text-secondary">Correo</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="correo"></textarea>
                                    </div>
                                    <div class="col-12 col-sm-3">
                                            <label class="label text-secondary">Contacto</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="contacto"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">Dirección</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="direccion"></textarea>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">Ciudad</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="ciudad"></textarea>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">Datos bancarios</label>
                                            <textarea class="form-control form-control-sm" rows="3" name="datbancario"></textarea>
                                    </div>
                                    
                                    
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">Num. Proveedor</label>
                                            <input type="text" class="form-control form-control-sm"  name="nproveedor">
                                    </div>
                                    <div class="col-12 col-sm-4">
                                            <label class="label text-secondary">RFC</label>
                                            <input class="form-control form-control-sm"  type="Text" name="rfc">
                                    </div>
                                    <div class="col-12 col-sm-4"><br>
                                        <button class="btn btn-primary btn-sm float-right">Guardar</button>
                                    </div>
                                    
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                    </div>
            </div>
        </div>


<?php
  include_once '../dependencias/php/footer.php';
?>
<script type="text/javascript" src="../dependencias/js/Catalogo/Provider.js"></script>


    