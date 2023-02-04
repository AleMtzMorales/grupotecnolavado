<?php
sleep(1);
require_once "../../clases/Conexion.php";
require_once "../../clases/Ventas.php";

$c = new conectar();
$conexion = $c->conexion();

$obj = new ventas();

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

$sqlVentas = ("SELECT id_venta, fechaCompra, id_cliente, estatus  FROM ventas WHERE `fechaCompra` BETWEEN '$fechaInit 00:00:00' AND '$fechaFin 23:59:59' group by id_venta DESC");
// $sql = "SELECT id_venta,
// 				fechaCompra,
// 				id_cliente 
// 			from ventas group by id_venta";	

$query = mysqli_query($conexion, $sqlVentas);
//print_r($sqlTrabajadores);
// $total = mysqli_num_rows($query);
// echo '<strong>Total: </strong> (' . $total . ')';
?>

<br>
<br>
<!-- Start component for search sales-->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-4">
            <input class="form-control" id="myInputSearch" type="text" placeholder="Buscar venta por folio...">
        </div>
    </div>
</div>
<!-- End component for search sales-->


<table id="ventas-reportes" class="table table-hover table-condensed table-bordered" style="text-align: center;">
    <caption><label>
            <?php $total = mysqli_num_rows($query);
            echo '<strong>Ventas, total: </strong> (' . $total . ')'; ?>
        </label></caption>
    <thead>
        <tr>
            <td>Folio</td>
            <td>Fecha y hora</td>
            <td>Cliente</td>
            <td>Total de compra</td>
            <td>Estatus</td>
            <td>Ticket</td>
            <td><strong>Historial de pagos</strong></td>

        </tr>
    </thead>
    <?php
    while ($ver = mysqli_fetch_array($query)) { ?>
        <tbody id="ventas-reportes-tbody">
            <tr>
            <tr>
                <td><?php echo $ver["id_venta"] ?></td>
                <td><?php echo $ver["fechaCompra"] ?></td>
                <td>
                    <?php
                    if ($obj->nombreCliente($ver["id_cliente"]) == " ") {
                        echo "S/C";
                    } else {
                        echo $obj->nombreCliente($ver["id_cliente"]);
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
Saldar venta</span>
</span>';
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
                    <a href="../procesos/ventas/crearTicketPdf.php?idventa=<?php echo $ver["id_venta"] ?>" class="btn btn-danger btn-sm">
                        Ticket <span class="glyphicon glyphicon-list-alt"></span>
                    </a>
                </td>
                <td>

                    <?php

                    echo '
<span class="btn btn-primary btn-sm" data-toggle="modal" data-target="#openModalSeePay" onclick="saldarDeudaPendiente(',  $ver[0], ",", $ver[2], ",", $obj->obtenerTotal($ver[0]), ",", $obj->obtenerAnticipo($ver[0]), ')" ">
Ver pagos <span class="bi bi-clock-history"></span></span>';
                    ?>
                </td>

            </tr>
            </tr>
        </tbody>

    <?php } ?>

</table>

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