<?php 
date_default_timezone_set('America/Bogota');
  
include('conexion.php');
include('funciones.php');

$accion = $_POST['accion'];
$fechaActual = date('Y-m-d');
$hora = date("H").':'.date("i");
$respuesta = "";
switch ($accion) {
	case 'loaddistribuidor':
	$idEmpresa = $_POST['idEmpresa'];
	$respuesta .="
      <label>DISTRIBUIDOR</label>
      <select class=\"form-select\" id=\"sltIdPersona\">";
      $sql = "SELECT IDPERSONAS, IDEMPRESA,NOMBRES,APELLIDOS FROM PERSONAS WHERE IDEMPRESA = ".$idEmpresa;
      $execute = mysqli_query($conexion,$sql);
      while ($fila =mysqli_fetch_assoc($execute)) {
      	$respuesta .="<option value=\"{$fila['IDPERSONAS']}\">{$fila['NOMBRES']} {$fila['APELLIDOS']}</option>";
      }
    $respuesta.="</select>";
    echo $respuesta;
	break;
	case 'nuevoProducto':
		if (!empty($_POST)){
			$empresa = $_POST['empresa'];
			$codigobarras = $_POST['codigobarras'];
			$persona = $_POST['idPersona'];
			$nombreProducto = strtoupper($_POST['nombreProducto']);
			$marca = strtoupper($_POST['marca']);
			$medida = strtoupper($_POST['medida']);
			$sabor = strtoupper($_POST['sabor']);
			$unidad = strtoupper($_POST['unidad']);
			$precioCompra = $_POST['precioCompra'];
			$valor = $_POST['valor'];
			$cantidad = $_POST['cantidad'];
			$sql ="";
			$sql = mysqli_prepare($conexion, "INSERT INTO PRODUCTOS(
				IDEMPRESA,
				IDPERSONAS,
				NOMBREPRODUCTO,
				MARCA,
				MEDIDA,
				UNIDAD,
				VALOR,
				PRECIOCOMPRA,
				FECHACREACION,
				ACTIVO)
			VALUES(?,?,?,?,?,?,?,?,?,1)");
			$sql->bind_param("iissssiis", $empresa,$persona,$nombreProducto,$marca,$medida,$unidad,$valor,$precioCompra,$fechaActual);
			$execute = $sql->execute();
			$codigo = mysqli_insert_id($conexion);
			if ($execute == 1) {
				$sqlDetalleProducto = "";
				$sqlDetalleProducto = mysqli_prepare($conexion,"INSERT INTO DETALLE_PRODUCTOS(
					IDPRODUCTOS,
					CODIGOBARRAS,
					SABOR)
				VALUES(?,?,?)");
				$sqlDetalleProducto->bind_param("iss",$codigo,$codigobarras,$sabor);
				$ejecutaDetalleProducto = $sqlDetalleProducto->execute();
				$sql2 = "INSERT INTO BODEGA(IDPRODUCTOS,CANTIDAD) VALUES(".$codigo.",0)";
				$ejecuta2 = mysqli_query($conexion,$sql2);
				$sql3 = "";
				$sql3 = mysqli_prepare($conexion,"INSERT INTO ESTANTE(IDPRODUCTOS,CANTIDAD) VALUES(?,?)");
				$sql3->bind_param('ii',$codigo,$cantidad);
				$execute3 = $sql3->execute();
				echo $codigo;
			}else{
				echo 0;
			}
		}
	break;
	case 'buscabarraexistente':
		if (!empty($_POST)) {
			$respuesta = 0;
			$codigobarras = $_POST['codigobarras'];
			$sql = 'SELECT * FROM DETALLE_PRODUCTOS WHERE CODIGOBARRAS = "'.$codigobarras.'"';
			$ejecuta = mysqli_query($conexion, $sql);
			$row = mysqli_num_rows($ejecuta);
			if($row >0){
				$respuesta = 1;
			}else{
				$respuesta = 2;
			}
			echo $respuesta;
		}
	break;	
	default:
		// code...
	break;
}



?>
