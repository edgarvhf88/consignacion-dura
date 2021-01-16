<?php include("conexion.php"); 
	$id_user = '';
	 if (isset($_POST['id_user'])){
      $id_user = $_POST['id_user'];
	 mostrar_cc($id_user);
		}
     function mostrar_cc($id_user){ 
global $database_conexion, $conex;
$consulta_cc = "SELECT r.id_cc as id_cc, cc.nombre_cc as nombre_cc 
				FROM centro_costos_relacion r
				INNER JOIN centro_costos cc on cc.id_cc = r.id_cc
				WHERE r.id_usuario='$id_user' 
				order by nombre_cc asc";	

$resultado = mysql_query($consulta_cc, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
if ($total_rows > 0){ // con resultados
echo '<option value="0" >(Sin Centro de costos)</option> ';	
while($row_resultados = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id_cc = $row_resultados['id_cc'];
$centro_costos = $row_resultados['nombre_cc'];
echo '<option value="'.$id_cc.'" >'.$centro_costos.'</option>';					
}
} 
else /// sin resultados
{
	echo '<option value="0" >Sin Centros de costos perimitidos</option> ';		
}
}
?>