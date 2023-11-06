<?php 

	class conectar{
		private $servidor="92.249.45.106";
		private $usuario="grup1396_markconfig";
		private $password="markconfig";
		private $bd="grup1396_ventas";

		public function conexion(){
			$conexion=mysqli_connect($this->servidor,
									 $this->usuario,
									 $this->password,
									 $this->bd);
			return $conexion;
		}
	}


 ?>