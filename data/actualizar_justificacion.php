<?php  include("conexion.php");

if ((isset($_POST['id_justify'])) && ($_POST['id_justify'] != "") ){
	$justificacion = $_POST['justificacion'];
	$id_requi = $_POST['id_justify'];
	actualizar($id_requi, $justificacion);
}


function actualizar($id_requi, $justificacion){
	global $conex;
	
$update_justificacion = "UPDATE requi_autorizacion SET justificacion='$justificacion'  WHERE id_requi='$id_requi'";
			if (mysql_query($update_justificacion, $conex) or die(mysql_error())){
				//echo "update";
			}
			
}
?> 
