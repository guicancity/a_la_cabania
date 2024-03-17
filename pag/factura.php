<?php
require_once('../metodos/conexion.php');
$medidaTicket = 180;


?>
<!DOCTYPE html>
<html>

<head>

    <style>
        * {
            font-size: 12px;
            font-family: 'DejaVu Sans', serif;
        }

        h1 {
            font-size: 18px;
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
            margin: 0 auto;
        }

        td.precio {
            text-align: right;
            font-size: 11px;
        }

        td.cantidad {
            font-size: 11px;
        }

        td.producto {
            text-align: center;
        }

        th {
            text-align: center;
        }


        .centrado {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: <?php echo $medidaTicket ?>px;
            max-width: <?php echo $medidaTicket ?>px;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .ticket {
            margin: 0;
            padding: 0;
        }

        body {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="ticket centrado">
        <h1>AUTOSERVICIO LA CABAÑA</h1>
        <h2>Ticket de venta #12</h2>
        <h2>2020-02-05 00:12:22</h2>
        <?php
        # Recuerda que este arreglo puede venir de cualquier lugar; aquí lo defino manualmente para simplificar
        # Puedes obtenerlo de una base de datos, por ejemplo: https://parzibyte.me/blog/2019/07/17/php-bases-de-datos-ejemplos-tutoriales-conexion/

        $sql = "SELECT DF.CANTIDAD, CONCAT(P.NOMBREPRODUCTO,' ',P.MARCA) NOMBRECOMPLETO, DF.VALORPRODUCTOS,DF.VTOTAL FROM DETALLE_FACTURA DF INNER JOIN PRODUCTOS P ON DF.IDPRODUCTOS = P.IDPRODUCTOS WHERE IDFACTURA = 512";
        $execute = mysqli_query($conexion,$sql);


        $sqltotal = " SELECT SUM(VTOTAL) TOTAL FROM DETALLE_FACTURA  WHERE IDFACTURA = 512";
        $executetotal = mysqli_query($conexion,$sqltotal);
        $rowtotal = mysqli_fetch_assoc($executetotal);

        ?>

        <table>
            <thead>
                <tr class="centrado">
                    <th class="cantidad">CANT</th>
                    <th class="producto">PRODUCTO</th>
                    <th class="precio">$$/u</th>
                    <th class="precio">$$</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = $rowtotal['TOTAL'];
                while ($row = mysqli_fetch_array($execute)) {
                ?>
                
                    <tr>
                        <td class="cantidad"><?php echo $row['CANTIDAD'] ?></td>
                        <td class="cantidad"><?php echo $row['NOMBRECOMPLETO'] ?></td>
                        <td class="cantidad">$<?php echo number_format($row['VALORPRODUCTOS'],0,",",".")  ?></td>
                        <td class="cantidad">$<?php echo number_format($row['VTOTAL'],0,",",".")  ?></td>
                        
                    </tr>
                <?php } ?>
            </tbody>
            <tr>
                
                <td class="producto" colspan="3">
                    <strong>TOTAL</strong>
                </td>
                <td class="precio">
                    $<?php echo number_format($total,0,",",".") ?>
                </td>
            </tr>
        </table>
        <p class="centrado">¡GRACIAS POR SU COMPRA!
            <br>autolacabanaguican@gmail.com</p>
    </div>
</body>

</html>