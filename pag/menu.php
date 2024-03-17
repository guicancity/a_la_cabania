<!DOCTYPE html>
<?php
require('../metodos/conexion.php');
require_once('../metodos/session.php');
  $sql1 = 'SELECT * FROM MENU WHERE ACTIVO = 1 ORDER BY POSICION';
  $sql = mysqli_query($conexion,$sql1);
?>
<html>
<head>
  <?php 
  include_once("../metodos/links.php");
  ?>  
</head>
<style type="text/css">
.navbar-toggler-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgb(0,0,0)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");

}
.navbar-toggler {
  border-color: rgb(0,0,0);
} 
</style>

<body>
  <?php 
  include_once('banner.php');
  ?>

  
  <nav class="navbar navbar-expand-lg  bg-info">
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
    <div id="navbarNavDropdown" class="collapse navbar-collapse">
      <ul class="navbar-nav  ">
       <?php
          while ($fila = mysqli_fetch_array($sql)){
          $nombre = $fila['NOMBRE'];
          $url = $fila['DIRECCION'];
          $posicion = $fila['POSICION'];
          echo '<li class="nav-item"> <a class="nav-link p-3 text-white"  href="'.$url.'">'.$nombre.'</a> </li>';
          }
      ?>
      </ul>
    </div>
  </nav>
