<?php ob_start();

include_once("../../controlador/conexion.php");
include_once("../../controlador/Suborden.php");
include_once("../../class/Fecha.php");
include_once("../../class/Header.php");
require_once("../../dependencias/dompdf/autoload.inc.php");

 $idOrden = (int) base64_decode($_GET['orden']);
 if(!filter_var($idOrden,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}

// //Se crea el objeto
 $oOrden = new Suborden();
 $nControl = 7; //Numero de control

$resp = $oOrden->Print($idOrden);

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
    <title>PRINT - ORDEN</title>
    <style>
        @page {
            margin: 20px;
            margin-left: 40px;
            margin-top: 245px;
            margin-bottom: 200px;
        }

        header {
            position: fixed;
            top: -225px;
            bottom: 0;
            left: 0;
            right: 0;
        }

        footer {
            position: fixed;
            bottom: -160px;
            left: 0;
            right: 0;
            /* height: 30px; */
            /* Altura del pie de página */
        }

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
            border: 1px solid black;
            padding: 2px;
            text-align: left;
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

        .signature-table .left {
            text-align: center;
        }

        .signature-table .right {
            text-align: center;
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
        .title-table-td {
            background-color: #17365D;
            color:white;
        }
        .table-header tr td{
            padding-bottom:5px;
            /* border:1 solid grey; */
        }
        .footer-info tr td{
            color: #00B050;
        }
    </style>
</head>

<body>

    <header>
        <table>
            <tr>
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
                <td colspan="2" style="width:77%"></td>
                <td colspan="2" style="background:#17365D;color:white ;font-size: 8pt;text-align:center;">
                    <strong>Orden de Trabajo</strong>
                </td>
            </tr>
            <tr>
                <td class="header-text" style="width:45%">Cliente:</td>
                <td class="header-text" style="width:32%">Depto</td>
                <td class="header-folio" style="width:13%;font-weight:bold;">OT:</td>
                <td style="font-size: 8pt;font-weight:bold;color:#C00000;width:10%"><?php echo $letras.$resp['folio']; ?></td>
            </tr>
            <tr>
                <td valign="top" class="header-subtext"><?php echo substr($resp['nombre_cli'],0,85); ?></td>
                <td valign="top" class="header-subtext"><?php echo $resp['depto']; ?></td>
                <td valign="top" class="header-folio" style="font-weight:bold;">Fecha:</td>
                <td valign="top"><?php echo Fecha::convertir($resp['fecha']); ?></td>
            </tr>
            <tr>
                <td class="header-text" colspan="2">Solicito:</td>
                <!-- <td valign="top" class="header-folio" style="font-weight:bold;">Cotización:</td>
                <td valign="top" style="font-size: 8pt;"><?php echo $letras.$resp['folio_cot']; ?></td> -->
            </tr>
            <tr>
                <td class="header-subtext" colspan="2"><?php echo $resp['titulo'].'. '.$resp['nombre_user']; ?></td>
            </tr>
        </table><br>
        <!-- Fin de encabezado -->

        <table class="table-header-title">
            <thead>
                <th style="width: 6.3%;" class="title-table-td">Pda.</th>
                <th style="width: 6.6%;" class="title-table-td">Cantidad</th>
                <th style="width: 8%;" class="title-table-td"> Unidad</th>
                <th class="title-table-td">Descripción</th>
                <th style="width: 10%;" class="title-table-td">No. Dibujo</th>

            </thead>
        </table>
    </header>
    <footer>
        <table class="signature-table content-table">
            <tr>
                <td class="left">
                    <strong>ELABORÓ</strong>
                </td>
                <td class="right">
                    <strong>ENTERADO</strong>
                </td>
            </tr>
            <tr>
                <td class="left" style="height: 80px;">
                    <p style="margin-bottom:-30px">
                        ____________________________________ <br>
                        <strong><?= $resp['nombre'].' '.$resp['apellidos']; ?></strong></strong> <br>AUXILIAR DE PRODUCCIÓN
                    </p>

                </td>
                <td class="right">
                    <p style="margin-bottom:-30px">

                        ____________________________________ <br>
                       <strong><?= $resp['nombre_elaboro'].' '.$resp['apellido_elaboro']; ?></strong> <br>JEFE DE PRODUCCIÓN
                    </p>

                </td>
            </tr>

        </table><br>
        <center><small style="color:#808080;font-weight:bold;font-size: 7pt;">Calle 8, LT-1-C MZA-III, Fraccionamiento DEIT, R/A Anacleto Canabal 1ª Sección, Villahermosa, Tabasco.  CP 86280 - ventas01@mspetroleros.com - Tel: (993) 337 9968</small></center>
        <div style="border:0.1px solid black;width:100%;margin-bottom:3px;"></div>
        <small style="font-size: 7.5pt">
            DOCUMENTO CONTROLADO<br>
            La copia de este documento sólo se deberá utilizar como referencia<br>
            Queda prohibida su reproducción total o parcial sin autorización de MSP-Maquinados y Servicios
            Petroleros<br>
        </small>
        

    </footer>


    <main>
        <table class="table-header-title" style="border:unset;table-layout: fixed;">
            <?php foreach($oOrden->Servprint($resp['pksuborden']) as $result) { ?>
                <tr>
                    <td valign="top" style="width: 6.4%;text-align:right;border: 0.6px solid #BFBFBF"><?= $result['pda']; ?></td>
                    <td valign="top" style="width: 6.7%;text-align:right;border: 0.6px solid #BFBFBF"><?= $result['cantidad']; ?></td>
                    <td valign="top" style="width: 8.5%;text-align:center;border: 0.6px solid #BFBFBF"><?= $result['nombre']; ?></td>
                    <td style="width: 71%;border: 0.6px solid #BFBFBF"><?php echo nl2br( $result['descripcion']); ?></td>
                    <td valign="top" style="width: 10.2%;text-align:left;word-wrap: break-word;border: 0.6px solid #BFBFBF"><?= $result['dibujo']; ?></td>
                </tr>
            <?php } ?>

        </table>

        <table class="content-table" style="margin-top:20px;margin-bottom:10px;">
            <tr class="title-table-td ">
                <th colspan="2" style="text-align: center;">Requerimientos Especiales y Contractuales / Observaciones<br>
                    (Relacionados con la calidad, la seguridad, la salud y la protección del ambiente)</th>
            </tr>
            <tr>
                <td class="observaciones" colspan="2">
                <?= nl2br($resp['observaciones']); ?>
                </td>
            </tr>
        </table>
        <table style="font-weight: bold;font-size: 8pt;" class="footer-info">
                    <?php
                        $tipo = $resp['tipo'] == 'normal' ?'Normal':'Urgente';                           
                    ?>
            <tr>
                <th style="width:50%">Días programado de entrega previa orden de compra o preautorización:</th>
                <td style="width:5%"><span><?= $resp['diaentrega']; ?></span></td>
                <th style="width:12%">Tipo de servicio:</th>
                <td style="width:10%"><span><?= $tipo; ?></span></td>
                <th style="width:18%">Aplica monograma:</th>
                <td style="width:5%"><span>No</span></td>
            </tr>
        </table>
    </main>

</body>



</html>


<?php
$folio = substr($resp['folio'],0,-3) ;
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
$dompdf->getCanvas()->page_text(535,745, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0,0,0));
$dompdf->getCanvas()->page_text(500,755, "$formato", null, 8, array(0,0,0));
$dompdf->stream("OT.-MSP-A{$folio}.pdf", array("Attachment" => false));


?>