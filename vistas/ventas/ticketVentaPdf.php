<?php
require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";
require_once "../../clases/Constants.php";

$myObjConstants = new Constants();


$objv = new ventas();


$c = new conectar();
$conexion = $c->conexion();
$idventa = $_GET['idventa'];

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
		@page {
			margin-top: 0.3em;
			margin-left: 0.9em;

		}

		body {
			font-size: xx-small;
		}
	</style>

</head>

<body>
	<img width="100" height="50" src=
		<?php
			$host = $myObjConstants->getHost();
			$routeImg = "/img/imagen23.jpeg";
			$absoluteUrlImage = $host.$routeImg; 
			echo $absoluteUrlImage;
		?>>
	<p style="text-align: center;"><strong>"Grupo Tecnolavado"</strong>
		
	</p>
	<p>Lavandería y Tintorería</p>
	<p> Calle Miguel Hidalgo Centro Acatlan</p>

	<p><strong>
		Fecha: <?php echo $fecha; ?>
	</p></strong>
	<p><strong>
		Folio: <?php echo $folio ?>
	</p></strong>
	<p><strong>
		Cliente: <?php echo $objv->nombreCliente($idcliente); ?>
	</p></strong>

	<table style="border-color: black;" border="1">
		<tr>
			<td>Descripción</td>
			<td>Precio</td>
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
		while ($mostrar = mysqli_fetch_row($result)) {
		?>
			<tr>
				<td><?php echo $mostrar[3]; ?></td>
				<td><?php echo $mostrar[4] ?></td>
			</tr>
		<?php
			$total = $total + $mostrar[4];
		}
		?>
		<tr>
			<td>Total: <?php echo "$" . $total ?></td>
		</tr>
	</table>
	<p style="text-align: center;">
		"Es un placer servirle"
	</p>
	<p style="text-align: center;">
		Tel:9531294506
	</p>
	<p>Horario 8:00am-9:00pm</p>
</body>

</html>