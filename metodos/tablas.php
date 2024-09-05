<?php 
session_start();
date_default_timezone_set('America/Bogota');
	
require_once('conexion.php');
require_once('funciones.php');

$tabla = $_POST['tabla'];
$respuesta = '';

switch ($tabla) {
    case 'loadEmpresas':
      $dato = $_POST['dato'];

      if($dato == null){
        $sql = "SELECT IDEMPRESA,NOMBRES FROM EMPRESA ORDER BY NOMBRES ";
      }else{
        $sql = "SELECT IDEMPRESA,NOMBRES FROM EMPRESA WHERE NOMBRES LIKE '%". $dato ."%'  ORDER BY NOMBRES ";
      }
      
      $ejecutar = mysqli_query($conexion,$sql);
      $row = mysqli_num_rows($ejecutar);

      $respuesta .="
      <table class=\"table table-hover\">
        <thead>
          <tr>
            <th>Nombre empresa</th>
            <th class=\"text-end\">Opciones</th>
          </tr>
        <tbody>";
        while($fila = mysqli_fetch_array($ejecutar)){
          $respuesta.="
          <tr>
          <td>
            <button type=\"button\" class=\"editar btn btn-link\"  data-bs-toggle=\"modal\" data-bs-target=\"#datosempresa\" data-id=\"{$fila['IDEMPRESA']}\">{$fila['NOMBRES']}</button>
          </td>
            

                <td class=\"text-end\">
                  <div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                  <a target=\"_blank\" href=\"../pag/provedores.php?idempresa= {$fila['IDEMPRESA']} \" class=\" btn btn-warning\">PROVEDORES</a>  
                  <a target=\"_blank\" href=\"../pag/productos.php?idempresa= {$fila['IDEMPRESA']} \" class=\" btn btn-primary\"> PRODUCTOS</a>
                      
                  </div>
                </td>


           
        </tr>";
              
      }  
  $respuesta .="
                </tbody>
            </thead>
        </table>
";


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
  	default: 
		// code...
		break;
}
 ?>