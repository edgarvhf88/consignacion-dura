<?php include("conexion.php");
// planta 3
$almacen_remplazar = 431265;
$almacen_nuevo = 11142;

			$update_almacenes = "UPDATE existencias SET almacen_id='$almacen_nuevo'  WHERE almacen_id='$almacen_remplazar' ";
			if (mysql_query($update_almacenes, $conex) or die(mysql_error())){
				echo "se actualizo Almacen Dura Planta 3 <br />";
			}
			
			// planta 4
$almacen_remplazar = 431267;
$almacen_nuevo = 11143;

			$update_almacenes2 = "UPDATE existencias SET almacen_id='$almacen_nuevo'  WHERE almacen_id='$almacen_remplazar' ";
			if (mysql_query($update_almacenes2, $conex) or die(mysql_error())){
				echo "se actualizo Almacen Dura Planta 4";
			}