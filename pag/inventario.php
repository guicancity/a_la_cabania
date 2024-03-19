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
 

<script>

   const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 1000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
  
$(buscar());


$(document).on('click','.desactivar',function (e) {
  e.preventDefault();
  var id = $(this).attr('data-id');
  var producto = $('#txtBuscar').val();
  desactivaProducto(id,producto);
 });


$(function(){
$(document).on('click','#btnGuardarBodega',function(e) {
  e.preventDefault();
      const idProducto = $('#txtIdProductome').val();
      const cantidad = $('#txtCantidadme').val();
      if(cantidad=="" || cantidad == 0){
          toastr.error("La CANTIDAD no puede ser vacia o 0", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }else{
          $.ajax({
            url: '../metodos/consultasJS.php',
            type: 'POST',
            data: {
              accion:'agregacbodega',
              idProducto:idProducto,
              cantidad:cantidad
            },
            success: function(respuesta){
              if (respuesta == 1) {
                toastr.success("Cantidad actualizada",{
                "progressBar":true,
                "closeButton":true,
                "timeOut":500
                });
                window.setTimeout(function(){window.open("inventario.php","_self");}, 700);
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
    const idProducto = $('#txtIdProductomes').val();
    const cantidad = $('#txtCantidadmes').val();  
       if(cantidad==""){
          toastr.error("La CANTIDAD no puede ser vacia o 0", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          return false;
        }else{

          $.ajax({
            url: '../metodos/consultasJS.php',
            type: 'POST',
            data: {
              accion:'cambiaestante',
              idProducto:idProducto,
              cantidad:cantidad
            },
            success: function(respuesta){
              if (respuesta == 1) {
                toastr.success("Cantidad actualizada",{
                "progressBar":true,
                "closeButton":true,
                "timeOut":500
                });
                window.setTimeout(function(){window.open("inventario.php","_self");}, 1000);
              }else if(respuesta == 2){
                toastr.error("verifique la CANTIDAD a cambiar", "Error!",{
                "progressBar":true,
                "closeButton":true,
                "timeOut":4000
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
          toastr.error("El NOMBRE DEL PRODUCTO no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          
          return false;
        }
    if(precioCompra==""){
          toastr.error("El PRECIO DE COMPRA no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          
          return false;
        }

    if(valor==""){
          toastr.error("El VALOR no puede ser vacio", "Error!",{
            "progressBar":true,
            "closeButton":true,
            "timeOut":2000
          });
          
          return false;
        }



      
        updateproduct(idProducto,idEmpresa,idPersonas,nombreProducto,marca,medida,unidad,precioCompra,valor);
    

})
})

$(document).on('keyup','#txtPrecioCompra',function(){
    const precioCompra =$('#txtPrecioCompra').val();
    
    $('#txtValor').val(margenganacia(precioCompra));
  })



$(document).on('click','.bodega',function(){
  const idProducto = $(this).attr('data-idpb');
  $('#txtIdProductome').val(idProducto);
  $('#agregaBodegaModal').modal();
});

$(document).on('click','.estante',function(){
  const idProducto = $(this).attr('data-idpe');
  $('#txtIdProductomes').val(idProducto);
  $('#agregaEstanteModal').modal();   
});

$(document).on('click','.producto',function(){
  const idProducto = $(this).attr('data-id');
  $('#txtIdProducto').val(idProducto);
  $.ajax({
    url:'../metodos/tablas.php',
    type:'POST',
    data:{tabla: 'seleccionaProductoxId',
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
    const empresa =    $('#sltEmpresa').val();
    var idproducto = $('#txtIdProducto').val();
    loadRepartidor(empresa,idproducto);

  });
   

   function loadRepartidor(idEmpresa,idproducto){
  $.ajax({
    url:'../metodos/tablas.php',
    type:'POST',
    data:{
      tabla:'loadDistribuidor',
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
    url:'../metodos/buscainventario.php',
    type:'POST',
    data:{dato:dato,
          tipo:tipo},
  success:function(resultado){
    $('#tabla').html(resultado);
  }
});
}


function updateproduct(idProducto,idEmpresa,idPersonas, nombreProducto, marca, medida, unidad, precioCompra, valor){
    $.ajax({
    url: '../metodos/consultasJS.php',
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
      setTimeout( function() { window.open("inventario.php","_self"); }, 600 ); 
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
          url: '../metodos/consultasJS.php',
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


