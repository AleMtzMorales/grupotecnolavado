<?php

class egresos
{

	public function agregaEgreso($datos)
	{
		$c = new conectar();
		$conexion = $c->conexion();
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');

		$idusuario = $_SESSION['iduser'];

		$sql = "INSERT into egresos (id_usuario,
										descripcion,
										totalEgreso,
										fechaEgreso)
							values ('$idusuario',
									'$datos[0]',
									'$datos[1]',
									'$fecha' )";
		return mysqli_query($conexion, $sql);
	}


	public function obtenDatosEgreso($idegreso)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "SELECT 
					id_egreso,
					descripcion,
					totalEgreso 
				from egresos WHERE id_egreso=$idegreso";
		$result = mysqli_query($conexion, $sql);
		$ver = mysqli_fetch_row($result);

		$datos = array(
			'id_egreso' => $ver[0],
			'descripcion' => $ver[1],
			'cantidad' => $ver[2]
		);
		return $datos;
	}

	public function actualizaEgreso($datos)
	{
		$c = new conectar();
		$conexion = $c->conexion();
		$sql = "UPDATE egresos set descripcion='$datos[1]',
										totalEgreso='$datos[2]'
								where id_egreso='$datos[0]'";
		return mysqli_query($conexion, $sql);
	}

	public function eliminaEgreso($idegreso)
	{
		$c = new conectar();
		$conexion = $c->conexion();

		$sql = "DELETE from egresos where id_egreso='$idegreso'";

		return mysqli_query($conexion, $sql);
	}
}
