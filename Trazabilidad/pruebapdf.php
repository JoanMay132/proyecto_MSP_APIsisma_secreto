<?php
require('../dependencias/fpdf/fpdf.php');

class PDF extends FPDF
{
    public function Header()
    {
        $this->SetFont('Arial','',12);
        $this->Write(5,"GNet");
        $this->SetX(-40);
        $this->Write(5, "De Prueba");
    }


    public function Footer()
    {
        $this->SetFont('Arial','',12);
        $this->SetY(-15);
        $this->AliasNbPages();
        $this->Write(5,"Pagina ");
        $this->Write(5,$this->PageNo().' de {nb}');

    }
}
$pdf = new PDF();
$pdf->AddPage('portrait','letter');
$pdf->SetY(20);
$pdf->SetFont('Arial','',12);
$pdf->Cell(20,5,"1");
$pdf->SetTextColor(250,110,110);
$pdf->MultiCell(50,5,"Hola mundo desde PDF hhhhhhhhhhhhhhh \nHola mundo desde PDF hhhhhhhhhhhhhhh Hola mundo desde PDF hhhhhhhhhhhhhhh Hola mundo desde PDF hhhhhhhhhhhhhhh Hola mundo desde PDF hhhhhhhhhhhhhhh",1,'J');

$pdf->AddPage('portrait','letter');
$pdf->Output();
?>