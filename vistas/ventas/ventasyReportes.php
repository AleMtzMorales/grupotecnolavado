<?php

require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";

$c = new conectar();
$conexion = $c->conexion();

$obj = new ventas();

$sql = "SELECT id_venta,
				fechaCompra,
				id_cliente 
			from ventas group by id_venta";
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
					<div class="col-6 col-xs-10 col-md-6 col-sm-6 col-xlg-6">

						<span style="background-color: #212121; color: white;" class="btn btn-dark mb-2 pt-2" id="filtro">Filtrar</span>
						<button type="submit" class="btn btn-success mb-2 pt-2"><i class="bi bi-file-earmark-pdf-fill"></i> Descargar Reporte</button>

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
			<table id="ventas-reportes" class="table table-hover table-condensed table-bordered" style="text-align: center;">
				<caption><label>
						<?php $total = mysqli_num_rows($result);
						echo '<strong>Ventas, total: </strong> (' . $total . ')'; ?>
					</label></caption>
				<tr>
					<td>Folio</td>
					<td>Fecha</td>
					<td>Cliente</td>
					<td>Total de compra</td>
					<td>Ticket</td>
					<!-- <td>Reporte</td> -->
				</tr>
				<?php while ($ver = mysqli_fetch_row($result)) : ?>
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
							echo "$" . $obj->obtenerTotal($ver[0]);
							?>
						</td>
						<td>
							<a href="../procesos/ventas/crearTicketPdf.php?idventa=<?php echo $ver[0] ?>" class="btn btn-danger btn-sm">
								Ticket <span class="glyphicon glyphicon-list-alt"></span>
							</a>
						</td>
						<!-- <td>
							<a href="../procesos/ventas/crearReportePdf.php?idventa=<?php echo $ver[0] . "&finicio=" . mktime(0, 0, 0, date("m") - 1, date("d"),   date("Y")) . "&ffinal=" . mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")) ?>" class="btn btn-danger btn-sm">
								Reporte <span class="glyphicon glyphicon-file"></span>
							</a>
						</td> -->
					</tr>
				<?php endwhile; ?>
			</table>
		</div>
	</div>
	<div class="col-sm-1"></div>
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
</script>