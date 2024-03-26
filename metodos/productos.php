<?php 
session_start();
date_default_timezone_set('America/Bogota');
require_once('conexion.php');
require_once('funciones.php');

$opcion = $_POST['opcion'];
$respuesta = '';

switch ($opcion) {
	case 'productosxempresa':
      $dato = $_POST['dato'];
      $empresa = $_POST['empresa'];

      if($dato == null){
        $sql = "SELECT P.NOMBREPRODUCTO, P.MARCA,P.MEDIDA,P.UNIDAD,B.CANTIDAD CANTIDADB,E.CANTIDAD CANTIDADE FROM PRODUCTOS P
	                INNER JOIN ESTANTE E ON P.IDPRODUCTOS = E.IDPRODUCTOS
	                INNER JOIN BODEGA B ON P.IDPRODUCTOS = B.IDPRODUCTOS 
                WHERE P.IDEMPRESA = ". $empresa . " 
                ORDER BY E.CANTIDAD DESC ";
      }else{
        $sql = "SELECT P.NOMBREPRODUCTO, P.MARCA,P.MEDIDA,P.UNIDAD,B.CANTIDAD CANTIDADB,E.CANTIDAD CANTIDADE FROM PRODUCTOS P
                  INNER JOIN ESTANTE E ON P.IDPRODUCTOS = E.IDPRODUCTOS
                  INNER JOIN BODEGA B ON P.IDPRODUCTOS = B.IDPRODUCTOS
				        WHERE (P.IDEMPRESA = ". $empresa . ") 
					        AND (P.NOMBREPRODUCTO LIKE '%". $dato ."%' 
						      OR P.MARCA LIKE '%". $dato ."%') 
				        ORDER BY E.CANTIDAD DESC ";
      }
      
      $ejecutar = mysqli_query($conexion,$sql);
      $row = mysqli_num_rows($ejecutar);

      $respuesta .="
      <table class=\"table table-hover\">
        <thead>
          <tr>
            <th>NOMBRE</th>
            <th>MARCA</th>
            <th>MEDIDA</th>
            <th>CANTIDAD BODEGA</th>
            <th>CANTIDAD ESTANTE</th>
          </tr>
        <tbody>";
        while($fila = mysqli_fetch_array($ejecutar)){
          $respuesta.="
          <tr>
          <td>{$fila['NOMBREPRODUCTO']}</button>
          <td>{$fila['MARCA']}</button>
          <td>{$fila['MEDIDA']}{$fila['UNIDAD']}</button>
          <td>{$fila['CANTIDADB']}</button>
          <td>{$fila['CANTIDADE']}</button>
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
		break;
	
	default:
		// code...
		break;
}



 ?>