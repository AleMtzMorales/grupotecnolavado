<?php 

session_start();
	require_once "../../clases/Conexion.php";
	require_once "../../clases/Egresos.php";

	$obj= new egresos();


	$datos=array(
			$_POST['descripcion'],
			$_POST['cantidad']
				);

	echo $obj->agregaEgreso($datos);
