<?php
require_once "../../clases/Conexion.php";
$c = new conectar();
$conexion = $c->conexion();

$idproducto = $_POST['idproducto'];


$sql = "SELECT art.id_producto,
			art.cantidad, 
			art.nombre, 
			art.descripcion, 
			cate.nombreCategoria, 
			img.ruta
		from articulos as art 
				inner join imagenes as img 
			on art.id_imagen=img.id_imagen
				inner join categorias as cate 
			on art.id_categoria=cate.id_categoria where art.id_producto = $idproducto ORDER BY art.id_producto ASC";
$result = mysqli_query($conexion, $sql);

?>
<?php

if (mysqli_num_rows($result) <= 0) {
	echo '<div class="alert alert-warning" role="alert">No existe ningún registro con el folio ingresado [' . $idproducto . ']</div>';
} else {
?>
	<table id="tablaPrendasFor" class="table table-hover table-condensed table-bordered table-responsive" style="text-align: center;">
		<!-- <caption><label>Historial de pagos </label></caption> -->
		<thead>
			<tr>
				<td>Folio</td>
				<td>Cantidad kg / piezas</td>
				<td>Nombre</td>
				<td>Descripción</td>
				<td>Categoría</td>
				<td>Imagen</td>

			</tr>
		</thead>

		<tbody>



			<?php while ($ver = mysqli_fetch_row($result)) : ?>

				<tr>
					<td><?php echo $ver[0]; ?></td>

					<td><?php echo $ver[1]; ?></td>
					<td><?php echo $ver[2]; ?></td>
					<td><?php echo $ver[3]; ?></td>
					<td><?php echo $ver[4]; ?></td>
					<td>
						<?php
						$imgVer = explode("/", $ver[5]);
						$imgruta = $imgVer[1] . "/" . $imgVer[2] . "/" . $imgVer[3];
						?>
						<img width="80" height="80" src="<?php echo $imgruta ?>">
					</td>
				</tr>
			<?php endwhile; ?>



		</tbody>
	</table>

<?php } ?>