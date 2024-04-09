<?php
require('../fpdf/fpdf.php');
include('../metodos/conexion.php');


class PDF extends FPDF
{
// Cabecera de página
function Header()
{
	
    
    if ($this->PageNo() <= 1){
    include('../metodos/conexion.php');
    include('../metodos/funciones.php');
	$idfactura = $_GET['idfactura'];
	$sql = "";
		$sql = mysqli_prepare($conexion,"SELECT PE.NOMBRES VNOMB, PE.APELLIDOS VAPELL, PE.TELEFONO, PE2.NOMBRES CNOMB, PE2.APELLIDOS CAPELL, PE2.CEDULA,F.FECHAVENTA 
FROM FACTURA F
INNER JOIN PERSONAS PE
	ON F.IDPERSONAS = PE.IDPERSONAS
INNER JOIN PERSONAS PE2
	ON F.IDCLIENTE = PE2.IDPERSONAS WHERE F.IDFACTURA = ?");
		$sql->bind_param('i',$idfactura);
		$ex = $sql->execute();
		$execute = $sql->get_result();
		$row = mysqli_fetch_assoc($execute);
        
        $fechaenletra = fechaaletras($row["FECHAVENTA"]);
    $this->Image('imagenes/logo.png',5,5,42);
    $this->SetFont('Arial','B',15);
    $this->Cell(45);
    $this->Cell(70,10,utf8_decode('AUTOSERVICIO LA CABAÑA'),0,0,'C');
    
    $this->Ln(7);
    $this->Cell(45);
    $this->SetFont('Arial','',12);
    $this->Cell(70,8,utf8_decode('Trv 4 # 6-71 B. Laureles'),0,0,'C');
    $this->SetFont('Arial','B',12);
    $this->Cell(38,8,utf8_decode('Fecha factura:'),0,0,'R');
    $this->SetFont('Arial','',10);
    $this->Cell(40,8,$fechaenletra,0,0,'L');
    
    $this->Ln(5);
    $this->Cell(45);
    $this->SetFont('Arial','',12);
    $this->Cell(70,8,utf8_decode('Cel.3102641664'),0,0,'C');
    $this->SetFont('Arial','B',12);
    $this->Cell(38,8,utf8_decode('Factura No:'),0,0,'R');
    $this->SetFont('Arial','',10);
    $this->Cell(40,8,$idfactura,0,0,'L');
    // Salto de línea

    $this->Ln(10);
    $this->SetFont('Arial','B',10);
    $this->Cell(25,8,utf8_decode('VENDEDOR:'),'B',0,'L');
    $this->SetFont('Arial','',10);
    $this->Cell(90,8,utf8_decode($row['VNOMB'].' '.$row['VAPELL']),'B',0,'L');
    $this->SetFont('Arial','B',10);
    $this->Cell(25,8,utf8_decode('CELULAR :'),'B',0,'L');
    $this->SetFont('Arial','',10);
	$this->Cell(50,8,utf8_decode($row['TELEFONO']),'B',1,'L');

	$this->SetFont('Arial','B',10);
	$this->Cell(25,8,utf8_decode('CLIENTE:'),0,0,'L');
    $this->SetFont('Arial','',10);
    $this->Cell(90,8,utf8_decode($row['CNOMB'].' '.$row['CAPELL']),0,0,'L');
    $this->SetFont('Arial','B',10);
    $this->Cell(25,8,utf8_decode('CEDULA/NIT :'),0,0,'L');
    $this->SetFont('Arial','',10);
	$this->Cell(50,8,utf8_decode($row['CEDULA']),0,0,'L');
    $this->Ln(15);

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
$pdf->Cell(70,7,'DESCRIPCION','1',0,'L',0);
$pdf->Cell(20,7,'UNIDAD','1',0,'R',0);
$pdf->Cell(20,7,'CANT','1',0,'R',0);
$pdf->Cell(20,7,'TOTAL','1',1,'R',0);
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
	$pdf->Cell(70,7,utf8_decode($row['MARCA'] .' '. $row['MEDIDA'] . $row['UNIDAD']),'LR',0,'L',0);
	$pdf->Cell(20,7,'$'.$valorunidad,'LR',0,'R',0);
	$pdf->Cell(20,7,$row['CANTIDAD'],'LR',0,'R',0);
	$pdf->Cell(20,7,'$'.$valortotal,'LR',1,'R',0);
}
$pdf->SetFont('Arial','B',15);
$slq ="";
$sql = mysqli_prepare($conexion,"SELECT SUM(VTOTAL) TOTAL FROM DETALLE_FACTURA WHERE IDFACTURA = ?");
$sql->bind_param('i',$idfactura);
$ex = $sql->execute();
$execute = $sql->get_result();
$row = mysqli_fetch_assoc($execute);
$total = number_format($row['TOTAL'],0,",",".");
$pdf->Cell(150,10,'Valor total:',1,0,'R',0);
$pdf->Cell(40,10,'$'.$total,1,1,'R',0);
$pdf->Output("Factura_No_".$idfactura,"I");
?>