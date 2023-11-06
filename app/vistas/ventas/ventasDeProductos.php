<?php

require_once "../../clases/Conexion.php";
$c = new conectar();
$conexion = $c->conexion();
?>


<h4 style="padding-bottom: 2rem; padding-top: 1rem;">Vender un producto</h4>
<div class="row">
	<div class="col-sm-4">
		<form id="frmVentasProductos">
			<label>Seleciona Cliente</label>
			<select class="form-control input-sm" id="clienteVenta" name="clienteVenta">
				<option value="A">Selecciona</option>
				<!-- <option value="0">Sin cliente</option> -->
				<?php
				$sql = "SELECT id_cliente,nombre,apellido 
				from clientes ORDER BY id_cliente DESC";
				$result = mysqli_query($conexion, $sql);
				while ($cliente = mysqli_fetch_row($result)) :
				?>
					<option value="<?php echo $cliente[0] ?>"><?php echo $cliente[2] . " " . $cliente[1] ?></option>
				<?php endwhile; ?>
			</select>
			<label>Artículo</label>
			<select class="form-control input-sm" id="productoVenta" name="productoVenta">
				<option value="A">Selecciona</option>
				<?php
				$sql = "SELECT id_producto,
				nombre
				from articulos";
				$result = mysqli_query($conexion, $sql);

				while ($producto = mysqli_fetch_row($result)) :
				?>
					<option value="<?php echo $producto[0] ?>"><?php echo $producto[1] ?></option>
				<?php endwhile; ?>
			</select>
			<label>Descripción</label>
			<textarea readonly="" id="descripcionV" name="descripcionV" class="form-control input-sm"></textarea>
			<label>Cantidad</label>
			<input readonly="" type="text" class="form-control input-sm" id="cantidadV" name="cantidadV">
			<label>Precio</label>
			<input readonly="" type="text" class="form-control input-sm" id="precioV" name="precioV">

			<label>Cantidad recibida kg / pieza</label>
			<input type="number" class="form-control input-sm" id="cantidadRecibida" name="cantidadRecibida">
			<!-- 
			<div class="form-check form-switch">
				<input id="paymentCheck" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
				<label class="form-check-label" for="flexSwitchCheckChecked">Pago pendiente</label>
			</div>


			<div id="fieldAdvancePayment">
			
			</div> -->

			<p></p>
			<span class="btn btn-primary" id="btnAgregaVenta">Agregar</span>
			<span class="btn btn-danger" id="btnVaciarVentas">Vaciar ventas</span>
		</form>
	</div>
	<div class="col-sm-3">
		<div id="imgProducto"></div>
	</div>
	<div class="col-sm-4">
		<div id="tablaVentasTempLoad"></div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$('#tablaVentasTempLoad').load("ventas/tablaVentasTemp.php");

		$('#productoVenta').change(function() {
			$.ajax({
				type: "POST",
				data: "idproducto=" + $('#productoVenta').val(),
				url: "../procesos/ventas/llenarFormProducto.php",
				success: function(r) {
					dato = jQuery.parseJSON(r);

					$('#descripcionV').val(dato['descripcion']);
					$('#cantidadV').val(dato['cantidad']);
					$('#precioV').val(dato['precio']);

					$('#imgProducto').prepend('<img class="img-thumbnail" id="imgp" src="' + dato['ruta'] + '" />');
				}
			});
		});

		$('#btnAgregaVenta').click(function() {
			vacios = validarFormVacio('frmVentasProductos');

			if (vacios > 0) {
				alertify.alert("Debes llenar todos los campos!!");
				return false;
			}

			datos = $('#frmVentasProductos').serialize();
			$.ajax({
				type: "POST",
				data: datos,
				url: "../procesos/ventas/agregaProductoTemp.php",
				success: function(r) {
					$('#tablaVentasTempLoad').load("ventas/tablaVentasTemp.php");
				}
			});
		});

		$('#btnVaciarVentas').click(function() {

			$.ajax({
				url: "../procesos/ventas/vaciarTemp.php",
				success: function(r) {
					$('#tablaVentasTempLoad').load("ventas/tablaVentasTemp.php");
				}
			});
		});

	});
</script>

<script type="text/javascript">
	function quitarP(index) {
		$.ajax({
			type: "POST",
			data: "ind=" + index,
			url: "../procesos/ventas/quitarproducto.php",
			success: function(r) {
				$('#tablaVentasTempLoad').load("ventas/tablaVentasTemp.php");
				alertify.success("Se quito el producto :D");
			}
		});
	}

	function crearVenta(anticipoVar, isAdvanceSaleVar) {

		console.log({
			anticipo: anticipoVar != undefined && anticipoVar != null && anticipoVar >= 0 ? anticipoVar : -1,
			isAdvanceSale: isAdvanceSaleVar != undefined && isAdvanceSaleVar != null ? isAdvanceSaleVar : false,
		})
		$.ajax({
			url: "../procesos/ventas/crearVenta.php",
			type: "POST",
			data: {
				anticipo: anticipoVar != undefined && anticipoVar != null && anticipoVar >= 0 ? anticipoVar : -1,
				isAdvanceSale: isAdvanceSaleVar != undefined && isAdvanceSaleVar != null ? isAdvanceSaleVar : false,
			},
			success: function(r) {
				//r[0] res for ventas registers
				//r[1] res for anticipos registers
				//r[2] res for boolean if is register
				console.log(r)
			
					let res = JSON.parse(r);

				console.log("r:", res[0]);
				console.log("a:", res[1]);
				console.log("isAdvanced:", res[2]);
				if (res[0] > 0) {

					if (res[2] == true) {
						if (res[1] > 0) {
							$('#tablaVentasTempLoad').load("ventas/tablaVentasTemp.php");
							$('#frmVentasProductos')[0].reset();
							alertify.alert("Venta creada con exito, consulte la informacion de esta en ventas hechas");
						} else {
							$('#tablaVentasTempLoad').load("ventas/tablaVentasTemp.php");
							$('#frmVentasProductos')[0].reset();
							alertify.alert("Venta creada con inconsistencias , consulte la informacion de esta en ventas hechas.");
						}
					} else {
						$('#tablaVentasTempLoad').load("ventas/tablaVentasTemp.php");
						$('#frmVentasProductos')[0].reset();
						alertify.alert("Venta creada con exito, consulte la informacion de esta en ventas hechas.");
					}

				} else if (r == 0) {
					alertify.alert("¡No hay lista de venta!");
				} else {
					alertify.alert("No se pudo crear la venta");
				}
			}
		});
	}
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#clienteVenta').select2();
		$('#productoVenta').select2();
		// $("#fieldAdvancePayment").hide();

	});
</script>