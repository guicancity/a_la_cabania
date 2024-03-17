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
  <title>VALOR | LA CABAÑA</title>
</head>
<body>
  <div class="container mt-4">
      <!-- <form onsubmit="return false">-->
    <div class="row">
      <div class="col-lg-11 mb-4">
        <input class="form-control" placeholder="escriba..." autofocus id="txtBuscar" type="text" name="txtBuscar">
      </div>
      
    </div>
    <!--  </form>-->
    <section id="tabla" class="mb-4">
     
    </section>
  </div>







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


$('#txtBuscar').on('keyup',function(e){
  var producto = $('#txtBuscar').val();
  if(producto !=""){
    buscar(producto);
  }else{
    buscar();
  }
  //$('#txtBuscar').val('');
  //$('#txtBuscar').focus();

});

</script>