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
  <title>INVENTARIO | LA CABAÑA</title>
</head>
<body>
  <div class="container mt-4">
       
    <div class="row">
      <div class="col-12 col-lg-12 mb-4">
        <input class="form-control" placeholder="escriba..." autofocus id="txtBuscar" type="text" name="txtBuscar">
       </div>

      </div>
      
    <section id="tabla" class="mb-4">
     
    </section>
  </div>
</body>

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
                  <input class="form-control" type="hidden"  name="txtIdProductome" id="txtIdProductome">
                  <input class="form-control" type="number" name="txtCantidadme" id="txtCantidadme">
              </div>
            </div>
          </div>
      </div>            
      <div class="modal-footer">
        <button id="btnGuardarBodega" class="btn btn-success mt-2">Guardar</button>
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
  const myModal = document.getElementById('agregaBodegaModal');
  var myInput = document.getElementById('txtCantidadme');
  myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus();
  });

  const myModal2 = document.getElementById('agregaEstanteModal');
  var myInput2 = document.getElementById('txtCantidadmes');
  myModal2.addEventListener('shown.bs.modal', function () {
  myInput2.focus();
  });

  
$(buscar());


$(document).on('click','.desactivar',function (e) {
  e.preventDefault();
  var id = $(this).attr('data-id');
  var producto = $('#txtBuscar').val();
  desactivaProducto(id,producto);
 });

$(document).on('click','.bodega',function(){
  const idProducto = $(this).attr('data-idpb');
  $('#txtIdProductome').val(idProducto);
  $('#agregaBodegaModal').modal('show');

});
$(document).on('click','.estante',function(){
  const idProducto = $(this).attr('data-idpe');
  $('#txtIdProductomes').val(idProducto);
  $('#agregaEstanteModal').modal('show');   
});
$(document).on('click','.producto',function(){
  const idProducto = $(this).attr('data-id');
  $('#editProducto').modal('show');
  $('#txtIdProducto').val(idProducto);
  $.ajax({
    url:'../metodos/inventario.php',
    type:'POST',
    data:{accion: 'seleccionaProductoxId',
          idProducto:idProducto
    },
  })
  .done(function(resultado){
    $('#resultado').html(resultado);
    var empresa = document.getElementById("sltEmpresa").value;
    var idproducto = $('#txtIdProducto').val();
    loadRepartidor(empresa,idproducto);
  }); 

});


$(function(){
$(document).on('click','#btnGuardarBodega',function(e) {
  e.preventDefault();
      var  busqueda = $('#txtBuscar').val();
      const idProducto = $('#txtIdProductome').val();
      const cantidad = $('#txtCantidadme').val();
      if(cantidad=="" || cantidad == 0){
        Toast.fire({
                  icon: 'error',
                  title: 'Error',
                  html:'La <b>CANTIDAD</b> no puede ser <b>vacia</b> o <b>0</b>'
                  });
          return false;
        }else{
          $.ajax({
            url: '../metodos/inventario.php',
            type: 'POST',
            data: {
              accion:'agregacbodega',
              idProducto:idProducto,
              cantidad:cantidad
            },
            success: function(respuesta){
              if (respuesta == 1) {
                Toast.fire({
                  icon: 'success',
                  title: 'Cantidad actualizada'
                  });
                if(busqueda !=""){
                  buscar(busqueda);
                }else{
                  buscar();
                } 
                $('#txtCantidadme').val("");
                $('#agregaBodegaModal').modal('hide');
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
});


$(function(){
    $(document).on('click','#btnGuardarEstante',function(e) {
      e.preventDefault();
      var  busqueda = $('#txtBuscar').val();
    const idProducto = $('#txtIdProductomes').val();
    const cantidad = $('#txtCantidadmes').val();  
       if(cantidad==""){
        Toast.fire({
                  icon: 'error',
                  title: 'La CANTIDAD no puede ser vacia o 0'
                  });
          return false;
        }else{

          $.ajax({
            url: '../metodos/inventario.php',
            type: 'POST',
            data: {
              accion:'cambiaestante',
              idProducto:idProducto,
              cantidad:cantidad
            },
            success: function(respuesta){
              if (respuesta == 1) {
                Toast.fire({
                  icon: 'success',
                  title: 'Cantidad actualizada!'
                  });
                
                if(busqueda !=""){
                  buscar(busqueda);
                }else{
                  buscar();
                }                
                $('#txtCantidadmes').val("");

                $('#agregaEstanteModal').modal('hide');
              }else if(respuesta == 2){
                Toast.fire({
                  icon: 'error',
                  title: 'verifique la CANTIDAD a cambiar'
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

$(function(){
$(document).on('click','#btnGuardar',function(e){
  e.preventDefault();
  const idEmpresa = $('#sltEmpresa').val();
  var idPersonas = document.getElementById("sltIdPersona").value;
  if(idPersonas == ""){
    idPersonas = 1;
  }
  const idProducto =          $('#txtIdProducto').val();
  const nombreProducto =      $('#txtNombreProducto').val();
  const marca =               $('#txtMarca').val();
  const medida =              $('#txtMedida').val();
  const unidad =              $('#txtUnidad').val();
  const precioCompra =        $('#txtPrecioCompra').val();
  const valor =               $('#txtValor').val();

  if(nombreProducto==""){
        Toast.fire({
                  icon: 'error',
                  title: 'El NOMBRE DEL PRODUCTO no puede ser vacio'
                  });
          return false;
        }
    if(precioCompra==""){
      Toast.fire({
                  icon: 'error',
                  title: 'El PRECIO DE COMPRA no puede ser vacio'
                  });          
          return false;
        }
    if(valor==""){
      Toast.fire({
                  icon: 'error',
                  title: 'El VALOR no puede ser vacio'
                  });        
          return false;
        }
        updateproduct(idProducto,idEmpresa,idPersonas,nombreProducto,marca,medida,unidad,precioCompra,valor);
    

})
})

$(document).on('keyup','#txtPrecioCompra',function(){
    const precioCompra =$('#txtPrecioCompra').val();
    $('#txtValor').val(margenganacia(precioCompra));
});

$(document).on('keyup','#txtBuscar',function(e){
  e.preventDefault();

  var producto = $('#txtBuscar').val();
      if(producto !=""){
        buscar(producto);
      }else{
        buscar();
      } 
/*
   var keycode = e.keyCode || e.which;
    if (keycode == 13) {
       
    }
*/
 
});

$(document).on('change','#sltEmpresa',function(){
    var empresa =  $('#sltEmpresa').val();
    var idproducto = $('#txtIdProducto').val();
    loadRepartidor(empresa,idproducto);

  });
   

   function loadRepartidor(idEmpresa,idproducto){
  $.ajax({
    url:'../metodos/inventario.php',
    type:'POST',
    data:{
      accion:'loadDistribuidor',
      idEmpresa:idEmpresa,
      idproducto:idproducto
    },
    })
    .done(function(resultado){
      $("#distribuidor").html(resultado);
    });

  }


  function buscar(dato){
    var tipo = 0;
    if($.isNumeric(dato)){
      tipo = 1;
    }
    
  $.ajax({
    url:'../metodos/inventario.php',
    type:'POST',
    data:{accion:'cargainventario',
      dato:dato,
          tipo:tipo},
  success:function(resultado){
    $('#tabla').html(resultado);
  }
});
}


function updateproduct(idProducto,idEmpresa,idPersonas, nombreProducto, marca, medida, unidad, precioCompra, valor){
  var  busqueda = $('#txtBuscar').val();
    $.ajax({
    url: '../metodos/inventario.php',
    type: 'POST',
    data: {
      accion:'actualizarProducto',
      idProducto:idProducto,
      idEmpresa:idEmpresa,
      idPersonas:idPersonas,
      nombreProducto:nombreProducto,
      marca:marca,
      medida:medida,
      unidad:unidad,
      precioCompra:precioCompra,
      valor:valor
    },
    success: function(respuesta){
      if (respuesta >= 1) {
       Toast.fire({
        icon: 'success',
        title: 'Producto actualizado con éxito!'
      });
       $('#editProducto').modal('hide');
      if(busqueda !=""){
                  buscar(busqueda);
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
  function desactivaProducto(idproducto,productobusca){
    Swal.fire({
      title: 'Desactivar',
      text: "¿está seguro de desactivar este producto?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#000',
      confirmButtonText: 'DESACTIVAR'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../metodos/inventario.php',
          type: 'POST',
          data: {
            accion:'desactivaproducto',
            idproducto:idproducto},
          success: function(respuesta){
            if (respuesta == 1) {
              Swal.fire(
                'desactivado!',
                'producto desactivado',
                'success'
              )
              if(productobusca !=""){
                buscar(productobusca);
              }else{
                buscar();
              } 
            }else if(respuesta == 3){
              Swal.fire(
                'error!',
                'No se pueden DESACTIVAR productos con existencias',
                'error'
              )
            }

            else{
              Swal.fire(
                'precaucion!',
                'Revise los datos ingresados',
                'info'
              )
            }
          }
        })
      }
    })
  }

 </script>


