<?php 
	$servidor = "127.0.0.1";
	$base = "LA_CABANIA_2";// POS_A_LA_CABANIA LA_CABANIA_2
	$usuario = "root";
	$password = "";

	$conexion = mysqli_connect($servidor, $usuario, $password) or die ("Error de conexion");
	$db = mysqli_select_db($conexion, $base) or die ("Error de base de datos");
	mysqli_query( $conexion,"SET NAMES 'utf8'");	   
 ?>