<?php include("conexion.php"); 
	  if (isset($_POST['id_cc'])){
      $id_cc = $_POST['id_cc'];
      
			datos_cc($id_cc);
	 }
	 
     function datos_cc($id_cc){ 
global $database_conexion, $conex;

$consulta_cc = "SELECT * FROM centro_costos WHERE id_cc=$id_cc";
$resultado = mysql_query($consulta_cc, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados
	
		$tipo = $row['id_empresa'];
		$nombre_cc = $row['nombre_cc'];
 
 echo '<script> 
	
	//$("#select_cc_empresa").val("'.$tipo.'");
	$("#txt_cc_nombre").val("'.$nombre_cc.'");

</script>';  

 
} 

}




?>