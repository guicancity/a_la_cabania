<?php

require_once('../metodos/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
  require_once('../metodos/links.php');
  require_once('menu.php');
?>
  <title>DETALLE PRODUCTO | LA CABAÑA</title>
</head>
<body>
  <div class="container">
    <div class="row mt-3 mb-4">
      <div class="col">
        
        
    
      </div>
    </div>
    <input type="text"  class="form-control mt-3 mb-4" autofocus id="txtBuscar" placeholder="escriba...">
    <div id="tabla"></div>
  </div>





<body>
  <div class="container mt-4">
    <section class="mb-4">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Direccion</th>
            <th>Activo</th>
            <th>Posición</th>
            <th>Nivel Permiso</th>
            
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = mysqli_query(
            $conexion,
            "SELECT
              NOMBRE,
              DIRECCION,
              ACTIVO,
              POSICION,
              NIVELPERMISO
            FROM MENU
            "
          );
          while ($row = mysqli_fetch_array($query)) {
            echo "<tr>
              <td>{$row["NOMBRE"]}</td>
              <td>{$row["DIRECCION"]}</td>
              <td>{$row["ACTIVO"]}</td>
              <td>{$row["POSICION"]}</td>
              <td>{$row["NIVELPERMISO"]}</td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  </div>



  

</body>

</html>


