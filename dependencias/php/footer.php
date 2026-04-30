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

</body>

</html>