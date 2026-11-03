<?php @session_start();
//Mantiene viva la sesión mientras la cotización está abierta y avisa si ya expiró
header('Content-Type: application/json');
header('Cache-Control: no-store');

echo json_encode(array("active" => !empty($_SESSION['id_usuario'])));
