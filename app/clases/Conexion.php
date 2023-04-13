<?php 

	class conectar{
		private $servidor="212.1.210.115";
		private $usuario="grupotec_Grupo-Tecnolavado";
		private $password="ale_1998";
		private $bd="grupotec_lavanderia";

		public function conexion(){
			$conexion=mysqli_connect($this->servidor,
									 $this->usuario,
									 $this->password,
									 $this->bd);
			return $conexion;
		}
	}


 ?>