<?php 
  require_once('../metodos/conexion.php');
  require_once('menu.php');
?>
<head>
<title>VENTA | LA CABAÑA</title>
</head>
<body>
  <div class="container mt-4">
    <div class="row justify-content-md-center">
        <div class="col-12 col-lg-6 mb-4">
      <form onsubmit="return false">
          <input class="form-control shadow" placeholder="escriba..." autofocus autocomplete="off" id="txtBuscar" type="number" name="txtBuscar">
          <button hidden class="btn btn-success shadow" name="btnBuscar" id="btnBuscar"></button>
      </form>
        </div>
      <div class="col-12 col-lg-4">
        <button type="button" class="btn btn-success shadow" data-bs-toggle="modal" data-bs-target="#buscaPro">
          <i class="fa-solid fa-magnifying-glass"></i> BUSCAR PRODUCTO</button>
      </div>
    </div>
     <section id="encabezadoFactura" class="mb-4 ">
     <div class="row  ">
      <div class="col-lg-6">
        <h1 class="text-success display-2  " id="ttotal">TOTAL:</h1>
      </div>
      <div class="col-lg-6  d-flex align-items-center justify-content-end">
          <input class="form-control" type="hidden" name="txtFactura" id="txtFactura">
          <button class="me-4 btn btn-lg btn-warning shadow" id="btnPagar">PAGAR</button>
           <div class="btn-group" role="group" aria-label="Basic example">
          <button class="btn btn-lg btn-dark shadow " data-bs-toggle="modal" data-bs-target="#agregacredito" id="btnCredito">APLICAR CR&Eacute;DITO
          </button>
        </div>
      </div>
    </div>
    </section>
    <section id="factura">
    </section>
  </div>

  <div class="modal fade " id="agregacredito" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Agregar crédito</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label>Número de cédula</label>
          <input type="text" class="form-control" name="txtCedulam" id="txtCedulam">
          <section id="datoscliente"></section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade " id="buscaPro"  tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Agregar producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <label>Nombre producto</label>
            <input type="text" id="txtMDato" class="form-control" >
          </div>
        </div>
        <section id="tablaM" class="mb-4">
     
    </section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  var myModal = document.getElementById('buscaPro');
  var myInput = document.getElementById('txtMDato');
  myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus();
  });

  var myModal2 = document.getElementById('agregacredito');
  var myInput2 = document.getElementById('txtCedulam');
  myModal2.addEventListener('shown.bs.modal', function () {
  myInput2.focus();
  });

  $('#txtMDato').on('keyup',function(e){
    var producto = $('#txtMDato').val();
    if(producto !=""){
      buscar(producto);
    }else{
      buscar();
    }
  })

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
    $('#btnPagar').hide();
    $('#btnCredito').hide();
    creaFactura();
  })

  $('#btnBuscar').on('click',function(e){
    var producto = $('#txtBuscar').val();
    const factura = $('#txtFactura').val();
    insertaAutomatico(producto,factura);
    $('#txtBuscar').val('');
    $('#txtBuscar').focus();
  })

  $(document).on('click','.factura',function(){//AGREGA A FACTURA_TEMP POR MODAL(buscando producto por nombre)
    const producto= $(this).attr('data-id');
    const factura = $('#txtFactura').val();
    insertaAutomatico(producto,factura);
    $('#txtMDato').val('');
    buscar();
  })

  $('#btnPagar').on('click',function(){
    const factura = $('#txtFactura').val();
    const idcliente = 34;
    pagaFactura(factura, 'PAGADO',idcliente);
  })
  
  $(document).on('click','.eliminar',function(e){
    var factura = $('#txtFactura').val();
    var idProducto = $(this).attr("id");
    DeleProduDFT(idProducto,factura);
  })

  $(document).on('click', '.agregadeudor', function(e) {
		const factura = $('#txtFactura').val();
		var idcliente = $(this).attr("id");
			pagaFactura(factura, 'CREDITO',idcliente);
		Toast.fire({
      icon: 'success',
      title: 'Agregado credito!'
      });
    setTimeout( function() { window.open("venta.php","_self"); }, 700 ); 
	})

  $(document).on('click','#btnguardapersona',function(e){
		const factura = $('#txtFactura').val();
		const cedulacliente = $('#txtCedulam').val();
		const nombres = $('#txtnombresm').val();
    const apellidos = $('#txtapellidosm').val();
    if(nombres==""){
      toastr.error("El NOMBRE  no puede ser vacio", "Error!",{
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
      })
      return false;
    }
		agregapersona(factura,cedulacliente,nombres,apellidos);
				
	})

  $(document).on('keyup','.txtCantidadProducto', function() { //change
    var factura = $('#txtFactura').val();
    var idProducto = $(this).attr("id");
    var cantidad = parseInt($(this).val());
    actualizaCantidad(idProducto,cantidad,factura);
  })

  $(document).on('keyup','#txtCedulam',function(){
		const cedulacliente = $('#txtCedulam').val();
		$.ajax({
			url: '../metodos/tablas.php',
			type: 'POST',
			data: {
				tabla: 'persxcedula',
				cedulacliente: cedulacliente,
			}

		})
		.done(function(resultado) {
			$("#datoscliente").html(resultado);
		})

	})

/*Funciones JavaScript*/
  function buscaTotal(facturas){
    $.ajax({
      url:'../metodos/consultasJS.php',
      type:'POST',
      data:{
        accion:'totalFactura',
        factura:facturas
      },
    })
    .done(function(resultado){
      $("#ttotal").text('TOTAL: $'+ resultado);
    })
    $('#txtBuscar').focus();
  }

  function muestraTablaFactura(facturas){
    $.ajax({
      url:'../metodos/tablafactura.php',
      type:'POST',
      data:{
        dato:facturas
      },
    })
    .done(function(resultado){
      $("#factura").html(resultado);
    })
    $('#txtBuscar').focus();
  }

  function insertaAutomatico(codigoBarras,factura){
    $.ajax({
      url:'../metodos/consultasJS.php',
      type:'POST',
      data:{
        accion:'insertaAuto',
        codigoBarras:codigoBarras,
        factura:factura
      },
    success:function(respuesta){
      if(respuesta == 1){
        buscaTotal(factura);
        muestraTablaFactura(factura);
        productosTemp();
      }else{
        Swal.fire({
          title: 'Error',
          text: "EL PRODUCTO NO FUE AGREGADO",
          icon: 'error',
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar'
        });
        buscaTotal(factura);
        muestraTablaFactura(factura);
        productosTemp();
      }
      
    }
    })
  }

  function productosTemp(){
    $.ajax({
      url:'../metodos/consultasJS.php',
      type:'POST',
      data:{
        accion:'ProductosTemp'
      },
    success:function(respuesta){
      if(respuesta > 0){
        $('#btnPagar').show();
        $('#btnCredito').show();
      }else{
        $('#btnPagar').hide();
        $('#btnCredito').hide();
      }
    }
    })
  }

  function DeleProduDFT(idProducto,factura){
    $.ajax({
      url:'../metodos/consultasJS.php',
      type:'POST',
      data:{
        accion:'eliminaProdTemp',
        idProducto:idProducto
      },
    success: function(respuesta){
      buscaTotal(factura);
      muestraTablaFactura(factura);
      productosTemp();
    }
    })
  }

  function actualizaCantidad(idProducto,cantidad,factura){
    $.ajax({
      url:'../metodos/consultasJS.php',
      type:'POST',
      data:{
        accion:'actualizaTotal',
        idProducto:idProducto,
        cantidad:cantidad
      },
    success: function(respuesta){
      buscaTotal(factura);
      muestraTablaFactura(factura);
      productosTemp();
    }
    })      
  }
  function creaFactura(){
    $.ajax({
      url: '../metodos/consultasJS.php',
      type: 'POST',
      data: {
        accion:'creafactura'
      },
    success: function(respuesta){
      $('#txtFactura').val(respuesta);
      var factura = $('#txtFactura').val();
      buscaTotal(factura);
      muestraTablaFactura(factura);
      productosTemp();
    }
    });
  }

  function buscar(dato){
    $.ajax({
      url:'../metodos/buscaventa.php',
      type:'POST',
      data:{
        dato:dato
      },
    })
    .done(function(resultado){
      $("#tablaM").html(resultado);
    })
  }

  function pagaFactura(factura, tipo,idcliente) {
    $.ajax({
      url: '../metodos/consultasJS.php',
      type: 'POST',
      data: {
        accion: 'pagaFactura',
        factura: factura,
        tipo: tipo,
        idcliente:idcliente
      },
    success: function(respuesta) {
      creaFactura();
      buscaTotal(factura);
      muestraTablaFactura(factura);
      productosTemp(factura);
    }
    });
  }

  function agregapersona(factura,cedulacliente,nombres,apellidos){
		$.ajax({
			url: '../metodos/consultasJS.php',
			type: 'POST',
			data: {
				accion: 'agregacliente',
				cedulacliente:cedulacliente,
				nombres:nombres,
        apellidos:apellidos
			},
		success: function(respuesta) {
			var tipo = "CREDITO";
			pagaFactura(factura, tipo,respuesta);
			Toast.fire({
        icon: 'success',
        title: 'Credito agregado !'
      });
      setTimeout( function() { window.open("venta.php","_self"); }, 700 );
		},
		error: function(respuesta){
			toastr.error("Error al agregar persona", "Error!",{
        "progressBar":true,
        "closeButton":true,
        "timeOut":2000
      });
		}
		})
  }
</script>
</body>
</html>