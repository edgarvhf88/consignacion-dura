<?php include("conexion.php"); 
				  
	if (isset($_POST['id_empresa']))
	{
		$id_empresa = $_POST['id_empresa'];
  
		$lista_almacenes = lista_almacenes_consigna();
	 	echo '<option value="0">Todos</option>';
		foreach($lista_almacenes as $id_almacen => $almacen)
		{
		echo '<option value="'.$id_almacen.'">'.$almacen.'</option>';			
		}

		
	}								
?>									