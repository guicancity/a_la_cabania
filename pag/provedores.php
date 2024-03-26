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
  <title>PROVEDORES | LA CABAÑA</title>
</head>
<body>
  <div class="container mt-4">
       <input type="text" name="txtidempresa" id="txtidempresa" hidden=""  value="<?php echo $_GET['idempresa']?>">



      <div class="col-12 col-lg-4">
        <button type="button" class="btn btn-success shadow" data-bs-toggle="modal" data-bs-target="#variedad">
          <i class="fa-solid fa-add"></i> Nueva provedor</button>
      </div>
    <section id="tabla" class="mb-4">
     
    </section>
  </div>


  <?php
/*
INICIO AGREGAR VARIEDAD
 */
?>
 <div class="modal fade" id="variedad" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar provedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col">
          <div class="form-group">
            <label>código de barras</label>
            <input value="<?php echo $_GET['idempresa']; ?>" hidden type="text" name="txtidempresa" id="txtidempresa">
            <input class="form-control" type="text" name="txtCodigoBarras" id="txtCodigoBarras">
          </div>
          </div>
           </div>
           <div class="row">
             <div class="col">
              <div class="form-group">
                <label>variedad</label>
                <input class="form-control" type="text" name="txtSabor" id="txtSabor">
              </div>
             </div>
           </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnGuardar" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
      </div>
      </form>
    </div>
  </div>
</div>



<?php
/*
INICIO AGREGAR VARIEDAD
 */
?>

  <?php
/*
INICIO EDITAR VARIEDAD
 */
?>
 <div class="modal fade" id="editaVariProd" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar variedad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
      <div class="modal-body">
        <section id="tabladet"></section>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnUpdate" class="btn btn-warning">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>



<?php
/*
INICIO EDITAR VARIEDAD
 */
?>

<script>
	var myModal = document.getElementById('variedad');
  var myInput = document.getElementById('txtCodigoBarras');
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

$(window).on('load',function(e){
  var idempresa = $('#txtidempresa').val();
    cargaTabla(idempresa);
});

  $('#btnGuardar').on('click',function(e){
  
  e.preventDefault();
  const idProductos = $('#txtidProductos').val();
  const codigoBarras =        $('#txtCodigoBarras').val();
  const sabor =               $('#txtSabor').val();


  if(codigoBarras==""){
          toastr.error("El CODIGO DE BARRAS no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });

          
          return false;
        }
  if(sabor==""){
          toastr.error("La VARIEDAD no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          
          return false;
        }

        insertDetProduct(idProductos, codigoBarras,sabor);
})



function cargaTabla(idempresa){//detalleProductos
  $.ajax({
    url:'../metodos/tablas.php',
    type:'POST',
    data:{tabla:'cargaprovedores',
          idempresa:idempresa},
  })
  .done(function(resultado){
    $("#tabla").html(resultado);
  })
}


$(document).on('click','.editar',function(){
  var idprovedor = $(this).attr('data-id');
  $.ajax({
    url:'../metodos/tablas.php',
    type:'POST',
    data:{tabla:'editarprovedor',
          idprovedor:idprovedor},
  })
  .done(function(resultado){
    $("#tabladet").html(resultado);
  });
})

$(document).on('click','#btnUpdate',function(){
  var idProductos = $('#txtidprovedor').val();
   const idProductosUp = $('#txtidProductosUp').val();
  const codigoBarrasUp =        $('#txtCodigoBarrasUp').val();
  const saborUp =               $('#txtSaborUp').val();

  updateDetProduct(idProductos, idProductosUp,codigoBarrasUp,saborUp);


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

function insertDetProduct(idProductos, codigoBarras,sabor){
    $.ajax({
    url: '../metodos/consultasJS.php',
    type: 'POST',
    data: {
      accion:'insertaVariedadProd',
      idProductos:idProductos,
      codigoBarras:codigoBarras,
      sabor:sabor,
    },
    success: function(respuesta){
      if (respuesta >= 1) {
       Toast.fire({
        icon: 'success',
        title: 'Variedad agregado con éxito!'
      });
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

  function updateDetProduct(idProductos,idProductosUp, codigoBarras,sabor){
    $.ajax({
    url: '../metodos/consultasJS.php',
    type: 'POST',
    data: {
      accion:'updateVariedadProd',
      idProductos:idProductos,
      idProductosUp:idProductosUp,
      codigoBarras:codigoBarras,
      sabor:sabor,
    },
    success: function(respuesta){
      if (respuesta >= 1) {
       Toast.fire({
        icon: 'success',
        title: 'Variedad actualizada con éxito!'
      });
      setTimeout( function() { window.open("detalleProducto.php?idp="+respuesta,"_self"); }, 600 ); 
      }else{
        Toast.fire({
        icon: 'info',
        title:  respuesta
      });
      }
    }
  })
  }
</script>

