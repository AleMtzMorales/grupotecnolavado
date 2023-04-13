<?php
session_start();
if (isset($_SESSION['usuario'])) {

?>


	<!DOCTYPE html>
	<html>

	<head>
		<title>articulos</title>
		<?php require_once "menu.php"; ?>
		<?php require_once "../clases/Conexion.php";
		$c = new conectar();
		$conexion = $c->conexion();
		$sql = "SELECT id_categoria,nombreCategoria
		from categorias";
		$result = mysqli_query($conexion, $sql);
		?>
	</head>

	<body>
		<div class="container">
			<h1>Registro de Ropa</h1>
			<div class="row">
				<div class="col-sm-4">
					<form id="frmArticulos" enctype="multipart/form-data">
						<label>Categoria</label>
						<select class="form-control input-sm" id="categoriaSelect" name="categoriaSelect">
							<option value="A">Selecciona Categoria</option>
							<?php while ($ver = mysqli_fetch_row($result)) : ?>
								<option value="<?php echo $ver[0] ?>"><?php echo $ver[1]; ?></option>
							<?php endwhile; ?>
						</select>
						<label>Nombre</label>
						<input type="text" class="form-control input-sm" id="nombre" name="nombre">
						<label>Descripcion</label>
						<input type="text" class="form-control input-sm" id="descripcion" name="descripcion">
						<label>Cantidad kg / piezas</label>
						<input type="number" class="form-control input-sm" id="cantidad" name="cantidad" min="1">
						<label>Precio</label>
						<input type="number" class="form-control input-sm" id="precio" name="precio" min="1">
						<label>Imagen</label>
						<input type="file" id="imagen" name="imagen">
						<p></p>
						<span id="btnAgregaArticulo" class="btn btn-primary">Agregar</span>
					</form>
				</div>
				<div class="col-sm-8">
					<div id="tablaArticulosLoad"></div>
				</div>
			</div>
		</div>

		<!-- Button trigger modal -->

		<!-- Modal -->
		<div class="modal fade" id="abremodalUpdateArticulo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Actualiza Articulo</h4>
					</div>
					<div class="modal-body">
						<form id="frmArticulosU" enctype="multipart/form-data">
							<input type="text" id="idArticulo" hidden="" name="idArticulo">
							<label>Categoria</label>
							<select class="form-control input-sm" id="categoriaSelectU" name="categoriaSelectU">
								<option value="A">Selecciona Categoria</option>
								<?php
								$sql = "SELECT id_categoria,nombreCategoria
								from categorias";
								$result = mysqli_query($conexion, $sql);
								?>
								<?php while ($ver = mysqli_fetch_row($result)) : ?>
									<option value="<?php echo $ver[0] ?>"><?php echo $ver[1]; ?></option>
								<?php endwhile; ?>
							</select>
							<label>Nombre</label>
							<input type="text" class="form-control input-sm" id="nombreU" name="nombreU">
							<label>Descripcion</label>
							<input type="text" class="form-control input-sm" id="descripcionU" name="descripcionU" min="1">
							<label>Cantidad kg / piezas</label>
							<input type="text" class="form-control input-sm" id="cantidadU" name="cantidadU" min="1" min>
							<label>Precio</label>
							<input type="text" class="form-control input-sm" id="precioU" name="precioU">

						</form>
					</div>
					<div class="modal-footer">
						<button id="btnActualizaarticulo" type="button" class="btn btn-warning" data-dismiss="modal">Actualizar</button>

					</div>
				</div>
			</div>
		</div>

	</body>

	</html>

	<script type="text/javascript">
		function agregaDatosArticulo(idarticulo) {
			$.ajax({
				type: "POST",
				data: "idart=" + idarticulo,
				url: "../procesos/articulos/obtenDatosArticulo.php",
				success: function(r) {

					dato = jQuery.parseJSON(r);
					$('#idArticulo').val(dato['id_producto']);
					$('#categoriaSelectU').val(dato['id_categoria']);
					$('#nombreU').val(dato['nombre']);
					$('#descripcionU').val(dato['descripcion']);
					$('#cantidadU').val(dato['cantidad']);
					$('#precioU').val(dato['precio']);

				}
			});
		}

		function eliminaArticulo(idArticulo) {
			alertify.confirm('¿Desea eliminar este articulo?', function() {
				$.ajax({
					type: "POST",
					data: "idarticulo=" + idArticulo,
					url: "../procesos/articulos/eliminarArticulo.php",
					success: function(r) {
						if (r == 1) {
							$('#tablaArticulosLoad').load("articulos/tablaArticulos.php");
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
			$('#btnActualizaarticulo').click(function() {

				datos = $('#frmArticulosU').serialize();
				$.ajax({
					type: "POST",
					data: datos,
					url: "../procesos/articulos/actualizaArticulos.php",
					success: function(r) {
						if (r == 1) {
							$('#tablaArticulosLoad').load("articulos/tablaArticulos.php");
							alertify.success("Actualizado con exito :D");
						} else {
							alertify.error("Error al actualizar :(");
						}
					}
				});
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#tablaArticulosLoad').load("articulos/tablaArticulos.php");

			$('#btnAgregaArticulo').click(function() {

				// vacios = validarFormVacio('frmArticulos');
				vacios = 0; //validarFormVacio('frmClientes');





				let campoNombre = $('#nombre').val();
				let campoCantidad = $('#cantidad').val();
				let campoPrecio = $('#precio').val();

				if (campoNombre == "") {
					alertify.alert("El nombre del artículo es requerido");
					return false;
				}

				if (campoCantidad == "") {
					alertify.alert("La cantidad es requerida");
					return false;
				} else if (campoCantidad <= 0 || campoCantidad == "0") {
					alertify.alert("La cantidad no puede ser 0");
					return false;
				}
				if (campoPrecio == "") {
					alertify.alert("El precio es requerido");
					return false;
				} else if (campoCantidad <= 0 || campoCantidad == "0") {
					alertify.alert("El precio no puede ser 0");
					return false;
				}

				if (vacios > 0) {
					alertify.alert("Debes llenar todos los campos");
					return false;
				}

				$('#btnAgregaArticulo').text('Cargando...').attr('disabled', true).unbind('click');


				var formData = new FormData(document.getElementById("frmArticulos"));

				$.ajax({
					url: "../procesos/articulos/insertaArticulos.php",
					type: "post",
					dataType: "html",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,

					success: function(r) {

						if (r == 1) {
							$('#frmArticulos')[0].reset();
							$('#tablaArticulosLoad').load("articulos/tablaArticulos.php");
							alertify.success("Agregado con exito :D");
						} else {
							alertify.error("Fallo al subir el archivo :(");
						}
						$('#btnAgregaArticulo').removeAttr("disabled");
						$('#btnAgregaArticulo').text('Guardar')

					}
				});



			});
		});
	</script>

<?php
} else {
	header("location:../index.php");
}
?>