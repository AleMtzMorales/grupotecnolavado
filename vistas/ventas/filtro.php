<?php
sleep(1);
require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";

$c = new conectar();
$conexion = $c->conexion();

$obj = new ventas();

// $sql = "SELECT id_venta,
// 				fechaCompra,
// 				id_cliente 
// 			from ventas group by id_venta";
// $result = mysqli_query($conexion, $sql);


/**
 * Nota: Es recomendable guardar la fecha en formato año - mes y dia (2022-08-25)
 * No es tan importante que el tipo de fecha sea date, puede ser varchar
 * La funcion strtotime:sirve para cambiar el forma a una fecha,
 * esta espera que se proporcione una cadena que contenga un formato de fecha en Inglés US,
 * es decir año-mes-dia e intentará convertir ese formato a una fecha Unix dia - mes - año.
 */

 
$fechaInit = date("Y-m-d", strtotime($_POST['f_ingreso']));
$fechaFin = date("Y-m-d", strtotime($_POST['f_fin']));

$sqlVentas = ("SELECT id_venta, fechaCompra, id_cliente  FROM ventas WHERE `fechaCompra` BETWEEN '$fechaInit' AND '$fechaFin' group by id_venta");
// $sql = "SELECT id_venta,
// 				fechaCompra,
// 				id_cliente 
// 			from ventas group by id_venta";	

$query = mysqli_query($conexion, $sqlVentas);
//print_r($sqlTrabajadores);
// $total = mysqli_num_rows($query);
// echo '<strong>Total: </strong> (' . $total . ')';
?>


<table class="table table-hover table-condensed table-bordered" style="text-align: center;">
    <caption><label>
            <?php $total = mysqli_num_rows($query);
            echo '<strong>Ventas, total: </strong> (' . $total . ')'; ?>
        </label></caption>
    <thead>
        <tr>
            <td>Folio</td>
            <td>Fecha</td>
            <td>Cliente</td>
            <td>Total de compra</td>
            <td>Ticket</td>
        </tr>
    </thead>
    <?php
    while ($ver = mysqli_fetch_array($query)) { ?>
        <tbody>
            <tr>
            <tr>
                <td><?php echo $ver["id_venta"] ?></td>
                <td><?php echo $ver["fechaCompra"] ?></td>
                <td>
                    <?php
                    if ($obj->nombreCliente($ver["id_cliente"]) == " ") {
                        echo "S/C";
                    } else {
                        echo $obj->nombreCliente($ver["id_cliente"]);
                    }
                    ?>
                </td>
                <td>
                    <?php
                    echo "$" . $obj->obtenerTotal($ver["id_venta"]);
                    ?>
                </td>
                <td>
                    <a href="../procesos/ventas/crearTicketPdf.php?idventa=<?php echo $ver["id_venta"] ?>" class="btn btn-danger btn-sm">
                        Ticket <span class="glyphicon glyphicon-list-alt"></span>
                    </a>
                </td>
                <!-- <td>
							<a href="../procesos/ventas/crearReportePdf.php?idventa=<?php echo $ver["id_venta"] . "&finicio=" . mktime(0, 0, 0, date("m") - 1, date("d"),   date("Y")) . "&ffinal=" . mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")) ?>" class="btn btn-danger btn-sm">
								Reporte <span class="glyphicon glyphicon-file"></span>
							</a>
						</td> -->
            </tr>
            </tr>
        </tbody>
    <?php } ?>
</table>



















</table>