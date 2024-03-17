<?php 
include("conexion.php");
$tabla = "";
$dato = $_POST['dato'];
$sql = "SELECT CONCAT(P.NOMBREPRODUCTO,' ',P.MARCA,' ',P.MEDIDA,P.UNIDAD) PRODUCTO, DFT.IDPRODUCTOS, DFT.VALORPRODUCTOS, DFT.CANTIDAD,DFT.VTOTAL FROM DETALLE_FACTURA_TEMP DFT
INNER JOIN PRODUCTOS P
ON DFT.IDPRODUCTOS = P.IDPRODUCTOS
WHERE IDFACTURATEMP =".$dato.
" ORDER BY DFT.IDDETALLEFACTURATEMP DESC";
$ejecutar = mysqli_query($conexion,$sql);
$row = mysqli_num_rows($ejecutar);
if ($row > 0) {
$tabla.=
 '<div class="table-responsive">
 <table class="table  table-hover">
  <thead>
    <tr >
      <th>PRODUCTO</th>
      <th>CANTIDAD</th>
      <th>VALOR UNIDAD</th>
      <th>TOTAL</th>
      <th class="text-center">ELIMINAR</th>
    </tr>
  </thead>
        <tbody>';
while($fila = mysqli_fetch_array($ejecutar)){
    $producto = $fila['PRODUCTO'];
    $cantidad = $fila['CANTIDAD'];
    $idProducto = $fila['IDPRODUCTOS'];
    $valorproductos = number_format($fila['VALORPRODUCTOS'],0,",",".");
    $valor = number_format($fila['VTOTAL'],0,",",".");
    $tabla.= 
    '<tr>
        <td> '. $producto .'</td>
        <td><input type="number" value="'. $cantidad .'" class="txtCantidadProducto" id="'.$idProducto.'"></td>
        <td class="text-right text-success">$' .$valorproductos.'</td>
        <td class="text-right text-success">$' .$valor.'</td>
        <td class="text-center"> <button id="'.$idProducto.'" class="btn btn-danger btn-sm eliminar"><i class="fa-solid fa-xmark"></i> ELIMINAR</button></td>
    </tr>';             
}
$tabla.=
        '</tbody>
      </table>
      </div>';
}
echo $tabla;
?>



