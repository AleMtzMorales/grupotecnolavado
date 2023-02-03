<?php
require_once "../../clases/Conexion.php";

$obj = new conectar();
$conexion = $obj->conexion();

$sql = "SELECT id_cliente, 
				nombre,
				apellido,
				direccion,
				observaciones,
				telefono
		FROM clientes ORDER BY id_cliente DESC";
$result = mysqli_query($conexion, $sql);
?>

<br>

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-8">
		</div>
		<div class="col-sm-4">
			<input class="form-control" id="myInput" type="text" placeholder="Buscar cliente...">
		</div>
	</div>
</div>
<div class="table-responsive" style="padding-right: 15px;">




	<table class="table table-hover table-condensed table-bordered" style="text-align: center; ">

		<caption><label>Clientes</label></caption>
		<thead>
			<tr>
				<td>Nombre</td>
				<td>Apellido</td>
				<td>Direccion</td>
				<td>Observaciones</td>
				<td>Telefono</td>
				<td>Editar</td>
				<!-- <td>Eliminar</td> -->
			</tr>

		</thead>

		<tbody id="myTable">
			<?php while ($ver = mysqli_fetch_row($result)) : ?>

				<tr>
					<td><?php echo $ver[1]; ?></td>
					<td><?php echo $ver[2]; ?></td>
					<td><?php echo $ver[3]; ?></td>
					<td><?php echo $ver[4]; ?></td>
					<td><?php echo $ver[5]; ?></td>
					<td>
						<span class="btn btn-warning btn-xs" data-toggle="modal" data-target="#abremodalClientesUpdate" onclick="agregaDatosCliente('<?php echo $ver[0]; ?>')">
							<span class="glyphicon glyphicon-pencil"></span>
						</span> 
					</td>
					<!-- <td>
						<span class="btn btn-danger btn-xs" onclick="eliminarCliente('<?php echo $ver[0]; ?>')">
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