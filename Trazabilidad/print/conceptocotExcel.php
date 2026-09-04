<?php
ob_start();
ini_set('memory_limit', '2048M');

include_once("../../controlador/conexion.php");
include_once("../../controlador/Cotizacion.php");

$idSuc = (int) base64_decode($_GET['suc'] ?? null);
if(!filter_var($idSuc,FILTER_VALIDATE_INT)){ echo "LA URL NO ES VALIDA :("; return false;}

$de = isset($_GET['de']) && $_GET['de'] != '' ? $_GET['de'] : date("Y")."-01-01";
$a = isset($_GET['a']) && $_GET['a'] != '' ? $_GET['a'] : date("Y-m-d");

//Se crea el objeto
$oCot = new Cotizacion();

$lista = $oCot->Concepto($idSuc,$de,$a);

if(empty($lista)){
    echo "NO HAY COTIZACIONES EN EL RANGO DE FECHAS SELECCIONADO";
    return false;
}

function xlsEsc($val){
    //Algunos campos quedaron guardados con entidades HTML literales (p.ej. "R&amp;M" en vez de "R&M");
    //se decodifican antes de re-escapar para XML y así no se dupliquen en el Excel.
    $val = html_entity_decode((string) $val, ENT_QUOTES, 'UTF-8');
    return htmlspecialchars($val, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

$headers = ['Cotización','Fecha','Cliente','Depto.','Concepto','Tipo Trabajo','Estado'];
$widths  = [70,70,170,100,260,90,130];
$colCount = count($headers);
$rowCount = count($lista) + 1;

//Descarta cualquier salida accidental generada por los includes (p.ej. CRLF previo a la etiqueta <?php)
//antes de que arranque el XML real, ya que ahí sí importa que no haya bytes extra al inicio.
ob_end_clean();

header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="COTIZACIONES-CONCEPTO-'.$de.'_a_'.$a.'.xls"');
header('Cache-Control: max-age=0');

echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
echo '<?mso-application progid="Excel.Sheet"?>'."\n";
?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="Header">
   <Font ss:Bold="1" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#4472C4" ss:Pattern="Solid"/>
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
  </Style>
  <Style ss:ID="DateCell">
   <NumberFormat ss:Format="dd/mm/yyyy"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Cot.por Concepto">
  <Table>
<?php foreach($widths as $w): ?>
   <Column ss:Width="<?= $w ?>"/>
<?php endforeach; ?>
   <Row ss:Height="30">
<?php foreach($headers as $h): ?>
    <Cell ss:StyleID="Header"><Data ss:Type="String"><?= xlsEsc($h) ?></Data></Cell>
<?php endforeach; ?>
   </Row>
<?php foreach($lista as $row):
      $fecha = $row['fecha'] != '0000-00-00' ? $row['fecha'] : null;
?>
   <Row>
    <Cell><Data ss:Type="String"><?= xlsEsc($row['folio']) ?></Data></Cell>
    <Cell<?= $fecha ? ' ss:StyleID="DateCell"' : '' ?>><Data ss:Type="<?= $fecha ? 'DateTime' : 'String' ?>"><?= $fecha ? date('Y-m-d', strtotime($fecha)).'T00:00:00.000' : '' ?></Data></Cell>
    <Cell><Data ss:Type="String"><?= xlsEsc($row['nombre']) ?></Data></Cell>
    <Cell><Data ss:Type="String"><?= xlsEsc($row['ndepto']) ?></Data></Cell>
    <Cell><Data ss:Type="String"><?= xlsEsc($row['descripcion']) ?></Data></Cell>
    <Cell><Data ss:Type="String"><?= xlsEsc($row['tipotrabajo']) ?></Data></Cell>
    <Cell><Data ss:Type="String"><?= xlsEsc($row['estado']) ?></Data></Cell>
   </Row>
<?php endforeach; ?>
  </Table>
  <AutoFilter x:Range="R1C1:R<?= $rowCount ?>C<?= $colCount ?>" xmlns="urn:schemas-microsoft-com:office:excel"/>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <FreezePanes/>
   <FrozenNoSplit/>
   <SplitHorizontal>1</SplitHorizontal>
   <TopRowBottomPane>1</TopRowBottomPane>
   <ActivePane>2</ActivePane>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
