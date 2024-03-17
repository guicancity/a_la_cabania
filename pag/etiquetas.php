<?php
require_once('../metodos/conexion.php');
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=etiquetas.csv');


    $salida = fopen("php://output", "w");
    if ($salida) {
        fputs($salida, '"NOMBRE","MARCA","TAMA","UNID","PRECIO"'.PHP_EOL);
        $sql = 'SELECT 
          NOMBREPRODUCTO,
            MARCA ,
            MEDIDA ,
            UNIDAD ,
           CONCAT("$", format(VALOR,0,"de_DE")) AS VALOR
        FROM
            PRODUCTOS
        WHERE
            (FECHACREACION <> "")
        GROUP BY NOMBREPRODUCTO , MARCA , MEDIDA';
        $ejecuta = mysqli_query($conexion,$sql);
            while ($fila =mysqli_fetch_assoc($ejecuta)) {
                fputs($salida, implode($fila, ',').PHP_EOL);
            }

        fclose($salida);

        $sqllimpiatabla = 'UPDATE PRODUCTOS SET FECHACREACION = ""';
        $ejesqllimpiatabla = mysqli_query($conexion,$sqllimpiatabla);
    }
    




?>