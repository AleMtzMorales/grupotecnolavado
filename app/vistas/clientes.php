<?php
session_start();
if (isset($_SESSION['usuario'])) {

?>


	<!DOCTYPE html>
	<html>

	<head>
		<title>clientes</title>
		<?php require_once "menu.php"; ?>
	</head>

	<body>
		<div class="container">
			<h1>Clientes</h1>
			<div class="row">
				<div class="col-sm-4">
					<form id="frmClientes">
						<label>Nombre</label>
						<input type="text" class="form-control input-sm" id="nombre" name="nombre" required>
						<label>Apellido</label>
						<input type="text" class="form-control input-sm" id="apellidos" name="apellidos">
						<label>Dirección</label>
						<input type="text" class="form-control input-sm" id="direccion" name="direccion">
						<label>Observación</label>
						<textarea type="text" rows="2" cols="60" maxlength="200" class="form-control input-sm" id="observaciones" name="observaciones">
						</textarea>
						<label>Teléfono</label>
						<input type="text" class="form-control input-sm" id="telefono" name="telefono">
						<p></p>
						<span class="btn btn-primary" id="btnAgregarCliente">Agregar</span>
					</form>
				</div>
				<div class="col-sm-8">
					<div id="tablaClientesLoad"></div>
				</div>
			</div>
		</div>

		<!-- Button trigger modal -->


		<!-- Modal -->
		<div class="modal fade" id="abremodalClientesUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Actualizar cliente</h4>
					</div>
					<div class="modal-body">
						<form id="frmClientesU">
							<input type="text" hidden="" id="idclienteU" name="idclienteU">
							<label>Nombre</label>
							<input type="text" class="form-control input-sm" id="nombreU" name="nombreU">
							<label>Apellido</label>
							<input type="text" class="form-control input-sm" id="apellidosU" name="apellidosU">
							<label>Direccion</label>
							<input type="text" class="form-control input-sm" id="direccionU" name="direccionU">
							<label>Observaciones</label>
							<input type="text" class="form-control input-sm" id="observacionesU" name="observacionesU">
							<label>Telefono</label>
							<input type="text" class="form-control input-sm" id="telefonoU" name="telefonoU">
						</form>
					</div>
					<div class="modal-footer">
						<button id="btnAgregarClienteU" type="button" class="btn btn-primary" data-dismiss="modal">Actualizar</button>

					</div>
				</div>
			</div>
		</div>

	</body>

	</html>

	<script type="text/javascript">
		function agregaDatosCliente(idcliente) {

			$.ajax({
				type: "POST",
				data: "idcliente=" + idcliente,
				url: "../procesos/clientes/obtenDatosCliente.php",
				success: function(r) {
					dato = jQuery.parseJSON(r);
					$('#idclienteU').val(dato['id_cliente']);
					$('#nombreU').val(dato['nombre']);
					$('#apellidosU').val(dato['apellido']);
					$('#direccionU').val(dato['direccion']);
					$('#observacionesU').val(dato['observaciones']);
					$('#telefonoU').val(dato['telefono']);
				}
			});
		}

		function eliminarCliente(idcliente) {
			alertify.confirm('¿Desea eliminar este cliente?', function() {
				$.ajax({
					type: "POST",
					data: "idcliente=" + idcliente,
					url: "../procesos/clientes/eliminarCliente.php",
					success: function(r) {
						if (r == 1) {
							$('#tablaClientesLoad').load("clientes/tablaClientes.php");
							alertify.success("¡Eliminado con éxito!");
						} else {
							alertify.error("No se pudo eliminar");
						}
					}
				});
			}, function() {
				alertify.error('¡Operación cancelada!')
			});
		}
	</script>

	<script type="text/javascript">
		$(document).ready(function() {

			$('#tablaClientesLoad').load("clientes/tablaClientes.php");

			$('#btnAgregarCliente').click(function() {

				vacios = 0; //validarFormVacio('frmClientes');

				let campoNombre = $('#nombre').val();

				if (campoNombre == "" || campoNombre == null || campoNombre == undefined) {
					alertify.alert("El nombre del cliente es requerido");
					return false;
				}

				datos = $('#frmClientes').serialize();

				$.ajax({
					type: "POST",
					data: datos,
					url: "../procesos/clientes/agregaCliente.php",
					success: function(r) {

						if (r == 1) {
							$('#frmClientes')[0].reset();
							$('#tablaClientesLoad').load("clientes/tablaClientes.php");
							alertify.success("Cliente agregado con exito :D");
						} else {
							alertify.error("No se pudo agregar cliente");
						}
					}
				});
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#btnAgregarClienteU').click(function() {
				vacios = 0; //validarFormVacio('frmClientes');

				let campoNombre = $('#nombreU').val();

				if (campoNombre == "" || campoNombre == null || campoNombre == undefined) {
					alertify.alert("El nombre del cliente es requerido");
					return false;
				}


				datos = $('#frmClientesU').serialize();

				$.ajax({
					type: "POST",
					data: datos,
					url: "../procesos/clientes/actualizaCliente.php",
					success: function(r) {

						if (r == 1) {
							$('#frmClientes')[0].reset();
							$('#tablaClientesLoad').load("clientes/tablaClientes.php");
							alertify.success("Cliente actualizado con exito :D");
						} else {
							alertify.error("No se pudo actualizar cliente");
						}
					}
				});
			})
		})
	</script>


<?php
} else {
	header("location:../index.php");
}
?>