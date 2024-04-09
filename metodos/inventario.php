
<?php 
date_default_timezone_set('America/Bogota');
  
include('conexion.php');
include('funciones.php');

$accion = $_POST['accion'];
$fechaActual = date('Y-m-d');
$hora = date("H").':'.date("i");
$respuesta = "";
switch ($accion) {
  case 'cargainventario':
   
  $sql = "SELECT P.IDPRODUCTOS,  P.NOMBREPRODUCTO, P.MARCA, P.MEDIDA, P.UNIDAD,B.CANTIDAD BODEGA,E.CANTIDAD ESTANTE FROM PRODUCTOS P
  INNER JOIN BODEGA B ON 
  B.IDPRODUCTOS = P.IDPRODUCTOS
  INNER JOIN ESTANTE E ON 
  E.IDPRODUCTOS = P.IDPRODUCTOS
  WHERE P.ACTIVO = 1
  ORDER BY P.NOMBREPRODUCTO,
             P.MARCA,
             P.MEDIDA
  LIMIT 12";
  if(isset($_POST['dato'])){
      $dato = $_POST['dato'];
      $tipo = $_POST['tipo'];
      $sql = "";
      if(($tipo == 1) && (strlen($dato) > 3 )){
        $sql = "SELECT P.ACTIVO, P.IDPRODUCTOS, P.NOMBREPRODUCTO,P.MARCA, P.MEDIDA, P.UNIDAD,B.CANTIDAD BODEGA,E.CANTIDAD ESTANTE FROM PRODUCTOS P
        INNER JOIN BODEGA B ON 
        P.IDPRODUCTOS = B.IDPRODUCTOS
        INNER JOIN ESTANTE E ON 
        P.IDPRODUCTOS = E.IDPRODUCTOS 
        INNER JOIN DETALLE_PRODUCTOS DP
        ON DP.IDPRODUCTOS = P.IDPRODUCTOS
              WHERE (P.ACTIVO = 1) 
              AND (DP.CODIGOBARRAS LIKE '".$dato."%')
              GROUP BY P.NOMBREPRODUCTO,
              P.MARCA,
              P.MEDIDA,
              P.UNIDAD
              ORDER BY P.NOMBREPRODUCTO,
               P.MARCA,
               P.MEDIDA";

  }else{
    $sql = "
    SELECT P.ACTIVO, P.IDPRODUCTOS, P.NOMBREPRODUCTO,P.MARCA, P.MEDIDA, P.UNIDAD,B.CANTIDAD BODEGA,E.CANTIDAD ESTANTE FROM PRODUCTOS P
      INNER JOIN BODEGA B ON 
      P.IDPRODUCTOS = B.IDPRODUCTOS
      INNER JOIN ESTANTE E ON 
      P.IDPRODUCTOS = E.IDPRODUCTOS 
      INNER JOIN DETALLE_PRODUCTOS DP
      ON DP.IDPRODUCTOS = P.IDPRODUCTOS
    WHERE (P.ACTIVO = 1) 
      AND (P.NOMBREPRODUCTO LIKE '%".$dato."%'
      OR P.MARCA LIKE '%".$dato."%')
    GROUP BY P.NOMBREPRODUCTO,
      P.MARCA,
      P.MEDIDA,
      P.UNIDAD
    ORDER BY P.NOMBREPRODUCTO,
      P.MARCA,
      P.MEDIDA";
  }

}
$ejecutar = mysqli_query($conexion,$sql);
$row = mysqli_num_rows($ejecutar);
if ($row > 0) {
  $respuesta.=
    "<table class= \"table table-hover\">
      <thead>
        <tr>
          <th>NOMBRE</th>
          <th>MARCA</th>
          <th>MEDIDA</th>
          <th>BODEGA</th>
          <th>ESTANTE</th>
          <th>ACTIVO</th>
          <th>BODEGA / ESTANTE / VARIEDADES</th>
        </tr>
      </thead>
    <tbody>";
          
while($fila = mysqli_fetch_array($ejecutar)){
  $idproducto = $fila['IDPRODUCTOS'];
  $nombre = $fila['NOMBREPRODUCTO'];
  $medida = $fila['MEDIDA'];
  $marca = $fila['MARCA'];    
  $unidad = $fila['UNIDAD'];
  $bodega = $fila['BODEGA'];
  $estante = $fila['ESTANTE'];
  $cantidad ="";
  if(($estante<0)){
    $cantidad = "table-dark";
  }else if(($estante==0)){
    $cantidad = "table-danger";
  }else if(($estante>0) && ($estante <10)) {
    $cantidad = "table-info";
  }else if(($estante>=10) && ($estante <20)){
    $cantidad = "table-warning";
  }else{
    $cantidad = "table-success";
  }
$respuesta.= 
  "<tr >
    <td> <button type=\"button\" class=\" btn btn-link producto\" data-id=\"{$idproducto}\">{$nombre}</button></td>
    <td> {$marca} </td>
    <td> {$medida} {$unidad} </td>
    <td> {$bodega} </td>
    <td class=\"{$cantidad}\"> {$estante} </td>
    <td> <button class=\"desactivar btn btn-danger\" data-id=\"{$idproducto}\">DESACTIVAR</button></td>
    <td> <div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"><button class=\"bodega btn btn-info\"  data-idpb=\"{$idproducto}\">AGREGAR</button> <button class=\"estante btn btn-primary\"  data-idpe=\" {$idproducto} \">AGREGAR</button></div>
      <a target=\"_blank\" href=\"../pag/detalleProducto.php?idp= {$idproducto} \" class=\" btn btn-warning\">VARIEDADES</a>
    </td>
  </tr>";
}
$respuesta.=
  "</tbody>
  </table>";
}else{
    $respuesta = "NO SE ENCONTRÓ PRODUCTO";
}
echo $respuesta;
 
 break;
 case 'agregacbodega':
  if(!empty($_POST)){
    $idProducto = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];
    $opcion = 'SUMA';
    echo cambioBodega($conexion,$idProducto,$cantidad,$opcion);
  }
  break;
  case 'cambiaestante':
    if(!empty($_POST)){
    $resp = 2;
    $idProducto = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];
    $opcion = 'RESTA';
    if(cambioBodega($conexion,$idProducto,$cantidad,$opcion)!= 2){
      $cactual = cantidadEstante($conexion,$idProducto);
      $total = $cantidad + $cactual;
      $sql = "UPDATE ESTANTE SET CANTIDAD =".$total. " WHERE IDPRODUCTOS = ".$idProducto;
      $ejecuta = mysqli_query($conexion, $sql);
      $resp = 1;
    }
    echo $resp;
    }
  break;
  case 'seleccionaProductoxId':
    $idProducto = $_POST['idProducto'];
    $respuesta .="
      <form method=\"POST\" class=\"container\">
      <div class=\"row pb-3\">
        <div class=\"col\">
          <h1>Editar producto</h1>
        </div>
      </div>
      <div class=\"row\">
    ";
    $sql = "SELECT E.IDEMPRESA,P.NOMBREPRODUCTO,P.IDEMPRESA,E.NOMBRES,P.MARCA,P.MEDIDA,P.UNIDAD,P.PRECIOCOMPRA,P.VALOR FROM PRODUCTOS P  INNER JOIN EMPRESA E ON E.IDEMPRESA = P.IDEMPRESA WHERE P.IDPRODUCTOS = ". $idProducto;
    $ejec = mysqli_query($conexion,$sql);
    $row = mysqli_fetch_assoc($ejec);
    $respuesta .="
      <div class=\"col\">
      <div class=\"form-group\">
        <label>EMPRESA</label>
          <select class=\"form-select\" id=\"sltEmpresa\">";
            $sql = "SELECT * FROM EMPRESA ORDER BY NOMBRES";
            $ejecuta = mysqli_query($conexion,$sql); 
            $fila1 = mysqli_fetch_assoc($ejecuta) ;  
            
            while($fila = mysqli_fetch_assoc($ejecuta)){
              if($row['IDEMPRESA'] == $fila['IDEMPRESA']){
                $respuesta .= "
                  <option selected value=\"{$fila['IDEMPRESA']}\">{$row['NOMBRES']}</option>";
              }else{
                $respuesta .= "
                  <option value=\"{$fila['IDEMPRESA']}\">{$fila['NOMBRES']}</option>";
              }
            }
    $respuesta .= "
     </select>
       <section id=\"distribuidor\">
        </section>
        </div>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"col\">
        <div class=\"form-group\">
          <label>NOMBRE PRODUCTO</label>
          <input class=\"form-control\" type=\"text\" value=\"{$row['NOMBREPRODUCTO']}\" name=\"txtNombreProducto\" id=\"txtNombreProducto\">
        </div>
      </div>
    </div>

    <div class=\"row\">
      <div class=\"col\">
       <div class=\"form-group\">
          <label>MARCA</label>
          <input class=\"form-control\" type=\"text\" value=\"{$row['MARCA']}\" name=\"txtMarca\" id=\"txtMarca\">
        </div>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"col\">
        <div class=\"form-group\">
          <label>MEDIDA</label>
          <input class=\"form-control\" type=\"text\" value=\"{$row['MEDIDA']}\" name=\"txtMedida\" id=\"txtMedida\">
        </div>
      </div>

      <div class=\"col\">
        <div class=\"form-group\">
          <label>UNIDAD</label>
          <input class=\"form-control\" type=\"text\" value=\"{$row['UNIDAD']}\" name=\"txtUnidad\" id=\"txtUnidad\">
        </div>
      </div>
    </div>

    <div class=\"row mb-3\">
      <div class=\"col\">
        <div class=\"form-group\">
          <label>PRECIO COMPRA</label>
          <input class=\"form-control\" type=\"number\" value=\"{$row['PRECIOCOMPRA']}\" name=\"txtPrecioCompra\" id=\"txtPrecioCompra\">
        </div>
      </div>

      <div class=\"col\">
        <div class=\"form-group\">
          <label>VALOR</label>
          <input class=\"form-control\" type=\"number\" value=\"{$row['VALOR']}\" name=\"txtValor\" id=\"txtValor\">
        </div>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"col\">
        <button type=\"button\" id=\"btnGuardar\" class=\"btn btn-success btn-lg mt-2\">Guardar</button>
      </div>
    </div>
        
  </form>
    ";
  echo $respuesta;
  break;
  
case 'actualizarProducto':
    if (!empty($_POST)){
      $idProducto = $_POST['idProducto'];
      $idEmpresa= $_POST['idEmpresa'];
      $idPersonas= $_POST['idPersonas'];
      $nombreProducto= strtoupper($_POST['nombreProducto']);
      $marca= strtoupper($_POST['marca']);
      $medida= $_POST['medida'];
      $unidad= strtoupper($_POST['unidad']);
      $valor= $_POST['valor'];
      $precioCompra= $_POST['precioCompra'];
      $sql = "UPDATE PRODUCTOS SET IDEMPRESA =".$idEmpresa.",
                    IDPERSONAS =".$idPersonas.",
                    NOMBREPRODUCTO ='".$nombreProducto."',
                    MARCA ='".$marca."',
                    MEDIDA ='".$medida."',
                    UNIDAD ='".$unidad."',
                    VALOR =".$valor.",
                    PRECIOCOMPRA =".$precioCompra.",
                    FECHACREACION ='".$fechaActual."'
          WHERE IDPRODUCTOS =".$idProducto;
      $ejecuta = mysqli_query($conexion,$sql);
      echo $ejecuta;
      }
  break;
case'desactivaproducto':
  if(!empty($_POST)){
    //recepcion de variables
    $idproducto = $_POST['idproducto'];
    if(cantidadEstante($conexion,$idproducto) == 0){
      //creacion del update
      $sql ="";
      $sql = mysqli_prepare($conexion,"UPDATE PRODUCTOS SET ACTIVO = 0 WHERE IDPRODUCTOS = ?");
      $sql->bind_param('i',$idproducto);
      $execute = $sql->execute();
      if($execute){
        echo 1;
      }else{
        echo 2;
      }
    }else{
      echo 3;
    }
    
  }

  break;
  case 'loadDistribuidor':
    $idEmpresa = $_POST['idEmpresa'];
    $idproducto = $_POST['idproducto'];
  $respuesta .="
      <label>DISTRIBUIDOR</label>
      <select class=\"form-select\" id=\"sltIdPersona\">
    ";
    if($idproducto != 'null'){
      $idpersona = idpersonaxproducto($conexion,$idproducto);
      $sqlactual = "SELECT IDPERSONAS, IDEMPRESA,NOMBRES,APELLIDOS FROM PERSONAS WHERE IDEMPRESA = ".$idEmpresa;
      $ejecuta = mysqli_query($conexion,$sqlactual);
    }
    while ($fila =mysqli_fetch_assoc($ejecuta)) {
    if($fila['IDPERSONAS'] == $idpersona ){
      $respuesta .="<option selected value=\"{$fila['IDPERSONAS']}\">{$fila['NOMBRES']} {$fila['APELLIDOS']}</option>";
    }else{
      $respuesta .="<option value=\"{$fila['IDPERSONAS']}\">{$fila['NOMBRES']} {$fila['APELLIDOS']}</option>";
    }
    }
      $respuesta.="</select>";
    echo $respuesta;
    break;
  
  default:
    // code...
    break;
}



?>