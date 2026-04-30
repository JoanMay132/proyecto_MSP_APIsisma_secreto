  <!-- ! Sidebar -->
  <?php
    $main = $_SERVER['REQUEST_URI'] == "mspapisisma/main" || $_SERVER['REQUEST_URI'] == "/mspapisisma/main.php" ?  "main" : "../main";
    $url = $_SERVER['REQUEST_URI'] == "/mspapisisma/main" || $_SERVER['REQUEST_URI'] == "/mspapisisma/main.php" ?  "catalogo/" : "../catalogo/";
    $url_traz = $_SERVER['REQUEST_URI'] == "/mspapisisma/main" || $_SERVER['REQUEST_URI'] == "/mspapisisma/main.php" ? "trazabilidad/" : "../trazabilidad/";
    $url_pre = $_SERVER['REQUEST_URI'] == "/mspapisisma/main" || $_SERVER['REQUEST_URI'] == "/mspapisisma/main.php" ? "Presupuesto/" : "../Presupuesto/";
    $unlog = $_SERVER['REQUEST_URI'] == "/mspapisisma/main" || $_SERVER['REQUEST_URI'] == "/mspapisisma/main.php" ? "dependencias/" : "../dependencias/";
    $usuario = $_SERVER['REQUEST_URI'] == "/mspapisisma/main" || $_SERVER['REQUEST_URI'] == "/mspapisisma/main.php" ? "usuario/" : "../usuario/";
    $compra = $_SERVER['REQUEST_URI'] == "/mspapisisma/main" || $_SERVER['REQUEST_URI'] == "/mspapisisma/main.php" ? "compras/" : "../compras/";

    $sucursal = $_SESSION['sucursal']; //Cambiar por la sucursal de la session

    $decriptBranch = base64_decode($sucursal);
  ?>
  <div style="position: sticky;top:0;z-index:999" id="menu-principal">
   <div class="menu">
        <!-- <div class="menu-item" data-target="#index">
            <span><a href="<?php echo $main; ?>">Inicio</a></span>
        </div> -->
        <div class="menu-item" data-target="#catalogos">
            <span>Catálogos</span>
        </div>
        <div class="menu-item" data-target="#compras">
            <span>Compras</span>
        </div>
        <div class="menu-item" data-target="#trazabilidad">
            <span>Trazabilidad</span>
        </div>
        <div class="menu-item" data-target="#presupuesto">
            <span>Presupuestos</span>
        </div>
        <div class="user-info">
             <div class="btn-group">
                <span style="font-size:13px"><?php echo $_SESSION['name_user']; ?></span>
                <i type="button" class="dropdown-toggle dropdown-toggle-split" style="cursor:pointer" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                </i>
                <div class="dropdown-menu" style="cursor:pointer">
                    <a class="dropdown-item" href="javascript:user('<?php echo $url; ?>addUser?user=<?php echo base64_encode($_SESSION['id_usuario']); ?>')" ><i class="fa fa-pencil" style="font-size: 12px !important;" ></i> Editar usuario</a>
                    <?php if(@$_SESSION['tipo_user'] === 'ROOT' || $_SESSION['tipo_user'] === 'ADMIN'){ ?>
                    <a class="dropdown-item" href="<?php echo $usuario; ?>solicitud?suc=<?php echo $sucursal; ?>&status=pendiente"><i class="fa fa-file-text" style="font-size: 12px !important;" ></i> Solicitud de sesión</a>
                    <?php } ?>
                    <?php if(@$_SESSION['tipo_user'] === 'ROOT'){ ?>
                    <a class="dropdown-item" href="#" onclick="updateprivileges();"><i class="fa fa-refresh" style="font-size: 12px !important;" ></i> Actualizar privilegios</a>
                    <?php } ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo $unlog ?>php/unlog"><i class="fa fa-sign-out" style="font-size: 12px !important;" ></i> Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="submenu" id="index" >
    <div class="submenu-item" data-target="#sucursales">
            <a href="./<?php echo $url; ?>sucursal"><i class="fa fa-info"></i><span>Acerca de...</span></a>
        </div>
    </div> -->
    
    <div class="submenu" id="catalogos">
        <div class="submenu-item" data-target="#sucursales">
            <a href="<?php echo $main; ?>"><i class="fa fa-image"></i><span>Inicio</span></a>
        </div>
        <div class="submenu-item" data-target="#sucursales">
            <a href="./<?php echo $url; ?>sucursal"><i class="fa fa-home"></i><span>Sucursales</span></a>
        </div>
        <div class="submenu-item" data-target="#clientes" >
            <a href="./<?php echo $url; ?>clientes?suc=<?php echo $sucursal; ?>"><i class="fa fa-users"></i><span>Clientes</span></a>
        </div>
        <div class="submenu-item" data-target="#proveedores">
            <a href="./<?php echo $url; ?>proveedores?suc=<?php echo $sucursal; ?>"><i class="fa fa-cube"></i><span>Proveedores</span></a>
        </div>
        <div class="submenu-item" data-target="#empleados">
            <a href="./<?php echo $url; ?>employees?suc=<?php echo $sucursal; ?>"><i class="fa fa-male"></i><span>Empleados</span></a>
        </div>
        <div class="submenu-item" data-target="#empleados">
            <a href="./<?php echo $url; ?>encabezados"><i class="fa fa-header"></i><span>Encabezados</span></a>
        </div>

    </div>
    <div class="submenu" id="compras" style="display:none">
        <div class="submenu-item" data-target="#requisiciones">
            <a href="#" onclick="Compras('<?php echo $compra ?>requisiciones?suc=<?php echo $sucursal; ?>','LIST-REQUISICIONES',1000,380)"><i class="fa fa-file-text"></i><span>Requisiciones</span></a>
        </div>
        <div class="submenu-item" data-target="#ocompras" >
        <a href="javascript:Compras('<?php echo $compra ?>ocompras?suc=<?php echo $sucursal; ?>','LIST-OCOMPRAS',900,380)"><i class="fa fa-shopping-cart"></i><span>Ordenes de compra</span></a>
        </div>
    </div>
    <div class="submenu" id="trazabilidad" style="display:none">
        <div class="submenu-item" data-target="#revpreeliminar">
            <a href="javascript:ventana1('<?php echo $url_traz ?>revpreeliminar?suc=<?php echo $sucursal; ?>','LIST-REVISION')"><i class="fa fa-file-text"></i><span>Revisiones Preeliminar</span></a>
        </div>
        <div class="submenu-item" data-target="#cotizaciones">
            <a href="javascript:Trazabilidad('<?php echo $url_traz ?>cotizacion?suc=<?php echo $sucursal; ?>','LIST-COTIZACION')"><i class="fa fa-money"></i><span>Cotizaciones</span></a>
        </div>
        <div class="submenu-item" data-target="#cotconcepto">
            <a href="javascript:Busqueda('<?php echo $url_traz ?>conceptocot?suc=<?php echo $sucursal; ?>','CONCEPTO-COTIZACION')"><i class="fa fa-search"></i> <span>Cotizaciones por Concepto</span></a>
        </div>
        <div class="submenu-item" data-target="#ordenes">
            <a href="javascript:Trazabilidad('<?php echo $url_traz ?>ordenes?suc=<?php echo $sucursal; ?>','OT')"><i class="fa fa-list"></i><span>Ordenes de Trabajo</span></a>
        </div>
        <div class="submenu-item" data-target="#otconcepto">
            
            <a href="javascript:Busqueda('<?php echo $url_traz ?>conceptoorden?suc=<?php echo $sucursal; ?>','CONCEPTO-ORDEN')"><i class="fa fa-search"></i><span>Ordenes por Concepto</span></a>
        </div>
        <div class="submenu-item" data-target="#entregas">
            
            <a href="javascript:Trazabilidad('<?php echo $url_traz ?>entregas?suc=<?php echo $sucursal; ?>','ENTREGAS')"><i class="fa fa-truck"></i><span>Entregas</span></a>
        </div>

    </div>
    <div class="submenu" id="presupuesto" style="display:none">
        <div class="submenu-item" data-target="#materiales">
            <a href="javascript:Presupuesto('<?php echo $url_pre ?>materiales?suc=<?php echo $sucursal; ?>','MATERIALES')"><i class="fa fa-tag"></i><span>Materiales</span> </a>
        </div>
        <div class="submenu-item" data-target="#manodeobra">
            
            <a href="javascript:Presupuesto('<?php echo $url_pre ?>mobra?suc=<?php echo $sucursal; ?>','MOBRA')"><i class="fa fa-clock-o"></i><span>Mano de Obra</span> </a>
        </div>
        <div class="submenu-item" data-target="#maquinaria">
            
            <a href="javascript:Presupuesto('<?php echo $url_pre ?>maquinaria?suc=<?php echo $sucursal; ?>','MAQUINARIA')"><i class="fa fa-cog"></i><span>Maquinaria</span> </a>
        </div>
        <div class="submenu-item" data-target="#adicionales">
            
            <a href="javascript:Presupuesto('<?php echo $url_pre ?>adicionales?suc=<?php echo $sucursal; ?>','ADICIONALES')"><i class="fa fa-lightbulb-o"></i><span>Adicionales</span></a>
        </div>
        <div class="submenu-item" data-target="#analisis">
            
            <a href="javascript:Analisis('<?php echo $url_pre ?>listpresupuesto?suc=<?php echo $sucursal; ?>','LPRESUPUESTO')"><i class="fa fa-calculator"></i><span>Analisis de Costo</span></a>
            
        </div>
        <div class="submenu-item" data-target="#prefinidos">
            
            <a href="javascript:Analisis('<?php echo $url_pre ?>listpredefinidos?suc=<?php echo $sucursal; ?>','LPREDEFINIDO')"><i class="fa fa-list"></i><span>Predefinidos</span></a>
            
        </div>
    </div>
</div>
<?php if($_SESSION['tipo_user']==='ROOT'){
    if($_SERVER['REQUEST_URI']=== "/mspapisisma/main" ||$_SERVER['REQUEST_URI']=== "/mspapisisma/main.php") {
    ?>
<script src="./dependencias/js/Usuario/solicitud.js"></script>
<?php }else{ ?>
    <script src="../dependencias/js/Usuario/solicitud.js"></script>
<?php }} ?>
    
    
<script>
    function user(URL) {
    window.open(URL, "Dato usuario", "width=600,height=600,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0");
  }
    function ventana1(URL,name  =""){ 
        const ventanaAncho = window.innerWidth;
        const ventanaAlto = window.innerHeight;

        let izquierda = (ventanaAncho - 900) / 2;
        let arriba = (ventanaAlto - 380) / 2;
        window[name] ? window[name].focus() : window.open(URL,name,"width=900,height=380,scrollbars=yes,left="+izquierda+",top="+arriba+",addressbar=0,menubar=0,toolbar=0" ); return false;
    }
    function Trazabilidad(URL,name){
        const ventanaAncho = window.innerWidth;
        const ventanaAlto = window.innerHeight;

        let izquierda = (ventanaAncho - 1000) / 2;
        let arriba = (ventanaAlto - 380) / 2; 
        window[name] ? window[name].focus() :  window.open(URL,name,"width=1000,height=380,scrollbars=yes,left="+izquierda+",top="+arriba+",addressbar=0,menubar=0,toolbar=0" ); return false;
    }

    function Compras(URL,name,ancho,alto){
        let izquierda = (window.innerWidth - ancho) / 2;
        let arriba = (window.innerHeight- alto) / 2; 
        window[name] ? window[name].focus() :  window.open(URL,name,"width="+ancho+",height="+alto+",scrollbars=yes,left="+izquierda+",top="+arriba+",addressbar=0,menubar=0,toolbar=0" ); return false;
    }

    function Presupuesto(URL, name = ""){ 
        window[name] ? window[name].focus() :  window.open(URL,name,"width=600,height=600,scrollbars=yes,left=300,addressbar=0,menubar=0,toolbar=0" ); return false;
    }

    function Analisis(URL,NAME){

        const ventanaAncho = window.innerWidth;
        const ventanaAlto = window.innerHeight;

        let izquierda = (ventanaAncho - 1200) / 2;
        let arriba = (ventanaAlto - 380) / 2;
        window[NAME] ? window[NAME].focus() :  window.open(URL,NAME,"width=1200,height=380,scrollbars=yes,left="+izquierda+",top="+arriba+",addressbar=0,menubar=0,toolbar=0" ); return false;
        //window.open(URL);
        // Obtener el ancho y alto de la pantalla
      /*var anchoPantalla = window.screen.width;
      var altoPantalla = window.screen.height;
        window.open(URL,"","width="+anchoPantalla+",height="+altoPantalla+",scrollbars=yes,addressbar=0,menubar=0,toolbar=0" ); return false;*/
    }
    

    function Busqueda(URL,name){
        const ventanaAncho = window.innerWidth;
        const ventanaAlto = window.innerHeight;

        let izquierda = (ventanaAncho - 1300) / 2;
        let arriba = (ventanaAlto - 600) / 2; 
        window[name] ? window[name].focus() :  window.open(URL,name,"width=1300,height=600,scrollbars=yes,left="+izquierda+",top="+arriba+",addressbar=0,menubar=0,toolbar=0" ); return false;
    }

//     if (!window.opener) { // Esto asegura que solo se ejecuta en la ventana principal
//     window.addEventListener("beforeunload", function (event) {
//         event.preventDefault();
//         event.returnValue = ""; // Mensaje predeterminado del navegador
//     });
// }
</script>
