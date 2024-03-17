
<?php 
include("conexion.php");

$tabla = "";
$sql = "SELECT P.IDPRODUCTOS,  P.NOMBREPRODUCTO, P.MARCA, P.MEDIDA, P.UNIDAD,B.CANTIDAD BODEGA,E.CANTIDAD ESTANTE FROM PRODUCTOS P
INNER JOIN BODEGA B ON 
B.IDPRODUCTOS = P.IDPRODUCTOS
INNER JOIN ESTANTE E ON 
E.IDPRODUCTOS = P.IDPRODUCTOS
WHERE P.NOMBREPRODUCTO not like '%(ina)%'
ORDER BY P.NOMBREPRODUCTO,
             P.MARCA,
             P.MEDIDA
LIMIT 15";

if(isset($_POST['dato'])){
    $dato = $_POST['dato'];
    $sql = "SELECT P.IDPRODUCTOS, P.NOMBREPRODUCTO,P.MARCA, P.MEDIDA, P.UNIDAD,B.CANTIDAD BODEGA,E.CANTIDAD ESTANTE FROM PRODUCTOS P
INNER JOIN BODEGA B ON 
B.IDPRODUCTOS = P.IDPRODUCTOS
INNER JOIN ESTANTE E ON 
E.IDPRODUCTOS = P.IDPRODUCTOS
INNER JOIN DETALLE_PRODUCTOS DP
ON DP.IDPRODUCTOS = P.IDPRODUCTOS
            WHERE P.NOMBREPRODUCTO LIKE '%".$dato."%'
            OR P.MARCA LIKE '%".$dato."%'
            OR DP.CODIGOBARRAS LIKE '%".$dato."%'
            GROUP BY P.NOMBREPRODUCTO,
            P.MARCA,
            P.MEDIDA,
            P.UNIDAD
            ORDER BY P.NOMBREPRODUCTO,
             P.MARCA,
             P.MEDIDA";
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
        <td> <button type='button' class=\"producto btn btn-link\" data-id=\"{$idproducto}\" data-bs-toggle='modal' data-bs-target='#editProducto'>
  {$nombre}
</button></td>
<td> {$marca} </td>
        <td> {$medida} {$unidad} </td>
        <td> {$bodega} </td>
        <td> {$estante} </td>
        <td> <div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"><button class=\"bodega btn btn-info\" data-bs-toggle=\"modal\" data-bs-target=\"#agregaBodegaModal\" data-id=\"{$idproducto}\">AGREGAR</button> <button class=\"estante btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#agregaEstanteModal\"  data-id=\" {$idproducto} \">AGREGAR</button></div>
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
                <input class="form-control" type="number" name="txtCantidadme" id="txtCantidadme">
                <input class="form-control" type="hidden"  name="txtIdProductome" id="txtIdProductome">
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button id="btnGuardarBodega" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<script>
  $(function(){
    $( "#btnGuardarBodega" ).one('click',function() {
      $("#btnGuardarBodega").hide();
      const idProducto = $('#txtIdProductome').val();
    const cantidad = $('#txtCantidadme').val();
      if(cantidad==""){
          toastr.error("La CANTIDAD no puede ser vacia", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          
          return false;
        }else{

          $.ajax({
            url: '../metodos/consultasJS.php',
            type: 'POST',
            data: {
              accion:'agregacbodega',
              idProducto:idProducto,
              cantidad:cantidad
            },
            success: function(respuesta){
              if (respuesta == 1) {
                toastr.success("Cantidad actualizada",{
                "progressBar":true,
                "closeButton":true,
                "timeOut":500
                });
                window.setTimeout(function(){window.open("inventario.php","_self");}, 1000);
              }else{
                toastr.info("Revise los datos ingresados", "Alerta!",{
                "progressBar":true,
                "closeButton":true,
                "timeOut":2000
                });
              }      
            }
          });

        }  
});
  })
</script>


<script>
  $(function(){
    $("#btnGuardarEstante").one('click',function() {
      $("#btnGuardarEstante").hide();
    const idProducto = $('#txtIdProductomes').val();
    const cantidad = $('#txtCantidadmes').val();  
       if(cantidad==""){
          toastr.error("La CANTIDAD no puede ser vacia", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }else{

          $.ajax({
            url: '../metodos/consultasJS.php',
            type: 'POST',
            data: {
              accion:'cambiaestante',
              idProducto:idProducto,
              cantidad:cantidad
            },
            success: function(respuesta){
              if (respuesta == 1) {
                toastr.success("Cantidad actualizada",{
                "progressBar":true,
                "closeButton":true,
                "timeOut":500
                });
                window.setTimeout(function(){window.open("inventario.php","_self");}, 1000);
              }else if(respuesta == 2){
                toastr.error("verifique la CANTIDAD a cambiar", "Error!",{
                "progressBar":true,
                "closeButton":true,
                "timeOut":4000
                });
              }else{
                toastr.info("Revise los datos ingresados", "Alerta!",{
                "progressBar":true,
                "closeButton":true,
                "timeOut":2000
                });
              }       
            }
          });

        }
});
  })

</script>

