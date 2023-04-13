<?php

require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";

$c = new conectar();
$conexion = $c->conexion();
$totalVentasPagadas = 0;
$totalEgresos = 0;
$obj = new ventas();

// $sql = "SELECT id_venta,
// 				fechaCompra,
// 				id_cliente,
// 				estatus
// 			from ventas group by id_venta DESC";
$sql = "SELECT ve.id_venta,
					ve.fechaCompra,
					ve.cantidad,
		  			ve.precio,
					ve.estatus,
					user.apellido,
					user.nombre
					from ventas  as ve
						inner join articulos as art
					on ve.id_producto=art.id_producto
						inner join usuarios as user
					on ve.id_usuario=user.id_usuario
						inner join clientes as client
					on ve.id_cliente=client.id_cliente where ve.estatus =1 group by id_venta  DESC";

$sqlForPendingSales = "SELECT ve.id_venta,
								ant.fechaAnticipo,
								ve.cantidad,
								ant.anticipo,
								ve.estatus,
								user.apellido,
								user.nombre,
								ve.fechaCompra
						from anticipos as ant
							inner join ventas  as ve
								on ant.id_venta = ve.id_venta
							inner join usuarios as user
								on ve.id_usuario=user.id_usuario
						where ve.estatus = 2 OR ve.estatus = 0 ORDER BY ve.id_venta DESC";

$sqlEgresos = "SELECT
				eg.id_egreso,
				eg.totalEgreso,
				eg.fechaEgreso,
				eg.descripcion,
				user.apellido,
				user.nombre
			FROM egresos as eg
				inner join usuarios as user
					on eg.id_usuario=user.id_usuario
			ORDER BY eg.id_egreso DESC";

$result = mysqli_query($conexion, $sql);
$resultVentaParcial = mysqli_query($conexion, $sqlForPendingSales);
$resultEgresos = mysqli_query($conexion, $sqlEgresos);

?>

<h4 style="padding-bottom: 2rem; padding-top: 1rem;">Corte de caja</h4>

<div class="row">
	<div class="col-sm-1"></div>
	<section>
		<div class="col-sm-10 text-center mt-5 mb-5">
			<form action="../procesos/ventas/crearCorteDeCajaPdf.php" method="post" accept-charset="utf-8">
				<div class="row">
					<div class="col-3 col-xs-6  col-md-3 col-sm-3 col-xlg-5 ">
						<input id="fechaInicioCorteDeCaja" type="date" name="fechaIngresoCorteDeCaja" class="form-control" placeholder="Fecha de Inicio" required>
					</div>
					<div class="col-3 col-xs-6 col-md-3 col-sm-3 col-xlg-5">
						<input id="finCorteDeCaja" type="date" name="fechaFinCorteDeCaja" class="form-control" placeholder="Fecha Final" required>
					</div>
					<div style=" padding: 5px !important;" class="col-6 col-xs-10 col-md-6 col-sm-6 col-xlg-6">

						<span style="background-color: #212121; color: white;" class="btn btn-dark mb-2 pt-2" id="filtroCorteDeCaja">Filtrar</span>
						<button type="submit" class="btn btn-danger mb-2 pt-2"><i class="bi bi-file-earmark-pdf-fill"></i> Descargar corte de caja</button>

					</div>
				</div>
			</form>
		</div>

		<div class="col-md-12 text-center mt-5">
			<span id="loaderFiltroCorteDeCaja"> </span>
		</div>
	</section>
	<br>
	<br>

	<div class="col-sm-10">
		<div class="table-responsive resultadoFiltro">

			<br>
			<!-- End component for search sales-->
			<div id="ventas-reportes" class="table-responsive" style="padding-right: 15px;">
				<table class="table table-hover table-condensed table-bordered" style="text-align: center;">
					<caption>
						<label>
							<?php 
							$total = mysqli_num_rows($result);
							$total2 = mysqli_num_rows($resultVentaParcial);
							echo '<strong>Ingresos, total: </strong> (' . $total+$total2 . ')'; ?>
						</label>
					</caption>
					<thead>
						<tr>
							<!-- <td><strong>Folio</strong></td> -->
							<td><strong>Fecha y hora</strong></td>
							<!-- <td><strong>Cliente</strong></td> -->
							<td><strong>Total de compra</strong></td>
							<td><strong>Tipo de movimiento</strong></td>
							<!-- <td><strong>Descripció de movimiento</strong></td> -->
							<td><strong>Movimiento hecho por</strong></td>



							<!-- <td><strong>Estatus</strong></td> -->
							<!-- <td><strong>Ticket</strong></td> -->
							<!-- <td><strong>Historial de pagos</strong></td> -->
						</tr>
					</thead>
					<?php while ($ver = mysqli_fetch_row($result)) : ?>

						<tbody id="ventas-reportes-tbody">
							<tr>

								<td><?php echo $ver[1] ?></td>
								<td>
									<?php
									echo "$" . number_format($obj->obtenerTotal($ver[0]),  2, '.', ',');
									?>
								</td>

								<td>
									<?php
									echo '<span class="label label-success">Ingreso</span>';
									?>
								</td>

								<td>
									<?php
									echo $ver[5] . " " . $ver[6];
									?>
								</td>

							</tr>
						</tbody>

						<?php $totalVentasPagadas = $totalVentasPagadas + $obj->obtenerTotal($ver[0]); ?>

					<?php endwhile; ?>


					<?php while ($verVentasParciales = mysqli_fetch_row($resultVentaParcial)) : ?>

						<tbody id="ventas-reportes-tbody">
							<tr>

								<td><?php echo $verVentasParciales[1] ?></td>
								<td>
									<?php
									echo "$" . number_format($verVentasParciales[3],  2, '.', ',');
									?>
								</td>

								<td>
									<?php
									echo '<span class="label label-success">Ingreso</span>';
									?>
								</td>


								<td>
									<?php
									echo $verVentasParciales[5] . " " . $verVentasParciales[6];
									?>
								</td>
							</tr>
						</tbody>
						<?php $totalVentasPagadas = $totalVentasPagadas + $verVentasParciales[3] ?>
					<?php endwhile; ?>
				</table>
				<h4><strong> Total de ingresos: <?php echo "$" . number_Format($totalVentasPagadas, 2, '.', ',') ?></strong> </h4>
				<br>
			</div>


			<!--Tabla de egresos-->
			<div class="table-responsive" style="padding-right: 15px;">
				<table id="egresos" class="table table-hover table-condensed table-bordered" style="text-align: center;">
					<caption>
						<label>
							<?php $totalEgreso = mysqli_num_rows($resultEgresos);
							echo '<strong>Egresos, total: </strong> (' . $totalEgreso . ')'; ?>
						</label>
					</caption>
					<thead>
						<tr>
							<td><strong>Fecha y hora</strong></td>
							<td><strong>Total</strong></td>
							<td><strong>Tipo de movimiento</strong></td>
							<td><strong>Descripción de movimiento</strong></td>
							<td><strong>Movimiento hecho por</strong></td>
						</tr>
					</thead>
					<?php while ($verEgresos = mysqli_fetch_row($resultEgresos)) : ?>

						<tbody id="ventas-reportes-tbody">
							<tr>
								<td><?php echo $verEgresos[2] ?></td>
								<td>
									<?php
									echo "$" . number_format($verEgresos[1],  2, '.', ',');
									?>
								</td>

								<td>
									<?php
									echo '<span class="label label-danger">Egreso</span>';
									?>
								</td>
								<td>
									<?php
									echo $verEgresos[3];
									?>
								</td>


								<td>
									<?php
									echo $verEgresos[4] . " " . $verEgresos[5];
									?>
								</td>
							</tr>
							<?php $totalEgresos = $totalEgresos + $verEgresos[1] ?>

						</tbody>
					<?php endwhile; ?>
				</table>
				<h4><strong> Total de egresos: <?php echo "$" . number_Format($totalEgresos, 2, '.', ',') ?></strong> </h4>
				<br>
				<table id="report" style="text-align: center; padding-bottom: 5px">
					<tr>
						<td>
							<h3>
								<strong>
									Total del corte : <?php
														echo "$" . number_Format($totalVentasPagadas - $totalEgresos, 2, '.', ',');
														?>
								</strong>
							</h3>
						</td>
					</tr>
				</table>
			</div>

		</div>
	</div>





	<div class="col-sm-1"></div>
</div>

<!-- Modal para liquidar ventas -->
<div class="modal fade" id="abremodalClientesUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Pago de venta pendiente</h4>
			</div>
			<div class="modal-body">
				<form id="frmClientesU">
					<input type="text" hidden="" id="idventa" name="idventa">
					<input type="text" hidden="" id="idclienteU" name="idclienteU">
					<input type="text" hidden="" id="totalCompra" name="totalCompra">

					<strong><label id="labelForAdvancedSales"></label></strong>
					<br>
					<br>


					<!-- hidden=""  -->



					<!-- <label>Nombre</label>
						<input type="text" class="form-control input-sm" id="nombreU" name="nombreU">
						<label>Apellido</label>
						<input type="text" class="form-control input-sm" id="apellidosU" name="apellidosU">
						<label>Direccion</label>
						<input type="text" class="form-control input-sm" id="direccionU" name="direccionU">
						<label>Observaciones</label>
						<input type="text" class="form-control input-sm" id="observacionesU" name="observacionesU">
						<label>Telefono</label>
						<input type="text" class="form-control input-sm" id="telefonoU" name="telefonoU"> -->
				</form>

				<label id="labelConfirm">¿Está seguro saldar la venta por el valor antes mencionado?</label>
			</div>
			<div class="modal-footer">
				<button id="btnSaldarDeudaPendiente" type="button" class="btn btn-primary" data-dismiss="modal">Saldar venta</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal par ver historial de pagos de las ventas-->
<div class="modal fade" id="openModalAddEgreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar gasto</h4>
			</div>
			<div class="modal-body">
				<form id="frmEgresosAdd">



				</form>

				<!-- <label id="labelConfirm">¿Está seguro saldar la venta por el valor antes mencionado?</label> -->
			</div>
			<div class="modal-footer">
				<!-- <button id="btnSaldarDeudaPendiente" type="button" class="btn btn-primary" data-dismiss="modal">Saldar venta</button> -->
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>





<script>
	$(function() {
		setTimeout(function() {
			$('body').addClass('loaded');
		}, 1000);


		//FILTRANDO REGISTROS
		$("#filtroCorteDeCaja").on("click", function(e) {
			e.preventDefault();

			loaderF(true);

			var f_ingreso = $("#fechaInicioCorteDeCaja").val();
			var f_fin = $('input[name=fechaFinCorteDeCaja]').val();
			console.log(f_ingreso + '' + f_fin);
			console.log("Fecha de ingreso: ", f_ingreso);
			console.log("Fecha de fin: ", f_fin);



			if (f_ingreso != "" && f_ingreso != undefined && f_fin != "") {
				$.post("./ventas/filtroCorteDeCaja.php", {
					f_ingreso,
					f_fin
				}, function(data) {
					$("#ventas-reportes").hide();
					$("#egresos").hide();
					$(".resultadoFiltro").html(data);
					loaderF(false);
				});
			} else {
				$("#loaderFiltroCorteDeCaja").html('<p style="color:red;  font-weight:bold;">Debe seleccionar ambas fechas</p>');
			}
		});


		function loaderF(statusLoader) {
			console.log(statusLoader);
			if (statusLoader) {
				$("#loaderFiltroCorteDeCaja").show();
				$("#loaderFiltroCorteDeCaja").html('<img class="img-fluid" src="img/cargando.svg" style="left:50%; right: 50%; width:50px;">');
			} else {
				$("#loaderFiltroCorteDeCaja").hide();
			}
		}
	});

	$(document).ready(function() {
		$("#myInputSearch").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#ventas-reportes-tbody tr td:nth-child(1)").filter(function() {


				$(this).parent().toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});

		});
	});


	$(document).ready(function() {
		$('#btnSaldarDeudaPendiente').click(function() {
			datos = $('#frmClientesU').serialize();

			$.ajax({
				type: "POST",
				data: datos,
				url: "../procesos/ventas/saldarVenta.php",
				success: function(r) {

					if (r == 1) {
						// $('#frmClientes')[0].reset();
						// $('#tablaClientesLoad').load("clientes/tablaClientes.php");
						$('#ventasHechas').load('ventas/ventasyReportes.php');
						alertify.success("Venta pendiente saldada correctamente");
					} else {
						alertify.error("No se pudo saldar venta pendiente");
					}
				}
			});
		});



	})

	function saldarDeudaPendiente(idventa, idcliente, totalCompra, anticipo) {

		let restante = 0;

		if (totalCompra > anticipo) {
			restante = totalCompra - anticipo;
			restante = restante.toLocaleString('es-MX', {
				style: 'currency',
				currency: 'MXN'
			});
		}

		$('#idventa').val(idventa);
		$('#idclienteU').val(idcliente);
		$('#totalCompra').val(totalCompra);
		$('#labelForAdvancedSales').text(`Resta un pago de ${restante} pesos`);


		console.log("Este es el id del cliente ", idcliente);
		console.log("Este es el id de la  venta  ", idventa);
		console.log("Este es el anticipo es de  ", anticipo);


	}
</script>