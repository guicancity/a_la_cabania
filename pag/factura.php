<?php
require('../fpdf/fpdf.php');
include('../metodos/conexion.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
	$idfactura = $_GET['idfactura'];
	if ($this->PageNo() <= 1){
    // Logo
    $this->Image('imagenes/logo.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(45);
    // Título
    $this->Cell(80,10,utf8_decode('AUTOSERVICIO LA CABAÑA'),0,0,'C');
    $this->SetFont('Arial','B',12);
    $this->Cell(12);
    $this->Cell(54,10,utf8_decode('Factura de venta No.'),0,0,'L');
    $this->Ln(5);
    $this->Cell(45);
    $this->SetFont('Arial','',12);
    $this->Cell(80,10,utf8_decode('Trv 4 # 6-71 B. Laureles'),0,0,'C');
    $this->Cell(12);
    $this->SetFont('Arial','',15);
    $this->Cell(54,10,$idfactura,0,0,'L');
    // Salto de línea
    $this->Ln(10);
    $this->SetFont('Arial','B',10);
    $this->Cell(25,8,utf8_decode('VENDEDOR:'),'0',0,'R');
    $this->SetFont('Arial','',10);
    $this->Cell(90,8,utf8_decode('JEISSON FERNANDO ROJAS COCUNUBO'),'0',0,'L');
    $this->SetFont('Arial','B',10);
    $this->Cell(25,8,utf8_decode('CEDULA/NIT :'),'0',0,'R');
    $this->SetFont('Arial','',10);
	$this->Cell(50,8,utf8_decode('1052499459'),'0',1,'L');

	$this->SetFont('Arial','B',10);
	$this->Cell(25,8,utf8_decode('CLIENTE:'),'0',0,'R');
    $this->SetFont('Arial','',10);
    $this->Cell(90,8,utf8_decode('BOMBEROS VOLUNTARIOS GUICAN'),'0',0,'L');
    $this->SetFont('Arial','B',10);
    $this->Cell(25,8,utf8_decode('CEDULA/NIT :'),'0',0,'R');
    $this->SetFont('Arial','',10);
	$this->Cell(50,8,utf8_decode('900395098-0'),'0',1,'L');
    $this->Ln(10);

  }
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'R');
}
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,7,'PRODUCTO','1',0,'L',0);
$pdf->Cell(60,7,'DESCRIPCION','1',0,'L',0);
$pdf->Cell(20,7,'UNIDAD','1',0,'R',0);
$pdf->Cell(20,7,'CANT','1',0,'R',0);
$pdf->Cell(30,7,'TOTAL','1',1,'R',0);
$pdf->SetFont('Arial','',9);
$idfactura = $_GET['idfactura'];
$sql = "";
$idfactura = $_GET['idfactura'];
$sql = mysqli_prepare($conexion,"SELECT DF.VALORPRODUCTOS,DF.CANTIDAD,DF.VTOTAL,P.NOMBREPRODUCTO,P.MARCA,P.MEDIDA,P.UNIDAD FROM DETALLE_FACTURA DF
INNER JOIN PRODUCTOS P 
	ON DF.IDPRODUCTOS = P.IDPRODUCTOS
WHERE DF.IDFACTURA = ?
ORDER BY DF.IDDETALLEFACTURA DESC");
$sql->bind_param('i',$idfactura);
$ex = $sql->execute();
$execute = $sql->get_result();
while($row = mysqli_fetch_array($execute)){
	$valortotal = number_format($row['VTOTAL'],0,",",".");
	$valorunidad = number_format($row['VALORPRODUCTOS'],0,",",".");
	$pdf->Cell(60,7,utf8_decode($row['NOMBREPRODUCTO']),'LR',0,'L',0);
	$pdf->Cell(60,7,utf8_decode($row['MARCA'] . $row['MEDIDA'] . $row['UNIDAD']),'LR',0,'L',0);
	$pdf->Cell(20,7,'$'.$valorunidad,'LR',0,'R',0);
	$pdf->Cell(20,7,$row['CANTIDAD'],'LR',0,'R',0);
	$pdf->Cell(30,7,'$'.$valortotal,'LR',1,'R',0);

}
$pdf->SetFont('Arial','B',15);
$slq ="";
$sql = mysqli_prepare($conexion,"SELECT SUM(VTOTAL) TOTAL FROM DETALLE_FACTURA WHERE IDFACTURA = ?");
$sql->bind_param('i',$idfactura);
$ex = $sql->execute();
$execute = $sql->get_result();
$row = mysqli_fetch_assoc($execute);
$total = number_format($row['TOTAL'],0,",",".");
$pdf->Cell(140,15,'Valor total:',1,0,'R',0);
$pdf->Cell(50,15,'$'.$total,1,1,'R',0);
$pdf->SetTitle('Factura_No_'.$idfactura,1);
$pdf->Output();
?>