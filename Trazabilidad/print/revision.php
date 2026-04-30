<?php ob_start();

include_once("../../controlador/conexion.php");
include_once("../../controlador/Revpreeliminar.php");
include_once("../../class/Fecha.php");
include_once("../../class/Header.php");
require_once("../../dependencias/dompdf/autoload.inc.php");

$idRev = (int) base64_decode($_GET['revision']);
if(!filter_var($idRev,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}

 //Se crea el objeto
$oRev = new Revpreeliminar();
$nControl = 5;

$resp = $oRev->Print($idRev);

//Codigo para encriptación de imagen y poder renderizar en dompdf
$path = '../../dependencias/img/Logo_Premium_Maquinados-04.svg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

//Obtenemos información del array de encabezados
$index = null;
$sucursal = $resp['fksucursal'];
$resultado = array_filter($header, function($elemento) use ($nControl, $sucursal) {
    return $elemento['control'] == $nControl && $elemento['sucursal'] == $sucursal;
});

if(!empty($resultado)){
    $index = key($resultado);
}else{
    echo "No se encontro el encabezado, consulte con el administrador.";
    return false;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRINT - REVISION</title>
    <style>
    @page {
            margin: 20px;
            margin-left: 40px;
            margin-top: 270px;
            margin-bottom: 60px;
        }

        header {
            position: fixed;
            top: -245px;
            bottom: 0;
            left: 0;
            right: 0;
        }

        footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;

            /* height: 30px; */
            /* Altura del pie de página */
        }
        /* height: 30px; */
        /* Altura del pie de página */
    

    body {
        font-family: Arial, Helvetica, sans-serif;
        margin-left: unset;
        margin: 0;
        padding: 0;
    }

    table {
        font-size: 11px;
        width: 100%;
        border-collapse: collapse;
    }

    .header-table,
    .content-table,
    .table-header-title {
        width: 100%;
        border: 1px solid black;
    }

    #img-h1 {
        width: 15%;
    }

    .header-table td {
        border: none;
        padding: 5px;
    }

    .content-table th,
    .content-table td {
        /* border: 1px solid black; */
        padding: 2px;
        text-align: left;
        vertical-align: top;
        
    }
    .content-table td {
        font-weight: normal;
        color:#808080;
    }

    .header {
        text-align: right;
    }

    .contact-info {
        text-align: center;
        width: 45%;
    }

    .title {
        text-align: center;
        font-weight: bold;
    }

    .title-table-td {
        background-color: #D1D1D1;
    }

    .text-center {
        text-align: center;
    }

    .table-header-title thead th {
        border: 1px solid black;
    }

    .table-header-title tr td {
        border-bottom: 1.5px solid black;
        padding: 3px
    }

    .observaciones {
        height: auto;
    }

    /* new */
    table {
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }
        .header-text{
            font-size: 8pt;
            font-weight: bold;
        }
        .header-subtext{
            font-size: 7pt;
            font-weight: bold;
            color:#808080;
        }
        .linea{
            border:1.5px solid #002060;
            margin-top:5px;
            position: relative;
            margin-left: 10%;
            margin-right: 10%;
            

        }
        .header-folio{
            font-size: 8pt;
            font-weight: bold;
            color: #17365D;
        }
        .table-header tr td{
            padding-bottom:5px;
            /* border:1 solid grey; */
        }
        .title-table-td {
            background-color: #17365D;
            color:white;
        }

        .table-norma tr th{
            background-color: #F6F5EE;
        }
        .table-norma tr td{
            color: #7F7F7F;
        }

        .table-norma tr th,
        .table-norma tr td{
            text-align: left;
            font-size: 7pt;
            border:1px solid #BFBFBF;
            padding-bottom: 8px;
        }
        .table-info{
            text-align: left;
            font-size: 7pt;
            font-weight: bold;
            margin-top:10px;
        }
        .table-info tr th{
            text-align: left;
            color: #7F7F7F
        }
        .table-info tr th span{
            text-align: left;
            color: black;
        }

    </style>
</head>

<body>

    <header>
    <table>
            <tr >
                <td style="width:80%;font-size: 9pt;" align="middle">
                    <center>
                        <strong><?php echo $header[$index]['titulodesc']; ?></strong><br>
                        <div style="font-size: 8pt;font-weight:bold;color:#808080"><?php echo $header[$index]['rfc']; ?></div>
                       <div class="linea"></div>

                    </center>
                </td>
                <td style="width:20%">
                    <img src="<?php echo $base64; ?>" style="width:145px;height:80px;position:relative;top:-5px">
                </td>
            </tr>
            <tr>

            </tr>
        </table>
        <!-- En cabezado, información del cliente -->
        <table class="table-header" style="border-collapse:collapse;font-size: 9pt;">
            <tr>
                <th colspan="4" class="title-table-td">Revisión Preliminar del Contrato</th>
            </tr>
            <tr>
                <td style="height: 5px;"></td>
            </tr>
            <tr>
                <td class="header-text" style="width:43%">Cliente:</td>
                <td class="header-text" style="width:35%">Depto:</td>
                <td class="header-folio" style="width:10%;font-weight:bold;">FOLIO:</td>
                <td style="font-size: 8pt;font-weight:bold;color:#C00000;width:12%"><?php echo $letras.$resp['folio']; ?></td>
            </tr>
            <tr>
                <td valign="top" style="height:25px;" class="header-subtext"><?php echo substr($resp['nombre_cli'],0,85); ?></td>
                <td valign="top" class="header-subtext"><?= $resp['depto']; ?></td>
                <td valign="top" class="header-folio" style="font-weight:bold;">Fecha:</td>
                <td valign="top"><?php echo Fecha::convertir($resp['fecha']); ?></td>
            </tr>
            <tr>
                <td class="header-text">Solicito:</td>
                <td colspan="3" class="header-text">Proyecto:</td>
            </tr>
            <tr>
                <td valign="top" class="header-subtext"><?php echo $resp['titulo'].'. '.$resp['nombre_user']; ?></td>
                <td valign="top" class="header-subtext"  style="height:35px;"><?= substr($resp['proyecto'],0,85); ?></td>
                
            </tr>
        </table>
        <!-- Fin de encabezado -->

        <table class="table-header-title" style="margin-top:10px">
            <thead>
                <th style="width: 6.3%;" class="title-table-td">Pda.</th>
                <th style="width: 6.6%;" class="title-table-td">Cantidad</th>
                <th style="width: 8%;" class="title-table-td"> Unidad</th>
                <th style="width: 79.1%;" class="title-table-td">Descripción</th>
            </thead>
        </table>
    </header>
    <footer style="font-size: 8pt;">
        <center><small style="color:#808080;font-weight:bold;">Calle 8, LT-1-C MZA-III, Fraccionamiento DEIT, R/A Anacleto Canabal 1ª Sección, Villahermosa, Tabasco.  CP 86280 - ventas01@mspetroleros.com - Tel: (993) 337 9968</small></center>
        <div style="border:0.1px solid black;width:100%;margin-bottom:3px"></div>
        <small>
            DOCUMENTO CONTROLADO<br>
            La copia de este documento sólo se deberá utilizar como referencia<br>
            Queda prohibida su reproducción total o parcial sin autorización de MSP-Maquinados y Servicios
            Petroleros<br>
        </small>

    </footer>


    <main>
        <table class="table-header-title" style="border:unset">
            <?php foreach($oRev->Servprint($resp['pkrevpreeliminar']) as $row){ ?>
            <tr>
                <td valign="top" style="width: 6.3%;text-align:right;border: 0.6px solid #BFBFBF"><?=  $row['pda']; ?></td>
                <td valign="top" style="width: 6.6%;text-align:; border: 0.6px solid #BFBFBF"><?=  $row['cantidad']; ?></td>
                <td valign="top" style="width: 8%;text-align:center; border: 0.6px solid #BFBFBF"><?=  $row['nombre']; ?></td>
                <td style="border: 0.6px solid #BFBFBF""><?= nl2br($row['descripcion']); ?></td>
            </tr>
            <?php } ?>

        </table>
        <table class="content-table" style="margin-top:10px;border:unset">
            <tr>
                <th width="33.3%" >Requisitos de Inspección y Documentación:</th>
                <th width="33.3%" >Requisitos Legales y Reglamentarios:</th>
                <th width="33.3%" >Requisitos de Entrega:</th>
            </tr>
            <tr>
                <td class="header-text" style="height: 40px;"><?= nl2br($resp['reqinsdoc']); ?></td>
                <td class="header-text"><?= nl2br($resp['reqlegales']); ?></td>
                <td class="header-text"><?= nl2br($resp['reqent']); ?></td>
            </tr>

            <tr>
                <th width="50%"  >Condiciones de Pago</th>
                <th width="50%" >Desviaciones/Excepciones</th>
                <th width="50%" >Uso de Propiedad del Cliente</th>
            </tr>
            <tr>
                <td class="header-text" style="height: 40px;"><?= nl2br($resp['condpago']); ?></td>
                <td class="header-text"><?= $resp['desviacionexc']; ?></td>
                <td class="header-text"><?= nl2br($resp['propcli']); ?></td>
            </tr>
            <tr>
                <th colspan="3" >Requisitos especiales del servicio:</th>
            </tr>
            <tr>
                <td colspan="3" class="header-text"><?= nl2br($resp['reqespserv']); ?></td>
            </tr>
        </table><br>

        <table style="text-align: center; border:1px solid #BFBFBF;font-size: 8pt;">
            <tr>
                <th class="title-table-td" colspan="3" style="text-align: left;">Revisiones (Fecha y firma)</th>
            </tr>
            <tr>
                <td style="vertical-align: bottom;width: 33.3%">
                    <div style="height:90px"></div>
                    ___________________________________<br>
                    <?= $resp['nombre_ventas'].' '.$resp['apellido_ventas']; ?><br>
                    <strong>VENTAS</strong>
                </td>
                <td style="vertical-align: bottom;width: 33.3%">
                    <div ></div>
                    ___________________________________<br>
                    <?= $resp['nombre_produccion'].' '.$resp['apellido_produccion']; ?><br>
                    <strong>PRODUCCIÓN</strong>
                    
                </td>
                <td style="vertical-align: bottom;width: 33.3%">
                    <div ></div>
                    ___________________________________<br>
                    <?= $resp['nombre_calidad'].' '.$resp['apellido_calidad']; ?><br>
                    <strong>CALIDAD</strong>
                </td>
            </tr>
        </table>
    </main>
</body>



</html>


<?php
$folio = substr($resp['folio'],0,-3);
$formato = $header[$index]['formato'].' '.$header[$index]['revision'];

$html = ob_get_clean();

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set('isHtml5ParserEnabled', true);
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);


//se carga el contenido
$dompdf->loadHtml($html);


$dompdf->setPaper('letter');
$dompdf->render();

$dompdf->getCanvas()->page_text(535,755, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0,0,0));
$dompdf->getCanvas()->page_text(500,765, "$formato", null, 8, array(0,0,0));
$dompdf->stream("REV.-MSP-A{$folio}.pdf", array("Attachment" => false));


?>