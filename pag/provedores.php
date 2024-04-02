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
        <button type="button" class="btn btn-success shadow" data-bs-toggle="modal" data-bs-target="#nuevoprovedor">
          <i class="fa-solid fa-add"></i> Nueva provedor</button>
      </div>
    <section id="tabla" class="mb-4">
     
    </section>
  </div>


  <?php
/*
INICIO AGREGAR PROVEDOR
 */
?>
 <div class="modal fade" id="nuevoprovedor" tabindex="-1" role="dialog"  aria-hidden="true">
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
            <label>Nombres</label>
            <input value="<?php echo $_GET['idempresa']; ?>" hidden type="text" name="txtidempresa" id="txtidempresa">
            <input class="form-control" type="text" name="txtnombres" id="txtnombres">
          </div>
          </div>
           </div>
           <div class="row">
          <div class="col">
          <div class="form-group">
            <label>Apellidos</label>
            <input class="form-control" type="text" name="txtapellidos" id="txtapellidos">
          </div>
          </div>
           </div>
           <div class="row">
             <div class="col">
              <div class="form-group">
                <label>Teléfono</label>
                <input class="form-control" type="text" name="txttelefono" id="txttelefono">
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
INICIO EDITAR PROVEDOR
 */
?>
 <div class="modal fade" id="editaprovedor" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar provedor</h5>
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
	var myModal = document.getElementById('nuevoprovedor');
  var myInput = document.getElementById('txtnombres');
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
  const idempresa =   $('#txtidempresa').val();
  const nombres =     $('#txtnombres').val();
  const apellidos =   $('#txtapellidos').val();
  const telefono =    $('#txttelefono').val();
  if(nombres==""){
          toastr.error("El NOMBRE no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }
  if(apellidos==""){
          toastr.error("El APELLIDO no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }
  if(telefono==""){
          toastr.error("El TELEFONO no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }

        insertprovedor(idempresa,nombres,apellidos,telefono);
})

$(document).on('click','.editar',function(){
  var idprovedor = $(this).attr('data-id');
  $.ajax({
    url:'../metodos/provedores.php',
    type:'POST',
    data:{accion:'editarprovedor',
          idprovedor:idprovedor},
  })
  .done(function(resultado){
    $("#tabladet").html(resultado);
  });
})

$(document).on('click','#btnUpdate',function(){
  var idpersona =  $('#txtidprovedoru').val();
  var idempresa = $('#txtidempresa').val();
  const nombres =  $('#txtnombreproveu').val();
  const apellidos =     $('#txtapellidou').val();
  const telefono =     $('#txttelefonou').val();


  updateprovedor(idempresa,idpersona,nombres,apellidos,telefono);


})

function cargaTabla(idempresa){//detalleProductos
  $.ajax({
    url:'../metodos/provedores.php',
    type:'POST',
    data:{accion:'cargaprovedores',
          idempresa:idempresa},
  })
  .done(function(resultado){
    $("#tabla").html(resultado);
  })
}

function insertprovedor(idempresa,nombres,apellidos,telefono){
    $.ajax({
    url: '../metodos/provedores.php',
    type: 'POST',
    data: {
      accion:'insertaprovedor',
      idempresa:idempresa,
      nombres:nombres,
      apellidos:apellidos,
      telefono:telefono
    },
    success: function(respuesta){
      if (respuesta >= 1) {
       Toast.fire({
        icon: 'success',
        title: 'Provedor agregado con éxito!'
      });
      setTimeout( function() { window.open("provedores.php?idempresa="+respuesta,"_self"); }, 600 ); 
      }else{
        Toast.fire({
        icon: 'info',
        title: 'Revise los datos ingresados'
      });
      }
    }
  })
  }

  function updateprovedor(idempresa,idpersona,nombres,apellidos,telefono){
    $.ajax({
    url: '../metodos/provedores.php',
    type: 'POST',
    data: {
      accion:'updateprovedor',
      idpersona:idpersona,
      nombres:nombres,
      apellidos:apellidos,
      telefono:telefono,
    },
    success: function(respuesta){
      if (respuesta >= 1) {
       Toast.fire({
        icon: 'success',
        title: 'Provedor actualizada con éxito!'
      });
      setTimeout( function() { window.open("provedores.php?idempresa="+idempresa,"_self"); }, 600 ); 
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

