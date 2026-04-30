<?php ob_start();

include_once("../../controlador/conexion.php");
include_once("../../controlador/Ocompra.php");
include_once("../../class/Fecha.php");
include_once("../../class/Header.php");
require_once("../../dependencias/dompdf/autoload.inc.php");

 $idOrden = (int) base64_decode($_GET['ocompra']);
 if(!filter_var($idOrden,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}

// //Se crea el objeto
 $oCompra = new Ocompra();
 $nControl = 15; //Numero de control

$resp = $oCompra->Print($idOrden);

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
    <title>PRINT - OCOMPRA</title>
    <style>
        @page {
        
            margin: 20px;
            margin-left: 40px;
            margin-top: 320px;
            margin-bottom: 210px;
            
        }
        

        header {
            position: fixed;
            top: -305px;
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
            border-bottom: 0.3px solid grey;
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
            <!-- <tr style="text-align:right">
                <td colspan="3" style="color:#33BEFF">
                    <strong>Formulario - Sistema de Gestión de Calidad / Villahermosa / Tabasco</strong>
                </td>
            </tr> -->
            <tr>
                <td style="width:10%">
                    <img src="<?php echo $base64; ?>" style="width:145px;height:80px;position:relative;top:-5px">
                    
                </td>
                <td style="width:50%;font-size: 9pt;" align="middle">
                    <center>
                        <strong><?php echo $header[$index]['titulodesc']; ?></strong><br>
                        <div style="font-size: 8pt;font-weight:bold;color:#808080"><?php echo $header[$index]['rfc']; ?></div>
                        <div class="linea"></div>
                    </center>
                </td>
                
                <td>
                    <table>
                        <tr class="title-table-td">
                            <th colspan= "2">ORDEN DE COMPRA:</th>
                        </tr>
                        <tr >
                            <th style="text-align:left">FOLIO NUM.:</th>
                            <td style="text-align:left;color:crimson;padding:0px"><strong><?php echo 'MSP-A'.$resp['folio']; ?></strong></td>
                        </tr>
                        <tr>
                            <th style="text-align:left">FECHA ORDEN:</th>
                            <td style="text-align:left;padding:0px"><?php echo Fecha::convertir($resp['fechaorden']); ?></td>
                        </tr>
                        <tr>
                            <th style="text-align:left">FECHA ENTREGA:</th>
                            <td style="text-align:left;padding:0px"><?php echo Fecha::convertir($resp['fechaent']); ?></td>
                        </tr>
                        <tr>
                            <th style="text-align:left">NO. REQUISICION:</th>
                            <td style="text-align:left;padding:0px"><?php echo $resp['folioreq']; ?></td>
                        </tr>
                        <tr>
                            <th style="text-align:left">MONEDA:</th>
                            <td style="text-align:left;padding:0px"><?php echo $resp['moneda']; ?></td>
                        </tr>
                        <tr>
                            <th style="text-align:left">COND. DE PAGO:</th>
                            <td style="text-align:left;padding:0px"><?php echo $resp['condpago']; ?></td>
                        </tr> 
                    </table>
                </td>

            </tr>

        </table>
        <table style="margin-top:20px">
            <tr>
                <td width="50%">
                    <table style="max-height:100px">
                        <tr>
                            <td valign="top" colspan="2"  class="title-table-td"><strong>PROVEEDOR</strong> </td>
                        </tr>
                        <tr>
                            <td colspan="2"><?php echo substr($resp['nomproveedor'],0,55) ; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">RFC: <?php echo $resp['rfc']; ?></td>
                            
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 50px;" valign="top"><?php echo $resp['direccion']; ?></td>
                        </tr>
                        <tr>
                            <td width="25%"><strong>CONTACTO:</strong></td>
                            <td align="left"><?php echo substr($resp['contacto'],0,38) ; ?> </td>
                        </tr>
                        <tr>
                            <td><strong>TELEFONO:</strong></td>
                            <td align="left"><?php echo substr( $resp['telefono'],0,38); ?></td>
                        </tr>
                        <tr>
                            <td><strong>EMAIL:</strong></td>
                            <td align="left"><?php echo substr($resp['correo'],0,38); ?></td>
                        </tr>
                    </table>
                </td>
                <td width="50%" >
                <table style="max-height:100px">
                        <tr>
                            <td class="title-table-td"  valign="top" colspan="2"><strong>DIRECCIÓN DE ENTREGA</strong> </td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="height:65px"><?php echo $resp['direntrega']; ?></td>
                        </tr>
                        
                        <tr>
                            <td width="28%"><strong>No. PROVEEDOR:</strong></td>
                            <td><?php echo substr($resp['nproveedor'],0,35) ; ?></td>
                        </tr>
                        <tr>
                            <td><strong>COMPRADOR:</strong></td>
                            <td><?php echo substr($resp['comprador_nombre'].' '.$resp['comprador_apellidos'],0,38) ; ?></td>
                        </tr>
                        <tr>
                            <td><strong>TELEFONO</strong></td>
                            <td><?php echo substr($resp['telefono2'],0,38) ; ?></td>
                        </tr>
                        <tr>
                            <td><strong>EMAIL:</strong></td>
                            <td><?php echo substr($resp['email'],0,38) ; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <table class="table-header-title" style="margin-top:10px">
            <thead>
                <th style="width: 7.3%;" class="title-table-td">PTDA.</th>
                <th style="width: 7.3%;" class="title-table-td">CANT.</th>
                <th style="width: 7.3%;" class="title-table-td"> UNID.</th>
                <th class="title-table-td">DESCRIPCION</th>
                <th style="width: 11%;" class="title-table-td">P.UNIT</th>
                <th style="width: 11%;" class="title-table-td">P.IMPORTE</th>
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
                    <strong>AUTORIZA</strong>
                </td>
            </tr>
            <tr>
                <td class="left" style="height: 100px;width:50%">
                    <p style="margin-bottom:-16px">
                        _______________________________________________________ <br>
                        <strong><?php echo $resp['comprador_nombre'].' '.$resp['comprador_apellidos']; ?></strong></strong> <br>JEFE DE COMPRAS<span></span>
                    </p>

                </td>
                <td class="right" style="width:50%">
                    <p style="margin-bottom:-30px">

                    _______________________________________________________ <br>
                       <strong><?php echo $resp['autoriza_nombre'].' '.$resp['autoriza_apellidos']; ?></strong> <br>GERENTE GENERAL<br><span style="font-size:8px; "><?php echo $header[$index]['nota']; ?></span>
                    </p>

                </td>
            </tr>

        </table>
        <center style="margin-top:10px"><small style="color:#808080;font-weight:bold;font-size: 7pt;">Calle 8, LT-1-C MZA-III, Fraccionamiento DEIT, R/A Anacleto Canabal 1ª Sección, Villahermosa, Tabasco.  CP 86280 - ventas01@mspetroleros.com - Tel: (993) 337 9968</small></center>
        <div style="border:0.1px solid black;width:100%;margin-bottom:3px;"></div>
        <small style="font-size: 7.5pt">
            DOCUMENTO CONTROLADO<br>
            La copia de este documento sólo se deberá utilizar como referencia<br>
            Queda prohibida su reproducción total o parcial sin autorización de MSP-Maquinados y Servicios
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
        <table class="table-header-title" style="border:unset">
            <?php 
            foreach($oCompra->Servprint($resp['pkocompra']) as $result) { 
            ?>
                <tr>
                    <td valign="top" style="width: 7.3%;text-align:center;border: 0.6px solid #BFBFBF"><?php echo $result['pda']; ?></td>
                    <td valign="top" style="width: 7.3%;text-align:center;border: 0.6px solid #BFBFBF"><?php echo $result['cant']; ?></td>
                    <td valign="top" style="width: 7.3%;text-align:center;border: 0.6px solid #BFBFBF"><?php echo $result['nombre']; ?></td>
                    <td valign="top" style="text-align:left;border: 0.6px solid #BFBFBF"><?php echo nl2br($result['descripcion']); ?></td>
                    <td valign="top" style="width: 11%;text-align:right;border: 0.6px solid #BFBFBF">$<?php echo number_format($result['preciounit'],2,'.',','); ?></td>
                    <td valign="top" style="width: 11%;text-align:right;border: 0.6px solid #BFBFBF">$<?php echo number_format($result['subtotal'],2,'.',','); ?></td>
                </tr>
            <?php } 
                $descto = ($resp['importe'] * $resp['descto']);
                $subtotal = $resp['importe'] - $descto;
                $cIva =( $subtotal * ($resp['iva']/100));
                $total = $subtotal + $cIva;
            ?>

        </table>
        <table style="margin-top:10px">
            
                <tr>
                    <td width="105%"></td>
                    <th style="width:15%;border: 0.6px solid #BFBFBF">IMPORTE:</th>
                    <td style="width:15%;;text-align:right;border: 0.6px solid #BFBFBF">$<?php echo number_format($resp['importe'],2,'.',','); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <th style="border: 0.6px solid #BFBFBF">DESCUENTO:</th>
                    <td style="text-align:right;border: 0.6px solid #BFBFBF">$<?php echo number_format($descto,2,'.',','); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <th style="border: 0.6px solid #BFBFBF">SUBTOTAL:</th>
                    <td style="border: 0.6px solid #BFBFBF;text-align:right">$<?php echo number_format($subtotal,2,'.',','); ?><</td>
                </tr>
                <tr>
                    <td></td>
                    <th style="border: 0.6px solid #BFBFBF">IVA(16%)</th>
                    <td style="border: 0.6px solid #BFBFBF;text-align:right">$<?php echo number_format($cIva,2,'.',','); ?><</td>
                </tr>
                <tr>
                    <td></td>
                    <th style="border: 0.6px solid #BFBFBF" class="title-table-td">TOTAL:</th>
                    <td style="border: 0.6px solid #BFBFBF;text-align:right;background: #DDF0C8">$<?php echo number_format($total,2,'.',','); ?><</td>
                </tr>
        </table>

        <table class="content-table" style="margin-top:20px">
            <tr class="title-table-td ">
                <th colspan="2" style="text-align: center;">DEPARTAMENTO DE COMPRAS: OBSERVACIONES</th>
            </tr>
            <tr>
                <td class="observaciones" colspan="2"><?php echo nl2br($resp['observaciones']); ?></td>
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

$dompdf->getCanvas()->page_text(535,760, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0,0,0));
$dompdf->getCanvas()->page_text(500,770, "$formato", null, 8, array(0,0,0));
$dompdf->stream("OC.-{$folio}.pdf", array("Attachment" => false));


?>