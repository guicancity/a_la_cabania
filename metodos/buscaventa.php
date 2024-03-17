<?php 
include("conexion.php");

$tabla = "";
if(empty($_POST)){
}else{
    $dato = $_POST['dato'];
    $sql = "SELECT DP.SABOR,DP.CODIGOBARRAS, P.NOMBREPRODUCTO,P.MARCA, P.MEDIDA, P.UNIDAD FROM PRODUCTOS P
            INNER JOIN DETALLE_PRODUCTOS DP ON DP.IDPRODUCTOS = P.IDPRODUCTOS 
            WHERE P.NOMBREPRODUCTO LIKE '%".$dato."%'
                    OR DP.CODIGOBARRAS LIKE '%".$dato."%'
                    OR P.MARCA LIKE '%".$dato."%'
                    ORDER BY P.NOMBREPRODUCTO,
                    P.MARCA
                    ";
$ejecutar = mysqli_query($conexion,$sql);
$row = mysqli_num_rows($ejecutar);

if ($row > 0) {

$tabla.=
 '<table class="table table-hover">
        <thead>
          <tr>
            <th>NOMBRE</th>
            <th>MEDIDA</th>
            <th>AGREGAR CARRITO</th>
          </tr>
        </thead>
        <tbody>';
          
while($fila = mysqli_fetch_array($ejecutar)){
    $codigoBarras = $fila['CODIGOBARRAS'];
    $nombre = $fila['NOMBREPRODUCTO'];
    $medida = $fila['MEDIDA'];
    $marca = $fila['MARCA'];    
    $unidad = $fila['UNIDAD'];
    $sabor = $fila['SABOR'];
    $tabla.= 
    '<tr>
        <td> '. $nombre ." ". $sabor." <b>". $marca.'</b></td>
        <td> '. $medida . " " .$unidad.'</td>
        <td><a class="factura btn btn-success"  data-id="'.$codigoBarras.'">AGREGAR</a></td>
        </tr>';
              
}
$tabla.=
        '</tbody>
      </table>';
}
}
echo $tabla;
?>

