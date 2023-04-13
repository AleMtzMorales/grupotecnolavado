<?php 

	require_once "../../clases/Conexion.php";
	require_once "../../clases/Egresos.php";

	$obj= new egresos();

	
	echo $obj->eliminaEgreso($_POST['idegreso']);
 ?>