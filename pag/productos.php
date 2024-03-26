<?php 
require_once('../metodos/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
  require_once('menu.php');
?>
	<title>PRODUCTOS | LA CABAÑA</title>
</head>
<body>
	<div class="container">
		
	 <input type="text" name="txtidempresa" id="txtidempresa" hidden=""  value="<?php echo $_GET['idempresa']?>">
		<input type="text" class="form-control mt-3 mb-4" autofocus id="txtproducto" placeholder="escriba...">
		<div id="tabla"></div>
	</div>


</body>
</html>


<script>


const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})


	$(window).on("load", function(){
		var dato = $('#txtproducto').val();
var empresa = $('#txtidempresa').val();
    loadproductos(dato,empresa);
  });


$(document).on('keyup','#txtproducto',function(){
	var dato = $('#txtproducto').val();
  var empresa = $('#txtidempresa').val();
	 loadproductos(dato,empresa);
})


	function loadproductos(dato,empresa){
	$.ajax({
		url: '../metodos/productos.php',
    type: 'POST',
    data: {
    	opcion:'productosxempresa',
      empresa:empresa,
    	dato:dato
    }


	})
	.done(function(resultado){
      $("#tabla").html(resultado);
    });
	}
</script>






