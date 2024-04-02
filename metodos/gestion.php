<?php
//date_default_timezone_set('GMT-5');
date_default_timezone_set('America/Bogota');
	
include('conexion.php');
include('funciones.php');

$accion = $_POST['accion'];
$fechaActual = date('Y-m-d');
$hora = date("H").':'.date("i");
$respuesta = "";
switch ($accion) {
	case 'ventasDia':
    $fechaI = $_POST['fechaI'];
  $fechaF = $_POST['fechaF'];
  
	$sql = 'SELECT SUM(VALORTOTAL) SUMA,FECHAVENTA FROM FACTURA WHERE(PAGADO = 1) AND (FECHAVENTA BETWEEN "'.$fechaI.'" AND "'.$fechaF.'") GROUP BY FECHAVENTA';
  $ejecutar = mysqli_query($conexion,$sql);
  $sqlTotal = 'SELECT SUM(VALORTOTAL) SUMA FROM FACTURA WHERE (PAGADO = 1) AND (FECHAVENTA BETWEEN "'.$fechaI.'" AND "'.$fechaF.'")';
  $ejecutarTotal = mysqli_query($conexion,$sqlTotal);
  $total_array = mysqli_fetch_array($ejecutarTotal);
  $sumaventadia = number_format($total_array['SUMA'],0,",",".");

  $sqlabonot = 'SELECT SUM(VALORABONO) ABONO FROM ABONOS WHERE FECHAABONO BETWEEN "'.$fechaI.'" AND "'.$fechaF.'"';
  $ejecabonot = mysqli_query($conexion,$sqlabonot);
  $totalabonot = mysqli_fetch_array($ejecabonot);
  $sumaabonodia = number_format($totalabonot['ABONO'],0,",",".");

  $totalsumaventas = $totalabonot['ABONO'] + $total_array['SUMA'];

  $sumatotal = number_format($totalsumaventas,0,",",".");

		$respuesta .= "<table class=\"table table-hover\">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Ventas dia</th>
            <th>abonos</th>
            <th>facturas cerradas</th>
            <th>total día</th>
          </tr>
        </thead>
        <tbody>";
         while($fila = mysqli_fetch_array($ejecutar)){
          $fecha = $fila['FECHAVENTA'];
          $sqlabono = "SELECT CASE WHEN
            SUM(VALORABONO) IS NULL THEN 0
            ELSE SUM(VALORABONO)
            END AS ABONO FROM ABONOS WHERE  FECHAABONO = '".$fecha."' GROUP BY FECHAABONO";
          $ejecabono = mysqli_query($conexion,$sqlabono);
          $totalabono = mysqli_fetch_array($ejecabono);
          $sumadia = number_format($fila['SUMA'],0,",",".");
          $abonosdia = number_format($totalabono['ABONO'],0,",",".");
          
         // $suma = number_format($sumaventas,0,",",".");

    $respuesta.= 
      "<tr>
        <td ><button class=\"fechadia btn btn-link\" data-fecha=\"{$fecha}\">{$fecha}</button></td>
        <td >$ {$sumadia}</td>
        <td >$ {$abonosdia}</td>
        
      </tr>";             
        }
    $respuesta.=
      "<tr class=\"border\">
        <td class=\"text-right\"><h2> <b> TOTAL:</b></h2></td>
        <td >$ {$sumaventadia}</td>
        <td >$ {$sumaabonodia}</td>
        <td ></td>
        <td class=\"border border-success\"><h2> $ {$sumatotal} </h2></td>
      </tr>"; 
    $respuesta.="
      </tbody>
      </table>";
    echo $respuesta;  
	break;
  case 'facturasxdia':
  $fecha = $_POST['fecha'];
  $sql = "";
  $sql = mysqli_prepare($conexion,"SELECT F.IDFACTURA, F.VALORTOTAL, F.HORAFACTURA, P2.NOMBRES NVENDE,P2.APELLIDOS AVENDE, P.NOMBRES NCLIENTE, P.APELLIDOS ACLIENTE
FROM FACTURA F 
INNER JOIN PERSONAS P 
  ON F.IDCLIENTE = P.IDPERSONAS
INNER JOIN PERSONAS P2
  ON F.IDPERSONAS = P2.IDPERSONAS
WHERE PAGADO = 1 AND FECHAVENTA = ?
ORDER BY HORAFACTURA DESC");
  $sql->bind_param('s',$fecha);
  $ex = $sql->execute();
  $execute = $sql->get_result();
    $respuesta .="<table class=\"table table-hover\">
          <thead>
            <tr>
              <th>Factura</th>
              <th>Hora factura</th>
              <th>nombre vendedor</th>
              <th>nombre cliente</th>
              <th>valor total</th>
            </tr>
          </thead>
          <tbody>";
      while ($fila = mysqli_fetch_array($execute)) {
        $valortotalfactura = number_format($fila['VALORTOTAL'],0,",",".");
       $respuesta .="
        <tr>

        <td><button class=\"factura btn btn-link\" data-fecha=\"{$fila['IDFACTURA']}\">{$fila['IDFACTURA']}</button></td>
        <td>{$fila['HORAFACTURA']}</td>
        <td>{$fila['NVENDE']} {$fila['AVENDE']}</td>
        <td>{$fila['NCLIENTE']} {$fila['ACLIENTE']}</td>
        <td>$ {$valortotalfactura}</td>
          </tr>
       ";
      }
    $respuesta .="
            
          </tbody>
        </table>";
    echo $respuesta;
    break;


	default:

	break;

}
?>