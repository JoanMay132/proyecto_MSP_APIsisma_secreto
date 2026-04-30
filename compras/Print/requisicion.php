<?php ob_start();

include_once("../../controlador/conexion.php");
include_once("../../controlador/Requisicion.php");
include_once("../../class/Fecha.php");
include_once("../../class/Header.php");
require_once("../../dependencias/dompdf/autoload.inc.php");

 $idReq = (int) base64_decode($_GET['requisicion']);
 if(!filter_var($idReq,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}

// //Se crea el objeto
 $oReq = new Requisicion();
 $nControl = 14; //Numero de control

$resp = $oReq->Print($idReq);

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
    <title>PRINT - REQUISICIÓN</title>
    <style>
         @page {
            margin: 20px;
            margin-left: 40px;
            margin-top: 250px;
            margin-bottom: 220px;
        }

        header {
            position: fixed;
            top: -240px;
            bottom: 0;
            left: 0;
            right: 0;
        }

        footer {
            position: fixed;
            bottom: -200px;
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
            /* border: 1px solid black; */
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
        <table class="header-table">
            <!-- <tr style="text-align:right">
                <td colspan="3" style="color:#33BEFF">
                    <strong>Formulario - Sistema de Gestión de Calidad / Villahermosa / Tabasco</strong>
                </td>
            </tr> -->
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
        </table>
        <table class="content-table" style="margin-top:10px">
            <tr>
                <th  class="title-table-td" style="width:100%;text-align:center">REQUISICIÓN DE COMPRA DE MATERIALES Y/O SERVICIOS</th>
                <!-- <td  class="title-table-td " style="width:15%;text-align:center"><?php echo $header[$index]['texto3']; ?></td> -->
                
            </tr>
            <!-- <tr>
                    <td class="title-table-td" style="width:15%;text-align:center"><?php echo $header[$index]['revision']; ?></td>
            </tr> -->
            <tr>
                <th   style="width:100%;text-align:center">NOMBRE DEL PROYECTO (REFERENCIA / LOCALIZACIÓN):</th>
            </tr>
            <tr>
                <td valign="top"   style="height: 60px;"><?php echo nl2br($resp['proyecto']); ?></td>
            </tr>
        </table>

         <table class="content-table" style="margin-top:0px">
           <?php
                $selGeneral = $resp['clasificacion'] == 'general' ? 'X' : '';
                $selSGC = $resp['clasificacion'] == 'sgc' ? 'X' : '';
           ?> 
            <tr>
                <th class="title-table-td" style="width:16.8%">CLASIFICACIÓN</th>
                <td style="width:20%"><span>GENERAL: <strong><?php echo $selGeneral; ?></strong></span><span style="margin-left:60px">SGC: <strong><?php echo $selSGC; ?></strong></span></td>
                <th class="title-table-td" style="width:10%">FECHA</th>
                <td style="width:10%;text-align:center;"><?php echo Fecha::convertir($resp['fecha']); ?></td>
                <th class="title-table-td" style="width:10%">FOLIO</th>
                <td style="width:10%;text-align:center;color:crimson"><strong><?php echo 'MSP-C'.$resp['folio'] ?></strong></td>
            </tr>
        </table> 

        <table class="table-header-title">
            <thead>
                <th style="width: 7.3%;" class="title-table-td">Item.</th>
                <th style="width: 7.3%;" class="title-table-td">Cant.</th>
                <th style="width: 7.3%;" class="title-table-td"> Unidad</th>
                <th style="width: 11%;" class="title-table-td"> No. Parte</th>
                <th class="title-table-td">Descripción</th>


            </thead>
        </table>
    </header>
    <footer>
        <table class="signature-table content-table">
            <tr>
                <th colspan="4">NOTA IMPORTANTE: En caso de ser necesario, anexar hoja de especificaciones técnicas de las piezas solicitadas.</th>
            </tr>
            <tr >
                <td style="text-align:center;width:10%">
                    <strong>SOLICITA</strong>
                </td>
                <td style="text-align:center;width:10%">
                    <strong>RECIBE</strong>
                </td>
                <td style="text-align:center;width:10%">
                    <strong>AUTORIZA</strong>
                </td>
                <td style="text-align:center;width:25%">
                    <strong>LUGAR DE ENTREGA</strong>
                </td>
            </tr>
            <tr>
                <td class="left" style="height: 100px;">
                    <p style="margin-bottom:-5px">
                        _____________________________<br>
                        <strong><?php echo $resp['solicita_nombre'].' '.$resp['solicita_apellidos']; ?></strong> <br>NOMBRE Y FIRMA
                    </p>

                </td>
                <td class="right">
                    <p style="margin-bottom:-5px">

                        _____________________________<br>
                       <strong><?php echo $resp['recibe_nombre'].' '.$resp['recibe_apellidos']; ?></strong> <br>JEFE DE COMPRAS
                    </p>

                </td>
                <td class="right">
                    <p style="margin-bottom:-28px">

                        _____________________________<br>
                       <strong><?php echo $resp['autoriza_nombre'].' '.$resp['autoriza_apellidos']; ?></strong> <br>GERENTE GENERAL<br>
                       <span style="font-size:8px"><?php echo $header[$index]['nota']; ?></span>
                    </p>

                </td>
                <td class="right">
                    <p>
                        <?php echo $resp['lugarent']; ?>
                    </p>

                </td>
            </tr>

        </table><br>
        <center><small style="color:#808080;font-weight:bold;font-size: 7pt;">Calle 8, LT-1-C MZA-III, Fraccionamiento DEIT, R/A Anacleto Canabal 1ª Sección, Villahermosa, Tabasco.  CP 86280 - ventas01@mspetroleros.com - Tel: (993) 337 9968</small></center>
        <div style="border:0.1px solid black;width:100%;margin-bottom:3px;"></div>
        <small style="font-size:10px">

            DOCUMENTO CONTROLADO<br>
            La copia de este documento sólo se deberá utilizar como referencia<br>
            Queda prohibida su reproducción total o parcial sin autorización de Maquinados y Servicios
            Petroleros<br>

        </small>
        <!-- <script type="text/php">
    
        if ( isset($pdf) ) {
            $dompdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(1,1, "{PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));
            ');
        }
    
</script> -->

    </footer>


    <main>
        <table class="table-header-title" style="border:unset;table-layout: fixed;">
            <?php foreach($oReq->Servprint($resp['pkrequisicion']) as $result) { 
               
            ?>
                <tr>
                    <td valign="top" style="width: 7.3%;text-align:center"><?php echo $result['pda']; ?></td>
                    <td valign="top" style="width: 7.3%;text-align:center"><?php echo $result['cantidad']; ?></td>
                    <td valign="top" style="width: 7.3%;text-align:center"><?php echo $result['nombre']; ?></td>
                    <td valign="top" style="width: 11%;text-align:center;word-wrap: break-word"><?php echo $result['nparte']; ?></td>
                    <!-- <td valign="top" style="text-align:left">HOLA MUNDO DESDE VICTUS DEV <br><br>HOLA MUNDO</td> -->
                    <td valign="top" style="text-align:left"><?php echo nl2br($result['descripcion']); ?></td>
                </tr>
            <?php } ?>

        </table>

        <table class="content-table" style="margin-top:20px">
            <tr class="title-table-td ">
                <th colspan="2" style="text-align: center;">Observaciones / Requerimientos Especiales / Criterios de aceptación<br>
 (Relacionados con la calidad, la seguridad, la salud y la protección del ambiente)s</th>
            </tr>
            <tr>
                <td class="observaciones" colspan="2">
                    <?php echo $resp['observaciones']; ?>
                </td>
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

$dompdf->getCanvas()->page_text(535,755, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0,0,0));
$dompdf->getCanvas()->page_text(500,765, "$formato", null, 8, array(0,0,0));
$dompdf->stream("REQ.-MSP-A{$folio}.pdf", array("Attachment" => false));


?>