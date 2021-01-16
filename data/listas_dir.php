<?php include("../data/conexion.php"); 
if (isset($_POST['dir_fac'])){
	$id_usuario = $_POST['id_user'];
	lista_direccion_fac($id_usuario);
}
if (isset($_POST['dir_suc'])){
	$id_usuario = $_POST['id_user'];
	lista_direccion_suc($id_usuario);
}
if (isset($_POST['dir_select'])){
	$dir_select = $_POST['dir_select'];
	datos_direccion($dir_select);
}


function datos_direccion($dir_select){ 
global $database_conexion, $conex;
$consulta_sucursales = "SELECT b.direccion as dir, b.codigo_postal as cp, p.pais as pais, e.estado as estado , r.region as ciudad 
			FROM sucursales b 
			INNER JOIN paises p on b.id_pais = p.id_pais
			INNER JOIN estados e on b.id_estado = e.id_estado
			INNER JOIN region r on b.id_ciudad = r.id_region
			WHERE id_sucursal = '$dir_select'";

$resultado = mysql_query($consulta_sucursales, $conex) or die(mysql_error());
$row_sucursal = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

$direccion = $row_sucursal['dir'];
$cp = $row_sucursal['cp'];
$pais = $row_sucursal['pais'];
$estado = $row_sucursal['estado'];
$ciudad = $row_sucursal['ciudad'];

echo '<b>
'.$direccion.' <br/>
'.$pais.' <br/>
'.$estado.' <br/>
'.$ciudad.' <br/>
'.$cp.' <br/>

</b>';


}

function lista_direccion_suc($id_usuario){ 
global $database_conexion, $conex;

$consulta_sucursales = "
SELECT r.id_sucursal as id_sucursal, s.id_sucursal as id_suc, s.sucursal as sucursal, s.direccion as direccion, s.codigo_postal as cp
FROM registros_sucursales r 
INNER JOIN sucursales s on s.id_sucursal = r.id_sucursal
WHERE r.id_usuario = '$id_usuario' AND s.uso_suc='1'";

$resultado = mysql_query($consulta_sucursales, $conex) or die(mysql_error());

$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


				
while($row_sucursal = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id = $row_sucursal['id_suc'];
$sucursal = $row_sucursal['sucursal'];
				
echo '<option value="'.$id.'" >'.$sucursal.'</option>';					
}				
 


 
} 
else /// sin resultados
{
	echo '<option value="0" >No se ha regitrado ninguna direccion para el envio</option>';		
		


}

}
function lista_direccion_fac($id_usuario){ 
global $database_conexion, $conex;

$consulta_sucursales = "
SELECT r.id_sucursal as id_sucursal, s.id_sucursal as id_suc, s.sucursal as sucursal, s.direccion as direccion, s.codigo_postal as cp
FROM registros_sucursales r 
INNER JOIN sucursales s on s.id_sucursal = r.id_sucursal
WHERE r.id_usuario = '$id_usuario' AND s.uso_dir_fac='1'";

$resultado = mysql_query($consulta_sucursales, $conex) or die(mysql_error());

$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


				
while($row_sucursal = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id = $row_sucursal['id_suc'];
$sucursal = $row_sucursal['sucursal'];
				
echo '<option value="'.$id.'" >'.$sucursal.'</option>';					
}				
 


 
} 
else /// sin resultados
{
	echo '<option value="0" >No se ha registrado ninguna direccion para facturacion</option>';		
		


}

}


?>