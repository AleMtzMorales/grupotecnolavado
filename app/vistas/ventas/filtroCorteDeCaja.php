<?php
sleep(1);
require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";

$c = new conectar();
$conexion = $c->conexion();

$obj = new ventas();
$totalVentasPagadas = 0;
$totalEgresos = 0;

// $sql = "SELECT id_venta,
// 				fechaCompra,
// 				id_cliente 
// 			from ventas group by id_venta";
// $result = mysqli_query($conexion, $sql);


/**
 * Nota: Es recomendable guardar la fecha en formato año - mes y dia (2022-08-25)
 * No es tan importante que el tipo de fecha sea date, puede ser varchar
 * La funcion strtotime:sirve para cambiar el forma a una fecha,
 * esta espera que se proporcione una cadena que contenga un formato de fecha en Inglés US,
 * es decir año-mes-dia e intentará convertir ese formato a una fecha Unix dia - mes - año.
 */


$fechaInit = date("Y-m-d", strtotime($_POST['f_ingreso']));
$fechaFin = date("Y-m-d", strtotime($_POST['f_fin']));

$sqlVentas = ("SELECT ve.id_venta,
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
on ve.id_cliente=client.id_cliente 
WHERE ve.estatus =1 AND `fechaCompra` 
BETWEEN '$fechaInit 00:00:00' AND '$fechaFin 23:59:59' group by id_venta DESC");


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
						where `fechaCompra` 
BETWEEN '$fechaInit 00:00:00' AND '$fechaFin 23:59:59'AND ve.estatus = 2 OR ve.estatus = 0 ORDER BY ve.id_venta DESC";

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
                    WHERE `fechaEgreso` 
BETWEEN '$fechaInit 00:00:00' AND '$fechaFin 23:59:59'
			ORDER BY eg.id_egreso DESC";



$resultFilter = mysqli_query($conexion, $sqlVentas);
$resultVentaParcial = mysqli_query($conexion, $sqlForPendingSales);
$resultEgresos = mysqli_query($conexion, $sqlEgresos);

//print_r($sqlTrabajadores);
// $total = mysqli_num_rows($query);
// echo '<strong>Total: </strong> (' . $total . ')';
?>

<br>
<br>
<!-- Start component for search sales-->
<!-- <div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-4">
            <input class="form-control" id="myInputSearch" type="text" placeholder="Buscar venta por folio...">
        </div>
    </div>
</div> -->
<!-- End component for search sales-->

<br>
<!-- End component for search sales-->
<div id="ventas-reportes" class="table-responsive" style="padding-right: 15px;">
    <table class="table table-hover table-condensed table-bordered" style="text-align: center;">
        <caption>
            <label>
                <?php
                $total = mysqli_num_rows($resultFilter);
                $total2 = mysqli_num_rows($resultVentaParcial);
                echo '<strong>Ingresos, total: </strong> (' . $total + $total2 . ')'; ?>
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
        <?php while ($ver = mysqli_fetch_row($resultFilter)) : ?>

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




<!-- Modal -->
<div class="modal fade" id="abremodalClientesUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
<div class="modal fade" id="openModalSeePay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Historial de pagos</h4>
            </div>
            <div class="modal-body">
                <form id="frmClientesU">



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
    $(document).ready(function() {
        $("#myInputSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#ventas-reportes-tbody tr td:nth-child(1)").filter(function() {


                $(this).parent().toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });

        });
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
        })
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
</Script>