<?php

class ventas
{
	public function obtenDatosProducto($idproducto)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "SELECT 
				    art.nombre,
				    art.descripcion,
				    art.cantidad,
				    img.ruta,
				    art.precio
				FROM
				    articulos AS art
				        INNER JOIN
				    imagenes AS img ON art.id_imagen = img.id_imagen
				        AND art.id_producto = '$idproducto'";
		$result = mysqli_query($conexion, $sql);

		$ver = mysqli_fetch_row($result);

		$d = explode('/', $ver[3]);

		$img = $d[1] . '/' . $d[2] . '/' . $d[3];

		$data = array(
			'nombre' => $ver[0],
			'descripcion' => $ver[1],
			'cantidad' => $ver[2],
			'ruta' => $img,
			'precio' => $ver[4]
		);
		return $data;
	}


	/**
	 * Crea venta, 
	 *@param integer $anticipoVar valor del anticipo
	 *@param boolean $isadvancedSaleVar valor booleano si es anticipo

	 * @return array arreglo de valores 
	 */
	public function crearVenta($anticipoVar, $isadvancedSaleVar)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		date_default_timezone_set('America/Mexico_City');

		$fecha = date('Y-m-d H:i:s ');
		$idventa = self::creaFolio();
		$idAnticipo =  self::creaFolioForAnticipo();
		$datos = $_SESSION['tablaComprasTemp'];
		$idusuario = $_SESSION['iduser'];
		$estatusAnticipo = $isadvancedSaleVar == 'true' ? 0 : 1;
		$anticipo = $anticipoVar;

		$idCliente = 1;

		$r = 0;
		$a = 0;

		for ($i = 0; $i < count($datos); $i++) {
			$d = explode("||", $datos[$i]);

			$sql = "INSERT into ventas (id_venta,
										id_cliente,
										id_producto,
										id_usuario,
										precio,
										cantidad,
										estatus,
										fechaCompra)
							values ('$idventa',
									'$d[6]',
									'$d[0]',
									'$idusuario',
									'$d[3]',
									'$d[4]',
									'$estatusAnticipo',
									'$fecha')";
			$r = $r + $result = mysqli_query($conexion, $sql);

			$idCliente = $d[6];
		}

		if ($isadvancedSaleVar == 'true') {
			$sqlAnticipo = "INSERT into anticipos (id_anticipo, id_cliente,id_venta, anticipo, fechaAnticipo) VALUES ('$idAnticipo', '$idCliente','$idventa','$anticipo', '$fecha' )";

			$a = $a + $resultado = mysqli_query($conexion, $sqlAnticipo);
		}

		$res = [$r, $a, $isadvancedSaleVar];
		return json_encode($res);
	}

	public function creaFolio()
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "SELECT id_venta from ventas group by id_venta desc";

		$resul = mysqli_query($conexion, $sql);
		$id = mysqli_fetch_row($resul);
		if ($id != null) {
			if ($id[0] == "" or $id[0] == null or $id[0] == 0) {
				return 1;
			} else {
				return $id[0] + 1;
			}
		} else {
			return 1;
		}
	}

	public function creaFolioForAnticipo()
	{

		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "SELECT id_anticipo from anticipos order by id_anticipo desc";

		$resul = mysqli_query($conexion, $sql);
		$id = mysqli_fetch_row($resul);
		if ($id != null) {
			if ($id[0] == "" or $id[0] == null or $id[0] == 0) {
				return 1;
			} else {
				return $id[0] + 1;
			}
		} else {
			return 1;
		}
	}

	public function nombreCliente($idCliente)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "SELECT apellido,nombre 
			from clientes 
			where id_cliente='$idCliente'";
		$result = mysqli_query($conexion, $sql);

		$ver = mysqli_fetch_row($result);

		return $ver[0] . " " . $ver[1];
	}

	public function obtenerTotal($idventa)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "SELECT precio 
				from ventas 
				where id_venta='$idventa'";
		$result = mysqli_query($conexion, $sql);

		$total = 0;

		while ($ver = mysqli_fetch_row($result)) {
			$total = $total + $ver[0];
		}

		return $total;
	}

	public function saldarVenta($datos)
	{

		$fecha = date('Y-m-d H:i:s ');
		$c = new conectar();
		$conexion = $c->conexion();
		$sqlUpdateVenta = "UPDATE ventas set estatus = 2 
		where id_venta='$datos[0]'"; // Con estatus 2, porque hace referencia a una venta que paso por el status de pendiente (0)
		$resultVenta =  mysqli_query($conexion, $sqlUpdateVenta);

		self::guardarAnticipo(self::creaFolioForAnticipo(), $datos[1], $datos[0], $datos[2] - self::obtenerAnticipo($datos[0]), $fecha);


		// $ver = mysqli_fetch_row($result); 



		// return mysqli_query($conexion, $sql);
		return $resultVenta;
	}



	public function obtenerAnticipo($idventa)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "SELECT anticipo, id_venta, id_cliente FROM anticipos WHERE id_venta = '$idventa'";
		$result = mysqli_query($conexion, $sql);

		$anticipo = 0;

		while ($ver = mysqli_fetch_row($result)) {
			$anticipo =  $ver[0];
		}

		return $anticipo;
	}


	public function guardarAnticipo($idAnticipo, $idCliente, $idventa, $anticipo, $fecha)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "INSERT into anticipos (id_anticipo, id_cliente,id_venta, anticipo, fechaAnticipo) VALUES ('$idAnticipo', '$idCliente','$idventa','$anticipo', '$fecha' )";
		return $result = mysqli_query($conexion, $sql);
	}
}
