<?php

require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";

$c = new conectar();
$conexion = $c->conexion();

$obj = new ventas();

$sql = "SELECT id_venta,
				fechaCompra,
				id_cliente,
				estatus
			from ventas group by id_venta DESC";
$result = mysqli_query($conexion, $sql);
?>

<h4 style="padding-bottom: 2rem; padding-top: 1rem;">Reportes y ventas</h4>

<div class="row">
	<div class="col-sm-1"></div>
	<section>
		<div class="col-sm-10 text-center mt-5 mb-5">
			<form action="../procesos/ventas/crearReportePdf.php" method="post" accept-charset="utf-8">
				<div class="row">
					<div class="col-3 col-xs-6  col-md-3 col-sm-3 col-xlg-5 ">
						<input id="fechaInicio" type="date" name="fechaIngreso" class="form-control" placeholder="Fecha de Inicio" required>
					</div>
					<div class="col-3 col-xs-6 col-md-3 col-sm-3 col-xlg-5">
						<input id="fin" type="date" name="fechaFin" class="form-control" placeholder="Fecha Final" required>
					</div>
					<div style="padding: 5px !important;" class="col-6 col-xs-10 col-md-6 col-sm-6 col-xlg-6">

						<span style="background-color: #212121; color: white;" class="btn btn-dark mb-2 pt-2" id="filtro">Filtrar</span>
						<button type="submit" class="btn btn-success mb-2 pt-2"><i class="bi bi-file-earmark-pdf-fill"></i> Descargar reporte</button>

					</div>
				</div>
			</form>
		</div>

		<div class="col-md-12 text-center mt-5">
			<span id="loaderFiltro"> </span>
		</div>
	</section>
	<br>
	<br>

	<div class="col-sm-10">
		<div class="table-responsive resultadoFiltro">

			<br>
			<br>

			<!-- Start component for search sales-->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-8">
					</div>
					<div style=" padding: 5px !important;" s class="col-sm-4">
						<input class="form-control" id="myInputSearch" type="text" placeholder="Buscar venta por cliente...">
					</div>
				</div>
			</div>
			<!-- End component for search sales-->
			<div class="table-responsive" style="padding-right: 15px;">
				<table id="ventas-reportes" class="table table-hover table-condensed table-bordered" style="text-align: center;">
					<caption>
						<label>
							<?php $total = mysqli_num_rows($result);
							echo '<strong>Ventas, total: </strong> (' . $total . ')'; ?>
						</label>
					</caption>
					<thead>
						<tr>
							<td><strong>Folio</strong></td>
							<td><strong>Fecha y hora</strong></td>
							<td><strong>Cliente</strong></td>
							<td><strong>Total de compra</strong></td>
							<td><strong>Estatus</strong></td>
							<td><strong>Ticket</strong></td>
							<td><strong>Historial de pagos</strong></td>
						</tr>
					</thead>
					<?php while ($ver = mysqli_fetch_row($result)) : ?>

						<tbody id="ventas-reportes-tbody">
							<tr>
								<td><?php echo $ver[0] ?></td>
								<td><?php echo $ver[1] ?></td>
								<td>
									<?php
									if ($obj->nombreCliente($ver[2]) == " ") {
										echo "S/C";
									} else {
										echo $obj->nombreCliente($ver[2]);
									}
									?>
								</td>
								<td>
									<?php
									echo "$" . number_format($obj->obtenerTotal($ver[0]),  2, '.', ',');
									?>
								</td>
								<td><?php

									if ($ver[3] == 0) {
										echo '<span class="label label-warning">Pendiente</span> 
	<span class="btn btn-info btn-xs" data-toggle="modal" data-target="#abremodalClientesUpdate" onclick="saldarDeudaPendiente(',  $ver[0], ",", $ver[2], ",", $obj->obtenerTotal($ver[0]), ",", $obj->obtenerAnticipo($ver[0]), ')" ">
	Saldar venta</span>';
									} else if ($ver[3] == 2) {
										echo '<span class="label label-success">Liquidado</span>';
									} else {
										echo '<span class="label label-success">Pagado</span>';
									}
									// 	$RES =  $ver[3] == 0 ?
									// 		'<span class="label label-warning">Pendiente</span> 

									// 	  <span class="btn btn-info btn-xs" data-toggle="modal" data-target="#abremodalClientesUpdate"  ">
									// 	  Saldar venta</span>
									//   </span> 

									// 	  ' :
									// 		'<span class="label label-success">Pagado</span>';
									// 	echo $RES;
									?>
								</td>
								<td>
									<a href="../procesos/ventas/crearTicketPdf.php?idventa=<?php echo $ver[0] ?>" class="btn btn-danger btn-sm">
										Ticket <span class="glyphicon glyphicon-list-alt"></span>
									</a>
								</td>
								<td>
									<!-- <a href="../procesos/ventas/crearReportePdf.php?idventa=<?php echo $ver[0] . "&finicio=" . mktime(0, 0, 0, date("m") - 1, date("d"),   date("Y")) . "&ffinal=" . mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")) ?>" class="btn btn-primary btn-sm">
										Ver pagos<span class="bi bi-clock-history"></span>
									</a> -->


									<?php

									echo '
	<span class="btn btn-primary btn-sm" data-toggle="modal" data-target="#openModalSeePayments" onclick="agregaDatosAnticipos(',  $ver[0], ',',  $ver[3], ')" ">
	Ver pagos <span class="bi bi-clock-history"></span></span>';
									?>
								</td>
							</tr>
						</tbody>
					<?php endwhile; ?>
				</table>
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
	<div class="modal fade" id="openModalSeePayments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Historial de pagos</h4>
				</div>
				<div class="modal-body">
					<!-- <form id="frmClientesU">

					</form> -->

					<div id="tablaHistorialPagos"></div>

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
			$("#filtro").on("click", function(e) {
				e.preventDefault();

				loaderF(true);

				var f_ingreso = $("#fechaInicio").val();
				var f_fin = $('input[name=fechaFin]').val();
				console.log(f_ingreso + '' + f_fin);
				console.log("Fecha de ingreso: ", f_ingreso);
				console.log("Fecha de fin: ", f_fin);



				if (f_ingreso != "" && f_ingreso != undefined && f_fin != "") {
					$.post("./ventas/filtro.php", {
						f_ingreso,
						f_fin
					}, function(data) {
						$("#ventas-reportes").hide();
						$(".resultadoFiltro").html(data);
						loaderF(false);
					});
				} else {
					$("#loaderFiltro").html('<p style="color:red;  font-weight:bold;">Debe seleccionar ambas fechas</p>');
				}
			});


			function loaderF(statusLoader) {
				console.log(statusLoader);
				if (statusLoader) {
					$("#loaderFiltro").show();
					$("#loaderFiltro").html('<img class="img-fluid" src="img/cargando.svg" style="left:50%; right: 50%; width:50px;">');
				} else {
					$("#loaderFiltro").hide();
				}
			}
		});

		$(document).ready(function() {
			$("#myInputSearch").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#ventas-reportes-tbody tr").filter(function() {


					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
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


		function agregaDatosAnticipos(idanticipo, statusVenta) {

			if (statusVenta == 1) {
				$('#tablaHistorialPagos').empty();
				$('#tablaHistorialPagos').html('<div class="alert alert-success"><strong>¡Sin registros!</strong> esto se debe a que el pago se hizo completamente y en una exhibición.</div>');

			} else {
				$.ajax({
					type: "POST",
					data: "idanticipo=" + idanticipo,
					url: "../vistas/anticipos/tablaAnticipos.php",
					success: function(r) {
						// dato = jQuery.parseJSON(r);
						// $('#idclienteU').val(dato['id_cliente']);
						// $('#nombreU').val(dato['nombre']);
						// $('#apellidosU').val(dato['apellido']);
						// $('#direccionU').val(dato['direccion']);
						// $('#observacionesU').val(dato['observaciones']);
						// $('#telefonoU').val(dato['telefono']);
						$('#tablaHistorialPagos').empty();
						$('#tablaHistorialPagos').html(r);
					}
				});
			}


		}
	</script>