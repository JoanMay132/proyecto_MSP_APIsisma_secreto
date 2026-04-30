<?php ob_start();
include_once("../../controlador/conexion.php");
include_once("../../controlador/Cotizacion.php");
include_once("../../class/Fecha.php");
include_once("../../class/Conversion.php");
include_once("../../class/Helper.php");
include_once("../../class/Header.php");
require_once("../../dependencias/dompdf/autoload.inc.php");

  $id = (int) base64_decode($_GET['cotizacion']);
  if(!filter_var($id,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}

 //Se crea el objeto
 $oCot = new Cotizacion();
 $nControl = 6;

$resp = $oCot->Print($id);

$importe = 0;
$dolar = isset($_GET['moneda']) && @$_GET['moneda'] === 'true' ?true : false;
//Codigo para encriptación de imagen y poder renderizar en dompdf
$path = '../../dependencias/img/Logo_Premium_Maquinados-04.svg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

if (isset($_GET['iva']) && $_GET['iva'] === 'true') { $iva = true; } else { $iva = false;}

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
    <title>PRINT - COTIZACIÓN</title>
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

        body {
            font-family:Arial, Helvetica, sans-serif;
            margin-left: unset;
            margin: 0;
            padding: 0;
        }

        
        
        .header-table,
        .content-table,
        .table-header-title {
            width: 100%;
            /* border: 1px solid black;  */
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

        .text-center {
            text-align: center;
        }

        .table-header-title tr td {
            /* border-bottom: 1.5px solid black; */
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
        table.encabezado tr th,table.encabezado tr td{
                text-align: left;
        }
        table.encabezado tr td.sub{
                font-size: 10px;
        }
        table tr td small{
            color:green;
            font-size: 11px;
        }
        table tr td small span{
            color:black;
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
                <td class="header-text" style="width:40%">Cliente:</td>
                <td class="header-text" style="width:35%">At´n:</td>
                <td class="header-folio" style="width:13%;font-weight:bold;">Cotización</td>
                <td style="font-size: 8pt;font-weight:bold;color:#C00000;width:10%"><?php echo $letras.$resp['folio']; ?></td>
            </tr>
            <tr>
                <td valign="top" class="header-subtext" style="height: 20px;"><?php echo substr($resp['nombre'],0,85); ?></td>
                <td valign="top" class="header-subtext"><?php echo substr($resp['titulo_atn'].". ".$resp['nombre_atn'],0,85); ?></td>
                <td valign="top" class="header-folio" style="font-weight:bold;">Fecha:</td>
                <td valign="top" ><?php echo Fecha::convertir($resp['fecha']); ?></td>
            </tr>
            <tr>
                <td class="header-text">Solicito:</td>
                <td class="header-text">Cargo:</td>
                <?php if($resp['pkcliente'] == '115' || $resp['pkcliente'] == '407' || $resp['pkcliente'] == '475'|| $resp['pkcliente'] == '806'){ ?>
                    <td valign="top" class="header-folio" style="font-weight:bold;">Number Vendor:</td>
                    <td valign="top">1163335</td>
                <?php } ?>
            </tr>
            <tr>
                <td valign="top" class="header-subtext"><?php echo $resp['titulo'].". ".$resp['nombre_usercli']; ?></td>
                <td valign="top" class="header-subtext"><?php echo $resp['cargo']; ?></td>
                <td colspan="2" class="header-folio"></td>
            </tr>

            <tr>
                <td colspan="4" class="header-text">Dirección:</td>
            </tr>
            <tr>
                <td class="header-subtext" colspan="4"><?php echo substr($resp['direccion'],0,85); ?></td>
            </tr>
        </table>
        <!-- Fin de encabezado -->

        <table  style="border: none">
            <tr><td style="height: 8px;"></td></tr>
            <tr>
                <td colspan="8" style="border-collapse: collapse;color:#17365D;font-weight:bold;text-align:center;">De acuerdo con su amable solicitud ponemos a su disposición la siguiente cotización:</td>
            </tr>
            <thead>
                <th style="width: 5%;" class="title-table-td">PDA</th>
                <th style="width: 6%;" class="title-table-td">CANT.</th>
                <th style="width: 8%;" class="title-table-td"> UNIDAD</th>
                <th class="title-table-td">DESCRIPCIÓN</th>
                <th style="width: 8%;" class="title-table-td">CLAVE</th>
                <th style="width: 7%;" class="title-table-td">ITEM</th>
                <th style="width: 9%;" class="title-table-td">P/UNIT</th>
                <th style="width: 10%;" class="title-table-td">IMPORTE</th>

            </thead>
        </table>
    </header>
    <footer style="font-size: 7.5pt;">
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
        <table class="table-header-title" style="border:unset;table-layout: fixed;">
            <?php  $anterior = 0; foreach($oCot->Servprint($resp['pkcotizacion']) as $serv){               
                    $redondeo = intval($serv['pda']);
                    $importe += $serv['subtotal']; 
                ?>
                <tr>
                    <td valign="top" style="width: 5%;text-align:center; border:0.7px solid #BFBFBF"><?php if($redondeo == $anterior){ echo "";}else{ echo $redondeo;} ?></td>
                    <td valign="top" style="width: 6%;text-align:center; border:0.7px solid #BFBFBF"><?php echo $serv['cant']; ?></td>
                    <td valign="top" style="width: 8%;text-align:center; border:0.7px solid #BFBFBF"><?php echo $serv['nombre']; ?></td>
                    <td valign="top" style="width:47%;borde:1px solid #BFBFBF;border:0.7px solid #BFBFBF"><?php echo nl2br($serv['descripcion']); ?></td>
                    <td valign="top" style="width: 8%;text-align:center;word-wrap: break-word;border:0.7px solid #BFBFBF"><?php echo $serv['clave']; ?></td>
                    <td valign="top" style="width: 7%;text-align:center;word-wrap: break-word; border:0.7px solid #BFBFBF"><?php echo $serv['item']; ?></td>
                    <?php if($dolar){
                         ?>
                        <td valign="top" style="width: 9%;text-align:right;border:0.7px solid #BFBFBF"><?php echo "$".number_format(($serv['preciounit']/$resp['tipocambio']),2,".",",") ; ?></td>
                        <td valign="top" style="width: 10%;text-align:right;border:0.7px solid #BFBFBF"><?php echo "$".number_format(($serv['subtotal']/$resp['tipocambio']),2,".",","); ?></td>
                    <?php }else{ ?>
                    <td valign="top" style="width: 9%;text-align:right;border:0.7px solid #BFBFBF"><?php if($serv['preciounit'] != 0.00){ echo "$".number_format($serv['preciounit'],2,".",",") ; }?></td>
                    <td valign="top" style="width: 10%;text-align:right;border:0.7px solid #BFBFBF"><?php if($serv['subtotal'] != 0.00){ echo "$".number_format($serv['subtotal'],2,".",","); } ?></td>
                    <?php } ?>
                </tr>
                <?php $anterior = $redondeo; }  ?>
            
        </table>
        <div style="border:0.1px solid black;width:100%;margin-bottom:10px"></div>
        <table>
            <?php if($resp['descto'] == "0.00"){  
                if($iva){ 
                    $importeIva = ($importe * ($resp['iva']/100));
                    $importe2 = ($importeIva + $importe);
                    if($dolar){
                    ?>
                    
                <th  style="width:80%;text-align:left">(<?php echo strtoupper(Conversion::convertirNumeroALetras($importe2/$resp['tipocambio'],'DOLARES')); ?>)</th>
                <th>
                <table>
                    
                         <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt">SUBTOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($resp['subtotal1']/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>
                     <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt">IVA:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($importeIva/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>
                        <tr >
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt;background:#002060;color:white">TOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt;">$<?php echo number_format($importe2/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>
                   
                </table>
                </th>
            <?php }else{ //End not dolar ?>
                <th  style="width:80%;text-align:left">(<?php echo strtoupper(Conversion::convertirNumeroALetras($importe2,'PESOS')); ?>)</th>
                <th>
                <table>
                    
                         <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt">SUBTOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($resp['subtotal1'], 2, '.', ','); ?></td>
                        </tr>
                     <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt">IVA:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($importeIva, 2, '.', ','); ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;font-size: 9pt;background:#002060;color:white">TOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt;">$<?php echo number_format($importe2, 2, '.', ','); ?></td>
                        </tr>
                        
                </table>
                </th>

            <?php }  }else{ //End iva
                     if($dolar){
                ?>
            <tr>
                <th  style="width:80%;text-align:left">(<?php echo strtoupper(Conversion::convertirNumeroALetras($importe/$resp['tipocambio'],'DOLARES')); ?>)</th>
                <th style="width:10%;text-align:left;border:1px solid grey;font-size: 9pt;background:#002060;color:white">IMPORTE:</th>
                <td style="width:10%;text-align:center;font-size: 9pt;border:1px solid grey">
                    <strong>$<?php echo number_format($importe/$resp['tipocambio'], 2, '.', ','); ?></strong>
                </td>
            </tr>
            <?php }else{ ?>
            <tr>
                <th  style="width:80%;text-align:left">(<?php echo strtoupper(Conversion::convertirNumeroALetras($importe, 'PESOS')); ?>)</th>
                <th style="width:10%;text-align:left;font-size: 9pt;border:1px solid grey;background:#002060;color:white">IMPORTE:</th>
                <td style="width:10%;text-align:center;font-size: 9pt;border:1px solid grey">
                    <strong>$<?php echo number_format($importe, 2, '.', ','); ?></strong>
                </td>
            </tr>

            <?php } }
            
            }else{ //End not descto
                    $porcentaje = ($resp['subtotal1'] * $resp['descto']);
                    $importe = floatval(($resp['subtotal1'] - $porcentaje));
                ?>
            <tr>
            <?php if(!$iva){ 
                if($dolar){ ?>
                <th valign="top" style="width:65%;text-align:left">(<?php echo strtoupper(Conversion::convertirNumeroALetras($importe/$resp['tipocambio'], 'DOLARES')); ?>)</th>
               <?php }else{
                ?>
                <th valign="top" style="width:65%;text-align:left">(<?php echo strtoupper(Conversion::convertirNumeroALetras($importe,'PESOS')); ?>)</th>
                   <?php } }else {
                         $importeIva = ($importe * ($resp['iva']/100));
                         $importe2 = ($importeIva + $importe);

                         if($dolar){ ?>
                        <th valign="top" style="width:65%;text-align:left">(<?php echo strtoupper(Conversion::convertirNumeroALetras($importe2/$resp['tipocambio'],'DOLARES')); ?>)</th>

                         <?php }else{
                    ?> 
                        <th valign="top" style="width:65%;text-align:left">(<?php echo strtoupper(Conversion::convertirNumeroALetras($importe2,'PESOS')); ?>)</th>
                    <?php } } if($dolar){ ?>

                    
                <th style="width:35%;">
                    <table>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt">SUBTOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($resp['subtotal1']/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt">DESCUENTO: <small style="color:black"><?php echo Helper::porcentaje(floatval($resp['descto'])); ?></small></td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($porcentaje/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>

                        <?php if($iva){ ?>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt">IMPORTE:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($importe/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size: 9pt">IVA:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($importeIva/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>
                        <tr >
                            <td style="width:50%;text-align:left;border: 1px solid grey;font-size: 9pt;background:#002060;color:white">TOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt">$<?php echo number_format($importe2/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>
                        <?php }else{ ?>
                            <tr >
                            <td style="width:50%;text-align:left;border: 1px solid grey;font-size: 9pt;background:#002060;color:white">TOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size: 9pt;">$<?php echo number_format($importe/$resp['tipocambio'], 2, '.', ','); ?></td>
                        </tr>
                        <?php } ?>
                        
                    </table>
                    
                </th>
                <?php }else { ?>
                    <th style="width:35%;">
                    <table>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size:9pt">SUBTOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size:9ptpx">$<?php echo number_format($resp['subtotal1'], 2, '.', ','); ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size:9pt">DESCUENTO: <small style="color:black"><?php echo Helper::porcentaje(floatval($resp['descto'])); ?></small></td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size:9pt">$<?php echo number_format($porcentaje, 2, '.', ','); ?></td>
                        </tr>

                        <?php if($iva){ ?>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size:9pt">IMPORTE:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size:9pt">$<?php echo number_format($importe, 2, '.', ','); ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size:9pt">IVA:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size:9pt">$<?php echo number_format($importeIva, 2, '.', ','); ?></td>
                        </tr>
                        <tr style="background:#D9F2D0">
                            <td style="width:50%;text-align:left;border: 1px solid grey;color:#002060;font-size:9pt">TOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size; 9pt;">$<?php echo number_format($importe2, 2, '.', ','); ?></td>
                        </tr>
                        <?php }else{ ?>
                        <tr style="background:#D9F2D0">
                            <td style="width:50%;text-align:left;border: 1px solid grey;font-size:9pt;background:#002060;color:white">TOTAL:</td>
                            <td style="width:50%;text-align:right;border: 1px solid grey;font-size:9pt">$<?php echo number_format($importe, 2, '.', ','); ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </th>

              <?php  } ?>
            </tr>
           <?php } ?>
        </table>
        <div style="font-size:11px;font-weight:bold;margin-top:30px"><?php echo $resp['observacion']; ?></div>
        <div style="border:0.1px solid black;width:100%;margin-top:3px"></div>
        
        <table class="table-norma">
            <tr>
                <th style="width:23%">ESTANDARES DE FABRICACIÓN:</th>
                <td style="width:32%"><?php echo nl2br($resp['efabricacion']); ?></td>
                <th style="width:15%">REQUISITOS LEGALES:</th>
                <td style="width:30%"><?php echo nl2br($resp['doclegal']); ?></td>
            </tr>
            <tr>
                <th>PROCESOS DE CALIDAD:</th>
                <td colspan="3"><?php echo nl2br($resp['dnormativos']); ?></td>
            </tr>
        </table>
              
        <table class="table-info">
            <tr>
                <th style="width:25%">Vigencia de cot.:<br>
                <span><?php echo $resp['vigencia']; ?></span></th>
                <th style="width:25%">Condiciones de pago:<br>
                <span><?php echo $resp['dcredito']; ?></span></th>
                <th style="width:25%">Precios:<br>
                <span><?php echo "MONEDA".' '.$resp['moneda']; ?></span></th>
                <th style="width:25%">Tiempo de entrega:<br>
                <span><?php echo $resp['tiempoent']; ?></span></th>
                <th style="width:25%">L.A.B.:<br>
                <span><?php echo $resp['lab']; ?></span></th>
            </tr>
        </table><br>
        <table style="margin-top:10px">
            <!-- <tr> 
                <td style="font-size:13px;text-align:center; padding-bottom:30px" colspan="2">
                    En Maquinados y Servicios Petroleros mejoramos los precios ofertados por otra empresa, siempre y cuando la calidad de 
                    sus servicios, los términos de entrega y las condiciones de pago cotizados sean similares a los nuestros.<br>
                    <strong>Certificada por AMERICAN PETROLEUM INSTITUTE -API</strong><br>
                    Bajo los números de licencias: Q1_4727, 7-1_1667, 6A_2513
                </td>
            </tr> -->
            <tr>
                <th  colspan="2" style="font-size: 8pt;text-align:left;color:#002060;padding-bottom:30px">
                    <?php if(!$iva){ ?> Estos precios no incluyen I.V.A.<br> <?php } ?>
                    Desviaciones / Excepciones / Requisitos No contempladas por el cliente<br>
                    Para proceder con esta cotización deberá ser autorizado por escrito mediante orden de compra o pedido.<br>
                </th>
            </tr>
            <tr>
                <td style="font-size:11px;text-align:center;width:50%">
                    ELABORÓ<br><br><br><br><br>

                    _____________________________________<br>
                    <strong><?php echo $resp['nombre_empleado'].' '.$resp['apellidos']; ?></strong><br>
                    JEFE DE VENTAS
                </td>
                
                <td style="font-size:11px;text-align:center;width:50%">
                    VALIDÓ<br><br><br><br><br>

                    _____________________________________<br>
                    <strong>HENRRY HERNANDEZ PEREZ</strong><br>
                    GERENTE GENERAL
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
$dompdf->stream("COT.-MSP-A{$folio}.pdf", array("Attachment" => false));


?>