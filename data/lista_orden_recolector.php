<?php include("conexion.php"); 
	$id_user = '';
	 if (isset($_POST['id_user'])){
      $id_user = $_POST['id_user'];
	 mostrar_cc($id_user);
		}
     function mostrar_cc($id_user){ 
global $database_conexion, $conex;
$consulta = "SELECT r.id_relacion as id_r, users.id as id_user, users.nombre as nombre, users.apellido as apellido 
				FROM recolector_relacion r
				INNER JOIN usuarios users on users.id = r.id_recolector
				WHERE r.id_comprador='$id_user' 
				order by username asc";	

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
if ($total_rows > 0){ // con resultados
echo '<option value="0" >Personalmente</option> ';	
while($row_resultados = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id_u = $row_resultados['id_user'];
$nombre_recolector = $row_resultados['nombre'].' '.$row_resultados['apellido'];
echo '<option value="'.$id_u.'" >'.$nombre_recolector.'</option>';					
}
} 
else /// sin resultados
{
	echo '<option value="0" >Sin recolectores asignados</option> ';		
}
}
?>