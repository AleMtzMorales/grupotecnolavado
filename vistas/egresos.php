<?php
session_start();
if (isset($_SESSION['usuario'])) {

?>


	<!DOCTYPE html>
	<html>

	<head>
		<title>Egresos</title>
		<?php require_once "menu.php"; ?>
	</head>

	<body>
		<div class="container">
			<h1>Egresos</h1>
			<div class="row">
				<div class="col-sm-4">
					<form id="frmEgresos">
						<label>Descripción</label>
						<input type="text" class="form-control input-sm" id="descripcion" name="descripcion">
						<label>Cantidad</label>
						<input type="number" class="form-control input-sm" id="cantidad" name="cantidad">
						<p></p>
						<span class="btn btn-primary" id="btnAgregarEgreso">Agregar</span>
					</form>
				</div>
				<div class="col-sm-8">
					<div id="tablaEgresosLoad"></div>
				</div>
			</div>
		</div>

		<!-- Button trigger modal -->


		<!-- Modal -->
		<div class="modal fade" id="abremodalEgresosUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Actualizar egreso</h4>
					</div>
					<div class="modal-body">
						<form id="frmEgresosU">
							<input type="text" hidden="" id="idegresoU" name="idegresoU">
							<label>Descripción</label>
							<input type="text" class="form-control input-sm" id="descripcionU" name="descripcionU">
							<label>Cantidad</label>
							<input type="number" class="form-control input-sm" id="cantidadU" name="cantidadU">

						</form>
					</div>
					<div class="modal-footer">
						<button id="btnAgregarEgresoU" type="button" class="btn btn-primary" data-dismiss="modal">Actualizar</button>

					</div>
				</div>
			</div>
		</div>

	</body>

	</html>

	<script type="text/javascript">
		function agregaDatosEgreso(idegreso) {

			$.ajax({
				type: "POST",
				data: "idegreso=" + idegreso,
				url: "../procesos/egresos/obtenDatosEgreso.php",
				success: function(r) {
					dato = jQuery.parseJSON(r);
					$('#idegresoU').val(dato['id_egreso']);
					$('#descripcionU').val(dato['descripcion']);
					$('#cantidadU').val(dato['cantidad']);
				}
			});
		}

		function eliminarEgreso(idegreso) {
			alertify.confirm('¿Desea eliminar este egreso?', function() {
				$.ajax({
					type: "POST",
					data: "idegreso=" + idegreso,
					url: "../procesos/egresos/eliminarEgreso.php",
					success: function(r) {
						if (r == 1) {
							$('#tablaEgresosLoad').load("egresos/tablaEgresos.php");
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

			$('#tablaEgresosLoad').load("egresos/tablaEgresos.php");

			$('#btnAgregarEgreso').click(function() {

				vacios = validarFormVacio('frmEgresos');

				if (vacios > 0) {
					alertify.alert("Debes llenar todos los campos!!");
					return false;
				}

				datos = $('#frmEgresos').serialize();

				$.ajax({
					type: "POST",
					data: datos,
					url: "../procesos/egresos/agregaEgreso.php",
					success: function(r) {

						if (r == 1) {
							$('#frmEgresos')[0].reset();
							$('#tablaEgresosLoad').load("egresos/tablaEgresos.php");
							alertify.success("Egreso agregado con exito");
						} else {
							alertify.error("No se pudo agregar el egreso");
						}
					}
				});
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#btnAgregarEgresoU').click(function() {
				datos = $('#frmEgresosU').serialize();

				$.ajax({
					type: "POST",
					data: datos,
					url: "../procesos/egresos/actualizaEgreso.php",
					success: function(r) {

						if (r == 1) {
							$('#frmEgresos')[0].reset();
							$('#tablaEgresosLoad').load("egresos/tablaEgresos.php");
							alertify.success("Egreso actualizado con exito :D");
						} else {
							alertify.error("No se pudo actualizar el egreso");
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