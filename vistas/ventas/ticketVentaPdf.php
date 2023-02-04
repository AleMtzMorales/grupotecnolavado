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
        art.descripcion,
		ve.precio,
		ve.cantidad,
		ve.estatus
	from ventas  as ve 
	inner join articulos as art
	on ve.id_producto=art.id_producto
	and ve.id_venta='$idventa'";

$result = mysqli_query($conexion, $sql);

$ver = mysqli_fetch_row($result);

$folio = $ver[0];
$fecha = $ver[1];
$idcliente = $ver[2];
$estatusVenta = 1;

$anticipo = 0;
$faltante = 0;
//validando estatus pagado
if ($ver[8] == 0) {
	$estatusVenta = 0;
} else {
	$estatusVenta = 1;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Reporte de venta</title>
	<style type="text/css">
		/* @page {
			margin-top: 0.1em;
			margin-left: 0.1em;

		} */

		@page {
			margin: 13px 5px 5px 5px !important;
			padding: 0px 0px 0px 0px !important;
		}

		body {
			font-size: xx-small;
		}
	</style>

	<style type="text/css">
		#report {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		#report td,
		#report th {
			border: 1px solid #ddd;
			padding: 1px;
		}

		#report tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#report tr:hover {
			background-color: #ddd;
		}

		#report th {
			padding-top: 10px;
			padding-bottom: 10px;
			text-align: left;
			background-color: #04AA6D;
			color: white;
		}

		p {
			line-height: 0.9
		}
	</style>

</head>

<body>

	<div style="text-align: center; align-items: center; align-items: center;">
		<img width="100" height="50" src=<?php
											$host = $myObjConstants->getHost();
											$routeImg = "/img/imagen23.jpeg";
											$absoluteUrlImage = $host . $routeImg;
											echo $absoluteUrlImage;
											?>>
		<p style="text-align: center;">
			<strong>"Grupo Tecnolavado"</strong>
		</p>
		<p>Lavandería y Tintorería</p>
		<p> Calle Miguel Hidalgo Centro Acatlan</p>

		<p><strong>
				Fecha y hora: </strong>
			<?php echo $fecha; ?>
		</p>

		<p><strong>
				Folio: <?php echo $folio ?>
		</p></strong>
		<p><strong>
				Estatus:
			</strong>
			<?php 
			if( $estatusVenta == 0){
				echo "Pediente"; 
			}else if($estatusVenta ==2 ){
				echo "Liquidado"; 
			}else{
				echo "Pagado"; 

			}

			
			?>
		</p>
		<p><strong>
				Cliente: </strong><?php echo $objv->nombreCliente($idcliente); ?>
		</p>

	</div>

	<table id="report" class="table table-hover table-condensed table-bordered" style="text-align: center;">
		<tr>
			<td><strong>kg/piezas</strong></td>
			<td><strong>Descripción</strong></td>
			<td><strong>Precio</strong></td>
		</tr>
		<?php




		if ($estatusVenta == 0) {
			$sql = 	"SELECT ve.id_venta,
							ve.fechaCompra,
							ve.id_cliente,
							art.nombre,
							art.precio,
							art.descripcion,
							ve.precio,
							ve.cantidad,
							ve.estatus,
							ant.anticipo
						from ventas  as ve 
						inner join articulos as art
						on ve.id_producto=art.id_producto
						and ve.id_venta='$idventa'
						inner join anticipos as ant
						on ve.id_venta=ant.id_venta";
		} else {
			$sql = "SELECT ve.id_venta,
							ve.fechaCompra,
							ve.id_cliente,
							art.nombre,
					        art.precio,
					        art.descripcion,
							ve.precio,
							ve.cantidad
						from ventas  as ve 
						inner join articulos as art
						on ve.id_producto=art.id_producto
						and ve.id_venta='$idventa'";
		}

		$result = mysqli_query($conexion, $sql);
		$total = 0;

		while ($mostrar = mysqli_fetch_row($result)) {
		?>
			<tr>
				<td><?php echo $mostrar[7]; ?></td>
				<td><?php echo $mostrar[3]; ?></td>
				<td><?php echo "$" . number_format($mostrar[6],  2, '.', ','); ?></td>
			</tr>
		<?php
			$total = $total + $mostrar[6];
			$anticipo = array_key_exists(9, $mostrar) ? $mostrar[9] : 0;
		}
		?>
		<tr>
			<td>Total: <?php echo "$" . number_format($total,  2, '.', ',') ?></td>
			<td><?php
				if ($estatusVenta == 0) {
					echo "Anticipo: $" . number_format($anticipo,  2, '.', ',');
				} else {
					echo "";
				}

				?></td>
			<td><?php

				if ($estatusVenta == 0) {
					echo "Restante: $" . number_format($total - $anticipo,  2, '.', ',');
				} else {
					echo "";
				}


				?></td>
			<?php



			?>



		</tr>

	</table>
	<div style="text-align: center; align-items: center; align-items: center;">
		<p style="text-align: center;">
			"Es un placer servirle"
		</p>
		<p style="text-align: center;">
			Tel: 9531294506, 9535416217
		</p>
		<p>Horario: 8:00am - 9:00pm</p>

	</div>

</body>

</html>