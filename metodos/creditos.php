<?php
include('conexion.php');
date_default_timezone_set('America/Bogota');


	if (empty($_POST)) {
		return;
	}else{
	$id_credito = $_POST["id"];
	$fechaactual = date('Y-m-d');
	$hora = date("H") . ':' . date("i");
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
}
