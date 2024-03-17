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
      
        insertProduct(idProducto,idEmpresa,idPersonas,nombreProducto,marca,medida,unidad,precioCompra,valor);
    

})

$(document).on('keyup','#txtPrecioCompra',function(){
    const precioCompra =$('#txtPrecioCompra').val();
    
    $('#txtValor').val(margenganacia(precioCompra));
  })



$(document).on('click','.bodega',function(){
  const idProducto = $(this).attr('data-id');
  $('#txtIdProductome').val(idProducto);

  $('#agregaBodegaModal').modal();
});

$(document).on('click','.estante',function(){
  const idProducto = $(this).attr('data-id');
  $('#txtIdProductomes').val(idProducto);
   
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


$('#txtBuscar').on('keyup',function(e){
  var producto = $('#txtBuscar').val();
  if(producto !=""){
    buscar(producto);
  }else{
    buscar();
  }
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
  $.ajax({
    url:'../metodos/buscainventario.php',
    type:'POST',
    data:{dato:dato},
  })
  .done(function(resultado){
    $("#tabla").html(resultado);
  })
}



function insertProduct(idProducto,idEmpresa,idPersonas, nombreProducto, marca, medida, unidad, precioCompra, valor){
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





 </script>


