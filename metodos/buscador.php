<?php 
include("conexion.php");

$tabla = "";
$sql = "SELECT * FROM PRODUCTOS WHERE IDPRODUCTOS = 'ABCD' ORDER BY NOMBREPRODUCTO ";

if(isset($_POST['dato'])){
    $dato = $_POST['dato'];
    $sql = "SELECT P.VALOR,P.MEDIDA,P.UNIDAD, P.NOMBREPRODUCTO,P.MARCA,DP.CODIGOBARRAS FROM PRODUCTOS P
                    LEFT JOIN  DETALLE_PRODUCTOS DP ON DP.IDPRODUCTOS = P.IDPRODUCTOS 
            WHERE P.NOMBREPRODUCTO LIKE '%".$dato."%' 
                  OR P.MARCA LIKE '%".$dato."%'
                  OR DP.CODIGOBARRAS LIKE '%".$dato."%'
            ORDER BY P.NOMBREPRODUCTO, P.MARCA";
}

$ejecutar = mysqli_query($conexion,$sql);
$row = mysqli_num_rows($ejecutar);
if ($row > 0) {

$tabla.=
 '<table class="table table-hover">
        <thead>
          <tr>
            <th>CODIGO DE BARRAS</th>
            <th>NOMBRE</th>
            <th>MARCA</th>
			<th>DESCRIPCION</th>
            <th>PRECIO</th>
          </tr>
        </thead>
        <tbody>';
          
while($fila = mysqli_fetch_array($ejecutar)){
    $nombre = $fila['NOMBREPRODUCTO'];
    $marca = $fila['MARCA'];
    $medida = $fila['MEDIDA'];
    $unidad = $fila['UNIDAD'];
    $valor = number_format($fila['VALOR'],0,",",".");
    $tabla.= 
    '<tr>
        <td> '. $fila['CODIGOBARRAS'] .'</td>
        <td> '. $nombre .'</td>
        <td>' . $marca .'</td>
        <td>' .$medida ." " . $unidad . '</td>
        <td class="text-right text-success">$' .$valor.'</td>
        </tr>';
              
}
$tabla.=
        '</tbody>
      </table>';
}else{
    $tabla = "NO SE ENCONTRÓ PRODUCTO";
}
echo $tabla;
?>




<?php
/*
INICIO MODAL UPDATE
 */
?>
 <div class="modal fade" id="update" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Nombre</label>
            <input class="form-control" type="text" name="txtNombre" id="txtNombre">
          </div>
          </div>
           </div>
           <div class="row">
             <div class="col">
              <div class="form-group">
                <label>Medida</label>
                <input class="form-control" type="text" name="txtMedida" id="txtMedida">
              </div>
             </div>
           </div>
           <div class="row">
             <div class="col">
              <div class="form-group">
                <label>precio</label>
                <input class="form-control" type="number" name="txtPrecio" id="txtPrecio">
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btnGuardar" class="btn btn-primary">GUARDAR</button>
      </div>
      </form>
    </div>
  </div>
</div>



<?php
/*
FIN MODAL UPDATE
 */
?>