<?php
require_once "../../clases/Conexion.php";
$c = new conectar();
$conexion = $c->conexion();

$idventa = $_POST['idventa'];


$sql = "SELECT ve.id_venta,
				art.id_producto,
				art.nombre,
				art.precio,
				art.descripcion

				from ventas  as ve 
					inner join articulos as art
				on ve.id_producto=art.id_producto 
					inner join clientes as client
				on ve.id_cliente=client.id_cliente where ve.id_venta = $idventa ORDER BY `id_venta` ASC";
$result = mysqli_query($conexion, $sql);

?>

<table id="tablaPrendasFor" class="table table-hover table-condensed table-bordered table-responsive" style="text-align: center;">
	<!-- <caption><label>Historial de pagos </label></caption> -->
	<thead>
		<tr>
			<!-- <td>Folio venta</td> -->
			<td>Folio</td>
			<td>Nombre</td>
			<!-- <td>Precio</td> -->
			<td>Descripción</td>
		</tr>
	</thead>

	<tbody>
		<?php while ($ver = mysqli_fetch_row($result)) : ?>

			<tr>
				<!-- <td><?php echo $ver[0]; ?></td> -->
				<td><?php echo $ver[1]; ?></td>
				<td><?php echo $ver[2]; ?></td>
				<!-- <td><?php echo "$" . number_format((float)$ver[3], 2, '.', ','); ?></td> -->
				<td><?php echo $ver[4]; ?></td>
			</tr>
		<?php endwhile; ?>

	</tbody>
</table>