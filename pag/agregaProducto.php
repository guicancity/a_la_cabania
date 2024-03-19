<?php 
require_once('../metodos/conexion.php');
  require_once('menu.php');
?>
<!DOCTYPE html>

<head>

<?php
  require_once('../metodos/links.php');

?>
	<title>NUEVO PRODUCTO | LA CABAÑA</title>
</head>
<body>
   <form method="POST" class="container">
    <div class="row pb-3">
      <div class="col">
        <h1>Nuevo producto</h1>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>CÓDIGO DE BARRAS</label>
          <input class="form-control" type="text" autofocus name="txtCodigoBarras" id="txtCodigoBarras">
          <span class="badge badge-danger" hidden>El código de barras ya se encuentra registrado</span>
        </div>
      </div>
      <div class="col">
        <div class="form-group">
          <label>EMPRESA</label>
          <select class="form-select" id="sltEmpresa">
            <option selected="true" value="0">Seleccione...</option>
          <?php 
            $sql = 'SELECT * FROM EMPRESA ORDER BY NOMBRES';
            $ejecuta = mysqli_query($conexion,$sql);
            while($fila = mysqli_fetch_assoc($ejecuta)){
          ?>
          <option value="<?php echo $fila['IDEMPRESA'] ?>"><?php echo $fila['NOMBRES'] ?></option>
          <?php
            }
           ?>
           </select>
           <section id="distibuidor">
             
           </section>
           
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>NOMBRE PRODUCTO</label>
          <input class="form-control" type="text" name="txtNombreProducto" id="txtNombreProducto">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
       <div class="form-group">
          <label>MARCA</label>
          <input class="form-control" type="text" name="txtMarca" id="txtMarca">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>SABOR</label>
          <input class="form-control" type="text" name="txtSabor" id="txtSabor">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>MEDIDA</label>
          <input class="form-control" type="text" name="txtMedida" id="txtMedida">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>UNIDAD</label>
          <input class="form-control" type="text" name="txtUnidad" id="txtUnidad">
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <div class="form-group">
          <label>PRECIO COMPRA</label>
          <input class="form-control" type="text" name="txtPrecioCompra" id="txtPrecioCompra">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>VALOR</label>
          <input class="form-control" type="text"  name="txtValor" id="txtValor">
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <div class="form-group">
          <label>CANTIDAD</label>
          <input class="form-control" type="text" name="txtcantidad" id="txtcantidad">
        </div>
      </div>

      
    </div>
    <div class="row">
      <div class="col">
        <button type="button" id="btnGuardar" class="btn btn-success btn-lg mt-2"><i class="fa-regular fa-floppy-disk "></i></i> Guardar</button>
        <button type="button" id="btnTrash" onclick="emptyinputs()" class="btn btn-primary btn-lg mt-2"><i class="fa-regular fa-trash-can"></i> Limpiar</button>
      </div>
    </div>
        
      </form>

</body>
</html>



<script>
  const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})


$('#txtPrecioCompra').on('keyup',function(){
    const precioCompra =$('#txtPrecioCompra').val();
    
    $('#txtValor').val(margenganacia(precioCompra));
  })

$('#btnGuardar').on('click',function(e){
  e.preventDefault();
  const codigoBarras =        $('#txtCodigoBarras').val();
  const empresa =             $('#sltEmpresa').val();
  var idPersona = document.getElementById("sltIdPersona").value;
  if(idPersona == ""){
    idPersona = 1;
  }
  const nombreProducto =      $('#txtNombreProducto').val();
  const marca =               $('#txtMarca').val();
  const sabor =               $('#txtSabor').val();
  const medida =              $('#txtMedida').val();
  const unidad =              $('#txtUnidad').val();
  const precioCompra =        $('#txtPrecioCompra').val();
  const valor =               $('#txtValor').val();
  const cantidad =               $('#txtcantidad').val();

  if(codigoBarras==""){
    toastr.error("El CODIGO DE BARRAS no puede ser vacio", "Error!",{
      "progressBar":true,
      "closeButton":true,
      "timeOut":2000
    });
    return false;
  }

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
  if(cantidad==""){
    toastr.error("La CANTIDAD no puede ser vacio", "Error!",{
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
      accion:'buscabarraexistente',
      codigobarras:codigoBarras
    },
   success: function(resp){
      if (parseInt(resp) >= 1) {
        Swal.fire({
          icon: 'error',
          title: 'Alerta!',
          text: 'Producto '+ codigoBarras +' ya se encuentra registrado'
        });
               
      }else{        
        insertProduct(codigoBarras,empresa,idPersona,nombreProducto,marca,sabor,medida,unidad,precioCompra,valor,cantidad);
      }
  }
   })
  

})
 

$(function(){
  $('#sltEmpresa').on('change',function(){
    const empresa =    $('#sltEmpresa').val();
    loadRepartidor(empresa,'null');

  })

  

});
function insertProduct(codigoBarras,empresa,idPersona, nombreProducto, marca, sabor, medida, unidad, precioCompra, valor,cantidad){
    $.ajax({
    url: '../metodos/consultasJS.php',
    type: 'POST',
    data: {
      accion:'nuevoProducto',
      codigoBarras:codigoBarras,
      empresa:empresa,
      idPersona:idPersona,
      nombreProducto:nombreProducto,
      marca:marca,
      sabor:sabor,
      medida:medida,
      unidad:unidad,
      precioCompra:precioCompra,
      valor:valor,
      cantidad:cantidad
    },
    success: function(respuesta){
      if (respuesta >= 1) {
       Toast.fire({
        icon: 'success',
        title: 'Producto agregado con éxito!'
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

  function loadRepartidor(idEmpresa,idproducto){
    const tabla = 'loadDistribuidor';
  $.ajax({
    url:'../metodos/tablas.php',
    type:'POST',
    data:{
      tabla:tabla,
      idEmpresa:idEmpresa,
      idproducto:idproducto
    },
    })
    .done(function(resultado){
      $("#distibuidor").html(resultado);
    });

  }

  function emptyinputs(){
    $('#txtCodigoBarras').val("");
        $('#txtNombreProducto').val("");
        $('#txtMarca').val("");
        $('#txtSabor').val("");
        $('#txtMedida').val("");
        $('#txtUnidad').val("");
        $('#txtPrecioCompra').val("");
        $('#txtValor').val("");
        $('#sltEmpresa').val("0");
        $('#sltEmpresa').change();
        $('#txtCodigoBarras').focus();
        
  }

  




</script>