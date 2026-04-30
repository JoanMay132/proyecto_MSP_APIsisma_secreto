<?php @session_start(); {$title = " - SUCURSALES"; }

 if($_SESSION['tipo_user'] != 'ADMIN' && $_SESSION['tipo_user'] != 'ROOT'){
    header('Location:../');
    exit;
 }
  include '../dependencias/php/head.php';
  include '../dependencias/php/menu.php';
  include '../controlador/Sucursal.php';  

  ?>

  <div class="main-wrapper">


    <?php include '../dependencias/php/header.php'; ?>
  
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
      <div class="container">

        <h5 class="h5">SUCURSALES</h5>
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Nueva sucursal
                </div>
                <div class="card-body">
                    <form id="form-sucursal" onsubmit="return addSucursal()">
                        <div class="form-row">
                            <div class="col-4">
                                <label class="label text-secondary">Nombre</label>
                                <input type="text" class="form-control form-control-sm"  name="nombre" autocomplete="off">
                            </div>
                            <div class="col-8">
                                <label class="label text-secondary">Dirección</label>
                                <input type="text" class="form-control form-control-sm" name="direccion" autocomplete="nope">
                            </div>
                            <div class="float-right"><br>
                                <button class="btn-sm btn-primary" id="add">Añadir</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div><br>
        <div class="row" >
            <div class="col-12" >
            <div class="card" style="padding: 10px;" id="table-sucursal">
            <table class="table table-stripped display compact table-sucursal" id="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Dirección</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            </div>
            </div>
        </div>
        
      </div>
    </main>

    
  
    

<?php
  include_once '../dependencias/php/footer.php';
?>

<script type="text/javascript" src="../dependencias/js/Catalogo/Sucursal.js"></script>