<?php include("conexion.php"); 
	$id_empresa = '';
	 if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
	 busca_dep($id_empresa);
		}


 function busca_dep($id_empresa){ 
global $database_conexion, $conex;

$consulta = "SELECT dep.departamento as departamento,  dep.id_departamento as id_departamento 
FROM departamentos dep
WHERE dep.id_empresa='$id_empresa'";

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados
//echo '<option value="0" >(Sin Departamentos)</option> ';	
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id_cc = $row['id_departamento'];
$departamentos = $row['departamento'];
echo '<option value="'.$id_cc.'" >'.$departamentos.'</option>';					
}
} 
else /// sin resultados
{
	echo '<option value="0" >Sin Departamentos</option> ';		
}

//return $lista; 
}

?>