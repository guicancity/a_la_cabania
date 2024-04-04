<?php 
require_once('../metodos/conexion.php');
?>
<!DOCTYPE html>
<html>
<head>
  <style type="text/css">
    .table-responsive {
    max-height:300px;
}
  </style>
<?php
  require_once('menu.php');
?>
	<title>GESTION | LA CABAÑA</title>

</head>
<body>
  <div class="row">
    <div class="col-6">
      <h1 class="text-center">VENTAS POR DIARIAS</h1>
      <div class="container pt-4">
        <div class="row">
          <div class="col">
            <label>fecha inicio</label>
            <input type="date" class="form-control" id="txtFechaI">
          </div>

          <div class="col">
            <label>fecha fin</label>
            <input type="date" class="form-control" id="txtFechaF">
          </div>
        </div>
        <div id="tabla" class="mt-5">
        </div>
      </div>
    </div>
    <div class="col-6 text-center">
      <div class="row">
        <h2>Generar archivo CSV para etiquetas</h2>
       <div class="col">
          </div>
          <div class="row">
            <div class="col mt-5">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>cantidad productos</th>
                    <th>cantidad páginas</th>
                  </tr>
                </thead>
                <?php
                $sql = "";
                $sql = mysqli_query($conexion,"SELECT COUNT(IDPRODUCTOS) CANTIDAD FROM PRODUCTOS WHERE FECHACREACION <> ''");
                $row = mysqli_fetch_assoc($sql);
                $cantidad = $row['CANTIDAD'];
                $porpagina = round($cantidad / 21,2);
                ?>
                <tbody>
                    <tr>
                      <td><?php echo $cantidad ?></td>
                      <td><?php echo $porpagina .' '. 'Páginas'?> </td>
                    </tr>
                </tbody>
              </table>
            </div>
            <div class="col mt-5">
              <button class="btn btn-secondary btn-lg" id="btnGenerar"><i class="fa-solid fa-file-arrow-down"></i> Generar</button>
            </div>
          </div>
          
      </div>
      <hr class="mt-5 mb-5">
      <div class="row">
        <section id="facturas">
        <div class="row container">
          <h2><b>fecha</b></h2>
          <input type="" disabled class="form-control" id="txtfechadia"value="21/02/2021" name="txtfechadia">
        </div>
        <section id="facturasdia" class="container table-responsive"></section>
        
      </section>
        
      </div>
      
    </div>
  </div> 

</html>



<script>
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 800,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });

  $(window).on("load", function(){
    const fecha = new Date();
    var dia = fecha.getDate();
    var mes = fecha.getMonth() + 1; 
    var anio = fecha.getFullYear();
    var str_dia = new String (dia);
    var str_mes = new String (mes);
    var mes1 = '';
    var dia1 = '';
    if(str_mes.length == 2){
      mes1 = mes;
    }else{
      mes1 = '0'+ mes;
    }

    if(str_dia.length == 2){
      dia1 = dia;
    }else{
      dia1 = '0'+ dia;
    }
    $('#txtFechaI').val(anio+'-'+mes1+'-'+dia1);
    $('#txtFechaF').val(anio+'-'+mes1+'-'+dia1);
    const fechaI = $('#txtFechaI').val();
    const fechaF = $('#txtFechaF').val(); 
    ventasxFecha(fechaI, fechaF);
});



$(function(){
  $(document).on('click','.fechadia',function(){
  const fechadia = $(this).attr('data-fecha');
  $('#txtfechadia').val(fechadia);
  facturasxdia(fechadia);
  });
  $('#btnGenerar').on('click',function(event){
    event.preventDefault();
window.location.href = "etiquetas.php";
  });

  $('#txtFechaI').change(function(){
    const fechaI = $('#txtFechaI').val();
    const fechaF = $('#txtFechaF').val();
    ventasxFecha(fechaI,fechaF);
  });

  $('#txtFechaF').change(function(){
    const fechaI = $('#txtFechaI').val();
    const fechaF = $('#txtFechaF').val();
    ventasxFecha(fechaI,fechaF);
  });

  
  
});


function  ventasxFecha(fechaI, fechaF){
  
  $.ajax({
    url:'../metodos/gestion.php',
    type:'POST',
    data:{
      accion:'ventasDia',
      fechaI:fechaI,
      fechaF:fechaF
    },
    })
    .done(function(resultado){
      $("#tabla").html(resultado);
    });
}

function facturasxdia(fecha){
    $.ajax({
    url:'../metodos/gestion.php',
    type:'POST',
    data:{
      accion:'facturasxdia',
      fecha:fecha
    },
    })
    .done(function(resultado){
      $("#facturasdia").html(resultado);
    });
}
  


</script>
