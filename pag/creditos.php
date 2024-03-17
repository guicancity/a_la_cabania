<?php
$conexion = require_once('../metodos/conexion.php');
$validar_acceso = require_once('../metodos/session.php');
//$validar_acceso($conexion, "CREDITOS");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php require_once('../metodos/links.php') ?>
	<title>CREDITOS | LA CABAÑA</title>
</head>

<body>
	<?php require_once('menu.php') ?>
	<div class="container mt-4">
		<section class="mb-4">
			<table class="table  table-hover">
				<thead>
					<tr>
						<th>DEUDOR</th>
						<th>FECHA</th>
						<th>VALOR</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$query = mysqli_query(
						$conexion,
						"SELECT
							F.IDFACTURA,
							CONCAT(P.NOMBRES ,' ', P.APELLIDOS) AS PERSONA,
							F.FECHAVENTA,
							F.HORAFACTURA,
							F.VALORTOTAL
						FROM FACTURA F
						JOIN PERSONAS P
							ON F.IDCLIENTE = P.IDPERSONAS
						WHERE PAGADO = 0
						ORDER BY PERSONA"
					);
					while ($row = mysqli_fetch_array($query)) {
						$valortotal = number_format($row["VALORTOTAL"], 0, ",", ".");
						$fechaventa = date("d/m/Y",strtotime($row["FECHAVENTA"]));
						echo "<tr>
					
							<td>
							
							<button type='button' class=\"btn btn-link detallefactura\" data-bs-toggle=\"modal\" data-idfactura=\"{$row['IDFACTURA']}\" data-bs-target='#creditopersona'>{$row["PERSONA"]}</button>
								
							</td>
							<td>{$fechaventa} </td>
							<td>$ {$valortotal}</td>
							<td>
								<button class=\"btn  btn-success shadow cerrarcredito\" data-id=\"{$row['IDFACTURA']}\">
									<i class=\"fa-solid fa-sack-dollar\"></i> CERRAR CR&Eacute;DITO
								</button>

								<button class=\"btn btn-warning abonarfactura\" data-bs-toggle=\"modal\" data-idfactura=\"{$row['IDFACTURA']}\" data-bs-target='#abonarfactura'><i class=\"fa-solid fa-sack-dollar\"></i> ABONO
								</button>
							</td>
						</tr>";
					}
					?>
				</tbody>
			</table>
		</section>
	</div>

	
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
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Detalle de productos</h5>
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





		$(function() {
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
							data: {id:id_credito},
					success: function(respuesta){
						if (respuesta >= 1) {
						Swal.fire(
							'Cerrado!',
							'Credito cerrado',
							'success'
						)
					setTimeout( function() { window.open("creditos.php","_self"); }, 600 ); 
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
    url:'../metodos/tablas.php',
    type:'POST',
    data:{tabla:'productosxfactura',
		idfactura: idfactura},
  })
  .done(function(resultado){
    $("#tabla").html(resultado);
  });
})


	$(document).on('click','#btnGuardarAbono',function(){
  var idfactura = $('#txtidfacturam').val();
  var valorabono = $('#txtvalorabonom').val();

   if(valorabono==""){
          toastr.error("El VALOR A ABONAR no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });

          
          return false;
        }


  $.ajax({
    url: '../metodos/consultasJS.php',
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
      setTimeout( function() { window.open("creditos.php","_self"); }, 600 ); 
      }else if(respuesta == 2){
      	Toast.fire({
        icon: 'warning',
        title: 'El valor a abonar no puede ser mayor a la deuda'
      });
      	}else{
        Toast.fire({
        icon: 'info',
        title: 'Revise los datos ingresados'
      });
      }
    }
  })
 
});


$(document).on('click','.abonarfactura',function(){
  var idfactura = $(this).attr('data-idfactura');
 	$('#txtidfacturam').val(idfactura);
})

		});

	
		




	</script>
</body>

</html>