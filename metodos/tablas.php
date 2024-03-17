<?php 
session_start();
date_default_timezone_set('America/Bogota');
	
require_once('conexion.php');
require_once('funciones.php');

$tabla = $_POST['tabla'];
$respuesta = '';

switch ($tabla) {
	case 'ventasDia':
    $fechaI = $_POST['fechaI'];
  $fechaF = $_POST['fechaF'];
  
	$sql = 'SELECT SUM(VALORTOTAL) SUMA,FECHAVENTA FROM FACTURA WHERE(PAGADO = 1) AND (FECHAVENTA BETWEEN "'.$fechaI.'" AND "'.$fechaF.'") GROUP BY FECHAVENTA';
  $ejecutar = mysqli_query($conexion,$sql);
  $sqlTotal = 'SELECT SUM(VALORTOTAL) SUMA FROM FACTURA WHERE (PAGADO = 1) AND (FECHAVENTA BETWEEN "'.$fechaI.'" AND "'.$fechaF.'")';
  $ejecutarTotal = mysqli_query($conexion,$sqlTotal);
  $total_array = mysqli_fetch_array($ejecutarTotal);

  $sqlabonot = 'SELECT SUM(VALORABONO) ABONO FROM ABONOS WHERE FECHAABONO BETWEEN "'.$fechaI.'" AND "'.$fechaF.'"';
  $ejecabonot = mysqli_query($conexion,$sqlabonot);
  $totalabonot = mysqli_fetch_array($ejecabonot);
  $totalsumaventas = $totalabonot['ABONO'] + $total_array['SUMA'];

  $sumatotal = number_format($totalsumaventas,0,",",".");

		$respuesta .= '<table class="table table-hover">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Ventas</th>
          </tr>
        </thead>
        <tbody>';
         while($fila = mysqli_fetch_array($ejecutar)){
          $fecha = $fila['FECHAVENTA'];
          $sqlabono = "SELECT CASE WHEN
            SUM(VALORABONO) IS NULL THEN 0
            ELSE SUM(VALORABONO)
            END AS ABONO FROM ABONOS WHERE  FECHAABONO = '".$fecha."' GROUP BY FECHAABONO";
          $ejecabono = mysqli_query($conexion,$sqlabono);
          $totalabono = mysqli_fetch_array($ejecabono);
          $sumaventas = $totalabono['ABONO'] + $fila['SUMA'];
          
          $suma = number_format($sumaventas,0,",",".");

    $respuesta.= 
      "<tr>
        <td ><button class=\"btn btn-link\">{$fecha}</button></td>
        <td >$ {$suma}</td>
        
      </tr>";             
        }
    $respuesta.=
      "<tr class=\"border\">
        <td class=\"text-right\"><h2> <b> TOTAL:</b></h2></td>
        <td class=\"border border-success\"><h2> $ {$sumatotal} </h2></td>
      </tr>"; 
    $respuesta.="
      </tbody>
      </table>";
    echo $respuesta;  
	break;

  case 'productosxempresa':
    $idEmpresa = $_POST['idEmpresa'];
    $idPersona = $_POST['idPersona'];
    $empleado = "";
    if($idPersona != null){
      $empleado = ' AND P.IDPERSONAS = '.$idPersona;
    }
    


    $sql = 'SELECT E.NOMBRES, P.IDPRODUCTOS, P.NOMBREPRODUCTO, P.MARCA,P.MEDIDA,P.UNIDAD,P.PRECIOCOMPRA FROM PRODUCTOS P
        INNER JOIN EMPRESA E
        ON P.IDEMPRESA = E.IDEMPRESA WHERE P.IDEMPRESA = '.$idEmpresa.$empleado.' GROUP BY P.NOMBREPRODUCTO, P.MARCA,P.MEDIDA,P.IDPERSONAS ORDER BY P.NOMBREPRODUCTO';
  $ejecutar = mysqli_query($conexion,$sql);

        $respuesta .= '
        <button class="btn btn-primary mt-3 mt-3" id="btnRetroceder"><i class="fa-solid fa-arrow-left-long"></i> Regresar</button>
        <table class="table table-striped">
        <thead>
          <tr>
            <th>Nombre producto</th>
            <th>Marca</th>
            <th>Medida</th>
            <th>Unidad</th>
            <th>Precio compra</th>
          </tr>
        </thead>
        <tbody>';
         while($fila = mysqli_fetch_array($ejecutar)){
            
    $respuesta.= 
    '<tr >
        <td> <button  class="btn btn-link" data-id="'.$fila["IDPRODUCTOS"].'">' .$fila["NOMBREPRODUCTO"].' </button></td>
        <td> <span class="algo2">' .$fila["MARCA"].'</td>
        <td> <span>' .$fila["MEDIDA"].'</span></td>
        <td> <span>' .$fila["UNIDAD"].'</span></td>
        <td class="text-end"> <span class="fs-4"><b> $' . number_format($fila["PRECIOCOMPRA"],0,",",".").'</b></span></td>
    </tr>';             
}
$respuesta.='
        </tbody>
      </table>

      ';

    echo $respuesta;
    
    break;

    case 'loadDistribuidor':
    $idEmpresa = $_POST['idEmpresa'];
    $idproducto = $_POST['idproducto'];
    

    


    $sql = 'SELECT IDPERSONAS, IDEMPRESA,NOMBRES,APELLIDOS FROM PERSONAS WHERE IDEMPRESA = '.$idEmpresa;
    $ejecutar = mysqli_query($conexion,$sql);

    $respuesta .='
        <label>DISTRIBUIDOR</label>
           <select class="form-select" id="sltIdPersona">
          ';

       if($idproducto != 'null'){
        $idpersona = idpersonaxproducto($conexion,$idproducto);
    $sqlactual = "SELECT IDPERSONAS, IDEMPRESA,NOMBRES,APELLIDOS FROM PERSONAS WHERE IDPERSONAS = ".$idpersona;
    $ejecuta = mysqli_query($conexion,$sqlactual);
    $fila = mysqli_fetch_array($ejecuta);




  $respuesta .=' 
<option value="'.$fila['IDPERSONAS'].'">'. $fila['NOMBRES'] .' '.$fila['APELLIDOS'].'</option>
';
}
while ($row =mysqli_fetch_assoc($ejecutar)) {
$respuesta .=
'
<option value="'.$row['IDPERSONAS'].'">'. $row['NOMBRES'] .' '.$row['APELLIDOS'].'</option>
';
    
}
$respuesta.='</select>

    ';
    echo $respuesta;
        
        break;
    case 'loadEmpresas':
    $dato = $_POST['dato'];

    if($dato == null){
       $sql = "SELECT IDEMPRESA,NOMBRES FROM EMPRESA ORDER BY NOMBRES ";
    }else{
      $sql = "SELECT IDEMPRESA,NOMBRES FROM EMPRESA WHERE NOMBRES LIKE '%". $dato ."%'  ORDER BY NOMBRES ";

    }
            $ejecutar = mysqli_query($conexion,$sql);
            $row = mysqli_num_rows($ejecutar);

       $respuesta .='
        <table class="table table-hover">
            <thead>
                <tr>
                    
                    <th>Nombre empresa</th>
                    <th class="text-end">Opciones</th>
                </tr>
                <tbody>';
        while($fila = mysqli_fetch_array($ejecutar)){
     $respuesta.='               
        
        <tr>
            <td> <button type="button" class="editar btn btn-link"  data-bs-toggle="modal" data-bs-target="#datosempresa" data-id="'.$fila['IDEMPRESA'].'">'.$fila['NOMBRES'].'</button></td>
            

                <td class="text-end">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button class="editar btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarprovedores" data-id="'.$fila['IDEMPRESA'].'"><i class="fa-solid fa-dolly"></i> PROVEDORES</button>
                      <button class="productos btn btn-primary" data-bs-toggle="modal" data-bs-target="#verproductos"  data-id="'.$fila['IDEMPRESA'].'"><i class="fa-solid fa-boxes-stacked"></i> PRODUCTOS</button>
                  </div>
                </td>


           
        </tr>';
              
}  
$respuesta .='
                </tbody>
            </thead>
        </table>
';


    echo $respuesta;
        break;
      case 'detalleProductos':
      $idProducto = $_POST['idProducto'];

      $sql = "select NOMBREPRODUCTO,MARCA,MEDIDA,UNIDAD from productos where idproductos = ".$idProducto;
      $ejec = mysqli_query($conexion,$sql);
      $row =mysqli_fetch_assoc($ejec);

       $respuesta .= '
       
       <div class="container mt-4">
    <div class="row">
        <div class="col">
        <div class="form-group">
            <label>NOMBRE DEL PRODUCTO</label>
            <input class="form-control" type="text" disabled="" value="'.  $row['NOMBREPRODUCTO'] .'" name="txtNombre" id="txtNombre">
          </div>
        </div>

        <div class="col">
          <div class="form-group">
            <label>MARCA</label>
            <input class="form-control" type="text" disabled="" value="'.  $row['MARCA'] .'" name="txtMarca" id="txtMarca">
          </div>
        </div>

        <div class="row">
        <div class="col">
        <div class="form-group">
            <label>MEDIDA</label>
            <input class="form-control" type="text" disabled="" value="'.  $row['MEDIDA'] .'" name="txtMedida" id="txtMedida">
          </div>
        </div>

        <div class="col">
          <div class="form-group">
            <label>UNIDAD</label>
            <input class="form-control" type="text" disabled="" value="'.  $row['UNIDAD'] .'" name="txtUnidad" id="txtUnidad">
          </div>
        </div>
  </div>
  </div>
</div>
       ';   

$sql = "SELECT P.IDPRODUCTOS, DP.IDDETALLE_PRODUCTO , DP.CODIGOBARRAS, DP.SABOR 
        FROM PRODUCTOS P
        INNER JOIN DETALLE_PRODUCTOS DP
        ON DP.IDPRODUCTOS = P.IDPRODUCTOS
        WHERE P.IDPRODUCTOS =".$idProducto." ORDER BY DP.SABOR";
            $ejecutar = mysqli_query($conexion,$sql);
            $row = mysqli_num_rows($ejecutar);

        $respuesta .='
        <table class="table table-hover">
            <thead>
                <tr>
                    
                    <th >CODIGO DE BARRAS</th>
                    <th >SABOR</th>
                    <th class="text-end">OPCIONES</th>
                </tr>
                <tbody>';
        while($fila = mysqli_fetch_array($ejecutar)){
     $respuesta.='               
   <tr>
            
        <td> '.$fila['CODIGOBARRAS'].' </td>
        <td > '.$fila['SABOR'].'</td>
        <td class="text-end"><div class="btn-group" role="group" aria-label="Basic example"><button class="editar btn btn-warning" data-bs-toggle="modal" data-bs-target="#editaVariProd" data-id="'.$fila['IDDETALLE_PRODUCTO'].'"><i class="fa-regular fa-pen-to-square"></i> EDITAR</button> <button class="eliminar btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminaVariProd"  data-id="'.$fila['IDDETALLE_PRODUCTO'].'"><i class="fa-solid fa-trash"></i> ELIMINAR</button></div></td>
        </tr>';
              
}  
$respuesta .='
                </tbody>
            </thead>
        </table>
';


    echo $respuesta;
        
        break;

    case 'seleccionaProductoxId':
      $idProducto = $_POST['idProducto'];

    $respuesta .='
        <form method="POST" class="container">
    <div class="row pb-3">
      <div class="col">
        <h1>Editar producto</h1>
      </div>
    </div>

    <div class="row">
    ';

    $sql = 'SELECT P.NOMBREPRODUCTO,P.IDEMPRESA,E.NOMBRES,P.MARCA,P.MEDIDA,P.UNIDAD,P.PRECIOCOMPRA,P.VALOR FROM PRODUCTOS P  INNER JOIN EMPRESA E ON E.IDEMPRESA = P.IDEMPRESA WHERE P.IDPRODUCTOS = '. $idProducto;
    $ejec = mysqli_query($conexion,$sql);
    $row = mysqli_fetch_assoc($ejec);
          
    $respuesta .='
      
      <div class="col">
        <div class="form-group">
          <label>EMPRESA</label>
          <select class="form-select" id="sltEmpresa">';
                $sql = 'SELECT * FROM EMPRESA ORDER BY NOMBRES';
            $ejecuta = mysqli_query($conexion,$sql); 
            $fila1 = mysqli_fetch_assoc($ejecuta) ;  
            $respuesta .='        
            <option selected="true" value="'. $row['IDEMPRESA'] .'">'. $row['NOMBRES'] .'</option>';

            while($fila = mysqli_fetch_assoc($ejecuta)){
      $respuesta .= '
          <option value="'. $fila['IDEMPRESA'] .'">'. $fila['NOMBRES'] .'</option>';
            }
        
          $respuesta .= ' 

           </select>
           <section id="distribuidor">
             
           </section>
           
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>NOMBRE PRODUCTO</label>
          <input class="form-control" type="text" value="'. $row['NOMBREPRODUCTO'].'" name="txtNombreProducto" id="txtNombreProducto">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
       <div class="form-group">
          <label>MARCA</label>
          <input class="form-control" type="text"  value="'. $row['MARCA'].'" name="txtMarca" id="txtMarca">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label>MEDIDA</label>
          <input class="form-control" type="text"  value="'. $row['MEDIDA'].'" name="txtMedida" id="txtMedida">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>UNIDAD</label>
          <input class="form-control" type="text"  value="'. $row['UNIDAD'].'" name="txtUnidad" id="txtUnidad">
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <div class="form-group">
          <label>PRECIO COMPRA</label>
          <input class="form-control" type="number"  value="'. $row['PRECIOCOMPRA'].'" name="txtPrecioCompra" id="txtPrecioCompra">
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label>VALOR</label>
          <input class="form-control" type="number"   value="'. $row['VALOR'].'" name="txtValor" id="txtValor">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <button type="button" id="btnGuardar" class="btn btn-success btn-lg mt-2">Guardar</button>
      </div>
    </div>
        
</form>
    ';



echo $respuesta;


	break;
    case 'editaVarieProdu':
    $IdDetProductos = $_POST['IdDetProductos'];

    $sql = "SELECT SABOR,CODIGOBARRAS FROM DETALLE_PRODUCTOS WHERE IDDETALLE_PRODUCTO = ".$IdDetProductos;
    $ejecuta = mysqli_query($conexion,$sql);
    $fila = mysqli_fetch_assoc($ejecuta);
    $respuesta .='



    <div class="row">
          <div class="col">
          <div class="form-group">
            <label>código de barras</label>
            <input value="'.$IdDetProductos.'"  type="text" hidden name="txtidProductos" id="txtidProductosUp">
            <input class="form-control" value="'.$fila['CODIGOBARRAS'].'" type="text" name="txtCodigoBarrasUp" id="txtCodigoBarrasUp">
          </div>
          </div>
           </div>
           <div class="row">
             <div class="col">
              <div class="form-group">
                <label>variedad</label>
                <input class="form-control" type="text" value="'.$fila['SABOR'].'" name="txtSaborUp" id="txtSaborUp">
              </div>
             </div>
           </div>



    ';
    echo $respuesta;

    break;
    case 'persxcedula':
      $cedulacliente = $_POST['cedulacliente'];
      $sql1 = "SELECT IDPERSONAS,NOMBRES, APELLIDOS,CEDULA FROM PERSONAS WHERE (CEDULA LIKE '".$cedulacliente."%') ORDER BY NOMBRES";
      $sql = mysqli_query($conexion,$sql1);
      $sqlr = mysqli_num_rows($sql);
      if($sqlr > 0){

     
      
      $respuesta .='

      <table class="table table-hover">
      <thead>
          <tr>
              <th>CEDULA</th>
              <th>NOMBRE CLIENTE</th>
              <th>ACCION</th>
          </tr>
      </thead>';
      while( $fila = mysqli_fetch_assoc($sql)){
        

      $respuesta .="
      <tbody>
          <tr>
          <td> {$fila["CEDULA"]}  </td>
          <td> {$fila["NOMBRES"]} {$fila["APELLIDOS"]} </td>
          <td> <button class=\" agregadeudor btn btn-success\" id=\"{$fila['IDPERSONAS']}\">Agregar</button></td>
              
          </tr>
      </tbody>
  


    ";
  }

  $respuesta .="
  </table>
  ";
}else{
  $respuesta .='



  <div class="row">
        <div class="col">
        <div class="form-group">
          <label>Nombres</label>
          <input class="form-control" type="text"  name="txtnombresm" id="txtnombresm">
        </div>
        <div class="form-group">
          <label>Apellidos</label>
          <input class="form-control" type="text"  name="txtapellidosm" id="txtapellidosm">
        </div>
        </div>
         </div>
         <div class="row pt-3">
           <div class="col">
            <button class="btn btn-success" id="btnguardapersona"> Guardar</button>
           </div>
         </div>



  ';

}
    echo $respuesta;

      break;



    case 'd':
    $idempresa = $_POST['idempresa'];

    $sql = "SELECT NOMBRES FROM EMPRESA WHERE   IDEMPRESA = ".$idempresa;
    $ejecuta = mysqli_query($conexion,$sql);
    $fila = mysqli_fetch_assoc($ejecuta);
    $respuesta .='



    <div class="row">
          <div class="col">
            <label>código de barras</label>
            <input value="'.$idEmpresa.'"  type="text" hidden name="txtidProductos" id="txtidProductosUp">
            <input class="form-control" value="'.$fila['NOMBRES'].'" type="text" name="txtCodigoBarrasUp" id="txtCodigoBarrasUp">
          </div>
           </div>

    ';
    echo $respuesta;

    break;
    case 'productosxfactura':
      $idfactura = $_POST['idfactura'];
      
      $sql = "SELECT P.IDPRODUCTOS, P.NOMBREPRODUCTO,DF.VALORPRODUCTOS,DF.CANTIDAD,DF.VTOTAL, DF.FECHAVENTA,DF.HORAFACTURA FROM DETALLE_FACTURA DF INNER JOIN PRODUCTOS P ON P.IDPRODUCTOS = DF.IDPRODUCTOS WHERE IDFACTURA = ". $idfactura." ORDER BY DF.FECHAVENTA DESC";
      $ejecuta = mysqli_query($conexion,$sql);
      $idproductos = "";

      $respuesta .="
      <table class=\"table table-hover\">
								<thead>
									<tr>
										<th>Productos</th>
										<th>Valor</th>
										<th>cantidad</th>
										<th>Total</th>
                    <th>Fecha de venta</th>
									</tr>
								</thead>
								<tbody>";
                while($fila=mysqli_fetch_array($ejecuta)){
                  $valor = number_format($fila["VALORPRODUCTOS"], 0, ",", ".");
                  $valortotal = number_format($fila["VTOTAL"], 0, ",", ".");
                  $fechaventa = date("d/m/Y",strtotime($fila["FECHAVENTA"]));
                $respuesta .="
									<tr>
										<td>{$fila["NOMBREPRODUCTO"]}  </td>
										<td>$ {$valor}</td>
										<td>{$fila["CANTIDAD"]}</td> 
										<td>$ {$valortotal}</td>
                    <td>{$fechaventa}</td>

									</tr>

                  ";
                }
$respuesta .="   
        {$idproductos}               
								</tbody>
							</table>
      
      
      ";
      echo $respuesta;

      break;

  	default: 
		// code...
		break;
}


 ?>

 
