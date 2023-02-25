<?php
require_once "../../clases/Conexion.php";
$c = new conectar();
$conexion = $c->conexion();

$idanticipo = $_POST['idanticipo'];


$sql = "SELECT id_anticipo, id_cliente, id_venta, anticipo, fechaAnticipo FROM anticipos WHERE id_venta =$idanticipo";
$result = mysqli_query($conexion, $sql);

?>

<table id="tablaAnticiposFor" class="table table-hover table-condensed table-bordered" style="text-align: center;">
	<!-- <caption><label>Historial de pagos </label></caption> -->
	<thead>
		<tr>
			<td>Fecha</td>
			<td>Cantidad</td>
		</tr>

	</thead>


	<tbody>
		<?php while ($ver = mysqli_fetch_row($result)) : ?>

			<tr>
				<td><?php echo $ver[4]; ?></td>
				<td><?php echo "$".number_format((float)$ver[3], 2, '.', ','); ?></td>
			</tr>
		<?php endwhile; ?>

	</tbody>
</table>