   <?php if(!$popper){?>
   <!-- ! Footer -->
    <!-- <footer class="footer">
      <div class="container footer--flex">
        <div class="footer-start">
          <p>2022 © SISMA - <a href="elegant-dashboard.com" target="_blank"
              rel="noopener noreferrer">By GNet</a></p>
        </div>
      </div>
    </footer> -->
    <?php } ?>




<?php 
   
  if($_SERVER['REQUEST_URI']=== "/mspapisisma/main" ||$_SERVER['REQUEST_URI']=== "/mspapisisma/main.php") {   ?>

<!-- Icons library -->
<!-- <script src="./dependencias/js/feather.min.js"></script> -->
<!-- Custom scripts -->
<!-- <script src="./dependencias/js/script.js"></script> -->

<script src="./dependencias/js/jquery-3.6.0.min.js"></script>
<script src="./dependencias/js/jquery.dataTables.min.js"></script>
<script src="./dependencias/js/sweetalert2.min.js"></script>
<script src="./dependencias/js/Moneda.js"></script>
<script src="./dependencias/js/menu.js"></script>
<script src="./dependencias/js/popper.min.js"></script>
<script src="./dependencias/js/bootstrap.min.js"></script>


<?php }else{?>
<!-- Icons library -->
<!-- <script src="../dependencias/js/feather.min.js"></script> -->
<!-- Custom scripts -->
<!-- <script src="../dependencias/js/script.js"></script> -->

<script src="../dependencias/js/jquery-3.6.0.min.js"></script>
<script src="../dependencias/js/jquery.dataTables.min.js"></script>
<script src="../dependencias/js/sweetalert2.min.js"></script>
<script src="../dependencias/js/Moneda.js"></script>
<script src="../dependencias/js/menu.js"></script>
<script src="../dependencias/js/popper.min.js"></script>
<script src="../dependencias/js/bootstrap.min.js"></script>



  <?php } ?>

<!-- Mantiene viva la sesión mientras haya una pantalla abierta y avisa si ya expiró -->
<script>
(function () {
  if (window.__sismaKeepAlive) return;
  window.__sismaKeepAlive = true;
  var url = '/' + location.pathname.split('/')[1] + '/Trazabilidad/Cargas/keepalive.php';
  var avisado = false;
  setInterval(function () {
    fetch(url, { credentials: 'same-origin', cache: 'no-store' })
      .then(function (r) { return r.json(); })
      .then(function (r) {
        if (r && r.active === false && !avisado && window.Swal) {
          avisado = true;
          Swal.fire({
            icon: 'warning',
            title: 'Tu sesión expiró',
            html: '<div style="font-size:14px">Antes de guardar, inicia sesión en otra pestaña y regresa a esta. Lo que tienes en pantalla no se pierde mientras no cierres esta ventana.</div>',
            showCancelButton: true,
            confirmButtonText: 'Iniciar sesión',
            cancelButtonText: 'Cerrar'
          }).then(function (res) { if (res.isConfirmed) window.open('/' + location.pathname.split('/')[1] + '/index.php', '_blank'); });
        }
        if (r && r.active) avisado = false;
      })
      .catch(function () {});
  }, 4 * 60 * 1000);
})();
</script>

</body>

</html>