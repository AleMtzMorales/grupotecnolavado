<?php 

	require_once "../../clases/Conexion.php";
	require_once "../../clases/Anticipos.php";

	$obj= new anticipos();

	echo json_encode($obj->obtenDatosAnticipo($_POST['idanticipo']));

 ?>