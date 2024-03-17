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
  <title>PRUEBAS | LA CABAÑA</title>
</head>
<body>
  <input type="text" id="txtn1" placeholder="1" name="">
  <input type="text" id="txtn2" placeholder="2" name="">
  <button id="btnPagar">hola</button>
    
</body>
</html>

<script>
  $('#btnPagar').on('click',function(){
    
    const n2 = parseInt($('#txtn2').val());
   
 console.log(sumar(n1,n2));
  });

  $('#txtn1').on('keyup',function(){
    const n1 = parseInt($('#txtn1').val());
    var gato = margenganacia(n1);
    $('#txtn2').val(gato);
  });


</script>