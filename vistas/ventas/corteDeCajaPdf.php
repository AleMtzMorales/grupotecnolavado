<?php
require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";
require_once "../../clases/Constants.php";

$obj = new ventas();
$myObjConstants = new Constants();
$c = new conectar();
$conexion = $c->conexion();

$totalVentasPagadas = 0;
$totalEgresos = 0;

/***RECIBIENDO LAS VARIABLE DE LA FECHA */
$fechaInit = date("Y-m-d", strtotime($_GET['fechaIngresoCorteDeCaja']));
$fechaFin  = date("Y-m-d", strtotime($_GET['fechaFinCorteDeCaja']));

$total = 0; // Total para ventas pagadas
$sqlVentas = ("SELECT ve.id_venta,
ve.fechaCompra,
ve.cantidad,
  ve.precio,
ve.estatus,
user.apellido,
user.nombre
from ventas  as ve
    inner join articulos as art
on ve.id_producto=art.id_producto
    inner join usuarios as user
on ve.id_usuario=user.id_usuario
    inner join clientes as client
on ve.id_cliente=client.id_cliente 
WHERE ve.estatus =1 AND `fechaCompra` 
BETWEEN '$fechaInit 00:00:00' AND '$fechaFin 23:59:59' group by id_venta DESC");


$sqlForPendingSales = "SELECT ve.id_venta,
								ant.fechaAnticipo,
								ve.cantidad,
								ant.anticipo,
								ve.estatus,
								user.apellido,
								user.nombre,
								ve.fechaCompra
						from anticipos as ant
							inner join ventas  as ve
								on ant.id_venta = ve.id_venta
							inner join usuarios as user
								on ve.id_usuario=user.id_usuario
						where `fechaCompra` 
BETWEEN '$fechaInit 00:00:00' AND '$fechaFin 23:59:59'AND ve.estatus = 2 OR ve.estatus = 0 ORDER BY ve.id_venta DESC";

$sqlEgresos = "SELECT
				eg.id_egreso,
				eg.totalEgreso,
				eg.fechaEgreso,
				eg.descripcion,
				user.apellido,
				user.nombre
			FROM egresos as eg
				inner join usuarios as user
					on eg.id_usuario=user.id_usuario
                    WHERE `fechaEgreso` 
BETWEEN '$fechaInit 00:00:00' AND '$fechaFin 23:59:59'
			ORDER BY eg.id_egreso DESC";



$resultFilter = mysqli_query($conexion, $sqlVentas);
$resultVentaParcial = mysqli_query($conexion, $sqlForPendingSales);
$resultEgresos = mysqli_query($conexion, $sqlEgresos);


// $idventa = $_GET['idventa'];
// $fechaInicio = $_GET['finicio'];
// $fechaFinal = $_GET['ffinal'];

// $sql = "SELECT ve.id_venta,
// 		ve.fechaCompra,
// 		ve.id_cliente,
// 		art.nombre,
//         art.precio,
//         art.descripcion
// 	from ventas  as ve 
// 	inner join articulos as art
// 	on ve.id_producto=art.id_producto
// 	where `fechaCompra` BETWEEN '$fechaInit' AND '$fechaFin'";


// $result = mysqli_query($conexion, $sql);

// $ver = mysqli_fetch_row($result);

// $folio = $ver[0];
// $fecha = $ver[1];
// $idcliente = $ver[2];

?>
<!DOCTYPE html>
<html>

<head>
	<title>Corte de caja</title>
	<style type="text/css">
		#report {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		#report td,
		#report th {
			border: 1px solid #ddd;
			padding: 8px;
		}

		#report tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#report tr:hover {
			background-color: #ddd;
		}

		#report th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #04AA6D;
			color: white;
		}
	</style>


</head>

<body>
	<img width="160" height="80" src=<?php
										$host = $myObjConstants->getHost();
										$routeImg = "/img/imagen23.jpeg";
										$absoluteUrlImage = $host . $routeImg;
										echo $absoluteUrlImage;
										?>>

	<br>
	<table id="report" style="text-align: center; padding-bottom: 5px">
		<tr>
			<td> Reporte de ventas con fechas del <?php echo "<strong>$fechaInit</strong>" ?> al <?php echo "<strong>$fechaFin</strong>" ?> <br></td>
		</tr>

		<tr>
			<td> Fecha de creacion:
				<strong>
					<?php
					date_default_timezone_set('America/Mexico_City');
					$date = date_create();
					echo date_format($date, "Y/m/d H:i:s");
					?>
				</strong>
			</td>
		</tr>
	</table>

	<br>


	<br>
	<!-- End component for search sales-->
	<div id="ventas-reportes" style="padding-right: 15px;">
		<table id="report" style="text-align: center;">
			<caption>
				<!-- <label>
                <?php
				$total = mysqli_num_rows($resultFilter);
				$total2 = mysqli_num_rows($resultVentaParcial);
				echo '<strong>Ingresos, total: </strong> (' . $total + $total2 . ')'; ?>
            </label> -->
			</caption>
			<thead>
				<tr>
					<!-- <td><strong>Folio</strong></td> -->
					<td><strong>Fecha y hora</strong></td>
					<!-- <td><strong>Cliente</strong></td> -->
					<td><strong>Total de compra</strong></td>
					<td><strong>Tipo de movimiento</strong></td>
					<!-- <td><strong>Descripció de movimiento</strong></td> -->
					<td><strong>Movimiento hecho por</strong></td>



					<!-- <td><strong>Estatus</strong></td> -->
					<!-- <td><strong>Ticket</strong></td> -->
					<!-- <td><strong>Historial de pagos</strong></td> -->
				</tr>
			</thead>
			<?php while ($ver = mysqli_fetch_row($resultFilter)) : ?>

				<tbody id="ventas-reportes-tbody">
					<tr>

						<td><?php echo $ver[1] ?></td>
						<td>
							<?php
							echo "$" . number_format($obj->obtenerTotal($ver[0]),  2, '.', ',');
							?>
						</td>

						<td>
							<?php
							echo '<span class="label label-success">Ingreso</span>';
							?>
						</td>

						<td>
							<?php
							echo $ver[5] . " " . $ver[6];
							?>
						</td>

					</tr>
				</tbody>

				<?php $totalVentasPagadas = $totalVentasPagadas + $obj->obtenerTotal($ver[0]); ?>

			<?php endwhile; ?>


			<?php while ($verVentasParciales = mysqli_fetch_row($resultVentaParcial)) : ?>

				<tbody id="ventas-reportes-tbody">
					<tr>

						<td><?php echo $verVentasParciales[1] ?></td>
						<td>
							<?php
							echo "$" . number_format($verVentasParciales[3],  2, '.', ',');
							?>
						</td>

						<td>
							<?php
							echo '<span class="label label-success">Ingreso</span>';
							?>
						</td>


						<td>
							<?php
							echo $verVentasParciales[5] . " " . $verVentasParciales[6];
							?>
						</td>
					</tr>
				</tbody>
				<?php $totalVentasPagadas = $totalVentasPagadas + $verVentasParciales[3] ?>
			<?php endwhile; ?>
		</table>
		<h4><strong> Total de ingresos: <?php echo "$" . number_Format($totalVentasPagadas, 2, '.', ',') ?></strong> </h4>
		<br>
	</div>


	<!--Tabla de egresos-->
	<div style="padding-right: 15px;">
		<table id="report" style="text-align: center;">
			<caption>
				<!-- <label>
                <?php $totalEgreso = mysqli_num_rows($resultEgresos);
				echo '<strong>Egresos, total: </strong> (' . $totalEgreso . ')'; ?>
            </label> -->
			</caption>
			<thead>
				<tr>
					<td><strong>Fecha y hora</strong></td>
					<td><strong>Total</strong></td>
					<td><strong>Tipo de movimiento</strong></td>
					<td><strong>Descripción de movimiento</strong></td>
					<td><strong>Movimiento hecho por</strong></td>
				</tr>
			</thead>
			<?php while ($verEgresos = mysqli_fetch_row($resultEgresos)) : ?>

				<tbody id="ventas-reportes-tbody">
					<tr>
						<td><?php echo $verEgresos[2] ?></td>
						<td>
							<?php
							echo "$" . number_format($verEgresos[1],  2, '.', ',');
							?>
						</td>

						<td>
							<?php
							echo '<span class="label label-danger">Egreso</span>';
							?>
						</td>
						<td>
							<?php
							echo $verEgresos[3];
							?>
						</td>


						<td>
							<?php
							echo $verEgresos[4] . " " . $verEgresos[5];
							?>
						</td>
					</tr>
					<?php $totalEgresos = $totalEgresos + $verEgresos[1] ?>

				</tbody>
			<?php endwhile; ?>
		</table>
		<h4><strong> Total de egresos: <?php echo "$" . number_Format($totalEgresos, 2, '.', ',') ?></strong> </h4>
		<br>
		<table id="report" style="text-align: center; padding-bottom: 5px">
			<tr>
				<td>
					<h3>
						<strong>
							Total del corte : <?php
												echo "$" . number_Format($totalVentasPagadas - $totalEgresos, 2, '.', ',');
												?>
						</strong>
					</h3>
				</td>
			</tr>
		</table>
	</div>


	<!-- <p><strong> Total de ventas parciales: <?php echo "$" . number_Format($totalForPendingSales, 2, '.', ',') ?></strong></p>
	<br>
	<table id="report" style="text-align: center; padding-bottom: 5px">
		<tr>
			<td>
				<p><strong>Total venta : <?php echo "$" . number_Format($totalForPendingSales + $total, 2, '.', ',') ?></strong></p>
			</td>
		</tr>
	</table> -->
</body>

</html>