<?php 
include('conexion.php');

function buscaIdxCodi($conexion,$codigobarras){
	$codigo = 0;
	$sql = "SELECT IDPRODUCTOS FROM DETALLE_PRODUCTOS WHERE CODIGOBARRAS = "."'".$codigobarras."'";
	$ejecuta = mysqli_query($conexion,$sql);
	while ($fila = mysqli_fetch_array($ejecuta)) {
		$codigo = $fila['IDPRODUCTOS'];
	}
	return $codigo;
}

function cambioBodega($conexion,$idProducto,$cantidad,$opcion){
	$sql = 'SELECT CANTIDAD FROM BODEGA WHERE IDPRODUCTOS = '.$idProducto;
	$cActual = 0;
	$ejecuta = mysqli_query($conexion,$sql);
	$ejecutas = 0;
	while ($fila = mysqli_fetch_array($ejecuta)) {
		$cActual = $fila['CANTIDAD'];
	}
	if($opcion == 'SUMA'){
		$total = $cActual + $cantidad ;	
		$sql1 = "UPDATE BODEGA SET CANTIDAD =".$total. " WHERE IDPRODUCTOS = ".$idProducto;
		$ejecuta1 = mysqli_query($conexion,$sql1);
		$ejecutas = 1;

	}else if($opcion == 'RESTA'){
		if($cActual<$cantidad){
			$ejecutas = 2;
		}else{
			$total = $cActual - $cantidad ;
			$sql1 = "UPDATE BODEGA SET CANTIDAD =".$total. " WHERE IDPRODUCTOS = ".$idProducto;
			$ejecuta1 = mysqli_query($conexion,$sql1);	
			$ejecutas = 1;
		}
		
	}
	
	
	return $ejecutas;
}

function cantidadEstante($conexion,$idProducto){
	$sql = 'SELECT CANTIDAD FROM ESTANTE WHERE IDPRODUCTOS = '.$idProducto;
	$cActual = 0;
	$ejecuta = mysqli_query($conexion,$sql);
	$ejecutas = 0;
	while ($fila = mysqli_fetch_array($ejecuta)) {
		$cActual = $fila['CANTIDAD'];
	}
	return $cActual;
}

function valorProducto($conexion,$idProducto){
	$sql = 'SELECT VALOR FROM PRODUCTOS WHERE IDPRODUCTOS ='.$idProducto;
	$sql1 = mysqli_query($conexion,$sql);
	$ejecuta = mysqli_fetch_array($sql1);
	$precio = $ejecuta["VALOR"];
	return $precio;

}
function idfactempxidproduc($conexion,$idProductos){
	$sql = 'SELECT IDDETALLEFACTURATEMP FROM DETALLE_FACTURA_TEMP WHERE IDPRODUCTOS = '.$idProductos;
	$eje = mysqli_query($conexion,$sql);
	$ejecuta = mysqli_fetch_array($eje);
	$idtabla = $ejecuta['IDDETALLEFACTURATEMP'];
	return $idtabla;

}

function actualizarTotal($conexion,$cantidad,$total,$idtabla){
	$sql = 'UPDATE DETALLE_FACTURA_TEMP SET CANTIDAD = '.$cantidad.', VTOTAL = '.$total.' WHERE IDDETALLEFACTURATEMP = '.$idtabla ;
	$ejecuta = mysqli_query($conexion,$sql);
	return 1;
}

function cantidadProducto($conexion,$idProducto){
	$sql = 'SELECT CANTIDAD FROM DETALLE_FACTURA_TEMP WHERE IDPRODUCTOS = '.$idProducto;
	$ejecuta = mysqli_query($conexion,$sql);
	$ejecuta1 = mysqli_fetch_array($ejecuta);
	$cantidad = $ejecuta1['CANTIDAD'];
	return $cantidad;
}

function existsfacturaxcliente($conexion, $idcliente){
	$sql = 'SELECT IDFACTURA FROM FACTURA WHERE IDCLIENTE = '.$idcliente.' AND PAGADO = 0';
	$ejec = mysqli_query($conexion,$sql);
	$row = mysqli_num_rows($ejec);
	if($ejec > 0){
		$fila = mysqli_fetch_array($ejec);
		$idfactura = $fila['IDFACTURA'];
		return $idfactura;
	}else{
		return 0;
	}
}

function buscatotalfacturaactual($conexion,$idfactura){
	$sql = 'SELECT VALORTOTAL FROM FACTURA WHERE IDFACTURA = '.$idfactura;
	$eje = mysqli_query($conexion,$sql);
	$fila = mysqli_fetch_array($eje);
	$valortotalfactura = $fila['VALORTOTAL'];
	return $valortotalfactura;
}

function idpersonaxproducto($conexion,$idproducto){
	$sql = "SELECT IDPERSONAS FROM PRODUCTOS WHERE IDPRODUCTOS = ". $idproducto;
	$execute = mysqli_query($conexion,$sql);
	$fila = mysqli_fetch_array($execute);
	$idpersona = $fila['IDPERSONAS'];
	return $idpersona;
}

 ?>