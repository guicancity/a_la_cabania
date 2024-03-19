
<?php 
include("conexion.php");

$tabla = "";
$sql = "SELECT P.IDPRODUCTOS,  P.NOMBREPRODUCTO, P.MARCA, P.MEDIDA, P.UNIDAD,B.CANTIDAD BODEGA,E.CANTIDAD ESTANTE FROM PRODUCTOS P
INNER JOIN BODEGA B ON 
B.IDPRODUCTOS = P.IDPRODUCTOS
INNER JOIN ESTANTE E ON 
E.IDPRODUCTOS = P.IDPRODUCTOS
WHERE P.ACTIVO = 1
ORDER BY P.NOMBREPRODUCTO,
             P.MARCA,
             P.MEDIDA
LIMIT 10";

if(isset($_POST['dato'])){
    $dato = $_POST['dato'];
    $tipo = $_POST['tipo'];
    $sql = "";
    if(($tipo == 1) && (strlen($dato) > 3 )){
      $sql = "SELECT P.ACTIVO, P.IDPRODUCTOS, P.NOMBREPRODUCTO,P.MARCA, P.MEDIDA, P.UNIDAD,B.CANTIDAD BODEGA,E.CANTIDAD ESTANTE FROM PRODUCTOS P
      INNER JOIN BODEGA B ON 
      P.IDPRODUCTOS = B.IDPRODUCTOS
      INNER JOIN ESTANTE E ON 
      P.IDPRODUCTOS = E.IDPRODUCTOS 
      INNER JOIN DETALLE_PRODUCTOS DP
      ON DP.IDPRODUCTOS = P.IDPRODUCTOS
            WHERE (P.ACTIVO = 1) 
            AND (DP.CODIGOBARRAS LIKE '".$dato."%')
            GROUP BY P.NOMBREPRODUCTO,
            P.MARCA,
            P.MEDIDA,
            P.UNIDAD
            ORDER BY P.NOMBREPRODUCTO,
             P.MARCA,
             P.MEDIDA";

    }else{
      $sql = "SELECT P.ACTIVO, P.IDPRODUCTOS, P.NOMBREPRODUCTO,P.MARCA, P.MEDIDA, P.UNIDAD,B.CANTIDAD BODEGA,E.CANTIDAD ESTANTE FROM PRODUCTOS P
      INNER JOIN BODEGA B ON 
      P.IDPRODUCTOS = B.IDPRODUCTOS
      INNER JOIN ESTANTE E ON 
      P.IDPRODUCTOS = E.IDPRODUCTOS 
      INNER JOIN DETALLE_PRODUCTOS DP
      ON DP.IDPRODUCTOS = P.IDPRODUCTOS
            WHERE (P.ACTIVO = 1) 
            AND (P.NOMBREPRODUCTO LIKE '%".$dato."%'
            OR P.MARCA LIKE '%".$dato."%')
            GROUP BY P.NOMBREPRODUCTO,
            P.MARCA,
            P.MEDIDA,
            P.UNIDAD
            ORDER BY P.NOMBREPRODUCTO,
             P.MARCA,
             P.MEDIDA";

    }

}

$ejecutar = mysqli_query($conexion,$sql);
$row = mysqli_num_rows($ejecutar);
if ($row > 0) {

$tabla.=
 "<table class= \"table table-hover\">
        <thead>
          <tr>
            
            <th>NOMBRE</th>
            <th>MARCA</th>
            <th>MEDIDA</th>
            <th>BODEGA</th>
            <th>ESTANTE</th>
            <th>ACTIVO</th>
            <th>BODEGA / ESTANTE / VARIEDADES</th>

          </tr>
        </thead>
        <tbody>";
          
while($fila = mysqli_fetch_array($ejecutar)){
    $idproducto = $fila['IDPRODUCTOS'];
    $nombre = $fila['NOMBREPRODUCTO'];
    $medida = $fila['MEDIDA'];
    $marca = $fila['MARCA'];    
    $unidad = $fila['UNIDAD'];
    $bodega = $fila['BODEGA'];
    $estante = $fila['ESTANTE'];
    $tabla.= 
    "<tr>
    
        <td> <button type='button' class=\"producto btn btn-link\" data-id=\"{$idproducto}\" data-bs-toggle=\"modal\" data-bs-target=\"#editProducto\">
  {$nombre}
</button></td>
        <td> {$marca} </td>
        <td> {$medida} {$unidad} </td>
        <td> {$bodega} </td>
        <td> {$estante} </td>
        <td> <button class=\"desactivar btn btn-danger\" data-id=\"{$idproducto}\">DESACTIVAR</button></td>
        <td> <div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"><button class=\"bodega btn btn-info\" data-bs-toggle=\"modal\" data-bs-target=\"#agregaBodegaModal\" data-idpb=\"{$idproducto}\">AGREGAR</button> <button class=\"estante btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#agregaEstanteModal\"  data-idpe=\" {$idproducto} \">AGREGAR</button></div>
        <a target=\"_blank\" href=\"../pag/detalleProducto.php?idp= {$idproducto} \" class=\" btn btn-warning\">VARIEDADES</a>
        </td>
        
        </tr>";
              
}
$tabla.=
        "</tbody>
      </table>";
}else{
    $tabla = "NO SE ENCONTRÓ PRODUCTO";
}
echo $tabla;
?>




<?php
/*
INICIO MODAL BODEGA
 */
?>
 <div class="modal fade" id="agregaBodegaModal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">AGREGAR A BODEGA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
             <div class="col">
              <div class="form-group">
                <label>Cantidad</label>
                  <input class="form-control" type="hidden"  name="txtIdProductome" id="txtIdProductome">
                  <input class="form-control" type="number" name="txtCantidadme" id="txtCantidadme">
                  <div class="row">
                    <div class="col">
                      <button id="btnGuardarBodega" class="btn btn-success btn-lg mt-2">Guardar</button>
                    </div>
                  </div>
                
              </div>
            </div>
          </div>
      </div>
      
    </div>
  </div>
</div>


<?php
/*
FIN MODAL BODEGA
 */
?>

<?php
/*
INICIO EDITA PRODUCTO 
 */
?>
 <div class="modal fade" id="editProducto" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">EDITAR PRODUCTO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <input class="form-control" type="text" hidden  name="txtIdProducto" id="txtIdProducto">
        <section id="resultado"></section>
      </div>
      
    </div>
  </div>
</div>


<?php
/*
FIN MODAL EDITA PRODUCTO
 */
?>


<?php
/*
INICIO MODAL ESTANTE
 */
?>
 <div class="modal" id="agregaEstanteModal" tabindex="-1" >
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">AGREGAR AL ESTANTE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
             <div class="col">
              <div class="form-group">
                <label>Cantidad</label>
                <input class="form-control" type="number" name="txtCantidadmes" id="txtCantidadmes">
                <input class="form-control" type="hidden"  name="txtIdProductomes" id="txtIdProductomes">
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        
        <button id="btnGuardarEstante" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<?php
/*
FIN MODAL ESTANTE
 */
?>

<script >
  var myModal = document.getElementById('agregaBodegaModal');
  var myInput = document.getElementById('txtCantidadme');
  myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus();
  });

  var myModal2 = document.getElementById('agregaEstanteModal');
  var myInput2 = document.getElementById('txtCantidadmes');
  myModal2.addEventListener('shown.bs.modal', function () {
  myInput2.focus();
  });

</script>