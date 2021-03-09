<?php  
session_start();
// error_reporting(0);
if (!isset($_SESSION["logged_user"])){
	$_SESSION["logged_user"] = '';
}
/* */
$ruta = "10.0.0.9:C:\\microsip datos\\ALLPARTS.fdb";
//$ruta = "10.0.0.9:C:\\microsip datos\\nef2011.fdb";
$username = "SYSDBA";
$password = "masterkey";

try {
   $con_micro = new PDO("firebird:dbname=$ruta", "$username", "$password");
   $con_micro->exec("SET CHARACTER SET utf8_decode"); 
    $con_micro->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   print "Error!: " . $e->getMessage() . "<br/>";
   die();
} 
$ruta_nef = "10.0.0.9:C:\\microsip datos\\nef2011.fdb";

try {
   $con_micro_nef = new PDO("firebird:dbname=$ruta_nef", "$username", "$password");
   $con_micro_nef->exec("SET CHARACTER SET utf8_decode"); 
    $con_micro_nef->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   print "Error!: " . $e->getMessage() . "<br/>";
   die();
}  

// mysql pdo


$conex_sqli = new mysqli('162.214.184.221','consigna_adminallpart','Nacional_2021','consigna_dura','3306') or die(mysqli_error());
///////////////////////////////////////////////////////


/*$hostname_conexion = "localhost:3306";*/	//
/*$database_conexion = "consignacion_dura";
=======
/* $hostname_conexion = "162.214.184.221:3306"; // db hostgator
$hostname_conexion = "localhost";	//
$database_conexion = "consignacion_dura";

$username_conexion = "root";
$password_conexion = "fAMMA1234";
*/ 
 $hostname_conexion = "162.214.184.221:3306";  // db hostgator
$database_conexion = "consigna_dura";
$username_conexion = "consigna_adminallpart";
$password_conexion = "Nacional_2021"; 

$conex = mysql_pconnect($hostname_conexion, $username_conexion, $password_conexion) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_conexion, $conex);


    function redimensionar_imagen($nombreimg, $rutaimg, $xmax, $ymax){  
        $ext = explode(".", $nombreimg);  
        $ext = $ext[count($ext)-1];  
      
        if($ext == "jpg" || $ext == "jpeg"){  
            $imagen = imagecreatefromjpeg($rutaimg);  
		}
        elseif($ext == "png")  {
            $imagen = imagecreatefrompng($rutaimg);  
		}
        elseif($ext == "gif")  {
            $imagen = imagecreatefromgif($rutaimg);  
		}
          
        $x = imagesx($imagen);  
        $y = imagesy($imagen);  
          
        if($x <= $xmax && $y <= $ymax){
            echo "<center>Esta imagen ya esta optimizada para los maximos que deseas.<center>";
            return $imagen;  
        }
      
        if($x >= $y) {  
            $nuevax = $xmax;  
            $nuevay = $nuevax * $y / $x;  
        }  
        else {  
            $nuevay = $ymax;  
            $nuevax = $x / $y * $nuevay;  
        }  
          
        $img2 = imagecreatetruecolor($nuevax, $nuevay);  
        imagecopyresized($img2, $imagen, 0, 0, 0, 0, floor($nuevax), floor($nuevay), $x, $y);  
        echo "<center>La imagen se ha optimizado correctamente.</center>";
        return $img2;   
    }
	//  $imagen = $_POST['imagen']; //imagen qu subo
    //	$imagen_optimizada = redimensionar_imagen($imagen,'images/imagen.jpg',700,700);
    //	imagejpeg($imagen_optimizada, "../inv_docs/imagenes/imagen_optimizada.jpg"); //imagen que bajo

/////////////////////////////////
function Nombre($identificador) { // obtiene NOMBRE de usuario**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM usuarios WHERE id = $identificador";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

if ($totalRows > 0){
return $row['nombre']." ". $row['apellido'];
}
 else
{
return 'Sin Usuario';
}	
mysql_free_result($resultado);  
}
function correo_usuario($identificador) { // obtiene NOMBRE de usuario**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM usuarios WHERE id = $identificador";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

if ($totalRows > 0){
return $row['correo'];
}
 else
{
return 'Sin correo';
}	
mysql_free_result($resultado);  
}

function DEPARTAMENTO($identificador) { // obtiene NOMBRE de usuario**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM usuarios WHERE id = $identificador";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

if ($totalRows > 0){
return $row['id_departamento'];
}
 else
{
return '0';
}	
mysql_free_result($resultado);  
}

function EMPRESA_NOMBRE($id) { // Nombre de Empresa**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM empresas WHERE id_empresa = '$id'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$nombre = "";

if ($totalRows > 0){
$nombre = $row['nombre'];

}
return $nombre;
	
mysql_free_result($resultado);  
}

function ARTICULO_NOMBRE($id) { // Nombre de ARTICULO**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM articulos WHERE id = '$id'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$nombre = "";

if ($totalRows > 0){
$nombre = $row['nombre']." (".$row['clave_empresa'].")";

}
return $nombre;
	
mysql_free_result($resultado);  
}
function validar_articulo($articulo_id,$almacen_id) { // VALIDA EXISTENCIA DEL ARTICULO*
global $conex;

$query = "SELECT * 
		FROM articulos a
		INNER JOIN existencias exis ON exis.id_articulo = a.id
		WHERE a.id_microsip = '$articulo_id'
		AND exis.almacen_id = '$almacen_id'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
if ($totalRows > 0){
	return 1;

}else{
	return 0;
}

mysql_free_result($resultado);  
}
function existencia_articulo($articulo_id,$almacen_id) { // VALIDA EXISTENCIA DEL ARTICULO del almacen correspondiente
global $conex;

$query = "SELECT exis.existencia_actual as existencia 
		FROM articulos a
		INNER JOIN existencias exis ON exis.id_articulo = a.id
		WHERE a.id_microsip = '$articulo_id'
		AND exis.almacen_id = '$almacen_id'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$existencia = 0;
if ($totalRows > 0){
	$existencia = str_replace(",","",$row['existencia']);
	
}else{
	
}
return $existencia;
mysql_free_result($resultado);  
}
function almacen_inventario($id_inventario) { // devuelve el almacen_id del inventario activo
global $conex;

$query = "SELECT *
		FROM inventarios
		WHERE id_inventario = '$id_inventario'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$almacen_id = 0;
if ($totalRows > 0){
	$almacen_id = $row['almacen_id'];

}else{
	
}
return $almacen_id;
mysql_free_result($resultado);  
}

function CC_NOMBRE($id) { // Nombre de centro de costos *********************************
global $database_conexion, $conex;

$query = "SELECT * FROM centro_costos WHERE id_cc = '$id'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$nombre = $id;

if ($totalRows > 0){
$nombre = $row['nombre_cc'];

}
return $nombre;
	
mysql_free_result($resultado);  
}

function direccion_vendedor($id_usuario) { // Direccion sucursal*********************************
global $database_conexion, $conex;

$query = "SELECT b.direccion as dir, b.codigo_postal as cp, p.pais as pais, e.estado as estado , r.region as ciudad 
			FROM usuarios a 
			INNER JOIN sucursales b on a.id_sucursal = b.id_sucursal
			INNER JOIN paises p on b.id_pais = p.id_pais
			INNER JOIN estados e on b.id_estado = e.id_estado
			INNER JOIN region r on b.id_ciudad = r.id_region
			WHERE a.id = '$id_usuario'";

$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$sucursal = "1";

if ($totalRows > 0){
$sucursal = $row['dir']."_".$row['cp']."_".$row['pais']."_".$row['estado']."_".$row['ciudad'];

}
return $sucursal;
	
mysql_free_result($resultado);  
}
function direccion_sucursal($id_sucursal) { // Direccion sucursal*********************************
global $database_conexion, $conex;

$query = "SELECT b.direccion as dir, b.codigo_postal as cp, p.pais as pais, e.estado as estado , r.region as ciudad 
			FROM sucursales b 
			INNER JOIN paises p on b.id_pais = p.id_pais
			INNER JOIN estados e on b.id_estado = e.id_estado
			INNER JOIN region r on b.id_ciudad = r.id_region
			WHERE b.id_sucursal = '$id_sucursal'";

$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$sucursal = "1";

if ($totalRows > 0){
$sucursal = $row['dir']."_".$row['cp']."_".$row['pais']."_".$row['estado']."_".$row['ciudad'];

}
return $sucursal;
	
mysql_free_result($resultado);  
}
function EMPRESA_NOMBRE_RFC($id) { // Lista de Empresas**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM empresas WHERE id_empresa = '$id'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$nombre = "";

if ($totalRows > 0){
if ($row['nombre'] == ""){
	$nombre = "-";
}else{
$nombre = $row['nombre'];	
}
if ($row['rfc'] == ""){
	$rfc = "-";
}else{
$rfc = $row['rfc'];	
}	
	
$nombre_rfc = $nombre."_".$rfc;

}
return $nombre_rfc;
	
mysql_free_result($resultado);  
}
function id_empresa($id_usuario) { // obtiene id de empresa **********************************
global $database_conexion, $conex;
if ($id_usuario != ""){
$query = "SELECT * FROM usuarios WHERE id = $id_usuario";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$id_empresa = '0';
if ($totalRows > 0){
	
$id_empresa = $row['id_empresa'];


} 
else 
{
	$id_empresa = "0";
}	
}
else 
{
	$id_empresa = "0";
}
return $id_empresa;
	
mysql_free_result($resultado);  
}
function validar_usuario($id_usuario) { // Valida usuario**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM usuarios WHERE id = $id_usuario";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$tipo_usuario = '0';
if ($totalRows > 0){
	
$tipo_usuario = $row['tipo_usuario'];


} 
else 
{
	$tipo_usuario = "0";
}	


return $tipo_usuario;
	
mysql_free_result($resultado);  
}
function display_empresas() { // Lista de Empresas para display**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM empresas";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_empresa']] = $row['display'];

}
	
} 
else 
{
	$lista[0] = "Sin Empresas";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function lista_empresas() { // Lista de Empresas**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM empresas";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_empresa']] = $row['nombre'];

}
	
} 
else 
{
	$lista[0] = "Sin Empresas";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function lista_almacenes_consigna(){ // Lista de Almacenes ***************************
global $database_conexion, $conex;

$query = "SELECT * FROM almacenes";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['almacen_id']] = $row['almacen'];

}
	
} 
else 
{
	$lista[0] = "Sin almacenes registrados";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function lista_almacenes_inventario(){ // Lista de Almacenes que estan en inventario actualmente o en progreso   ***************************
global $database_conexion, $conex;

$query = "SELECT alm.almacen as almacen, i.almacen_id as almacen_id 
			FROM inventarios i 
			INNER JOIN almacenes alm ON alm.almacen_id = i.almacen_id
			WHERE i.estatus ='A' AND i.cancelado = 'N' AND i.aplicado = 'N'
			";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['almacen_id']] = $row['almacen'];

}
	
} 
else 
{
	$lista[0] = "0";
}	


return $lista;
	
mysql_free_result($resultado);  
}

function categorias_lista($id_empresa) { // Lista de Empresas**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM categorias WHERE id_empresa = '$id_empresa'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_categoria']] = $row['categoria'];

}
	
} 
else 
{

}	


return $lista;
	
mysql_free_result($resultado);  
}

function DEPARTAMENTO_NOMBRE($id) { // Nombre de departamento**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM departamentos WHERE id_departamento = '$id'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$nombre = "";

if ($totalRows > 0){
$nombre = $row['departamento'];

}
return $nombre;
	
mysql_free_result($resultado);  
}

function COMODITY_NOMBRE($id) { // Nombre de departamento**********************************
global $database_conexion, $conex;

$query = "SELECT c.categoria as nombre_cat, c.id_categoria as id_cat
			FROM registros_categorias rc
			INNER JOIN categorias c ON c.id_categoria = rc.id_categoria
			WHERE rc.id_articulo = '$id'";
			
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$categorias = "";

$lista = array();
	if ($totalRows > 0)
	{
		while ($row = mysql_fetch_array($resultado,MYSQL_BOTH))
		{
			if ($categorias != ""){
				$categorias .= ", ".$row['nombre_cat'];	
			}else {
			$categorias .= $row['nombre_cat'];	
			}
	
		}
		
	} 
return $categorias;
	
mysql_free_result($resultado);  
}
function lista_departamentos() { // Lista de Departamentos**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM departamentos";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_departamento']] = $row['departamento'];

}
	
} 
else 
{
	$lista[0] = "Sin Departamentos";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function lista_requisitores() { // Lista de Requisitores**********************************
global $database_conexion, $conex;

$query = "SELECT a.nombre as nombre_usuario, a.apellido as apellido, a.id as id_usuario, b.nombre as nombre_empresa FROM usuarios a
INNER JOIN empresas b on a.id_empresa = b.id_empresa 
WHERE a.tipo_usuario = '2'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_usuario']] = $row['nombre_usuario'].' '.$row['apellido'].' ('.$row['nombre_empresa'].')';

}
	
} 
else 
{
	$lista[0] = "Sin requisitores registrados";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function lista_relaciones() { // Lista de Requisitores**********************************
global $database_conexion, $conex;

$query = "SELECT a.nombre as nombre_requisitor, a.apellido as apellido_requisitor, r.id_relacion as id_relacion, b.nombre as nombre_vendedor, b.apellido as apellido_vendedor 
FROM relaciones r
INNER JOIN usuarios a on r.id_requisitor = a.id 
INNER JOIN usuarios b on r.id_vendedor = b.id 
			";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_relacion']] = $row['nombre_requisitor'].' '.$row['apellido_requisitor'].' Atendido por '.$row['nombre_vendedor'].' '.$row['apellido_vendedor'];

}
	
} 
else 
{
	$lista[0] = "Sin relaciones registrados";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function lista_vendedores() { // Lista de Vendedores**********************************
global $database_conexion, $conex;

$query = "SELECT a.nombre as nombre_usuario, a.apellido as apellido, a.id as id_usuario, b.nombre as nombre_empresa FROM usuarios a
INNER JOIN empresas b on a.id_empresa = b.id_empresa 
WHERE a.tipo_usuario = '3'
			";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_usuario']] = $row['nombre_usuario'].' '.$row['apellido'].' ('.$row['nombre_empresa'].')';

}
	
} 
else 
{
	$lista[0] = "Sin requisitores registrados";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function PUESTO_NOMBRE($id) { // obtiene nombre del puesto**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM puestos WHERE id_puesto = '$id'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$nombre = "";

if ($totalRows > 0){
$nombre = $row['puesto'];

}
return $nombre;
	
mysql_free_result($resultado);  
}
function lista_puestos() { // Lista de Puestos**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM puestos";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_puesto']] = $row['puesto'];

}
	
} 
else 
{
	$lista[0] = "Sin Puestos";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function lista_turnos() { // Lista de Turnos**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM turnos";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_turno']] = $row['turno'];

}
	
} 
else 
{
	$lista[0] = "Sin Turnos";
}	


return $lista;
	
mysql_free_result($resultado);  
}
function LISTA_D() { // **********************************
global $database_conexion, $conex;

$query = "SELECT * FROM departamentos";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$lista = array();

while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){

$lista[$row['id_departamento']] = $row['nombre'];

}

return $lista;
	
mysql_free_result($resultado);  
}

function folio_consecutivo($id_empresa,$tipo) { // Folio consecutivo **********************************
global $database_conexion, $conex;

//$query = "SELECT * FROM folios WHERE id_empresa = '$id_empresa'";
$query = "SELECT * FROM folios WHERE id_empresa = '$id_empresa' AND tipo_folio='$tipo'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$valor = 0;
$folio = 0;
 
if ($totalRows > 0){
	$folio = $row['folio'];
	$id_folio =  $row['id_folio'];
}
else 
{
	$folio = 1;
	$insert_folio = "INSERT INTO folios (folio,id_empresa,tipo_folio) VALUES ('$folio','$id_empresa','$tipo')";
				if (mysql_query($insert_folio, $conex) or die(mysql_error()))
				{
					//$id =  mysql_insert_id();
				}
}
	while($valor == 0){
		
		if ($tipo == "PED")  // veirifica existencia del folio en pedidos
		{
			$query_existencia_folio = "SELECT * FROM pedidos WHERE id_empresa = '$id_empresa' AND folio = '$folio'"; 
		}
		else if ($tipo == "INV") // verifica existencia de folio en inventarios
		{ 
			$query_existencia_folio = "SELECT * 
								FROM inventarios 
								WHERE id_empresa = '$id_empresa' 
								AND folio = '$folio'";
		}
		else if ($tipo == "PED_N") // verifica existencia de folio en pedido_nef
		{ 
			$query_existencia_folio = "SELECT * 
								FROM pedido_nef 
								WHERE folio = '$folio'";
		}
		else if ($tipo == "PED_T") // verifica existencia de folio en pedido_traspaso
		{ 
			$query_existencia_folio = "SELECT * 
								FROM pedido_traspaso
								WHERE folio = '$folio'";
		}
			$resultado_existencia = mysql_query($query_existencia_folio, $conex) or die(mysql_error());
			$total_rows = mysql_num_rows($resultado_existencia);
		if ($total_rows > 0){
			$folio++;
		}
		else
		{
			$valor = 1; // detiene el bucle
		}
	}	
	
		$update_folio = "UPDATE folios SET folio='$folio' WHERE id_folio='$id_folio'";
		if (mysql_query($update_folio, $conex) or die(mysql_error()))
				{
					
				}
	
return $folio;
	
mysql_free_result($resultado);  
}

/////////// funciones para autorizaciones de pedidos por limites de spend excedidos

function estatus_autorizaciones($id_pedido){
		global $database_conexion, $conex;
			
			$consulta = "SELECT * FROM requi_autorizacion WHERE id_pedido = '$id_pedido'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$cantidad_aprobadas = 0; 
		$cantidad_denegadas = 0; 
		$cantidad_total = 0; 
		$cantidad_pendientes = 0; 
		if ($total_rows > 0){
			
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$cantidad_total++;
				if ($row['estatus'] == 1){ //aprobadas
				$cantidad_pendientes++;	
				} else if ($row['estatus'] == 2){ //aprobadas
				$cantidad_aprobadas++;	
				} else if ($row['estatus'] == 3){ //denegadas
				$cantidad_denegadas++;
				}
			}
							
		}
		$valores = $cantidad_total.'-'.$cantidad_aprobadas.'-'.$cantidad_denegadas.'-'.$cantidad_pendientes;
		  return $valores;
		  
	  }
	  function guardar_pedido_autorizacion($id_pedido, $estatus){ 
global $database_conexion, $conex;
$id_usuario_activo = $_SESSION["logged_user"];
date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");
	
	$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
	$folio = folio_consecutivo($id_empresa_user_activo,"PED");
if ($estatus == 2){
	$estatus_p = '1';
	$con_autorizacion = '1';
	$update_pedido = "UPDATE pedidos SET folio='$folio', estatus='$estatus_p', con_autorizacion='$con_autorizacion', fecha_pedido_oficial='$fecha_actual' WHERE id='$id_pedido'";
	
}else if ($estatus == 3){
	$estatus_p = '0p';
	$update_pedido = "UPDATE pedidos SET  estatus='$estatus_p',  fecha_pedido_oficial='$fecha_actual' WHERE id='$id_pedido'";
		// se puede agregar la ultima fecha hora de modificacion
}
		if (mysql_query($update_pedido, $conex) or die(mysql_error()))
		{	
			echo '<script>
				$("#autorizacion_detalle").modal("hide");
				enviar_correo('.$id_pedido.','.$id_usuario_activo.','.$estatus.');
					</script>';
			
			if ($estatus_p == '1'){	
				$folio_consecutivo = $folio + 1;
				$update_folio = "UPDATE folios SET folio='$folio_consecutivo'  WHERE id_empresa='$id_empresa_user_activo' and tipo_folio='PED'";
				if (mysql_query($update_folio, $conex) or die(mysql_error())){}
				
					$delete_reg = "DELETE FROM requi_autorizacion WHERE id_pedido = '$id_pedido' ";
					if (mysql_query($delete_reg, $conex) or die(mysql_error())){}
			}else
			{ // si se denega completamente entonces 
				$update_histo = "UPDATE historial_autorizacion SET autorizacion_valida='0' WHERE id_pedido = '$id_pedido' and estatus = '3' ";
				if (mysql_query($update_histo, $conex) or die(mysql_error())){}
			}
			
			
			
		}
		else 
		{
			echo 0;
		}
}
	  function validar_estatus($id_pedido){
		  $estatus_autorizaciones = estatus_autorizaciones($id_pedido);
		  
							//echo $estatus_autorizaciones;
							$arr_cant_aut = explode('-',$estatus_autorizaciones);
							$cant_total = $arr_cant_aut[0];
							$cant_aprob = $arr_cant_aut[1];
							$cant_deneg = $arr_cant_aut[2];
							$cant_pend = $arr_cant_aut[3];
							$porcentaje_aprob = ($cant_aprob / $cant_total) * 100;
							$porcentaje_deneg = ($cant_deneg / $cant_total) * 100;
							
							$clase_td = 'class="btn-info"';	/// estatus default cuando no se a aprobado ni denegado nada
							if ($cant_pend == 0){ // si no hay requis pendientes entondes de todas tomaron desicion (son aprobadas y/o denegadas)
								if ($cant_deneg > 0){ //si existen requis denegadas 
									
								// se guarda el pedido con estatus 0p	
									guardar_pedido_autorizacion($id_pedido,3);	// guardar pedido denegado a pausados
									//echo '<script> enviar_correo('.$id_pedido.','.$id_usuario_activo.',3); </script>';
								}else if($cant_aprob == $cant_total){ // si todas las requis se aprovaron
								$clase_td = 'class="btn-success"';	
//////// si esta todo aprovado entonces se cambia el estatus del pedido a ordenado 1 automatico para que el pedido se encuentre en preparacions por almacen	
									guardar_pedido_autorizacion($id_pedido,2); // guarda pedido aprobado y pone estatus 1 ordenado
									
								}
									
							}
	  }
////  FUNCION PARA OBTENER LA CANTIDAD DE UNIDADES DE UN ARTICULO QUE ESTEN EN PEDIDO 	  
function unidades_pedidas($id,$id_empresa){ 
global $database_conexion, $conex;
$cantidad_pedido = 0;
$consulta = "SELECT SUM(pd.cantidad) as cantidad_pedidas 
					FROM pedidos_det pd
					INNER JOIN pedidos p ON p.id = pd.id_pedido 
					WHERE pd.id_articulo = $id 
					 AND p.estatus = '1'
					 AND p.id_empresa = '$id_empresa' OR
					 pd.id_articulo = $id 
					 AND p.estatus = '2'
					 AND p.id_empresa = '$id_empresa' ";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
		if ($total_rows > 0){
			if ($row['cantidad_pedidas'] != "")
				{
					$cantidad_pedido = $row['cantidad_pedidas'];
				}
		} 
		else /// sin resultados
		{
			$cantidad_pedido = 0;
		}
return $cantidad_pedido;
}
////  FUNCION PARA OBTENER LA CANTIDAD contada en inventario si es que se ha registrado conteo	  
function unidades_contadas($id,$id_inventario){ 
global $database_conexion, $conex;
$cantidad_contada = 0;
$consulta = "SELECT SUM(ivd.cantidad_contada) as cantidad_contada 
					FROM inventarios_det ivd
					WHERE ivd.id_inventario = '$id_inventario'
					AND ivd.id_articulo = '$id'
					 ";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
		if ($total_rows > 0){
			if ($row['cantidad_contada'] != "")
				{
					$cantidad_contada = $row['cantidad_contada'];
				}
				else
				{
					$cantidad_contada = "-";
				}		
		} 
		else /// sin resultados
		{
			$cantidad_contada = "-";
		}
return $cantidad_contada;
}	  
	
////  FUNCION PARA OBTENER LA SUMA DE TODOS LOS CONTEOS DE UN ARTICULO REGISTRADO EN EL ALMACEN	  
function suma_conteos($id_inventario_det){ 
global $database_conexion, $conex;
$cantidad_contada = 0;
$consulta = "SELECT SUM(conteos.cantidad) as cantidad_sumada 
					FROM inventarios_det_conteos conteos
					WHERE conteos.id_inventario_det = '$id_inventario_det' ";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
		if ($total_rows > 0){
			if ($row['cantidad_sumada'] != "")
				{
					$cantidad_contada = $row['cantidad_sumada'];
				}
				else
				{
					$cantidad_contada = 0;
				}		
		} 
		else /// sin resultados
		{
			$cantidad_contada = "-";
		}
return $cantidad_contada;
}	  
////  FUNCION PARA OBTENER LA SUMA DE todas las cantidades a cobro
function suma_diferencias($id_articulo,$almacen_id,$estatus){ 
global $database_conexion, $conex;
$cantidad_contada = 0;
$sql_estatus = "";
if ($estatus == 1){
	$sql_estatus = " AND inv.estatus='C'";
}
else if ($estatus == 2)
{
	$sql_estatus = " AND inv.estatus='A'";
}

$consulta = "SELECT SUM(invdet.diferencia) as cantidad_sumada 
				FROM inventarios_det invdet 
			INNER JOIN inventarios inv ON inv.id_inventario = invdet.id_inventario
			WHERE invdet.id_articulo = '$id_articulo' AND inv.almacen_id = '$almacen_id' AND inv.cancelado='N' ";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
		if ($total_rows > 0){
			if ($row['cantidad_sumada'] != "")
				{
					$cantidad_contada = $row['cantidad_sumada'];
				}
				else
				{
					$cantidad_contada = 0;
				}		
		} 
		else /// sin resultados
		{
			$cantidad_contada = 0;
		}
return $cantidad_contada;
}
/// FUNCION PARA OBTENER LA ULTIMA CANTIDAD CONTADA EN EL INVENTARIO
function ultimo_inv_art($id_articulo){ 
		global  $conex;
		
		$consulta = "SELECT indet.cantidad_contada as canti_conteo, inv.fecha_hora_cierre as fecha_inventario
		FROM inventarios_det indet
		LEFT JOIN inventarios inv ON inv.id_inventario = indet.id_inventario
		WHERE indet.id_articulo = '$id_articulo' AND inv.estatus <> 'A' AND inv.cancelado = 'N' 
		GROUP BY inv.fecha_hora_cierre DESC";	

		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		// $row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$ult_conteo = "-";
		$cuenta = 0;
		if ($total_rows > 0){
			while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
			{
				if ($cuenta == 0){
				$ult_conteo = $row['canti_conteo'].'_'.$row['fecha_inventario'];	
				$cuenta++;
				}
			}
		}
		return $ult_conteo;
	}	  
////  FUNCION PARA OBTENER LA SUMA DE todas las cantidades con remision, osea con salida de almacen en sistema microsip
function cantidades_cobradas($id_articulo,$almacen_id){ 
global $database_conexion, $conex;
$cantidad_contada = 0;
$consulta = "SELECT SUM(odet.cantidad) as cantidad_sumada 
				FROM ordenes_det odet
			INNER JOIN ordenes ord ON ord.id_oc = odet.id_oc
			WHERE odet.articulo_id = '$id_articulo' AND ord.almacen_id = '$almacen_id' AND ord.folio_remision <> '' ";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
		if ($total_rows > 0){
			if ($row['cantidad_sumada'] != "")
				{
					$cantidad_contada = $row['cantidad_sumada'];
				}
				else
				{
					$cantidad_contada = 0;
				}		
		} 
		else /// sin resultados
		{
			$cantidad_contada = 0;
		}
return $cantidad_contada;
}	  
		  
////  obtener id_empresa por medio del id_almacen
function id_empresa_almacen($almacen_id){ 
global  $conex;

$consulta = "SELECT alm.id_empresa as id_empresa 
				FROM almacenes alm 
				WHERE alm.almacen_id = '$almacen_id'
				";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
		if ($total_rows > 0){
			if ($row['id_empresa'] != "")
				{
					$id_empresa = $row['id_empresa'];
				}
				else
				{
					$id_empresa = 0;
				}		
		} 
		else /// sin resultados
		{
			$id_empresa = 0;
		}
return $id_empresa;
}	  
function cant_proces_tras($id_pedido_cliente){
		  global $database_conexion, $conex;
	
		$lista_arts_tras_pend = array(); // cantidad en proceso de traspaso
		
		$sql = "SELECT  pd.id_articulo as id_articulo, SUM(pd.cantidad) as cantidad, SUM(pd.surtido) as surtido 
		FROM pedido_traspaso pt
		INNER JOIN pedido_traspaso_det pd ON pd.id_pedido = pt.id_pedido
		WHERE pt.id_pedido_cliente = '$id_pedido_cliente' AND pt.estatus = '1'
		GROUP BY pd.id_articulo "; 
		$cons = mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($cons);
		$total_cons = mysql_num_rows($cons);

		if ($total_cons > 0){
			while($row = mysql_fetch_array($cons,MYSQL_BOTH)) 
            { 
				if($row['cantidad'] <> $row['surtido']){
				$lista_arts_tras_pend[$row['id_articulo']] = $row['cantidad'];
				}
			}
		}
		return $lista_arts_tras_pend;
			
	  }
	  function cant_proces_pednef($id_pedido_nef){
		  global $database_conexion, $conex;
	
		$lista_arts_pednef_pend = array(); // cantidad en proceso de traspaso
		
		$sql = "SELECT  pd.id_articulo as id_articulo, SUM(pd.cantidad) as cantidad, SUM(ptd.cantidad) as cant_traspaso, SUM(pd.surtido) as surtido
		FROM pedido_nef pn
		INNER JOIN pedido_nef_det pd ON pd.id_pedido = pn.id_pedido
		INNER JOIN ligas_doctos lig ON lig.id_pedido = pn.id_pedido
		LEFT JOIN pedido_traspaso_det ptd ON ptd.id_pedido = lig.id_pedido_traspaso AND ptd.id_articulo = pd.id_articulo
		WHERE pn.id_pedido_cliente = '$id_pedido_nef' AND lig.id_pedido_traspaso <> '' AND pn.estatus <> '0'
		GROUP BY pd.id_articulo"; 
		$cons = mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($cons);
		$total_cons = mysql_num_rows($cons);

		if ($total_cons > 0){
			while($row = mysql_fetch_array($cons,MYSQL_BOTH)) 
            { 
				
				$lista_arts_pednef_pend[$row['id_articulo']] = $row['cantidad'] - $row['cant_traspaso'];
				
			}
		}
		return $lista_arts_pednef_pend;
			
	  }
/// obtener folio de orden con el id_oc
function folio_orden($orden_id)
{
	global $conex;
	$folio = 0;
	$sql = "SELECT * FROM ordenes WHERE id_oc='$orden_id'";
	$res = mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	$total_rows = mysql_num_rows($res);
	if ($total_rows > 0)
	{
		$folio = $row['folio'];
	}
	return $folio;
}
function totales_orden($orden_id)
{
	global $conex;
	$total = 0;
	$sql = "SELECT SUM(precio_total) as total FROM ordenes_det WHERE id_oc='$orden_id'";
	$res = mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	$total_rows = mysql_num_rows($res);
	if ($total_rows > 0)
	{
		$total = $row['total'];
	}
	return $total;
}

/// lista de articulos de almacen correspondiente
function busca_art($almacen_id,$id_empresa){ 
global $database_conexion, $conex;

$consulta = "SELECT 
		a.id as id,
		a.id_empresa as id_empresa,
		a.clave_microsip as c_microsip,
		a.id_microsip as id_microsip,
		a.clave_empresa as c_empresa, 
		a.nombre as articulo, 
		a.descripcion as descip, 
		a.precio as precio, 
		a.unidad_medida as udm, 
		a.src_img as imagen, 
		exis.min as min,
		exis.max as max,
		exis.reorden as reorden,
		exis.existencia_actual as existencia
		  FROM articulos a 
		  LEFT JOIN existencias exis on exis.id_articulo = a.id 
		  WHERE a.id_empresa = '$id_empresa' and exis.almacen_id = '$almacen_id'";

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
$lista = array();
if ($total_rows > 0){ // con resultados
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
	$lista[$row['id']] = $row['c_empresa'].' - '.$row['articulo']; //'';					
}
} 
else /// sin resultados
{
	$lista['0'] = 'Sin resultados';
}
return $lista; 
}	  

function Posicion($orden_id)
{
	global $conex;
	$sql_pos = "SELECT * FROM ordenes_det 
			WHERE id_oc='$orden_id' ORDER BY posicion";
			$res_pos = mysql_query($sql_pos, $conex) or die(mysql_error());
			//$row_pos = mysql_fetch_assoc($res_pos);
			$total_respos = mysql_num_rows($res_pos);
			$posicion = 1;
			$cont_pos = 0;
			if ($total_respos > 0) // 
			{	
				
				while($row_pos = mysql_fetch_array($res_pos,MYSQL_BOTH)){
					$cont_pos++;
					$id_oc_det = $row_pos['id_oc_det'];
					if ($row_pos['posicion'] == $cont_pos)
					{ // si coincide el conteo con la posicion no hace nada esta bien
							
					}
					else if($row_pos['posicion'] > $cont_pos)
					{ // si el numero de posicion es mayor al conteo	
						//entonces actualiza la posicion remplazandola con el conteo
						$update_pos = "UPDATE ordenes_det SET posicion='$cont_pos'  WHERE id_oc_det='$id_oc_det'";
						if (mysql_query($update_pos, $conex) or die(mysql_error())){}
					}
				}
				
				$num_registros = $cont_pos;
				$posicion = $num_registros + 1;
			}
		return $posicion;	
			
}
function VerifTraspasoStatus($id_pedido){
	// obtiene lista de articulos surtidos del id_pedido proporcionado que se encuentren es estatus 3 marcado como recibido por almacen
		global $database_conexion, $conex;
		$sql = "SELECT SUM(ptd.cantidad) as cantidad, ptd.id_articulo as id_articulo, ptd.clave_microsip as clave_microsip, ptd.articulo as articulo 
					FROM pedido_traspaso pt
					LEFT JOIN pedido_traspaso_det ptd ON ptd.id_pedido = pt.id_pedido
					WHERE pt.id_pedido_cliente = '".$id_pedido."' AND pt.estatus = '3' 
					GROUP BY ptd.id_articulo ";
		$res = mysql_query($sql, $conex) or die(mysql_error());
	    $tot_rows = mysql_num_rows($res);
		$lista_de_surtidos =  array();
		$cont_partidas = 0;  
		$porcentaje_aprob = 0;  
		$porcentaje_aprob_general = 0;  
		if ($tot_rows > 0)
		{
			while($row = mysql_fetch_array($res,MYSQL_BOTH)) 
			{
				$lista_de_surtidos[] = array("id_articulo"=> $row['id_articulo'],
				"articulo"=> $row['articulo'],
				"clave_microsip"=> $row['clave_microsip'],
				"cantidad"=> $row['cantidad']);
			}
			
				$sql2 = "SELECT SUM(ptd.cantidad) as cantidad, ptd.id_articulo as id_articulo, ptd.clave_microsip as clave_microsip, ptd.articulo as articulo 
					FROM pedidos pt
					LEFT JOIN pedidos_det ptd ON ptd.id_pedido = pt.id
					WHERE pt.id = '".$id_pedido."' AND pt.estatus = '1' 
					GROUP BY ptd.id_articulo ";
				$res2 = mysql_query($sql2, $conex) or die(mysql_error());
				$tot_rows2 = mysql_num_rows($res2);
				$lista_de_surtidos2 =  array();
			
				if ($tot_rows2 > 0)
				{
					while($row2 = mysql_fetch_array($res2,MYSQL_BOTH)) 
					{
						$cont_partidas++;
						foreach($lista_de_surtidos as $art_surtidos){
							if($art_surtidos['id_articulo'] == $row2['id_articulo'])
							{
								$porcentaje_aprob += ($art_surtidos['cantidad'] / $row2['cantidad']);
							}
							
						}
						
					}
					
					$porcentaje_aprob_general = ($porcentaje_aprob / $cont_partidas) * 100;
				
				}
			return number_format($porcentaje_aprob_general, 0);
				
		}
		else
		{
			return 0;  
		}
		  
		  
		  // return $lista_de_surtidos;
		  
	}
function CantRecibir($id_articulo,$id_pedido_cliente){
	// obtiene la cantidad del articulo que fuen enviada en espera de ser recibido por el almaceninsta
	global $database_conexion, $conex;
	
	$sql = "SELECT SUM(ptd.cantidad) as cantidad 
	FROM pedido_traspaso pt
	INNER JOIN pedido_traspaso_det ptd ON ptd.id_pedido = pt.id_pedido
	WHERE pt.id_pedido_cliente = '$id_pedido_cliente' AND ptd.id_articulo = '$id_articulo' AND pt.estatus = '2'";
	$res = mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	$tot_rows = mysql_num_rows($res);
	$variable = 0;
	if($tot_rows > 0){
		$variable = $row['cantidad'];
	}else{
		$variable = 0;
	}
	
	return $variable;
}
function ValidarPedidoCliente($id_pedido){
	global $conex;
	$consulta = "SELECT pt.cantidad as cantidad, pt.surtido as surtido
				FROM pedidos_det pt 
				WHERE pt.id_pedido = $id_pedido";
	$res = mysql_query($consulta, $conex) or die(mysql_error());
	$total = mysql_num_rows($res);
	$completo = 0;
	$estatus = 0;
	$cant_total = 0;
	if ($total > 0){
		while($row = mysql_fetch_array($res,MYSQL_BOTH)) // html de articulos a mostrar
		{
			$cant_total += $row['cantidad'];
			$resta = $row['cantidad'] - $row['surtido'];
			$completo += $resta;
		}
		if ($completo == 0){  // pedido completado 4
			$estatus = 4;
			
		}else if($completo <> 0){  // pedido incompleto 3
			// verifica si existen mas traspasos por recibir
			$consulta2 = "SELECT *
				FROM pedido_traspaso 
				WHERE id_pedido_cliente = '$id_pedido' and estatus='2'";
			$res2 = mysql_query($consulta2, $conex) or die(mysql_error());
			$total2 = mysql_num_rows($res2);
			if ($total2 > 0){
				$estatus = 2;
			}else{
				if ($cant_total == $completo){
				$estatus = 1;
				}else{
				$estatus = 3;
				}
			}
			
			
			
		}
		$up_estatus = "UPDATE pedidos SET estatus='$estatus' WHERE id='$id_pedido'";
			if (mysql_query($up_estatus, $conex) or die(mysql_error())){
			/* 	echo '<script>console.log("canttotal: "+'.$cant_total.'); 
				console.log("resta: "+'.$completo.'); </script>'; */
			}
	}
	
}
////////////////////////////******* funciones a base de datos firebird   ALLPART    ************************
///////// FUNCION PARA OBTENER UNIDAD DE CLAVE
function Articulo_Id($clave_articulo){
	global $con_micro;		
	$sql_clave = "SELECT 
	A.ARTICULO_ID AS ARTICULO_ID	
	FROM  CLAVES_ARTICULOS A 	
	WHERE (A.CLAVE_ARTICULO = '$clave_articulo')";
$consulta = $con_micro->prepare($sql_clave);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
if (!$consulta){
 //echo "sin resultados";
	 exit;}	
$CLAVE = "";
while($row=$consulta->fetch())	
{
	$CLAVE = $row->ARTICULO_ID;
}
return $CLAVE;
}

function ExistenciaMicrosip($id,$almacen_id){
global $con_micro;	
if ($id != ""){
	$sql_existencia = "SELECT 
	DOCS_INDET.TIPO_MOVTO AS TIPO,
	DOCS_INDET.UNIDADES	AS CANT	
FROM DOCTOS_IN_DET DOCS_INDET
		
WHERE (DOCS_INDET.ARTICULO_ID = '$id')
AND (DOCS_INDET.ALMACEN_ID = '$almacen_id')
AND (DOCS_INDET.CANCELADO = 'N')";


// ALMACEN STARKEY 390226
$consulta_existencia = $con_micro->prepare($sql_existencia);
$consulta_existencia->execute();
$consulta_existencia->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta_existencia){
	 
	 //echo "sin resultados";
	 exit;}	
$entradas = 0;
$salidas = 0;	
$registros = 0; 
while($row_exis=$consulta_existencia->fetch())	
{
	
if ($row_exis->TIPO == 'E')
{
	$entradas = $entradas + $row_exis->CANT;
}
else if($row_exis->TIPO == 'S')
{
	$salidas = $salidas + $row_exis->CANT;
}
$registros++;	
}

$res_oper = $entradas - $salidas;
$existencia = number_format($res_oper,2);
$existencia = str_replace(",","",$existencia);
//return $entradas.' - '.$salidas.' = '.$existencia;
}else{
	$existencia = 0;
}	
	
return $existencia;
}
function MinMaxReorden($id,$almacen_id){
	global $con_micro;		
	$sql_existencia = "SELECT 
		NIVA.INVENTARIO_MAXIMO AS MAXIMO,
		NIVA.INVENTARIO_MINIMO AS MINIMO,
		NIVA.PUNTO_REORDEN AS REORDEN	
	FROM NIVELES_ARTICULOS NIVA
		
	WHERE (NIVA.ARTICULO_ID = '$id')
	AND (NIVA.ALMACEN_ID = '$almacen_id')";

// ALMACEN STARKEY 390226
$consulta = $con_micro->prepare($sql_existencia);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){
	 
	 //echo "sin resultados";
	 exit;}	
$valor = 0;	 
$min = 0;
$max = 0;
$reorden = 0;
$registros = 0; 
while($row_exis=$consulta->fetch())	
{
	if ($row_exis->MAXIMO > 0){
		$max = $row_exis->MAXIMO;
	}
	if ($row_exis->MINIMO > 0){
		$min = $row_exis->MINIMO;
	}
	if ($row_exis->REORDEN > 0){
		$reorden = $row_exis->REORDEN;
	}
	
$registros++;	
}
$valor = number_format($max,0)."_".number_format($min,0)."_".number_format($reorden,0);

return $valor;
}
///////// FUNCION PARA OBTENER PRECIO DE MICROSIP
function PrecioArticulo($id){
	global $con_micro;		
	$sql_precio = "SELECT 
	PRE_ART.PRECIO AS PRECIO, PRE_ART.MONEDA_ID AS MONEDA_ID
	FROM PRECIOS_ARTICULOS PRE_ART
	WHERE (PRE_ART.ARTICULO_ID = '$id')
	AND (PRE_ART.PRECIO_EMPRESA_ID = '42')";
  // Dolares = 41560
$consulta = $con_micro->prepare($sql_precio);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){
 //echo "sin resultados";
	 exit;}	
	 $sql_tipocambio = "SELECT H.TIPO_CAMBIO
  FROM HISTORIA_CAMBIARIA H
 WHERE (H.MONEDA_ID = '2770')
 ORDER BY H.FECHA ASC";
  // Dolares = 41560
$consulta_cambio = $con_micro->prepare($sql_tipocambio);
$consulta_cambio->execute();
$consulta_cambio->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta_cambio){
 //echo "sin resultados";
	 exit;}	
	$tipocambio = 0; 
while($row_cambio=$consulta_cambio->fetch())	
{
	$tipocambio = $row_cambio->TIPO_CAMBIO;
}
	 
	 
$precio = 0;	 

while($row_exis=$consulta->fetch())	
{
	
if ($row_exis->MONEDA_ID == '2770'){
	
	$precio = $tipocambio * $row_exis->PRECIO;
}else {
	$precio = $row_exis->PRECIO;
}
	
}

return number_format($precio,2);
}
///////// FUNCION PARA OBTENER UNIDAD DE MEDIDA
function UDMArticulo($id){
	global $con_micro;		
	$sql_udm = "SELECT 
		A.UNIDAD_VENTA AS UDM	
	FROM  ARTICULOS A 	
	WHERE (A.ARTICULO_ID = '$id')";

$consulta = $con_micro->prepare($sql_udm);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){
 //echo "sin resultados";
	 exit;}	
$UDM = "";	 

while($row_exis=$consulta->fetch())	
{
	$UDM = $row_exis->UDM;
}

return $UDM;
}
///////// FUNCION PARA OBTENER UNIDAD DE CLAVE
function ClaveArticulo($id){
	global $con_micro;		
	$sql_clave = "SELECT 
		A.CLAVE_ARTICULO AS CLAVE	
	FROM  CLAVES_ARTICULOS A 	
	WHERE (A.ARTICULO_ID = '$id') 
	AND (A.ROL_CLAVE_ART_ID = '17')";
$consulta = $con_micro->prepare($sql_clave);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
if (!$consulta){
 //echo "sin resultados";
	 exit;}	
$CLAVE = "";
while($row_exis=$consulta->fetch())	
{
	$CLAVE = $row_exis->CLAVE;
}
return $CLAVE;
}

///////// FUNCION PARA OBTENER NOMBRE DE ARTICULO MICROSIP
function NombreArticulo($id){
	global $con_micro;		
	$sql_nombre = "SELECT 
		A.NOMBRE AS NOMBRE	
	FROM  ARTICULOS A 	
	WHERE (A.ARTICULO_ID = '$id')";
$consulta = $con_micro->prepare($sql_nombre);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
if (!$consulta){
 //echo "sin resultados";
	 exit;}	
$NOMBRE = "";
while($row_exis=$consulta->fetch())	
{
	$NOMBRE = $row_exis->NOMBRE;
}
return $NOMBRE;
}


function Format9digit($folio){
switch(strlen($folio)){
		
			case 1: 
			$folio_siguiente = "00000000".$folio;
			break;
			case 2:
			$folio_siguiente = "0000000".$folio;
			break;
			case 3:
			$folio_siguiente = "000000".$folio;
			break;
			case 4:
			$folio_siguiente = "00000".$folio;
			break;
			case 5:
			$folio_siguiente = "0000".$folio;
			break;
			case 6:
			$folio_siguiente = "000".$folio;
			break;
			case 7:
			$folio_siguiente = "00".$folio;
			break;
			case 8:
			$folio_siguiente = "0".$folio;
			break;
			case 9:
			$folio_siguiente = $folio;
			break;
		 
	 }
return $folio_siguiente; 
}
function ObtenerFolioTraspaso(){ 
global $con_micro;
$folio_siguiente = "";	
$valor = 36; // ID DE CONCEPTO TRASPASO SALIDA
$sql = "SELECT DI.CONSECUTIVO AS CONSECUTIVO	
FROM FOLIOS_CONCEPTOS DI
WHERE (DI.CONCEPTO_ID = '".$valor."')";

$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 	 exit;}	
$folio_siguiente = $row_result['CONSECUTIVO'];
return $folio_siguiente;
}

function ObtenerFolioAjusteEntrada(){ 
global $con_micro;
$folio_siguiente = "";	
$valor = 27; // ID DE CONCEPTO TRASPASO SALIDA
$sql = "SELECT DI.CONSECUTIVO AS CONSECUTIVO	
FROM FOLIOS_CONCEPTOS DI
WHERE (DI.CONCEPTO_ID = '".$valor."')";

$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 	 exit;}	
$folio_siguiente = $row_result['CONSECUTIVO'];
return $folio_siguiente;
}
function ObtenerFolio(){ 
global $con_micro;
$folio = "";	
$valor = 43312; // folio de remisiones
$sql = "SELECT A.CONSECUTIVO 	
FROM FOLIOS_VENTAS A
WHERE (A.FOLIO_VENTAS_ID = '".$valor."')";

$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 	 exit;}	
$folio_siguiente = $row_result['CONSECUTIVO'];
return $folio_siguiente;
}

function ObtenerIdTraspaso($folio){ 
global $con_micro;
$id_concepto = 36; // concepto de traspaso salida	
$sql = "SELECT A.DOCTO_IN_ID AS DOCTO_IN_ID	
FROM DOCTOS_IN A
WHERE (A.FOLIO = '".$folio."' AND A.CONCEPTO_IN_ID = '".$id_concepto."')";
$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 exit;}	
$docto_in_id = $row_result['DOCTO_IN_ID'];
return $docto_in_id;
}


function ObtenerIdEntrada($folio){ 
global $con_micro;
$id_concepto = 27; // concepto de traspaso salida	
$sql = "SELECT A.DOCTO_IN_ID AS DOCTO_IN_ID	
FROM DOCTOS_IN A
WHERE (A.FOLIO = '".$folio."' AND A.CONCEPTO_IN_ID = '".$id_concepto."')";
$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 exit;}	
$docto_in_id = $row_result['DOCTO_IN_ID'];
return $docto_in_id;
}


function ObtenerIdTraspasoDet($articulo_id,$docto_in_id,$concepto_in_id){ 
// edgar del futuro cuando vayas a modificar la lista de los articulos requeridos limita a que no puedan agregar 2 o mas veces la misma clave(articulo)

global $con_micro;
//$id_concepto = 36; // concepto de traspaso salida	
//$id_concepto = 25; // concepto de traspaso entrada	
$sql = "SELECT A.DOCTO_IN_DET_ID AS DOCTO_IN_DET_ID	
FROM DOCTOS_IN_DET A
WHERE (A.ARTICULO_ID = '".$articulo_id."' AND A.DOCTO_IN_ID = '".$docto_in_id."' AND A.CONCEPTO_IN_ID = '".$concepto_in_id."')";
$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 exit;}	
$docto_in_det_id = $row_result['DOCTO_IN_DET_ID'];
return $docto_in_det_id;
}
function ObtenerId($folio){ 
global $con_micro;
$tipo_docto = "R";	
$sql = "SELECT A.DOCTO_VE_ID AS DOCTO_VE_ID	
FROM DOCTOS_VE A
WHERE (A.FOLIO = '".$folio."' AND A.TIPO_DOCTO = '".$tipo_docto."')";
$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 exit;}	
$docto_ve_id = $row_result['DOCTO_VE_ID'];
return $docto_ve_id;
}
function Sucursal_Allpart(){ 
global $con_micro;

$sql = "SELECT SUC.SUCURSAL_ID AS SUCURSAL_ID	
FROM SUCURSALES SUC";
$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 exit;}	
$SUCURSAL_ID = $row_result['SUCURSAL_ID'];
return $SUCURSAL_ID;
}
function Sucursal_Nef(){ 
global $con_micro_nef;

$sql = "SELECT SUC.SUCURSAL_ID AS SUCURSAL_ID	
FROM SUCURSALES SUC";
$consulta = $con_micro_nef->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 exit;}	
$SUCURSAL_ID = $row_result['SUCURSAL_ID'];
return $SUCURSAL_ID;
}
///////// FUNCION PARA OBTENER Lista da almacenes miscrosip
function lista_almacenes_microsip(){
	global $con_micro;		
	$sql = "SELECT * FROM ALMACENES";

$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){
 //echo "sin resultados";
	 exit;}	
 
$lista = array();
while($row_exis=$consulta->fetch())	
{
	
$lista[$row_exis->ALMACEN_ID] = $row_exis->NOMBRE;
}
return $lista;
}

/////// FUNCION PARA OBTENER LISTA DE ARTICULOS CON MOVIMIENTOS EN ALMACEN GENERAL EMPRESA ALLPART
function lista_art_micro($almacen_id,$id_empresa){ 
global $database_conexion, $conex, $con_micro;


$consulta = "SELECT 
		ARTI.ARTICULO_ID AS ARTID,
		ARTI.NOMBRE AS ARTICULO,
		ARTI.UNIDAD_VENTA AS UDM,
		PRECIOS_A.PRECIO AS PRECIO,
		NIVA.INVENTARIO_MAXIMO AS MAXIMO,
		NIVA.INVENTARIO_MINIMO AS MINIMO,
		NIVA.PUNTO_REORDEN AS REORDEN,
		CLAVES.CLAVE_ARTICULO AS CLAVE
FROM ARTICULOS ARTI
	FULL OUTER JOIN CLAVES_ARTICULOS CLAVES ON CLAVES.ARTICULO_ID = ARTI.ARTICULO_ID
	LEFT JOIN PRECIOS_ARTICULOS PRECIOS_A ON PRECIOS_A.ARTICULO_ID = ARTI.ARTICULO_ID AND PRECIOS_A.PRECIO_EMPRESA_ID = '42'
	LEFT JOIN NIVELES_ARTICULOS NIVA ON NIVA.ARTICULO_ID = ARTI.ARTICULO_ID AND NIVA.ALMACEN_ID = '$almacen_id'
	
WHERE (CLAVES.ROL_CLAVE_ART_ID = '17')
AND (ARTI.ESTATUS = 'A')
AND (ARTI.ES_ALMACENABLE = 'S')
AND (ARTI.ARTICULO_ID IN(SELECT DOCS_INDET.ARTICULO_ID FROM DOCTOS_IN_DET DOCS_INDET WHERE (DOCS_INDET.ALMACEN_ID = '$almacen_id')))";
 //  
//AND (ARTI.NOMBRE LIKE '%$clave_nombre%')
 

$res = $con_micro->prepare($consulta);
$res->execute();
$res->setFetchMode(PDO::FETCH_OBJ);
if (!$res){
	 echo "<div style='color:#FF0000'>fallo en consulta!</div>";
	 exit;}	 
$count = 0;

$lista = array();
while ($row=$res->fetch()){
			$count++;
			//$precio = number_format($row->PRECIO,2);
			//$existencia = ExistenciaMicrosip($row->ARTID,$almacen_id);
			
	$lista[$row->ARTID] = utf8_decode($row->CLAVE).' - '.utf8_decode($row->ARTICULO); 					
}

if ($count > 0){
	// si el count es mayor a 0 entonces se agregaron componentes al array lista osea se econtraron resultados
	
}
else
{
	$lista['0'] = 'Sin resultados';
} 
return $lista; 
}

////////////////////////////******* funciones a base de datos firebird   NEF    ************************

function ExistenciaMicrosipNef($id,$almacen_id){
global $con_micro_nef;		
	$sql_existencia = "SELECT 
	DOCS_INDET.TIPO_MOVTO AS TIPO,
	DOCS_INDET.UNIDADES	AS CANT	
FROM DOCTOS_IN_DET DOCS_INDET
		
WHERE (DOCS_INDET.ARTICULO_ID = '$id')
AND (DOCS_INDET.ALMACEN_ID = '$almacen_id')
AND (DOCS_INDET.CANCELADO = 'N')";


// ALMACEN STARKEY 390226
$consulta_existencia = $con_micro_nef->prepare($sql_existencia);
$consulta_existencia->execute();
$consulta_existencia->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta_existencia){
	 
	 //echo "sin resultados";
	 exit;}	
$entradas = 0;
$salidas = 0;	
$registros = 0; 
while($row_exis=$consulta_existencia->fetch())	
{
	
if ($row_exis->TIPO == 'E')
{
	$entradas = $entradas + $row_exis->CANT;
}
else if($row_exis->TIPO == 'S')
{
	$salidas = $salidas + $row_exis->CANT;
}
$registros++;	
}

$res_oper = $entradas - $salidas;
$existencia = number_format($res_oper,2);
$existencia = str_replace(",","",$existencia);
//return $entradas.' - '.$salidas.' = '.$existencia;
return $existencia;
}
function MinMaxReordenNef($id){
	global $con_micro_nef;		
	$sql_existencia = "SELECT 
		NIVA.INVENTARIO_MAXIMO AS MAXIMO,
		NIVA.INVENTARIO_MINIMO AS MINIMO,
		NIVA.PUNTO_REORDEN AS REORDEN	
	FROM NIVELES_ARTICULOS NIVA
		
	WHERE (NIVA.ARTICULO_ID = '$id')
	AND (NIVA.ALMACEN_ID = '390226')";

// ALMACEN STARKEY 390226
$consulta = $con_micro_nef->prepare($sql_existencia);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){
	 
	 //echo "sin resultados";
	 exit;}	
$valor = 0;	 
$min = 0;
$max = 0;
$reorden = 0;
$registros = 0; 
while($row_exis=$consulta->fetch())	
{
	if ($row_exis->MAXIMO > 0){
		$max = $row_exis->MAXIMO;
	}
	if ($row_exis->MINIMO > 0){
		$min = $row_exis->MINIMO;
	}
	if ($row_exis->REORDEN > 0){
		$reorden = $row_exis->REORDEN;
	}
	
$registros++;	
}
$valor = number_format($max,0)."_".number_format($min,0)."_".number_format($reorden,0);

return $valor;
}
///////// FUNCION PARA OBTENER PRECIO DE MICROSIP
function PrecioArticuloNef($id){
	global $con_micro_nef;		
	$sql_precio = "SELECT 
		PRE_ART.PRECIO AS PRECIO, PRE_ART.MONEDA_ID AS MONEDA_ID
	FROM PRECIOS_ARTICULOS PRE_ART
	WHERE (PRE_ART.ARTICULO_ID = '$id')
	AND (PRE_ART.PRECIO_EMPRESA_ID = '42')";
  // Dolares = 41560
$consulta = $con_micro_nef->prepare($sql_precio);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){
 //echo "sin resultados";
	 exit;}	
	 $sql_tipocambio = "SELECT TIPO_CAMBIO AS TIPO_CAMBIO
  FROM HISTORIA_CAMBIARIA
 WHERE (MONEDA_ID = '41560') AND (HISTORIA_CAMB_ID = (SELECT MAX(HISTORIA_CAMB_ID) FROM HISTORIA_CAMBIARIA))";
  // Dolares = 41560
$consulta_cambio = $con_micro_nef->prepare($sql_tipocambio);
$consulta_cambio->execute();
$consulta_cambio->setFetchMode(PDO::FETCH_OBJ);
$row_cambio = $consulta_cambio->fetch(PDO::FETCH_ASSOC);

if (!$consulta_cambio){
 //echo "sin resultados";
	 exit;}	
	$tipocambio = 0; 

$tipocambio = $row_cambio["TIPO_CAMBIO"];

$precio = 0;	 

while($row_exis=$consulta->fetch())	
{
	
if ($row_exis->MONEDA_ID == '41560'){
	
	$precio = $tipocambio * $row_exis->PRECIO;
}else {
	$precio = $row_exis->PRECIO;
}
	
}

return number_format($precio,2);
}
///////// FUNCION PARA OBTENER UNIDAD DE MEDIDA
function UDMArticuloNef($id){
	global $con_micro_nef;		
	$sql_udm = "SELECT 
		A.UNIDAD_VENTA AS UDM	
	FROM  ARTICULOS A 	
	WHERE (A.ARTICULO_ID = '$id')";

$consulta = $con_micro_nef->prepare($sql_udm);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){
 //echo "sin resultados";
	 exit;}	
$UDM = "";	 

while($row_exis=$consulta->fetch())	
{
	$UDM = $row_exis->UDM;
}

return $UDM;
}
///////// FUNCION PARA OBTENER UNIDAD DE CLAVE
function ClaveArticuloNef($id){
	global $con_micro_nef;		
	$sql_clave = "SELECT 
		A.CLAVE_ARTICULO AS CLAVE	
	FROM  CLAVES_ARTICULOS A 	
	WHERE (A.ARTICULO_ID = '$id') 
	AND (A.ROL_CLAVE_ART_ID = '17')";
$consulta = $con_micro_nef->prepare($sql_clave);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
if (!$consulta){
 //echo "sin resultados";
	 exit;}	
$CLAVE = "";
while($row_exis=$consulta->fetch())	
{
	$CLAVE = $row_exis->CLAVE;
}
return $CLAVE;
}
///////// FUNCION PARA OBTENER NOMBRE DE ARTICULO MICROSIP
function NombreArticuloNef($id){
	global $con_micro_nef;		
	$sql_nombre = "SELECT 
		A.NOMBRE AS NOMBRE	
	FROM  ARTICULOS A 	
	WHERE (A.ARTICULO_ID = '$id')";
$consulta = $con_micro_nef->prepare($sql_nombre);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
if (!$consulta){
 //echo "sin resultados";
	 exit;}	
$NOMBRE = "";
while($row_exis=$consulta->fetch())	
{
	$NOMBRE = $row_exis->NOMBRE;
}
return $NOMBRE;
}



function ObtenerFolioNef(){ 
global $con_micro_nef;
$folio = "";	
$valor = 43312; // folio de remisiones
$sql = "SELECT A.CONSECUTIVO 	
FROM FOLIOS_VENTAS A
WHERE (A.FOLIO_VENTAS_ID = '".$valor."')";

$consulta = $con_micro_nef->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 	 exit;}	
$folio_siguiente = $row_result['CONSECUTIVO'];
return $folio_siguiente;
}

function ObtenerIdNef($folio){ 
global $con_micro_nef;
$tipo_docto = "R";	
$sql = "SELECT A.DOCTO_VE_ID AS DOCTO_VE_ID	
FROM DOCTOS_VE A
WHERE (A.FOLIO = '".$folio."' AND A.TIPO_DOCTO = '".$tipo_docto."')";
$consulta = $con_micro_nef->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 exit;}	
$docto_ve_id = $row_result['DOCTO_VE_ID'];
return $docto_ve_id;
}
///////// FUNCION PARA OBTENER Lista da almacenes miscrosip
function lista_almacenes_microsipNef(){
	global $con_micro_nef;		
	$sql = "SELECT * FROM ALMACENES";

$consulta = $con_micro_nef->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){
 //echo "sin resultados";
	 exit;}	
 
$lista = array();
while($row_exis=$consulta->fetch())	
{
	
$lista[$row_exis->ALMACEN_ID] = $row_exis->NOMBRE;
}
return $lista;
}
/////// FUNCION PARA OBTENER LISTA DE ARTICULOS CON MOVIMIENTOS EN ALMACEN GENERAL EMPRESA NEF
function lista_art_micro_NEF($almacen_id,$id_empresa){ 
global $con_micro_nef;


$consulta = "SELECT 
		ARTI.ARTICULO_ID AS ARTID,
		ARTI.NOMBRE AS ARTICULO,
		ARTI.UNIDAD_VENTA AS UDM,
		PRECIOS_A.PRECIO AS PRECIO,
		NIVA.INVENTARIO_MAXIMO AS MAXIMO,
		NIVA.INVENTARIO_MINIMO AS MINIMO,
		NIVA.PUNTO_REORDEN AS REORDEN,
		CLAVES.CLAVE_ARTICULO AS CLAVE
FROM ARTICULOS ARTI
	FULL OUTER JOIN CLAVES_ARTICULOS CLAVES ON CLAVES.ARTICULO_ID = ARTI.ARTICULO_ID
	LEFT JOIN PRECIOS_ARTICULOS PRECIOS_A ON PRECIOS_A.ARTICULO_ID = ARTI.ARTICULO_ID AND PRECIOS_A.PRECIO_EMPRESA_ID = '42'
	LEFT JOIN NIVELES_ARTICULOS NIVA ON NIVA.ARTICULO_ID = ARTI.ARTICULO_ID AND NIVA.ALMACEN_ID = '$almacen_id'
	
WHERE (CLAVES.ROL_CLAVE_ART_ID = '17')
AND (ARTI.ESTATUS = 'A')
AND (ARTI.ES_ALMACENABLE = 'S')
AND (ARTI.ARTICULO_ID IN(SELECT DOCS_INDET.ARTICULO_ID FROM DOCTOS_IN_DET DOCS_INDET WHERE (DOCS_INDET.ALMACEN_ID = '$almacen_id')))";
 //  
//AND (ARTI.NOMBRE LIKE '%$clave_nombre%')
 

$res = $con_micro_nef->prepare($consulta);
$res->execute();
$res->setFetchMode(PDO::FETCH_OBJ);
if (!$res){
	 echo "<div style='color:#FF0000'>fallo en consulta!</div>";
	 exit;}	 
$count = 0;
$articulo = '';
$lista = array();
while ($row=$res->fetch()){
			$count++;
			//$precio = number_format($row->PRECIO,2);
			//$existencia = ExistenciaMicrosip($row->ARTID,$almacen_id);
			
	$articulo = $row->ARTICULO;		
	//$articulo = str_replace('"','\"',$row->ARTICULO);		
	//$articulo = str_replace("'","\'",$articulo);		
	$lista[$row->ARTID] = utf8_decode($row->CLAVE).' - '.utf8_encode($articulo); 					
}

if ($count > 0){
	// si el count es mayor a 0 entonces se agregaron componentes al array lista osea se econtraron resultados
	
}
else
{
	$lista['0'] = 'Sin resultados';
} 
return $lista; 
}

//funciones para traer datos de la base de consigna 
function lista_articulos_consigna()//todos los articulos de consigna 
{
	
	global $database_conexion, $conex;
			
		$consulta = "SELECT id, nombre, clave_microsip FROM articulos";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$lista = array();	
		$articulo="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$articulo = $row['nombre'];	
				$lista[$row['id']] = utf8_decode($row['clave_microsip']).' - '.utf8_encode($articulo);
			}
			  return $lista;
	
}

function UDMArticulo_dura($id)
{
		global $database_conexion, $conex;
			
		$consulta = "SELECT unidad_medida FROM articulos WHERE id = '$id'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$unidad_medida="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$unidad_medida = $row['unidad_medida'];	
				
			}
			
			  return $unidad_medida;
	
}

function id_microsip_allpart($id)
{
		global $database_conexion, $conex;
			
		$consulta = "SELECT id_microsip FROM articulos WHERE id = '$id'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$id_microsip="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$id_microsip = $row['id_microsip'];	
				
			}
			
			  return $id_microsip;
	
}

function clave_consigna($id)
{
		global $database_conexion, $conex;
			
		$consulta = "SELECT clave_empresa FROM articulos WHERE id = '$id'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$clave_empresa="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$clave_empresa = $row['clave_empresa'];	
				
			}
			
			  return $clave_empresa;
	
}

function descripcion_empresa($id)
{
		global $database_conexion, $conex;
			
		$consulta = "SELECT descripcion FROM articulos WHERE id = '$id'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$descripcion="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$descripcion = $row['descripcion'];	
				
			}
			
			  return $descripcion;
	
}
function PrecioArticulo_dura($id)
{
		global $database_conexion, $conex;
			
		$consulta = "SELECT precio FROM articulos WHERE id = '$id'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$precio="";
			while($row = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
            {
				$precio = $row['precio'];	
				
			}
			
			  return $precio;
	
}

?>




	
	