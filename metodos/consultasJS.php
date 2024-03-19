<?php
session_start();
//date_default_timezone_set('GMT-5');
date_default_timezone_set('America/Bogota');
	
include('conexion.php');
include('funciones.php');

$accion = $_POST['accion'];
$fechaActual = date('Y-m-d');
$hora = date("H").':'.date("i");
switch ($accion) {
	case 'nuevoProducto':
		if (!empty($_POST)){
			$empresa = $_POST['empresa'];
			$codigoBarras = $_POST['codigoBarras'];
			$persona = $_POST['idPersona'];
			$nombreProducto = strtoupper($_POST['nombreProducto']);
			$marca = strtoupper($_POST['marca']);
			$medida = strtoupper($_POST['medida']);
			$sabor = strtoupper($_POST['sabor']);
			$unidad = strtoupper($_POST['unidad']);
			$precioCompra = $_POST['precioCompra'];
			$valor = $_POST['valor'];

			$fechaActual = date( 'Y-m-d' );

			$sql = "INSERT INTO PRODUCTOS"."(
										IDEMPRESA,
										IDPERSONAS,
										NOMBREPRODUCTO,
										MARCA,
										MEDIDA,
										UNIDAD,
										VALOR,
										PRECIOCOMPRA,
										FECHACREACION) 
					VALUES(".$empresa.
						",".$persona.
						",'".$nombreProducto.
						"','".$marca.
						"','".$medida.
						"','".$unidad.
						"',".$valor.
						",".$precioCompra.
						",'".$fechaActual."');
						";

			$ejecuta = mysqli_query($conexion,$sql);
			$codigo = mysqli_insert_id($conexion); 
			$_SESSION['idNuevoProducto']= $codigo;
			if ($ejecuta == 1) {
				$sqlDetalleProducto = "INSERT INTO DETALLE_PRODUCTOS(IDPRODUCTOS,
																	 CODIGOBARRAS,
																	 SABOR)
									   VALUES(".$codigo.
									   ",'".$codigoBarras.
									   "','".$sabor."')";
				$ejecutaDetalleProducto = mysqli_query($conexion,$sqlDetalleProducto);
				$sql2 = "INSERT INTO BODEGA(IDPRODUCTOS,CANTIDAD) VALUES(".$codigo.",0)";
				$sql3 = "INSERT INTO ESTANTE(IDPRODUCTOS,CANTIDAD) VALUES(".$codigo.",0)";
				$ejecuta2 = mysqli_query($conexion,$sql2);
				$ejecuta3 = mysqli_query($conexion,$sql3);
				echo $codigo;
			}else{
				echo 0;
			}
		}
	break;
	case 'actualizarProducto':
		if (!empty($_POST)){
			$idProducto = $_POST['idProducto'];
			$idEmpresa= $_POST['idEmpresa'];
			$idPersonas= $_POST['idPersonas'];
			$nombreProducto= strtoupper($_POST['nombreProducto']);
			$marca= strtoupper($_POST['marca']);
			$medida= $_POST['medida'];
			$unidad= strtoupper($_POST['unidad']);
			$valor= $_POST['valor'];
			$precioCompra= $_POST['precioCompra'];
			$sql = "UPDATE PRODUCTOS SET IDEMPRESA =".$idEmpresa.",
										IDPERSONAS =".$idPersonas.",
										NOMBREPRODUCTO ='".$nombreProducto."',
										MARCA ='".$marca."',
										MEDIDA ='".$medida."',
										UNIDAD ='".$unidad."',
										VALOR =".$valor.",
										PRECIOCOMPRA =".$precioCompra.",
										FECHACREACION ='".$fechaActual."'
					WHERE IDPRODUCTOS =".$idProducto;
			$ejecuta = mysqli_query($conexion,$sql);
			echo $ejecuta;
			}

	break;

	case 'BuscaBarraExistente':
		if (!empty($_POST)) {
			$respuesta = 0;
			$codigoBarras = $_POST['codigoBarras'];
			$sql = 'SELECT * FROM DETALLE_PRODUCTOS WHERE CODIGOBARRAS = "'.$codigoBarras.'"';
			$ejecuta = mysqli_query($conexion, $sql);
			$row = mysqli_num_rows($ejecuta);
			if($row >0){
				$respuesta = 1;

			}
			echo $respuesta;
		}
	break;

	case 'agregacbodega':
		if(!empty($_POST)){
			$idProducto = $_POST['idProducto'];
			$cantidad = $_POST['cantidad'];
			$opcion = 'SUMA';
			echo cambioBodega($conexion,$idProducto,$cantidad,$opcion);
		}
	break;
	
	case 'cambiaestante':
		if(!empty($_POST)){
		$resp = 2;
		$idProducto = $_POST['idProducto'];
		$cantidad = $_POST['cantidad'];
		$opcion = 'RESTA';
		if(cambioBodega($conexion,$idProducto,$cantidad,$opcion)!= 2){
			$cactual = cantidadEstante($conexion,$idProducto);
			$total = $cantidad + $cactual;
			$sql = "UPDATE ESTANTE SET CANTIDAD =".$total. " WHERE IDPRODUCTOS = ".$idProducto;
			$ejecuta = mysqli_query($conexion, $sql);
			$resp = 1;
		}
		echo $resp;
		}
	break;

	case 'creafactura':
		if(!empty($_POST)){
			$idpersonas = 5;
			$sql = "SELECT IDFACTURATEMP FROM FACTURA_TEMP WHERE IDPERSONAS = ".$idpersonas;
			$ejecuta1 = mysqli_query($conexion, $sql);
			$r = mysqli_fetch_array($ejecuta1);
			$row = mysqli_num_rows($ejecuta1);
			$sql1 = "";
			if ($row == 0) {
				$fechaActual = date('Y-m-d');
				$hora = date("H") . ':' . date("i");
				$sql1 = mysqli_prepare($conexion,"INSERT INTO FACTURA_TEMP(CONSECUTIVOFACTURA,FECHAVENTA,VALORTOTAL,HORAFACTURA,IDPERSONAS) VALUES(1,?,0,?,?)");
				$sql1->bind_param('ssi',$fechaActual,$hora,$idpersonas);
				$sql1->execute();
			}
			$sql1 = "SELECT IDFACTURATEMP FROM FACTURA_TEMP WHERE IDPERSONAS = ".$idpersonas;
			$ejecuta2 = mysqli_query($conexion, $sql);
			$r = mysqli_fetch_array($ejecuta2);	
			
		echo $r['IDFACTURATEMP'];
		}
	break;
	case 'agregaaFactura':
		if(!empty($_POST)){
			$idProducto = $_POST['idProducto'];
			$factura = $_POST['factura'];
			$precio = valorProducto($conexion,$idProducto);
			$sql1 = 'INSERT INTO DETALLE_FACTURA_TEMP(IDFACTURATEMP,IDPRODUCTOS,VALORPRODUCTOS,CANTIDAD,VTOTAL)VALUES('.$factura.','.$idProducto.','.$precio.',1,'.$precio.') ';
			$ejecuta1 = mysqli_query($conexion,$sql1);
		echo $ejecuta1;	
		}
	break;
	case 'totalFactura':
		if(!empty($_POST)){
			$factura = $_POST['factura'];
			$sql = 'SELECT SUM(VTOTAL) TOTAL FROM DETALLE_FACTURA_TEMP WHERE IDFACTURATEMP ='. $factura;
			$sqleje = mysqli_query($conexion,$sql);
			$ejecuta = mysqli_fetch_array($sqleje);
			$precio = $ejecuta["TOTAL"];
			echo number_format($precio,0,",",".");
			
		}
	break;
	case 'pagaFactura':
		if(!empty($_POST)){
			$factura = $_POST['factura'];
			$tipo = $_POST['tipo'];
			$idcliente = $_POST['idcliente'];
			$idPersonas = 5;

			
			$actualizaFecha = mysqli_prepare($conexion,"UPDATE FACTURA_TEMP SET FECHAVENTA = ?, HORAFACTURA = ? WHERE IDFACTURATEMP = ?");
			$actualizaFecha ->bind_param("ssi",$fechaActual,$hora,$factura);
			$ejeI = $actualizaFecha->execute();
			$idfactura = existsfacturaxcliente($conexion,$idcliente);

			if(($tipo == 'PAGADO')|| ($tipo == 'CREDITO' && $idfactura == 0)){
				if($tipo == 'PAGADO'){
					$pagado = 1;
				}else{
					$pagado = 0;
				}

				$crear_factura = mysqli_prepare( $conexion,"INSERT INTO FACTURA (CONSECUTIVOFACTURA, FECHAVENTA, VALORTOTAL, HORAFACTURA, IDPERSONAS,IDCLIENTE, PAGADO)
				SELECT CONSECUTIVOFACTURA, FECHAVENTA, VALORTOTAL, HORAFACTURA, ?,?,? FROM FACTURA_TEMP WHERE IDFACTURATEMP= ?");
				$crear_factura->bind_param("iidi", $idPersonas,$idcliente,$pagado,$factura);
				$crear_factura_ok = $crear_factura->execute();
				if(!$crear_factura_ok) {
					http_response_code(500);
					echo "No fue posible crear la factura";
					return;
				}
				$id_factura = $crear_factura->insert_id;
			}else{
				$pagado = 0;
				$totalactual = buscatotalfacturaactual($conexion,$idfactura);
				$id_factura = $idfactura;
			}
		
			$crear_detalle = mysqli_prepare(
				$conexion,
				"INSERT INTO DETALLE_FACTURA (IDFACTURA, IDPRODUCTOS, VALORPRODUCTOS, CANTIDAD, VTOTAL,FECHAVENTA,HORAFACTURA)
				SELECT ?, IDPRODUCTOS, VALORPRODUCTOS, CANTIDAD, VTOTAL,FECHAVENTA,HORAFACTURA FROM DETALLE_FACTURA_TEMP"
			);
			$crear_detalle->bind_param("d", $id_factura);
			$crear_detalle_ok = $crear_detalle->execute();

			if (!$crear_detalle_ok) {
				http_response_code(500);
				echo "No fue posible crear el detalle de la factura";
				return;
			}

			
			// inicio Actualiza cantidad
			$sql = mysqli_query($conexion,"SELECT IDPRODUCTOS, CANTIDAD FROM DETALLE_FACTURA_TEMP WHERE IDFACTURATEMP = ".$factura);
			while($fila = mysqli_fetch_assoc($sql)){
				$idproductodet = $fila['IDPRODUCTOS'];
				$cantidadactual = cantidadEstante($conexion,$idproductodet);
				$cantidad = $fila['CANTIDAD'];
				$cantfinal = $cantidadactual - $cantidad;
				$sql1 = mysqli_prepare($conexion,'UPDATE ESTANTE SET CANTIDAD = ? WHERE IDPRODUCTOS = ? ');
				$sql1 -> bind_param("ii",$cantfinal,$idproductodet);
				$sql1 ->execute();
			}
			//fin Actualiza cantidad
			

			// inicio Actualiza el total
			$sqleje = mysqli_query($conexion, "SELECT SUM(DFT.VTOTAL) TOTAL FROM DETALLE_FACTURA_TEMP DFT INNER JOIN FACTURA_TEMP FT ON FT.IDFACTURATEMP = DFT.IDFACTURATEMP WHERE FT.IDPERSONAS = ".$idPersonas);
			$ejecuta = mysqli_fetch_array($sqleje);

			if(($tipo == 'PAGADO')|| ($tipo == 'CREDITO' && $idfactura == 0)){
				$precio = $ejecuta["TOTAL"];
			}else{
				$precio = $ejecuta["TOTAL"] + $totalactual; 
			}
			
			$sql1 = 'UPDATE FACTURA SET VALORTOTAL  =' . $precio . ' WHERE IDFACTURA =' . $id_factura;
			$sql1eje = mysqli_query($conexion, $sql1);

			//fin actualiza el total

			//inicio vacia tablas temp
			if(!$sql1eje){
				http_response_code(500);
				echo "No fue posible actualizar el valor";
				return;
			}else{
			mysqli_query($conexion, "DELETE FROM DETALLE_FACTURA_TEMP WHERE IDFACTURATEMP = ".$factura);
			mysqli_query($conexion, "DELETE FROM FACTURA_TEMP WHERE IDFACTURATEMP = ".$factura);
			echo 1;

			}
			
			//fin vacia tablas temp
			
		}
	break;
	case 'actualizaTotal':
		if(!empty($_POST)){
			$idProducto = $_POST['idProducto'];
			$cantidad  = $_POST['cantidad'];
			$precio = valorProducto($conexion,$idProducto);
			$total = $cantidad * $precio;
			$idtabla = idfactempxidproduc($conexion,$idProducto);
			echo actualizarTotal($conexion,$cantidad,$total,$idtabla);
			
		}
	break;
	case 'insertaAuto':
		if(!empty($_POST)){
			$codigoBarras = $_POST['codigoBarras'];
			$factura = $_POST['factura'];
			$idProducto = buscaIdxCodi($conexion,$codigoBarras);
			$precio = valorProducto($conexion,$idProducto);

			$sqlexiste = 'SELECT * FROM DETALLE_FACTURA_TEMP WHERE IDPRODUCTOS = '.$idProducto;
			$ejecutaexiste = mysqli_query($conexion,$sqlexiste);
			$row = mysqli_num_rows($ejecutaexiste);
			

			if($row > 0){
				$cant = cantidadProducto($conexion,$idProducto);
				$cantidad = $cant + 1;
				$total = $precio * $cantidad;
				$idtabla = idfactempxidproduc($conexion,$idProducto);
				echo actualizarTotal($conexion,$cantidad,$total,$idtabla);

			}else{
				$cantidad = 1;
				
				//Se envia en el bind_param 2 veces $precio por lo que está validando que el producto no exista entonces el precio es solo de 1 producto 		
				$sql1 = "";
				$sql1 = mysqli_prepare($conexion,"INSERT INTO DETALLE_FACTURA_TEMP(IDFACTURATEMP,IDPRODUCTOS,VALORPRODUCTOS,CANTIDAD,VTOTAL,FECHAVENTA,HORAFACTURA)VALUES(?,?,?,?,?,?,?)");
				$sql1->bind_param('iiiiiss',$factura,$idProducto,$precio,$cantidad,$precio,$fechaActual,$hora);
				$ejecuta1 = $sql1-> execute();
					if($ejecuta1){
						echo 1;
					}else{
						echo 2;
					}
			}
		}
		
	break;
	case 'eliminaProdTemp':
	if(!empty($_POST)){
		$idProducto = $_POST['idProducto'];
		$sql = 'DELETE FROM DETALLE_FACTURA_TEMP WHERE IDPRODUCTOS = '.$idProducto;
		$ejecuta1 = mysqli_query($conexion,$sql);
	}
		echo 1;
	break;
	case 'ProductosTemp':
		if(!empty($_POST)){
			$sql = 'SELECT * FROM DETALLE_FACTURA_TEMP';
			$ejecuta = mysqli_query($conexion,$sql);
			$row = mysqli_num_rows($ejecuta);
		echo $row;
		}
	break;
	case 'datosEmpresa':
		if(!empty($_POST)){
		$datos = array();
		$idempresa = $_POST['idempresa'];
		$sql = "SELECT E.IDEMPRESA IDEMPRESAS,E.NOMBRES NOMBREEMPRESA, P.IDPERSONAS IDPERSONAS,CONCAT(P.NOMBRES,' ',P.APELLIDOS)NOMBRECOMPLETO FROM EMPRESA E
			LEFT JOIN PERSONAS P
			ON P.IDEMPRESA = E.IDEMPRESA WHERE E.IDEMPRESA = ".$idempresa;
			$ejecuta = mysqli_query($conexion,$sql);
		while($fila = mysqli_fetch_assoc($ejecuta)){
			$datos[] = $fila;
		}
		echo json_encode($datos, JSON_FORCE_OBJECT);
		}
	break;
	case 'insertaVariedadProd':
	if(!empty($_POST)){
		$idProductos =  $_POST['idProductos'];
		$codigoBarras =  $_POST['codigoBarras'];
		$sabor = strtoupper($_POST['sabor']); 



		$sql= "INSERT INTO DETALLE_PRODUCTOS(IDPRODUCTOS,CODIGOBARRAS,SABOR) VALUES(".$idProductos.",'".$codigoBarras."','".$sabor."')";
		$ejecuta = mysqli_query($conexion,$sql);
		echo $idProductos;

	}

	break;
	case'eliminarVariedadProd':
		$IdDetProductos = $_POST['IdDetProductos'];
		$idProductos = $_POST['idProductos'];
		$sql = 'DELETE FROM DETALLE_PRODUCTOS WHERE IDDETALLE_PRODUCTO = '. $IdDetProductos;
		$ejecuta = mysqli_query($conexion,$sql);
		if($ejecuta){
			echo $idProductos;	
		}else{
			echo $ejecuta;
		}
		

	break;

	case 'updateVariedadProd':
	if(!empty($_POST)){
		$idProductos =  $_POST['idProductos'];
		$idProductosUp =  $_POST['idProductosUp'];
		$codigoBarras =  $_POST['codigoBarras'];
		$sabor = strtoupper($_POST['sabor']);

		$sql = "UPDATE DETALLE_PRODUCTOS SET CODIGOBARRAS= '".$codigoBarras."',SABOR= '".$sabor."' WHERE IDDETALLE_PRODUCTO = ". $idProductosUp;
		$ejecuta = mysqli_query($conexion,$sql);
		if($ejecuta){
			echo $idProductos;
		}else{
			echo $vida1 =  mysqli_error($conexion);
		}
		

	}

	break;
	case 'nuevaempresa':
	if(!empty($_POST)){
	$nombreempresa = strtoupper($_POST['nombreempresa']);
	$sql = "";
	$sql = mysqli_prepare($conexion,"INSERT INTO EMPRESA(NOMBRES) VALUES(?)");
	$sql-> bind_param('s',$nombreempresa);
	$execute = $sql-> execute();
	if($execute){
		echo 1;
	}else{
		echo 2;
	}

	}
	break;
	case 'registrarpago':
	if(!empty($_POST)){
		$empresa =  $_POST['empresa'];
		$valorpago =  $_POST['valorpago'];
		$observacion = strtoupper($_POST['observacion']);
		$fechaActual = date( 'Y-m-d' );
		$sql = "";
		$sql = mysqli_prepare($conexion,"INSERT INTO PAGOPEDIDOS(IDEMPRESA,FECHAPAGO,VALORPAGO,OBSERVACION) VALUES(?,?,?,?)");
		$sql->bind_param('isis',$empresa,$fechaActual,$valorpago,$observacion);
		$execute = $sql-> execute();
		if($execute){
			echo 1;
		}else{
			echo 2;
		}

	}

	break;

	case 'agregacliente':
		if(!empty($_POST)){
			
			$nombres = strtoupper($_POST['nombres']);
			$apellidos = strtoupper($_POST['apellidos']);
			$cedulacliente = $_POST['cedulacliente'];
			$sql="";
			$sql = mysqli_prepare($conexion,"INSERT INTO PERSONAS(IDEMPRESA,NOMBRES,APELLIDOS,CEDULA,TIPO) VALUES(36,?,?,?,2)");
			$sql->bind_param('sss',$nombres,$apellidos,$cedulacliente);
			$execute = $sql->execute();
			$id_cliente = $sql->insert_id;
			if($execute){
				
				echo $id_cliente;
			}else{
				echo 2;
			}

		}
	break;

	case 'agregaprovedor':
		if(!empty($_POST)){
			//NECESITA RECIBIR LA EMPRESA DEL PROVEDOR
			$nombre = strtoupper($_POST['nombres']);
			$apellidos = strtoupper($_POST['apellidos']);
			$cedulacliente = $_POST['cedulacliente'];
			$sql="";
			$sql = "INSERT INTO PERSONAS(IDEMPRESA,NOMBRES,CEDULA,TIPO) VALUES(1,'".$nombre."','".$cedulacliente."',2)";
			$slq = mysqli_query($conexion,$sql);
	

			$idcliente = mysqli_insert_id($conexion);
		
			echo $idcliente;
		}
	break; 
	case'agregarabonofactura':
		if(!empty($_POST)){
			$idfactura = $_POST['idfactura'];
			$valorabono = $_POST['valorabono'];
			$valorfacturaactual = buscatotalfacturaactual($conexion,$idfactura);

			if($valorabono <= $valorfacturaactual){


				//INICIO inserción tabla abonos
				$sql = "";
				$sql = mysqli_prepare($conexion,"INSERT INTO ABONOS(IDFACTURA,FECHAABONO,HORAABONO,VALORABONO,CERRADO) VALUES(?,?,?,?,0)");
				$sql->bind_param('issi',$idfactura,$fechaActual,$hora,$valorabono);
				$execute = $sql->execute();
				//FIN inserción tabla abonos
				if($execute){
					//Si se realiza la inserción del abono se procede a restar el abono de la factura
					$nuevovalor = $valorfacturaactual - $valorabono;
					$sql1 = "";
					$sql1 = mysqli_prepare($conexion,"UPDATE FACTURA SET VALORTOTAL = ? WHERE IDFACTURA = ?");
					$sql1->bind_param('ii',$nuevovalor,$idfactura);
					$execute1 = $sql1->execute();
					echo $execute1;
				}
			}else{
				echo 2;
			}

		}
	break;
	case'desactivaproducto':
	if(!empty($_POST)){
		//recepcion de variables
		$idproducto = $_POST['idproducto'];
		if(cantidadEstante($conexion,$idproducto) == 0){
			//creacion del update
			$sql ="";
			$sql = mysqli_prepare($conexion,"UPDATE PRODUCTOS SET ACTIVO = 0 WHERE IDPRODUCTOS = ?");
			$sql->bind_param('i',$idproducto);
			$execute = $sql->execute();
			if($execute){
				echo 1;
			}else{
				echo 2;
			}
		}else{
			echo 3;
		}
		

	}

	break;
	case'activaproducto':
	if(!empty($_POST)){
		//recepcion de variables
		$idproducto = $_POST['idproducto'];

		//creacion del update
		$sql ="";
		$sql = mysqli_prepare($conexion,"UPDATE PRODUCTOS SET ACTIVO = 1 WHERE IDPRODUCTOS = ?");
		$sql->bind_param('i',$idproducto);
		$execute = $sql->execute();
		if($execute){
			echo 1;
		}else{
			echo 2;
		}

	}

	break;


	default:

	break;
}

?>