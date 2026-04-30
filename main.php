<?php @session_start(); $title = "-INICIO";
if(!isset($_COOKIE['sesion_usuario_mspapi']) && !isset($_SESSION['id_usuario'])){
  header('Location:./');
}

include './dependencias/php/head.php';  
include './dependencias/php/menu.php';

  ?>
<style>
  body{
    margin:0px;
    padding:0px;
  }
  .main-wrapper{
    background-image:url('dependencias/img/msp_wallpapers2.webp');
    background-size:cover;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 85%;
    margin:0;
    padding:0px;
    width: 100%;
    height: 85.5vh;

  }
</style>
  <div class="main-wrapper" loading="lazy">
    <!-- ! Main -->
    <!-- <main class="main users chart-page" id="skip-target">
      <div class="container">

        <h2 class="main-title">TABLERO</h2>
        <div class="row stat-cards">
          <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item">
              <div class="stat-cards-icon primary">
                <i data-feather="bar-chart-2" aria-hidden="true"></i>
              </div>
              <div class="stat-cards-info">
                <p class="stat-cards-info__num">1478 286</p>
                <p class="stat-cards-info__title">Total Levantamientos</p>
                <p class="stat-cards-info__progress">
                  <span class="stat-cards-info__profit success">
                    <i data-feather="trending-up" aria-hidden="true"></i>4.07%
                  </span>
                  Last month
                </p>
              </div>
            </article>
          </div>
          <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item">
              <div class="stat-cards-icon warning">
                <i class="fa fa-file" aria-hidden="true"></i>
              </div>
              <div class="stat-cards-info">
                <p class="stat-cards-info__num">1478 286</p>
                <p class="stat-cards-info__title">Total Cotizaciones</p>
                <p class="stat-cards-info__progress">
                  <span class="stat-cards-info__profit success">
                    <i class="fa fa-trending-up" aria-hidden="true"></i>0.24%
                  </span>
                  Last month
                </p>
              </div>
            </article>
          </div>
          <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item">
              <div class="stat-cards-icon purple">
                <i class="fa fa-file" aria-hidden="true"></i>
              </div>
              <div class="stat-cards-info">
                <p class="stat-cards-info__num">1478 286</p>
                <p class="stat-cards-info__title">Total de OT</p>
                <p class="stat-cards-info__progress">
                  <span class="stat-cards-info__profit danger">
                    <i data-feather="trending-down" aria-hidden="true"></i>1.64%
                  </span>
                  Last month
                </p>
              </div>
            </article>
          </div>
          <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item">
              <div class="stat-cards-icon success">
                <i class="fa fa-feather" aria-hidden="true"></i>
              </div>
              <div class="stat-cards-info">
                <p class="stat-cards-info__num">1478 286</p>
                <p class="stat-cards-info__title">Total visits</p>
                <p class="stat-cards-info__progress">
                  <span class="stat-cards-info__profit warning">
                    <i data-feather="trending-up" aria-hidden="true"></i>0.00%
                  </span>
                  Last month
                </p>
              </div>
            </article>
          </div>
        </div>
        
      </div>
    </main> -->
  </div>
<?php
  include_once 'dependencias/php/footer.php';
?>
