<?php
require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";
require_once "../../clases/Constants.php";

$objv = new ventas();

$myObjConstants = new Constants();


$c = new conectar();
$conexion = $c->conexion();
$idventa = $_GET['idventa'];
// $fechaInicio = $_GET['finicio'];
// $fechaFinal = $_GET['ffinal'];

$sql = "SELECT ve.id_venta,
		ve.fechaCompra,
		ve.id_cliente,
		art.nombre,
        art.precio,
        art.descripcion
	from ventas  as ve 
	inner join articulos as art
	on ve.id_producto=art.id_producto
	and ve.id_venta='$idventa'";

$result = mysqli_query($conexion, $sql);

$ver = mysqli_fetch_row($result);

$folio = $ver[0];
$fecha = $ver[1];
$idcliente = $ver[2];

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
		<!-- <tr>
			<td>Fecha inicio: <?php echo $fechaInicio ?></td>
		</tr>
		<tr>
			<td>Fecha final: <?php echo $fechaFinal ?></td>
		</tr> -->
		<tr>
			<td>Fecha inicio: <?php echo $fecha ?></td>
		</tr>
		<tr>
			<td>Folio: <?php echo $folio ?></td>
		</tr>
		<tr>
			<td>cliente: <?php echo $objv->nombreCliente($idcliente); ?></td>
		</tr>
	</table>


	<table id="report"style=" border-collapse: collapse; padding-bottom: 5px">
		<tr>
			<td><strong>Producto</strong></td>
			<td><strong>Precio</strong></td>
			<td><strong>Cantidad</strong></td>
			<td><strong>Descripcion</strong></td>
		</tr>

		<?php
		$sql = "SELECT ve.id_venta,
						ve.fechaCompra,
						ve.id_cliente,
						art.nombre,
				        art.precio,
				        art.descripcion
					from ventas  as ve 
					inner join articulos as art
					on ve.id_producto=art.id_producto
					and ve.id_venta='$idventa'";

		$result = mysqli_query($conexion, $sql);
		$total = 0;
		while ($mostrar = mysqli_fetch_row($result)) :
		?>

			<tr>
				<td><?php echo $ver[3]; ?></td>
				<td><?php echo $ver[4]; ?></td>
				<td>1</td>
				<td><?php echo $ver[5]; ?></td>
			</tr>
		<?php
			$total = $total + $ver[4];
		endwhile;
		?>
		
	</table>

	<p><strong> Total: <?php echo "$" . $total; ?></strong></p>
</body>

</html>