<?php
require_once "../../clases/Conexion.php";

$obj = new conectar();
$conexion = $obj->conexion();

$sql = "SELECT 
			eg.id_egreso, 
			eg.totalEgreso,
			eg.fechaEgreso, 
			eg.descripcion,
			user.nombre, 
			user.apellido
		FROM egresos as eg
 			inner join usuarios as user
				on eg.id_usuario=user.id_usuario
 		ORDER BY id_egreso DESC";

$result = mysqli_query($conexion, $sql);
?>
<br>

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-8">
		</div>
		<div class="col-sm-4">
			<input class="form-control" id="myInput" type="text" placeholder="Buscar egreso...">
		</div>
	</div>
</div>
<div class="table-responsive" style="padding-right: 15px;">




	<table class="table table-hover table-condensed table-bordered" style="text-align: center; ">

		<caption><label>Egresos</label></caption>
		<thead>
			<tr>
				<td>Descripción</td>
				<td>Cantidad</td>
				<td>Hecho por</td>
				<td>Fecha y hora de creación<nav></nav></td>
				<td>Editar</td>
				<!-- <td>Eliminar</td> -->
			</tr>

		</thead>

		<tbody id="myTable">
			<?php while ($ver = mysqli_fetch_row($result)) : ?>

				<tr>
					<td><?php echo $ver[3]; ?></td>
					<td><?php echo "$" . number_format($ver[1],  2, '.', ','); ?></td>
					<td><?php echo $ver[4] . " " . $ver[5]; ?></td>
					<td><?php echo $ver[2]; ?></td>
					<td>
						<span class="btn btn-warning btn-xs" data-toggle="modal" data-target="#abremodalEgresosUpdate" onclick="agregaDatosEgreso('<?php echo $ver[0]; ?>')">
							<span class="glyphicon glyphicon-pencil"></span>
						</span>
					</td>
					<!-- <td>
						<span class="btn btn-danger btn-xs" onclick="eliminarEgreso('<?php echo $ver[0]; ?>')">
							<span class="glyphicon glyphicon-remove"></span>
						</span>
					</td> -->
				</tr>
			<?php endwhile; ?>

		</tbody>
	</table>
</div>

<script>
	$(document).ready(function() {
		$("#myInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#myTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
</script>