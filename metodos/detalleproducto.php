<?php 
date_default_timezone_set('America/Bogota');
  
include('conexion.php');
include('funciones.php');

$accion = $_POST['accion'];
$fechaActual = date('Y-m-d');
$hora = date("H").':'.date("i");
$respuesta = "";
switch ($accion) {
	case 'detalleProductos':
      $idProducto = $_POST['idProducto'];
      $sql = "";
      $sql = mysqli_prepare($conexion,"SELECT NOMBREPRODUCTO,MARCA,MEDIDA,UNIDAD FROM PRODUCTOS WHERE IDPRODUCTOS = ?");
      $sql->bind_param('i',$idProducto);
      $execute = $sql->execute();
      $ejec = $sql->get_result();
      $row =mysqli_fetch_assoc($ejec);

       $respuesta .= "
       
       <div class=\"container mt-4\">
    <div class=\"row\">
        <div class=\"col\">
        <div class=\"form-group\">
            <label>NOMBRE DEL PRODUCTO</label>
            <input class=\"form-control\" type=\"text\" disabled value=\"{$row['NOMBREPRODUCTO']}\" name=\"txtNombre\" id=\"txtNombre\">
          </div>
        </div>

        <div class=\"col\">
          <div class=\"form-group\">
            <label>MARCA</label>
            <input class=\"form-control\" type=\"text\" disabled value=\"{$row['MARCA']}\" name=\"txtMarca\" id=\"txtMarca\">
          </div>
        </div>

        <div class=\"row\">
        <div class=\"col\">
        <div class=\"form-group\">
            <label>MEDIDA</label>
            <input class=\"form-control\" type=\"text\" disabled value=\"{$row['MEDIDA']}\" name=\"txtMedida\" id=\"txtMedida\">
          </div>
        </div>

        <div class=\"col\">
          <div class=\"form-group\">
            <label>UNIDAD</label>
            <input class=\"form-control\" type=\"text\" disabled value=\"{$row['UNIDAD']}\" name=\"txtUnidad\" id=\"txtUnidad\">
          </div>
        </div>
  </div>
  </div>
</div>
       ";   

$sql = "SELECT P.IDPRODUCTOS, DP.IDDETALLE_PRODUCTO , DP.CODIGOBARRAS, DP.SABOR 
        FROM PRODUCTOS P
        INNER JOIN DETALLE_PRODUCTOS DP
        ON DP.IDPRODUCTOS = P.IDPRODUCTOS
        WHERE P.IDPRODUCTOS =".$idProducto." ORDER BY DP.SABOR";
            $ejecutar = mysqli_query($conexion,$sql);
            $row = mysqli_num_rows($ejecutar);

        $respuesta .="
        <table class=\"table table-hover\">
            <thead>
                <tr>
                    <th >CODIGO DE BARRAS</th>
                    <th >SABOR</th>
                    <th class=\"text-end\">OPCIONES</th>
                </tr>
                <tbody>";
        while($fila = mysqli_fetch_array($ejecutar)){
     $respuesta.="              
   <tr>
        <td>{$fila['CODIGOBARRAS']}</td>
        <td >{$fila['SABOR']}</td>
        <td class=\"text-end\"><div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"><button class=\"editar btn btn-warning\" data-bs-toggle=\"modal\" data-bs-target=\"#editaVariProd\" data-id=\"{$fila['IDDETALLE_PRODUCTO']}\"><i class=\"fa-regular fa-pen-to-square\"></i> EDITAR</button> <button class=\"eliminar btn btn-danger\" data-bs-toggle=\"modal\" data-bs-target=\"#eliminaVariProd\" data-id=\"{$fila['IDDETALLE_PRODUCTO']}\"><i class=\"fa-solid fa-trash\"></i> ELIMINAR</button></div></td>
        </tr>";
}  
$respuesta .="
                </tbody>
            </thead>
        </table>
";
    echo $respuesta;
break;
case 'editaVarieProdu':
    $IdDetProductos = $_POST['IdDetProductos'];
    $sql = "";
    $sql = mysqli_prepare($conexion,"SELECT SABOR,CODIGOBARRAS FROM DETALLE_PRODUCTOS WHERE IDDETALLE_PRODUCTO = ?");
    $sql->bind_param('i',$IdDetProductos);
    $execute = $sql->execute();
    $ejecuta = $sql->get_result();

    $fila = mysqli_fetch_assoc($ejecuta);
    $respuesta .="
    <div class=\"row\">
          <div class=\"col\">
          <div class=\"form-group\">
            <label>código de barras</label>
            <input value=\"{$IdDetProductos}\"  type=\"text\" hidden name=\"txtidProductos\" id=\"txtidProductosUp\">
            <input class=\"form-control\" value=\"{$fila['CODIGOBARRAS']}\" type=\"text\" name=\"txtCodigoBarrasUp\" id=\"txtCodigoBarrasUp\">
          </div>
          </div>
           </div>
           <div class=\"row\">
             <div class=\"col\">
              <div class=\"form-group\">
                <label>variedad</label>
                <input class=\"form-control\" type=\"text\" value=\"{$fila['SABOR']}\" name=\"txtSaborUp\" id=\"txtSaborUp\">
              </div>
             </div>
           </div>
    ";
    echo $respuesta;

break;
case'eliminarVariedadProd':
		$IdDetProductos = $_POST['IdDetProductos'];
		$idProductos = $_POST['idProductos'];
		$sql = "";
		$sql = mysqli_prepare($conexion,"DELETE FROM DETALLE_PRODUCTOS WHERE IDDETALLE_PRODUCTO =?");
		$sql->bind_param('i',$IdDetProductos);
		$execute = $sql->execute();
		if($execute){
			echo $idProductos;	
		}else{
			echo $execute;
		}
break;
case 'insertaVariedadProd':
	if(!empty($_POST)){
		$idProductos =  $_POST['idProductos'];
		$codigoBarras =  $_POST['codigoBarras'];
		$sabor = strtoupper($_POST['sabor']);
		$sql = "";
		$sql = mysqli_prepare($conexion,"INSERT INTO DETALLE_PRODUCTOS(IDPRODUCTOS,CODIGOBARRAS,SABOR) VALUES(?,?,?) ");
		$sql->bind_param('iss',$idProductos,$codigoBarras,$sabor);
		$execute = $sql->execute();
		if($execute){
			echo $idProductos;
		}
	}

break;
case 'updateVariedadProd':
	if(!empty($_POST)){
		$idProductos =  $_POST['idProductos'];
		$idProductosUp =  $_POST['idProductosUp'];
		$codigoBarras =  $_POST['codigoBarras'];
		$sabor = strtoupper($_POST['sabor']);
		$sql = "";
		$sql = 	mysqli_prepare($conexion,"UPDATE DETALLE_PRODUCTOS SET CODIGOBARRAS = ?,SABOR = ? WHERE IDDETALLE_PRODUCTO = ?");
		$sql->bind_param('ssi',$codigoBarras,$sabor,$idProductosUp);
		$execute = $sql->execute();
		if($execute){
			echo $idProductos;
		}else{
			echo $vida1 =  mysqli_error($conexion);
		}
	}
break;

default:
	// code...
break;
}
?>