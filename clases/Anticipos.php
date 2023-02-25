<?php

class anticipos
{
	public function obtenDatosAnticipo($idanticipo)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "SELECT id_anticipo, id_cliente, id_venta, anticipo, fechaAnticipo FROM anticipos WHERE id_venta =$idanticipo";
		$result = mysqli_query($conexion, $sql);
		$ver = mysqli_fetch_row($result);

		$datos = array(
			'id_anticipo' => $ver[0],
			'id_cliente' => $ver[1],
			'id_venta' => $ver[2],
			'anticipo' => $ver[3],
			'fechaAnticipo' => $ver[4]
		);
		return $datos;
	}
}
