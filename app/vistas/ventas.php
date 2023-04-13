<?php
session_start();
if (isset($_SESSION['usuario'])) {

?>


	<!DOCTYPE html>
	<html>

	<head>
		<title>ventas</title>
		<?php require_once "menu.php"; ?>
	</head>

	<body>

		<div class="container">
			<h1>Ventas</h1>
			<div class="row">
				<div class="col-sm-12">
					<span class="btn btn-default" id="ventaProductosBtn">Vender producto</span>
					<span class="btn btn-default" id="ventasHechasBtn">Ventas hechas</span>
					<span class="btn btn-default" id="corteDeCajaBtn">Corte de caja</span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div id="ventaProductos"></div>
					<div id="ventasHechas"></div>
					<div id="corteDeCaja"></div>

				</div>
			</div>
		</div>

	</body>

	</html>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#ventaProductosBtn').click(function() {
				esconderSeccionVenta();
				$('#ventaProductos').load('ventas/ventasDeProductos.php'); //----->Nueva venta de productos
				$('#ventaProductos').show();
			});
			$('#ventasHechasBtn').click(function() {
				esconderSeccionVenta();
				$('#ventasHechas').load('ventas/ventasyReportes.php'); //----->Historial de venta y reportes
				$('#ventasHechas').show();
			});

			$('#corteDeCajaBtn').click(function() {
				esconderSeccionVenta();
				$('#corteDeCaja').load('ventas/corteDeCaja.php'); //----->Corte de caja 
				$('#corteDeCaja').show();
			});
		});

		function esconderSeccionVenta() {
			$('#ventaProductos').hide();
			$('#ventasHechas').hide();
			$('#corteDeCaja').hide();
		}
	</script>

<?php
} else {
	header("location:../index.php");
}
?>