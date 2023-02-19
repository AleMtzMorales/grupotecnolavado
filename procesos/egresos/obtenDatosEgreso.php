<?php 

	require_once "../../clases/Conexion.php";
	require_once "../../clases/Egresos.php";

	$obj= new egresos();

	echo json_encode($obj->obtenDatosEgreso($_POST['idegreso']));

 ?>