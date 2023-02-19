<?php

session_start();
//print_r($_SESSION['tablaComprasTemp']);
?>

<h4>Hacer venta</h4>
<h4><strong>
		<div id="nombreclienteVenta"></div>
	</strong></h4>
<table class="table table-bordered table-hover table-condensed" style="text-align: center;">
	<caption>
		<div class="form-check form-switch">


			<input id="paymentCheck" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
			<label class="form-check-label" for="flexSwitchCheckChecked">Pago pendiente</label>
		</div>

		<form id="endSale">
			<div id="fieldAdvancePayment">
				<!-- <label id="labelAdvancePayment">Anticipo</label>
				<input type="text" class="form-control input-sm" id="inputAdvancePayment" name="AdvancePayment"> -->
			</div>

		</form>

		<br>
		<button id="butttonSaveSale" class="btn btn-success"> Generar venta
			<span class="glyphicon glyphicon-usd"></span>
		</button>

	</caption>


	<thead>
		<tr>
			<td>Nombre</td>
			<td>Descripcion</td>
			<td>Precio</td>
			<td>Cantidad</td>
			<td>Quitar</td>
		</tr>
	</thead>

	<tbody>
		<?php
		$total = 0; //esta variable tendra el total de la compra en dinero
		$cliente = ""; //en esta se guarda el nombre del cliente
		if (isset($_SESSION['tablaComprasTemp'])) :
			$i = 0;
			foreach (@$_SESSION['tablaComprasTemp'] as $key) {

				$d = explode("||", @$key);
		?>

				<tr>
					<td><?php echo $d[1] ?></td>
					<td><?php echo $d[2] ?></td>
					<td><?php echo $d[3] ?></td>
					<td><?php echo $d[4] ?></td>
					<!-- <td><?php echo 1; ?></td> -->
					<td>
						<span class="btn btn-danger btn-xs" onclick="quitarP('<?php echo $i; ?>')">
							<span class="glyphicon glyphicon-remove"></span>
						</span>
					</td>
				</tr>

		<?php
				$total = $total + $d[3];
				$i++;
				$cliente = $d[5];
			}
		endif;
		?>

		<tr>
			<td>
				<strong>
					Total de venta: $<?php echo number_format($total,  2, '.', ','); ?>
				</strong>
				<p hidden id="totalVenta"><?php echo $total; ?></p>
			</td>
		</tr>
	</tbody>
</table>


<script type="text/javascript">
	$(document).ready(function() {
		nombre = "<?php echo @$cliente ?>";
		$('#nombreclienteVenta').text("Nombre de cliente: " + nombre);


		$("#butttonSaveSale").click(function() {

			let anticipo = null;
			let isAdvanceSale = false;
			if ($('#paymentCheck').is(":checked")) {
				let vacios = validarFormVacio('endSale');

				let valAnticipo = $('#inputAdvancePayment').val();
				if (valAnticipo != undefined && valAnticipo != null && valAnticipo >= 0) {
					isAdvanceSale = true;
					anticipo = valAnticipo;
				}
				if (vacios > 0) {
					alertify.alert("¡Debes llenar todos los campos!");
					return false;
				}
				console.log(vacios)
			}



			crearVenta(anticipo, isAdvanceSale);



		})



		$("#paymentCheck").click(function() {
			if ($('#paymentCheck').is(":checked")) {
				addElementsForAdvancedPayment();
				$("#inputAdvancePayment").keyup(
					function() {
						let anticipo = $("#inputAdvancePayment").val();
						let total = $("#totalVenta").html();
						if (anticipo >= 0 && anticipo < Number(total)) {
							let restante = total - anticipo;
							let parseRestante = Number.parseFloat(restante).toFixed(2);
							let totalRes = parseRestante.toLocaleString('es-MX', {
								style: 'currency',
								currency: 'MXN'
							});

							$("#labelRest").text(`Restan: $${totalRes}`);
							console.log(total - anticipo)
						} else {
							removeElementForAdvancedPayment();
							$("#paymentCheck").prop("checked", false);
						}
					}
				);
			} else {
				removeElementForAdvancedPayment();
			}
		});

		function addElementsForAdvancedPayment() {
			$("#fieldAdvancePayment").append('<label id="labelAdvancePayment">Anticipo</label>');
			$("#fieldAdvancePayment").append('<input type="number" min="1" max="10000" class="form-control input-sm" id="inputAdvancePayment" name="AdvancePayment">');
			$("#fieldAdvancePayment").append('<label id="labelRest">Restan: </label>');

		}

		function removeElementForAdvancedPayment() {
			$("#labelAdvancePayment").remove();
			$("#inputAdvancePayment").remove();
			$("#labelRest").remove();
		}

	});
</script>