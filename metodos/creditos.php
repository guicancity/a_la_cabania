<?php 
//region INICIO

date_default_timezone_set('America/Bogota');
  
include('conexion.php');
include('funciones.php');

$accion = $_POST['accion'];
$fechaactual = date('Y-m-d');
$hora = date("H").':'.date("i");
$respuesta = "";
switch ($accion) {
	case 'cargacreditos':
	
	$sql = mysqli_query($conexion,"SELECT
							F.IDFACTURA,
							CONCAT(P.NOMBRES ,' ', P.APELLIDOS) AS PERSONA,
							F.FECHAVENTA,
							F.HORAFACTURA,
							F.VALORTOTAL
						FROM FACTURA F
						JOIN PERSONAS P
							ON F.IDCLIENTE = P.IDPERSONAS
						WHERE PAGADO = 0
						ORDER BY PERSONA");
	if(isset($_POST['dato'])){
		$dato = $_POST['dato'];
		$sql = mysqli_query(
		$conexion,
		"SELECT
			F.IDFACTURA,
			CONCAT(P.NOMBRES ,' ', P.APELLIDOS) AS PERSONA,
			F.FECHAVENTA,
			F.HORAFACTURA,
			F.VALORTOTAL
		FROM FACTURA F
		JOIN PERSONAS P
			ON F.IDCLIENTE = P.IDPERSONAS
		WHERE (PAGADO = 0)
		AND (P.NOMBRES LIKE '%".$dato."%'
		OR P.APELLIDOS LIKE '%".$dato."%')
		ORDER BY PERSONA"
	);

	}
	$respuesta .="
			<table class=\"table  table-hover\">
				<thead>
					<tr>
						<th>DEUDOR</th>
						<th>FECHA</th>
						<th>VALOR</th>
						<th></th>
					</tr>
				</thead>
				<tbody>";
					
						$respuesta.=  mysqli_error($conexion);
					while ($row = mysqli_fetch_array($sql)) {
						$idfactura = $row['IDFACTURA'];
						$valorabonos = totalabonosxfactura($conexion,$idfactura);
						$valorfactura = $row["VALORTOTAL"];
						$totalfactura = $valorfactura;
						$valortotal = number_format($totalfactura, 0, ",", ".");
						$fechaventa = date("d/m/Y",strtotime($row["FECHAVENTA"]));
						$respuesta .="<tr>
							<td>
							<button type='button' class=\"btn btn-link detallefactura\" data-bs-toggle=\"modal\" data-idfactura=\"{$idfactura}\" data-bs-target='#creditopersona'>{$row["PERSONA"]}</button>
							</td>
							<td>{$fechaventa} </td>
							<td>$ {$valortotal}</td>
							<td>
								<button class=\"btn  btn-success shadow cerrarcredito\" data-id=\"{$row['IDFACTURA']}\">
									<i class=\"fa-solid fa-sack-dollar\"></i> CERRAR CR&Eacute;DITO
								</button>
								<button class=\"btn btn-warning abonarfactura\" data-idfactura=\"{$row['IDFACTURA']}\"><i class=\"fa-solid fa-sack-dollar\"></i> ABONO
								</button>
							</td>
						</tr>";
					}
				$respuesta .="</tbody>
			</table>";
			echo $respuesta;
		break;
	case 'cierracredito':
	$id_credito = $_POST["id"];
	$query_2 ="";
	$query = "";
	$query = mysqli_prepare($conexion, "UPDATE FACTURA SET PAGADO = 1,FECHAVENTA = ? , HORAFACTURA = ? WHERE IDFACTURA = ?");
	$query->bind_param("ssi", $fechaactual,$hora,$id_credito);
	$execute = $query->execute();

	$query_2 = mysqli_prepare($conexion,"UPDATE ABONOS SET CERRADO = 1 WHERE IDFACTURA = ?");
	$query_2 -> bind_param("i",$id_credito);
	$execute_2 = $query_2->execute();

	if (!$execute) {
		http_response_code(404);
	}else{
		echo 1;
	}
		break;
	case'agregarabonofactura':
		if(!empty($_POST)){
			$idfactura = $_POST['idfactura'];
			$valorabono = $_POST['valorabono'];
			$valorfacturaactual = buscatotalfacturaactual($conexion,$idfactura);

			if($valorabono < $valorfacturaactual){ //valida que no se abone mas de la deuda
				if($valorabono >= 100){ //valida que minimo se abonado sea 100 y no permite numeros negativos
					if(($valorabono % 100) == 0){	//valida que no abonen con centavos 199, 201, 250 sino cifras cerradas 100,1500,2300
						//INICIO inserción tabla abonos
						$sql = "";
						$sql = mysqli_prepare($conexion,"INSERT INTO ABONOS(IDFACTURA,FECHAABONO,HORAABONO,VALORABONO,CERRADO) VALUES(?,?,?,?,0)");
						$sql->bind_param('issi',$idfactura,$fechaactual,$hora,$valorabono);
						$execute = $sql->execute();
						//FIN inserción tabla abonos
						if($execute){
							//Si se realiza la inserción del abono se procede a restar el abono de la factura
							$nuevovalor = $valorfacturaactual - $valorabono;
							$sql1 = "";
							$sql1 = mysqli_prepare($conexion,"UPDATE FACTURA SET VALORTOTAL = ? WHERE IDFACTURA = ?");
							$sql1->bind_param('ii',$nuevovalor,$idfactura);
							$execute1 = $sql1->execute();
							echo 1;
						}
					}else{
						echo 4;
					}
				}else{
					echo 3;
				}
			}else{
				echo 2;
			}
		}
	break;
	case 'productosxfactura':
      $idfactura = $_POST['idfactura'];
      
      $sql = "SELECT P.IDPRODUCTOS, P.NOMBREPRODUCTO,DF.VALORPRODUCTOS,DF.CANTIDAD,DF.VTOTAL, DF.FECHAVENTA,DF.HORAFACTURA FROM DETALLE_FACTURA DF INNER JOIN PRODUCTOS P ON P.IDPRODUCTOS = DF.IDPRODUCTOS WHERE IDFACTURA = ". $idfactura." ORDER BY DF.FECHAVENTA DESC";
      $ejecuta = mysqli_query($conexion,$sql);
      $idproductos = "";

      $respuesta .="
      <table class=\"table table-hover\">
								<thead>
									<tr>
										<th>Productos</th>
										<th>Valor</th>
										<th>cantidad</th>
										<th>Total</th>
                    <th>Fecha de venta</th>
									</tr>
								</thead>
								<tbody>";
                while($fila=mysqli_fetch_array($ejecuta)){
                  $valor = number_format($fila["VALORPRODUCTOS"], 0, ",", ".");
                  $valortotal = number_format($fila["VTOTAL"], 0, ",", ".");
                  $fechaventa = date("d/m/Y",strtotime($fila["FECHAVENTA"]));
                $respuesta .="
									<tr>
										<td>{$fila["NOMBREPRODUCTO"]}  </td>
										<td>$ {$valor}</td>
										<td>{$fila["CANTIDAD"]}</td> 
										<td>$ {$valortotal}</td>
                    <td>{$fechaventa}</td>

									</tr>

                  ";
                }
$respuesta .="   
        {$idproductos}               
								</tbody>
							</table>
      
      
      ";
      echo $respuesta;

      break;
 default:
    // code...
    break;
}

	


