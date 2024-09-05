<?php
$conexion = require_once('../metodos/conexion.php');
//$validar_acceso = require_once('../metodos/session.php');
//$validar_acceso($conexion, "CREDITOS");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once('../metodos/links.php') ?>
	<?php require_once('menu.php') ?>
	<title>CREDITOS | LA CABAÑA</title>
</head>
<body>
	<body>
  <div class="container mt-4">
       
    <div class="row">
      <div class="col-12 col-lg-12 mb-4">
        <input class="form-control" placeholder="escriba..." autofocus id="txtbuscar" type="text" name="txtbuscar">
       </div>

      </div>
      
    <section id="tablacreditos" class="mb-4">
     
    </section>
  </div>
</body>
	
	<?php
//inicio modal productos factura
?>


	<div class="modal fade " id="creditopersona" tabindex="-1">
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Detalle de productos</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					
					<section id="tabla"></section>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<?php
//fin modal productos factura
?>

<?php
//inicio modal abono factura
?>

	<div class="modal fade " id="abonarfactura" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Valor a abonar</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label>Valor abono</label>
								<input type="hidden" id="txtidfacturam">
								<input type="number" name="txtvalorabonom" class="form-control" id="txtvalorabonom">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnGuardarAbono" class="btn btn-success">Guardar</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

<?php
//fin modal abono factura
?>
</body>

</html>
	<script>

		var myModal = document.getElementById('abonarfactura');
  var myInput = document.getElementById('txtvalorabonom');
  myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus();
});

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

buscar();


$(document).on('keyup','#txtbuscar',function(e){
  e.preventDefault();

  var producto = $('#txtbuscar').val();
      if(producto !=""){
        buscar(producto);
      }else{
        buscar();
      } 
 
});

			$(document).on("click", ".cerrarcredito",  function() {
				const id_credito = $(this).attr("data-id");

				Swal.fire({
					title: 'Eliminar',
					text: "¿está seguro de cerrar el crédito?",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'si, cerrar'
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							url: '../metodos/creditos.php',
							type: 'POST',
							data: {id:id_credito,
							accion:'cierracredito'},
					success: function(respuesta){
						if (respuesta >= 1) {
						Swal.fire(
							'Cerrado!',
							'Credito cerrado',
							'success'
						)
					 var producto = $('#txtbuscar').val();
      			if(producto !=""){
        			buscar(producto);
      			}else{
        			buscar();
      			} 
					}else{
					Toast.fire({
						icon: 'info',
						title: 'Revise los datos ingresados'
					});
					}
					}
					})
					}
				})
			});

	$(document).on('click','.detallefactura',function(){
  var idfactura = $(this).attr('data-idfactura');
  $.ajax({
    url:'../metodos/creditos.php',
    type:'POST',
    data:{accion:'productosxfactura',
		idfactura: idfactura},
  })
  .done(function(resultado){
    $("#tabla").html(resultado);
  });
})


	$(document).on('click','#btnGuardarAbono',function(){
  var idfactura = $('#txtidfacturam').val();
  var valorabono = $('#txtvalorabonom').val();
  var producto = $('#txtbuscar').val();

   if(valorabono==""){
   	Toast.fire({
                  icon: 'error',
                  title: 'Error',
                  html:'"El <b>VALOR A ABONAR</b> no puede ser <b>vacio</b>'
                  });
          return false;
        }
  $.ajax({
    url: '../metodos/creditos.php',
    type: 'POST',
    data: {
      accion:'agregarabonofactura',
      idfactura:idfactura,
    	valorabono:valorabono},
    success: function(respuesta){
      if (respuesta == 1) {

      	Toast.fire({
        icon: 'success',
        title: 'Abono registrado'
      });

      $('#abonarfactura').modal('hide');
      $('#txtvalorabonom').val('');
      if(producto !=""){
        buscar(producto);
      }else{
        buscar();
      } 

      }else if(respuesta == 2){
      	Toast.fire({
                  icon: 'warning',
                  title: 'alerta!',
                  html:'El valor a abonar no puede ser <b>MAYOR</b> o <b>IGUAL</b> a la deuda'
                  });
      	}else if(respuesta == 3){
      		Toast.fire({
                  icon: 'warning',
                  title: 'alerta!',
                  html:'El valor <b>MINIMO</b> del abono es de $100'
                  });
      }else if(respuesta == 4){
      		Toast.fire({
                  icon: 'warning',
                  title: 'alerta!',
                  html:'solo se puede abonar <b>MULTIPLOS</b> de $100'
                  });
      }else{
      	Toast.fire({
                  icon: 'info',
                  title: 'info',
                  html:'Revise los datos ingresados'+ ' ' + respuesta
                  });
      }
    }
  })
 
});


$(document).on('click','.abonarfactura',function(){
  var idfactura = $(this).attr('data-idfactura');
 	$('#txtidfacturam').val(idfactura);
 	$('#abonarfactura').modal('show');
})


function buscar(dato){
  $.ajax({
    url:'../metodos/creditos.php',
    type:'POST',
    data:{accion:'cargacreditos',
      dato:dato},
  success:function(resultado){
    $('#tablacreditos').html(resultado);
  }
});
}
		




	</script>
