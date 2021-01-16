<?php include("conexion.php");

      $almacen_id = $_POST['almacen_id'];
      $id_inventario = $_POST['id_inventario'];
		
		if ($almacen_id != ""){
			cambiar_almacen($almacen_id,$id_inventario);
		}

	function cambiar_almacen($almacen_id,$id_inventario){
		global $conex;
		
		$update = "UPDATE inventarios SET almacen_id='$almacen_id' WHERE id_inventario='$id_inventario'";
	if (mysql_query($update, $conex) or die(mysql_error()))
	{
		
	}
		
	}

  




?>