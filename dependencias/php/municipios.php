
<?php
	include_once('../../controlador/conexion.php');
    include_once('../../controlador/Municipio.php');

    $estados=$_POST['estado'];
    $obmun = new Municipio();

    foreach($obmun->GetDataAll($estados) as $data){
        $row[] = array($data["id_municipio"],$data["nombre"]);		
	}

    echo json_encode($row);

    ?>
    

	

