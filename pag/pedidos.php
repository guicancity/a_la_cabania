<?php 
require_once('../metodos/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
  require_once('../metodos/links.php');
  require_once('menu.php');
?>
  <title>PEDIDO | LA CABAÑA</title>
</head>
<body>
   <form method="POST" class="container">
    <div class="row pb-3">
      <div class="col">
        <h1>Nuevo pedido</h1>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>CÓDIGO DE BARRAS</label>
          <input class="form-control" type="text" autofocus name="txtCodigoBarras" id="txtCodigoBarras">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>NOMBRE PRODUCTO</label>
          <input class="form-control" type="text" name="txtNombreProducto" id="txtNombreProducto">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>DESCRIPCION PRODUCTO</label>
          <input class="form-control" type="text" name="txtDescripcionProducto" id="txtDescripcionProducto">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
       <div class="form-group">
          <label>MARCA</label>
          <input class="form-control" type="text" name="txtMarca" id="txtMarca">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>SABOR</label>
          <input class="form-control" type="text" name="txtSabor" id="txtSabor">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>MEDIDA</label>
          <input class="form-control" type="text" name="txtMedida" id="txtMedida">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>UNIDAD</label>
          <input class="form-control" type="text" name="txtUnidad" id="txtUnidad">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>PRECIO COMPRA</label>
          <input class="form-control" type="number" name="txtPrecioCompra" id="txtPrecioCompra">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>VALOR</label>
          <input class="form-control" type="number" name="txtValor" id="txtValor">
        </div>
      </div>
    </div>
        <button type="button" id="btnGuardar" class="btn btn-success"><img src="imagenes/save.png"></button>
      </form>





</body>
</html>




<script>
  
$(buscar());

function buscar(dato){
  $.ajax({
    url:'../metodos/buscador.php',
    type:'POST',
    data:{dato:dato},
  })
  .done(function(resultado){
    $("#tabla").html(resultado);
  })
}


$('#txtCodigoBarras').on('change', function(e){
const codigobarras = $('#txtCodigoBarras').val();


$.ajax({
          url: '../metodos/consultasJS.php',
          type: 'POST',
          data: {
          accion:'buscaProducto',
               txtTelefono:telefono,
               txtCedula:cedula},
          success: function(respuesta){
            try{
              
            }catch(e){
              toastr.info("Alguno de los datos registrados está incorrecto", "Información!"); 
            }
          }





});

</script>