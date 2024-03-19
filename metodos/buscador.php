<?php 
include("conexion.php");

$tabla = "";
$sql = "SELECT * FROM PRODUCTOS WHERE IDPRODUCTOS = 'ABCD' ORDER BY NOMBREPRODUCTO ";

if(isset($_POST['dato'])){
    $dato = $_POST['dato'];
    $sql = "SELECT P.ACTIVO, P.VALOR,P.MEDIDA,P.UNIDAD, P.NOMBREPRODUCTO,P.MARCA,DP.CODIGOBARRAS FROM PRODUCTOS P
                    LEFT JOIN  DETALLE_PRODUCTOS DP ON DP.IDPRODUCTOS = P.IDPRODUCTOS 
            WHERE (P.ACTIVO = 1) AND(P.NOMBREPRODUCTO LIKE '%".$dato."%' 
                  OR P.MARCA LIKE '%".$dato."%')
                  OR (DP.CODIGOBARRAS LIKE '".$dato."%')
            GROUP BY P.NOMBREPRODUCTO,
            P.MARCA,P.MEDIDA
            ORDER BY P.NOMBREPRODUCTO, P.MARCA, CAST(P.MEDIDA AS UNSIGNED)
            ";
}

$ejecutar = mysqli_query($conexion,$sql);
$row = mysqli_num_rows($ejecutar);
if ($row > 0) {

$tabla.=
 "<table class=\"table table-hover\">
        <thead>
          <tr>
            <th>CODIGO DE BARRAS</th>
            <th>NOMBRE</th>
            <th>MARCA</th>
			<th>DESCRIPCION</th>
            <th>PRECIO</th>
          </tr>
        </thead>
        <tbody>";
          
while($fila = mysqli_fetch_array($ejecutar)){
    $nombre = $fila['NOMBREPRODUCTO'];
    $marca = $fila['MARCA'];
    $medida = $fila['MEDIDA'];
    $unidad = $fila['UNIDAD'];
    $valor = number_format($fila['VALOR'],0,",",".");
    $tabla.= 
    "<tr>
        <td>{$fila['CODIGOBARRAS']} </td>
        <td>{$nombre}</td>
        <td>{$marca}</td>
        <td>{$medida} {$unidad}</td>
        <td class=\"text-right text-success\">$ {$valor}</td>
        </tr>";
              
}
$tabla.=
        "</tbody>
      </table>" ;
}else{
    $tabla = "NO SE ENCONTRÓ PRODUCTO";
}
echo $tabla;
?>




