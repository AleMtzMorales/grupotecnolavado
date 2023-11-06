<?php 

session_start();
	require_once "../../clases/Conexion.php";
	require_once "../../clases/Egresos.php";

	$obj= new egresos();


	$datos=array(
			$_POST['idegresoU'],
			$_POST['descripcionU'],
			$_POST['cantidadU']
				);

	echo $obj->actualizaegreso($datos);
