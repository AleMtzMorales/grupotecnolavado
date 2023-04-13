<?php 
	session_start();
	require_once "../../clases/Conexion.php";
	require_once "../../clases/Ventas.php";


	//Recibiendo datos del anticipo 
	$anticipo = $_POST['anticipo'];
	$isAdvanceSale  = $_POST['isAdvanceSale'];

	$obj= new ventas();

	if(isset($_SESSION['tablaComprasTemp'])){
		if(count($_SESSION['tablaComprasTemp'])==0 ){
			echo 0;
		}else{
			// $_SESSION['anticipo'] = $anticipo; 
			// $_SESSION['isAdavanceSale'] = $isAdvanceSale; 
	
			$result=$obj->crearVenta($anticipo, $isAdvanceSale);
			unset($_SESSION['tablaComprasTemp']);
	
			//limpiando valores de anticipos 
			// unset($_SESSION['anticipo']);
			// unset($_SESSION['isAdavanceSale']);
	
			echo $result;
		}
	
	}else{
		echo 0 ; 
	}

