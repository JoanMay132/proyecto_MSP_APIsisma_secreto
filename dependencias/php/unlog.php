<?php @session_start(); if(isset($_COOKIE['sesion_usuario_mspapi'])){
    setcookie('sesion_usuario_mspapi', '', time() - 3600, '/', '', false, true);
    session_destroy();
    //header('location:../../');
    echo "Cerrando sesión...";
}
?>

<script>
        setTimeout(function() {
            window.location.href = "../../";
        }, 3000);
    </script>