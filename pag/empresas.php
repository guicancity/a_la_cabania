<?php 
require_once('../metodos/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
  require_once('menu.php');
?>
	<title>EMPRESAS | LA CABAÑA</title>
</head>
<body>
	<input type="hidden" id="oculto">
	<div class="container">
		<div class="row mt-3 mb-4">
			<div class="col">
				<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevaempresa"><i class="fa-solid fa-add"></i>  Nueva empresa</button>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#pagofactura" ><i class="fa-solid fa-cash-register"></i> Registrar pagos</button>
				
		
			</div>
		</div>
		<input type="text" class="form-control mt-3 mb-4" autofocus id="txtBuscar" placeholder="escriba...">
		<div id="tabla"></div>
	</div>

  <?php
/*
INICIO PAGO FACTURA
 */
?>
 <div class="modal fade" id="pagofactura" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pago de facturas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Empresa</label>
            <select class="form-select" id="sltempresa">';
              <?php
              $sql = 'SELECT * FROM EMPRESA ORDER BY NOMBRES';
              $ejecuta = mysqli_query($conexion,$sql); ?>  
              <option selected>seleccione...</option>   
              <?php
                while($fila = mysqli_fetch_assoc($ejecuta)){ ?>
                  <option value="<?php echo $fila['IDEMPRESA'] ?>"> <?php echo $fila['NOMBRES']?> </option>;
              <?php  } ?>
           </select>
           
          </div>
          </div>
           </div>
            <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Valor</label>
            <input class="form-control" type="number" name="txtvalorpago" id="txtvalorpago">
          </div>
          </div>
           </div>
            <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Observación</label>
            <textarea class="form-control"  name="txtobservacion" id="txtobservacion"></textarea> 
          </div>
          </div>
           </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnguardarpago" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>



<?php
/*
INICIO PAGO FACTURA
 */
?>

 <?php
/*
INICIO ACTUALIZAR EMPRESA
*/
?>
 <div class="modal fade" id="datosempresa" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Actualizar empresa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Nombre de empresa</label>
            <input value="<?php echo $_GET['idp']; ?>" hidden type="text" name="txtidProductos" id="txtidProductos">
            <input class="form-control" type="text" name="txtCodigoBarras" id="txtCodigoBarras">
          </div>
          </div>
           </div>
           
      </div>
      <div class="modal-footer">
        <button type="button" id="btnactualizarempresa" class="btn btn-warning">Actualizar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>



<?php
/*
FIN ACTUALIZAR EMPRESA
 */
?>

<?php
/*
INICIO NUEVA EMPRESA
 */
?>
 <div class="modal fade" id="nuevaempresa" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar empresa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Nombre de empresa</label>
            <input class="form-control" type="text" name="txtnombreempresa" id="txtnombreempresa">
          </div>
          </div>
           </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnguardarempresa" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>



<?php
/*
FIN NUEVA EMPRESA
 */
?>

<?php
/*
INICIO PAGO FACTURA
 */
?>
 <div class="modal fade" id="pagofactura" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pago de facturas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Empresa</label>
            <select class="form-select" id="sltempresa">';
            	<?php
            	$sql = 'SELECT * FROM EMPRESA ORDER BY NOMBRES';
            	$ejecuta = mysqli_query($conexion,$sql); ?>  
            	<option selected>seleccione...</option>   
            	<?php
            		while($fila = mysqli_fetch_assoc($ejecuta)){ ?>
            			<option value="<?php echo $fila['IDEMPRESA'] ?>"> <?php echo $fila['NOMBRES']?> </option>;
            	<?php  } ?>
           </select>
           
          </div>
          </div>
           </div>
            <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Valor</label>
            <input class="form-control" type="number" name="txtvalorpago" id="txtvalorpago">
          </div>
          </div>
           </div>
            <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Observación</label>
            <textarea class="form-control"  name="txtobservacion" id="txtobservacion"></textarea> 
          </div>
          </div>
           </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnguardarpago" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>



<?php
/*
FIN PAGO FACTURA
 */
?>

</body>
</html>


<script>

var myModal = document.getElementById('nuevaempresa');
  var myInput = document.getElementById('txtnombreempresa');
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


	$(window).on("load", function(){
		var dato = $('#txtBuscar').val();
    loadEmpresas(dato);
  });


$(document).on('keyup','#txtBuscar',function(){
	var dato = $('#txtBuscar').val();
	 loadEmpresas(dato);
})

$(document).on('click','#btnguardarpago',function(){
var empresa = $('#sltempresa').val();
var valorpago = $('#txtvalorpago').val();
var observacion = $('#txtobservacion').val();

if(valorpago==""){
          toastr.error("El VALOR no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }

        $.ajax({
    url:'../metodos/consultasJS.php',
    type:'POST',
    data:{
      accion:'registrarpago',
      empresa:empresa,
      valorpago:valorpago,
      observacion:observacion
    	},
      success:function(respuesta){
        if(respuesta == 1){
          Toast.fire({
        icon: 'success',
        title: 'PAGO agregada con Éxito!!'
      });
          setTimeout( function() { window.open("empresas.php","_self"); }, 600 ); 
        }else{
          Toast.fire({
        icon: 'info',
        title: 'Revise los datos ingresados'
      });
        }
      }
  })


})


$(document).on('click','#btnguardarempresa',function(){
	var nombreempresa = $('#txtnombreempresa').val();

  if(nombreempresa==""){
          toastr.error("El NOMBRE no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }
	 
	$.ajax({
    url:'../metodos/consultasJS.php',
    type:'POST',
    data:{
      accion:'nuevaempresa',
      nombreempresa:nombreempresa
    	},
      success:function(respuesta){
        if(respuesta == 1){
          Toast.fire({
        icon: 'success',
        title: 'Empresa agregada con Éxito!!'
      });
          setTimeout( function() { window.open("empresas.php","_self"); }, 600 ); 
        }else{
          Toast.fire({
        icon: 'info',
        title: 'Revise los datos ingresados'
      });
        }
      }
  })


})

$(document).on('click','.editar',function(){
  var IdDetProductos = $(this).attr('data-id');
  $.ajax({
    url:'../metodos/tablas.php',
    type:'POST',
    data:{tabla:'editaVarieProdu',
          IdDetProductos: IdDetProductos},
  })
  .done(function(resultado){
    $("#tabladet").html(resultado);
  });
})


$(document).on('click','#btnactualizarempresa',function(){
	var nombreempresa = $('#txtnombreempresa').val();

  if(nombreempresa==""){
          toastr.error("El NOMBRE no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }
	 
	$.ajax({
    url:'../metodos/consultasJS.php',
    type:'POST',
    data:{
      accion:'nuevaempresa',
      nombreempresa:nombreempresa
    	},
      success:function(respuesta){
        if(respuesta == 1){
          Toast.fire({
        icon: 'success',
        title: 'Empresa agregada con Éxito!!'
      });
          setTimeout( function() { window.open("empresas.php","_self"); }, 600 ); 
        }else{
          Toast.fire({
        icon: 'info',
        title: 'Revise los datos ingresados'
      });
        }
      }
  })


})

	$(document).on('click','.eliminar',function(){
  var IdDetProductos = $(this).attr('data-id');
  var idProductos = $('#txtidProductos').val();

Swal.fire({
  title: 'Eliminar',
  text: "¿está seguro de eliminar?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'si, eliminar'
}).then((result) => {
  if (result.isConfirmed) {
    $.ajax({
    url: '../metodos/consultasJS.php',
    type: 'POST',
    data: {
      accion:'eliminarVariedadProd',
      IdDetProductos:IdDetProductos,
      idProductos:idProductos},
    success: function(respuesta){
      if (respuesta >= 1) {
        Swal.fire(
      'Eliminado!',
      'Variedad eliminada',
      'success'
    )
      setTimeout( function() { window.open("detalleProducto.php?idp="+respuesta,"_self"); }, 600 ); 
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


})



	function loadEmpresas(dato){
	$.ajax({
		url: '../metodos/tablas.php',
    type: 'POST',
    data: {
    	tabla:'loadEmpresas',
    	dato:dato
    }


	})
	.done(function(resultado){
      $("#tabla").html(resultado);
    });
	}
</script>






