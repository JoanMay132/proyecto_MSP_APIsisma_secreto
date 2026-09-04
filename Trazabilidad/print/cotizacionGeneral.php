<?php ob_start();
ini_set('memory_limit', '2048M');

include_once("../../controlador/conexion.php");
include_once("../../controlador/Cotizacion.php");
include_once("../../class/Fecha.php");
include_once("../../class/Header.php");
require_once("../../dependencias/dompdf/autoload.inc.php");

$idSuc = (int) base64_decode($_GET['suc'] ?? null);
if(!filter_var($idSuc,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}

$de = isset($_GET['de']) && $_GET['de'] != '' ? $_GET['de'] : date("Y")."-01-01";
$a = isset($_GET['a']) && $_GET['a'] != '' ? $_GET['a'] : date("Y-m-d");

//Se crea el objeto
$oCot = new Cotizacion();
$nControl = 6;

$lista = $oCot->GetDataJoinFull($idSuc,$de,$a);

if(empty($lista)){
    echo "NO HAY COTIZACIONES EN EL RANGO DE FECHAS SELECCIONADO";
    return false;
}

//Codigo para encriptación de imagen y poder renderizar en dompdf
$path = '../../dependencias/img/mspnew.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

//Obtenemos información del array de encabezados
$index = null;
$resultado = array_filter($header, function($elemento) use ($nControl, $idSuc) {
    return $elemento['control'] == $nControl && $elemento['sucursal'] == $idSuc;
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
    <title>PRINT - COTIZACIONES GENERAL</title>
    <style>
    @page {
        margin: 20px;
        margin-left: 40px;
        margin-top: 150px;
        margin-bottom: 60px;
    }

    header {
        position: fixed;
        top: -130px;
        left: 0;
        right: 0;
    }

    footer {
        position: fixed;
        bottom: -38px;
        left: 0;
        right: 0;
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
    .table-header-title {
        width: 100%;
        border: 1px solid black;
        table-layout: fixed;
    }

    .table-header-title td {
        word-wrap: break-word;
    }

    #img-h1 {
        width: 15%;
    }

    .header-table td {
        border: none;
        padding: 5px;
    }

    .header {
        text-align: right;
    }

    .contact-info {
        text-align: center;
        width: 45%;
    }

    .title-table-td {
        background-color: #D1D1D1;
    }

    .table-header-title thead th {
        border: 1px solid black;
    }

    .table-header-title tr td {
        border-bottom: 1.5px solid black;
        padding: 3px
    }
    </style>
</head>

<body>

    <header>
        <table class="header-table">
            <tr style="text-align:right">
                <td colspan="3" style="color:#33BEFF">
                    <strong><?php echo $header[$index]['texto1']; ?></strong>
                </td>
            </tr>
            <tr>
                <td id="img-h1" style="padding:0px;"><img src="<?php echo $base64; ?>" style="width:150px;height:60px;position:relative;top:-20px;margin-left:5px"></td>
                <td class="contact-info"></td>
                <td class="header">
                    <div style="margin-right:0px;position:relative;width:130%;margin-top:-35px;right:30%">
                        <strong><?php echo $header[$index]['texto2']; ?><br>
                        <?php echo $header[$index]['texto3']; ?><br>
                        <?php echo $header[$index]['texto4']; ?></strong>
                    </div>
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <div style="border:0.1px solid black;width:100%;margin-bottom:3px"></div>
        <small style="font-size:10px">
            DOCUMENTO CONTROLADO<br>
            La copia de este documento sólo se deberá utilizar como referencia<br>
            Queda prohibida su reproducción total o parcial sin autorización de Maquinados y Servicios Petroleros<br>
        </small>
    </footer>

    <table class="table-header-title">
        <tr>
            <th colspan="11" class="title-table-td" style="text-align:left">Trazabilidad de Cotizaciones <?= Fecha::convertir($de); ?> a <?= Fecha::convertir($a); ?>:</th>
        </tr>
        <thead>
            <th class="title-table-td" style="width:8%">Folio</th>
            <th class="title-table-td" style="width:8%">Fecha</th>
            <th class="title-table-td" style="width:8%">Rev.Folio</th>
            <th class="title-table-td" style="width:22%">Cliente</th>
            <th class="title-table-td" style="width:12%">Depto.</th>
            <th class="title-table-td" style="width:9%">Importe</th>
            <th class="title-table-td" style="width:12%">Estado</th>
            <th class="title-table-td" style="width:7%">OT.Fol.</th>
            <th class="title-table-td" style="width:7%">Ord.Comp.</th>
            <th class="title-table-td" style="width:3%">Ent.</th>
            <th class="title-table-td" style="width:4%">Factura</th>
        </thead>
        <?php foreach($lista as $row): ?>
        <tr>
            <td valign="top" style="text-align:center"><?= $row['folio']; ?></td>
            <td valign="top" style="text-align:center"><?= $row['fecha'] != '0000-00-00' ? Fecha::convertir($row['fecha']) : ''; ?></td>
            <td valign="top" style="text-align:center"><?= $row['revfolio']; ?></td>
            <td valign="top"><?= $row['ncliente']; ?></td>
            <td valign="top"><?= $row['ndepto']; ?></td>
            <td valign="top" style="text-align:right"><?= $row['total'] !== null ? number_format($row['total'],2) : ''; ?></td>
            <td valign="top"><?= $row['estado']; ?></td>
            <td valign="top" style="text-align:center"><?= $row['otfolio']; ?></td>
            <td valign="top" style="text-align:center"><?= $row['ordcompfolio']; ?></td>
            <td valign="top" style="text-align:center"><?= $row['totalentregas'] > 0 ? 'SI' : ''; ?></td>
            <td valign="top" style="text-align:center"><?= $row['factura']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>

<?php
$html = ob_get_clean();

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set('isHtml5ParserEnabled', true);
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter', 'landscape');
$dompdf->render();

$dompdf->getCanvas()->page_text(535,755, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0,0,0));
$dompdf->stream("COTIZACIONES-{$de}_a_{$a}.pdf", array("Attachment" => false));

?>
