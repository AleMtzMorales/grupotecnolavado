<?php
require_once "../../clases/Conexion.php";
$c = new conectar();
$conexion = $c->conexion();
$sql = "SELECT  art.nombre,
art.descripcion,
art.cantidad,
art.precio,
img.ruta,
cat.nombreCategoria,
art.id_producto
from articulos as art 
inner join imagenes as img
on art.id_imagen=img.id_imagen
inner join categorias as cat
on art.id_categoria=cat.id_categoria ORDER BY art.id_producto DESC";
$result = mysqli_query($conexion, $sql);

?>



<br>

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-8">
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-8">
					<input type="number" id="inputForFolio" class="form-control" type="text" placeholder="Folio">
				</div>
				<div class="col-sm-4">
					<button id="btnSearchByFolio" class="btn btn-primary">Buscar</button>
				</div>
			</div>

		</div>
	</div>
</div>
<table class="table table-hover table-condensed table-bordered" style="text-align: center;">
	<caption><label>Prendas</label></caption>
	<thead>
		<tr>
			<td>Folio</td>
			<td>Nombre</td>
			<td>Descripción</td>
			<td>Cantidad kg / piezas</td>
			<td>Precio</td>
			<td>Imagen</td>
			<td>Categoria</td>
			<td>Editar</td>
			<td>Eliminar</td>
		</tr>

	</thead>


	<tbody>
		<?php while ($ver = mysqli_fetch_row($result)) : ?>

			<tr>
				<td><?php echo $ver[6]; ?></td>

				<td><?php echo $ver[0]; ?></td>
				<td><?php echo $ver[1]; ?></td>
				<td><?php echo $ver[2]; ?></td>
				<td><?php echo "$" . number_format((float)$ver[3], 2, '.', ','); ?></td>
				<td>
					<?php
					$imgVer = explode("/", $ver[4]);
					$imgruta = $imgVer[1] . "/" . $imgVer[2] . "/" . $imgVer[3];
					?>
					<img width="80" height="80" src="<?php echo $imgruta ?>">
				</td>
				<td><?php echo $ver[5]; ?></td>
				<td>
					<span data-toggle="modal" data-target="#abremodalUpdateArticulo" class="btn btn-warning btn-xs" onclick="agregaDatosArticulo('<?php echo $ver[6] ?>')">
						<span class="glyphicon glyphicon-pencil"></span>
					</span>
				</td>
				<td>
					<span class="btn btn-danger btn-xs" onclick="eliminaArticulo('<?php echo $ver[6] ?>')">
						<span class="glyphicon glyphicon-remove"></span>
					</span>
				</td>
			</tr>
		<?php endwhile; ?>

	</tbody>
</table>



	<!-- Modal par ver listado prendas -->
	<div class="modal fade" id="openModalTablaVerPrendasArt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Listado de prendas</h4>
				</div>
				<div class="modal-body" style="padding: 1rem;">
					<!-- <form id="frmClientesU">

					</form> -->

					<div id="tablaVerPrendasArt" >

					</div>

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
	$(document).ready(function() {
		$("#btnSearchByFolio").on("click", function() {


			var valueFolio = $("#inputForFolio").val();

			if(valueFolio != null && valueFolio != undefined && valueFolio != "" && valueFolio> 0 ){
				
				$.ajax({
					type: "POST",
					data: "idproducto=" + valueFolio,
					url: "./articulos/tablaPrenda.php",
					success: function(r) {
				


						$('#tablaVerPrendasArt').empty();
						$('#tablaVerPrendasArt').html(r);
						$('#openModalTablaVerPrendasArt').modal('show');

						
					}
				});
			}else{
				alertify.alert("Debes de ingresar un folio válido");
			}


			$("#myTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});

			console.log("mi input ")
		});
	});
</script>