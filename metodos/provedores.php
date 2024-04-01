<?php
//date_default_timezone_set('GMT-5');
date_default_timezone_set('America/Bogota');
	
include('conexion.php');
include('funciones.php');

$accion = $_POST['accion'];
$fechaActual = date('Y-m-d');
$hora = date("H").':'.date("i");
$respuesta = "";
switch ($accion) {
	case 'cargaprovedores':
      $idempresa = $_POST['idempresa'];

      $sql = "SELECT IDEMPRESA,NOMBRES FROM EMPRESA WHERE IDEMPRESA = ".$idempresa;
      $ejec = mysqli_query($conexion,$sql);
      $row =mysqli_fetch_assoc($ejec);

       $respuesta .= "
       
       <div class=\"container mt-4\">
    <div class=\"row\">
        <div class=\"col\">
        <div class=\"form-group\">
            <label>NOMBRE DE LA EMPRESA</label>
            <input class=\"form-control\" type=\"text\" disabled=\"\" value=\"{$row['NOMBRES']}\" name=\"txtNombre\" id=\"txtNombre\">
          </div>
        </div>

        
  </div>
  </div>
</div>";   

$sql = "SELECT IDPERSONAS,IDEMPRESA, NOMBRES, APELLIDOS,TELEFONO FROM PERSONAS WHERE TIPO = 1 AND IDEMPRESA =".$idempresa;
            $ejecutar = mysqli_query($conexion,$sql);
            $row = mysqli_num_rows($ejecutar);

        $respuesta .="
        <table class=\"table table-hover\">
            <thead>
                <tr>
                    
                    <th >NOMBRE PROVEDOR</th>
                    <th >TELEFONO</th>
                    <th class=\"text-end\">OPCIONES</th>
                </tr>
                <tbody>";
        while($fila = mysqli_fetch_array($ejecutar)){
     $respuesta.="              
   <tr>
            
        <td>{$fila['NOMBRES']} {$fila['APELLIDOS']}</td>
        <td >{$fila['TELEFONO']}</td>
        <td class=\"text-end\"><div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"><button class=\"editar btn btn-warning\" data-bs-toggle=\"modal\" data-bs-target=\"#editaprovedor\" data-id=\"{$fila['IDPERSONAS']}\"><i class=\"fa-regular fa-pen-to-square\"></i> EDITAR</button></div></td>
        </tr>";
              
}  
$respuesta .='
                </tbody>
            </thead>
        </table>
';


    echo $respuesta;
        
        break;

case 'editarprovedor':
    $idprovedor = $_POST['idprovedor'];

    $sql = "SELECT NOMBRES,APELLIDOS,TELEFONO FROM PERSONAS WHERE TIPO=1 AND IDPERSONAS = ".$idprovedor;
    $ejecuta = mysqli_query($conexion,$sql);
    $fila = mysqli_fetch_assoc($ejecuta);
    $respuesta .="



    <div class=\"row\">
          <div class=\"col\">
          <div class=\"form-group\">
            <label>Nombres</label>
            <input value=\"{$idprovedor}\"  type=\"text\" hidden name=\"txtidprovedoru\" id=\"txtidprovedoru\">
            <input class=\"form-control\" value=\"{$fila['NOMBRES']}\" type=\"text\" name=\"txtnombreproveu\" id=\"txtnombreproveu\">
          </div>
          </div>
           </div>
           <div class=\"row\">
             <div class=\"col\">
              <div class=\"form-group\">
                <label>Apellidos</label>
                <input class=\"form-control\" type=\"text\" value=\"{$fila['APELLIDOS']}\" name=\"txtapellidou\" id=\"txtapellidou\">
              </div>
             </div>
           </div>
           <div class=\"row\">
             <div class=\"col\">
              <div class=\"form-group\">
                <label>Telefono</label>
                <input class=\"form-control\" type=\"text\" value=\"{$fila['TELEFONO']}\" name=\"txttelefonou\" id=\"txttelefonou\">
              </div>
             </div>
           </div>



    ";
    echo $respuesta;

    break;
    case 'insertaprovedor':
	if(!empty($_POST)){
		$idempresa = $_POST['idempresa'];
		$nombres = strtoupper($_POST['nombres']);
		$apellidos = strtoupper($_POST['apellidos']);
		$telefono = $_POST['telefono'];
		$sql ="";
		$sql = mysqli_prepare($conexion,"INSERT INTO PERSONAS(IDEMPRESA,NOMBRES,APELLIDOS,TELEFONO,TIPO) VALUES(?,?,?,?,1)");
		$sql->bind_param('isss',$idempresa,$nombres,$apellidos,$telefono);
		$execute = $sql->execute();

		if($execute){
			echo $idempresa;
		}
	}

	break;

	default:

	break;
}
?>