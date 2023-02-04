<?php 

session_start();
	require_once "../../clases/Conexion.php";
	require_once "../../clases/Ventas.php";

	$obj= new ventas();


	$datos=array(
			$_POST['idventa'],
			$_POST['idclienteU'],
			$_POST['totalCompra'],


			// $_POST['apellidosU'],
			// $_POST['direccionU'],
			// $_POST['observacionesU'],
			// $_POST['telefonoU']
				);

	echo $obj->saldarVenta($datos);
