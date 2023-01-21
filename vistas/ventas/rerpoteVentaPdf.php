<?php
require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";
require_once "../../clases/Constants.php";

$objv = new ventas();

$myObjConstants = new Constants();


$c = new conectar();
$conexion = $c->conexion();


/***RECIBIENDO LAS VARIABLE DE LA FECHA */
$fechaInit = date("Y-m-d", strtotime($_GET['fechaIngreso']));
$fechaFin  = date("Y-m-d", strtotime($_GET['fechaFin']));

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
	<title>Reporte de venta</title>
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


		<!-- <tr>
			<td>Fecha final: <?php echo $fechaFin ?></td>
		</tr> -->
		<!-- <tr>
			<td>Folio: <?php echo $folio ?></td>
		</tr>
		<tr>
			<td>cliente: <?php echo $objv->nombreCliente($idcliente); ?></td>
		</tr> -->
	</table>


	<table id="report" style=" border-collapse: collapse; padding-bottom: 5px">
		<tr>
			<td><strong>Folio venta</strong></td>
			<td><strong>Producto</strong></td>
			<td><strong>Precio</strong></td>
			<td><strong>Cantidad kg/pieza</strong></td>
			<td><strong>Descripcion</strong></td>
			<td><strong>Cliente</strong></td>
			<td><strong>Fecha</strong></td>

		</tr>

		<?php
		$sql = "SELECT ve.id_venta,
							ve.fechaCompra,
							ve.id_cliente,
							art.nombre,
							art.precio,
							art.descripcion,

							client.nombre,
							client.apellido,
							ve.cantidad,
							ve.precio
							from ventas  as ve 
							inner join articulos as art
							on ve.id_producto=art.id_producto 
							inner join clientes as client
							on ve.id_cliente=client.id_cliente where `fechaCompra` BETWEEN '$fechaInit 00:00:00' AND '$fechaFin 23:59:59' ORDER BY `id_venta` ASC";


		$result = mysqli_query($conexion, $sql);
		$total = 0;
		while ($mostrar = mysqli_fetch_row($result)) {
		?>

			<tr>
				<td><?php echo $mostrar[0]; ?></td>

				<td><?php echo $mostrar[3]; ?></td>
				<td><?php echo "$" . number_format($mostrar[9], 2, '.', ',') ?></td>
				<td><?php echo $mostrar[8]; ?></td>
				<td><?php echo $mostrar[5]; ?></td>
				<td><?php echo $mostrar[6] . " " . $mostrar[7]; ?></td>
				<td>

					<?php
					date_default_timezone_set('America/Mexico_City');
					$date = date_create($mostrar[1]);
					echo date_format($date, "Y/m/d H:i:s");
					?>

				</td>


			</tr>

		<?php
			$total = $total + $mostrar[4];
		};
		?>
	</table>

	<p><strong> Total: <?php echo "$" . $total; ?></strong></p>
</body>

</html>