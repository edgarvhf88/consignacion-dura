
<?php include("conexion.php");

      $puesto = '';
	  $departamento = '';
	  $id_empresa = '';
	  $empresa = '';
	  $id ='';
	  $username = '';
	  $list_subordinados_seleccionados='';
	  $list_recolectores_seleccionados='';
	  $list_cc_seleccionados='';
	  $aut_spend_limit = 0;

	  
	  
	   if (isset($_POST['id_usuario'])){
		$id = $_POST['id_usuario'];
		$tipo = $_POST['tipo'];
		$username= $_POST['username'];
		$nombre= $_POST['nombre'];
		$apellido= $_POST['apellido'];
		$contrasena= $_POST['contrasena'];
		$empresa_id= $_POST['empresa_id'];
		$list_sucursales_permitidas = $_POST['list_sucursales_permitidas'];
		$list_subordinados_seleccionados = $_POST['list_subordinados_seleccionados'];
		$list_recolectores_seleccionados = $_POST['list_recolectores_seleccionados'];
		$list_cc_seleccionados = $_POST['list_cc_seleccionados'];
		$correo= $_POST['correo'];
		$telefono= $_POST['telefono'];
		$departamento_id= $_POST['departamento_id'];
		$puesto_id= $_POST['puesto_id'];
		$turno= $_POST['turno'];
		if ($_POST['permiso_autorizar'] == true){
			$aut_spend_limit = 1;
		}else if ($_POST['permiso_autorizar'] == false){
			$aut_spend_limit = 0;
		}
		
      	actualizar($tipo,$username,$nombre,$apellido,$contrasena,$empresa_id,$list_sucursales_permitidas,$list_subordinados_seleccionados,$list_recolectores_seleccionados,$list_cc_seleccionados,$correo,$telefono,$departamento_id,$puesto_id,$turno,$id,$aut_spend_limit);
		
      }
	  if ((isset($_POST['username'])) && (!isset($_POST['id_usuario']))){
     $tipo = $_POST['tipo'];
		$username= $_POST['username'];
		$nombre= $_POST['nombre'];
		$apellido= $_POST['apellido'];
		$contrasena= $_POST['contrasena'];
		$empresa_id= $_POST['empresa_id'];
		$list_sucursales_permitidas = $_POST['list_sucursales_permitidas'];
		$list_subordinados_seleccionados = $_POST['list_subordinados_seleccionados'];
		$list_recolectores_seleccionados = $_POST['list_recolectores_seleccionados'];
		$list_cc_seleccionados = $_POST['list_cc_seleccionados'];
		$correo= $_POST['correo'];
		$telefono= $_POST['telefono'];
		$departamento_id= $_POST['departamento_id'];
		$puesto_id= $_POST['puesto_id'];
		$turno= $_POST['turno'];
		if ($_POST['permiso_autorizar'] == true){
			$aut_spend_limit = 1;
		}else if ($_POST['permiso_autorizar'] == false){
			$aut_spend_limit = 0;
		}
      	
      }
	  if (($username != '') && ($id == '')){
	  		guardar_usuario($tipo,$username,$nombre,$apellido,$contrasena,$empresa_id, $list_sucursales_permitidas, $list_subordinados_seleccionados,$list_recolectores_seleccionados,$list_cc_seleccionados,$correo,$telefono,$departamento_id,$puesto_id,$turno,$aut_spend_limit);
	  }
	  
	  if (isset($_POST['puesto'])){
      $puesto = $_POST['puesto'];
      }
	  if ($puesto != ''){
	  
			guardar_puesto($puesto);
	  }
	  
	  if (isset($_POST['departamento'])){
      $departamento = $_POST['departamento'];
	  $id_empresa = $_POST['id_empresa'];
      }
	  if ($departamento != ''){
	  
			guardar_departamento($departamento,$id_empresa);
	  }
	  
	  if ((isset($_POST['empresa'])) && (!isset($_POST['id_empresa']))){
      $empresa = $_POST['empresa'];
      $rfc = $_POST['rfc'];
      }
	  if ($empresa != ''){
	  
			guardar_empresa($empresa,$rfc);
	  }
	  if (isset($_POST['id_empresa'])){
	  $id_empresa = $_POST['id_empresa'];
      $empresa = $_POST['empresa'];
      $rfc = $_POST['rfc'];
      
	  if ($id_empresa != ''){
	  
			modificar_empresa($id_empresa,$empresa,$rfc);
	  }
	  }
	  
	  
	  
     function guardar_empresa($empresa,$rfc){ // Funcion para guardar empresa nueva
global $database_conexion, $conex;


$consulta_puesto = "SELECT * FROM empresas WHERE nombre = '$empresa'";
$resultado = mysql_query($consulta_puesto, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
if ($total_rows == 0){

$query_puesto = "INSERT INTO empresas (nombre,rfc) VALUES ('$empresa','$rfc')";
$registra_puesto = mysql_query($query_puesto, $conex) or die(mysql_error());
$id_empresa =  mysql_insert_id();

$carpeta = '../assets/images/productos/emp-'.$id_empresa;
		if (!file_exists($carpeta)) {
			mkdir($carpeta, 0777, true);
		}

echo '<script>
$("#txt_empresa_new").val("");
$("#txt_empresa_rfc_new").val("");
$("#div_new_empresa").hide();
$("#select_empresa").show();
$("#btn_new_empresa").show();

$("#select_empresa").append("<option value="+'.$id_empresa.'+" selected>'.$empresa.'</option>");

</script>';

} else if ($total_rows > 0){
echo '<script>
alert("La empresa que intenta agregar ya existe");
</script>';
}

}

function modificar_empresa($id_empresa,$empresa,$rfc){ // Funcion para actualizar datos de empresa
global $database_conexion, $conex;


$consulta_empresa = "SELECT * FROM empresas WHERE id_empresa = '$id_empresa'";
$resultado = mysql_query($consulta_empresa, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
 if ($total_rows > 0){
	 
	$update = "UPDATE empresas SET nombre='$empresa', rfc='$rfc' WHERE id_empresa='$id_empresa'";
	if (mysql_query($update, $conex) or die(mysql_error()))
	{
		$carpeta = '../assets/images/productos/emp-'.$id_empresa;
		if (!file_exists($carpeta)) {
			mkdir($carpeta, 0777, true);
		}
		
		
	echo '<script>
		mostrar_empresas();
		$("#txt_empresa").val("");
		$("#txt_rfc").val("");
		</script>';	
	}
}

}
 
     function guardar_puesto($puesto){ // Funcion para guardar puesto nuevo
global $database_conexion, $conex;


$consulta_puesto = "SELECT * FROM puestos WHERE puesto = '$puesto'";
$resultado = mysql_query($consulta_puesto, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
if ($total_rows == 0){

$query_puesto = "INSERT INTO puestos (puesto) VALUES ('$puesto')";
$registra_puesto = mysql_query($query_puesto, $conex) or die(mysql_error());
$id_puesto =  mysql_insert_id();



echo '<script>
$("#txt_puesto_new").val("");
$("#div_new_puesto").hide();
$("#select_puesto").show();
$("#new_puesto").show();

$("#select_puesto").append("<option value="+'.$id_puesto.'+" selected>'.$puesto.'</option>");

</script>';

} else if ($total_rows > 0){
echo '<script>
alert("El puesto que intenta agregar ya existe");
</script>';
}

}

	function guardar_departamento($departamento,$id_empresa){ // Funcion para guardar departamento nuevo
global $database_conexion, $conex;


$consulta_departamento = "SELECT * FROM departamentos WHERE departamento = '$departamento' AND id_empresa = '$id_empresa' ";
$resultado = mysql_query($consulta_departamento, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
if ($total_rows == 0){

$query_departamento = "INSERT INTO departamentos (departamento, id_empresa) VALUES ('$departamento','$id_empresa')";
$registra_departamento = mysql_query($query_departamento, $conex) or die(mysql_error());
$id_departamento =  mysql_insert_id();



echo '<script>
$("#txt_departamento_new").val("");
$("#div_new_departamento").hide();
$("#select_departamento").show();
$("#new_departamento").show();

$("#select_departamento").append("<option value="+'.$id_departamento.'+" selected>'.$departamento.'</option>");

</script>';

} else if ($total_rows > 0){
echo '<script>
alert("El departamento que intenta agregar ya existe");
</script>';
}

}

function guardar_usuario($tipo,$username,$nombre,$apellido,$contrasena,$empresa_id,$list_sucursales_permitidas,$list_subordinados_seleccionados,$list_recolectores_seleccionados,$list_cc_seleccionados,$correo,$telefono,$departamento_id,$puesto_id,$turno){ // Funcion para guardar usuario nuevo
global $database_conexion, $conex;

sleep(1);
$consulta_departamento = "SELECT * FROM usuarios WHERE username = '$username' OR correo = '$correo'";
$resultado = mysql_query($consulta_departamento, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);

if ($total_rows == 0){

$query_insert_usuario = "INSERT INTO usuarios (tipo_usuario,username,nombre,apellido,contrasena,id_empresa,correo,telefono,id_departamento,id_puesto,id_turno) VALUES 
						('$tipo','$username','$nombre','$apellido','$contrasena','$empresa_id','$correo','$telefono','$departamento_id','$puesto_id','$turno')";
$registra_usuario = mysql_query($query_insert_usuario, $conex) or die(mysql_error());
$id_usuario =  mysql_insert_id();
$defaul = 0;

//// LISTA DE SUCURSALES PERMITIDAS ///	
foreach ($list_sucursales_permitidas as $key => $value) {
		//echo $key." * ".$value;
		$arr_val = explode("_", $value);
		$id_sucursal = $arr_val[0];
		$val_check = $arr_val[1];
		//////
		
		//primero buscara la sucursal con el id sucursal y el id usuario
			$consulta_reg_suc = "SELECT * FROM registros_sucursales WHERE id_sucursal = '$id_sucursal' and id_usuario = '$id_usuario'";
			$resultado_reg_suc = mysql_query($consulta_reg_suc, $conex) or die(mysql_error());
			$row_reg_suc = mysql_fetch_assoc($resultado_reg_suc);
			$total_rows_reg = mysql_num_rows($resultado_reg_suc);
		//despues si existe se tomara el id 
		if ($total_rows_reg > 0)
		{
			$id_reg_sucursal = $row_reg_suc['id_reg_sucursal'];
			
		//luego se verificara el estatus de check y si es true ni se hara nada ya que al existir en la tabla significa que tiene permitido usa la sucursal
		if ($val_check == "true")
		{
			/// no se hace nada
		}
		// de lo contrario si existe y esta marcada como false la eliminara de la tabla con el id de la sucursal que obtuvimos arriba
		else if($val_check == "false")
		{ /// se elimina el registros para quitarle el permiso
			$delete_reg_suc = "DELETE FROM registros_sucursales WHERE id_reg_sucursal = '$id_reg_sucursal'";
			$executa_delete = mysql_query($delete_reg_suc, $conex) or die(mysql_error());
		}
		}// else si no existe tambien hara la validacion de los check y si es true inserta en la tabla y false ninguna accion.
		else 
		{
			if ($val_check == "true")
			{
				$insert_reg_suc = "INSERT INTO registros_sucursales (id_sucursal,id_usuario) VALUES ('$id_sucursal','$id_usuario')";
				$inserta_suc_permitida = mysql_query($insert_reg_suc, $conex) or die(mysql_error());
				
				if ($defaul == 0){
					
					$update_suc = "UPDATE usuarios SET id_sucursal='$id_sucursal'  WHERE id='$id_usuario'";
					if (mysql_query($update_suc, $conex) or die(mysql_error()))
					{}
					$defaul = 1;
				}
				
			}
			else if($val_check == "false")
			{ /// ninguna accion
				
			}
			
			
		}
	}
	//// LISTA DE SUBORDINADOS EN CASO DE SER SUPERVISOR ///	
foreach ($list_subordinados_seleccionados as $key1 => $valor) {
		//echo $key." * ".$value;
		if ($valor != "0"){
		$arr_valor = explode("_", $valor);
		$id_subordinado = $arr_valor[0];
		$valor_check = $arr_valor[1];
		//////
		//primero buscara la relacion id_supervisor y de id_subordinado
			$consulta_rel_sup = "SELECT * FROM supervisor_relacion WHERE id_supervisor = '$id_usuario' and id_subordinado = '$id_subordinado'";
			$resultado_rel_sup = mysql_query($consulta_rel_sup, $conex) or die(mysql_error());
			$row_rel_sup = mysql_fetch_assoc($resultado_rel_sup);
			$total_rel_sup = mysql_num_rows($resultado_rel_sup);
		//despues si existe se tomara el id 
		if ($total_rel_sup > 0)
		{
			$id_rel_sup = $row_rel_sup['id'];
		
		if ($valor_check == "true"){ }// no se hace nada 
		else if($valor_check == "false")
		{ /// se elimina el registro
			$delete_rel_sup = "DELETE FROM supervisor_relacion WHERE id = '$id_rel_sup'";
			$executa_delete_rel_sup = mysql_query($delete_rel_sup, $conex) or die(mysql_error());
		}
		}
		else 
		{
			if ($valor_check == "true")
			{
				$insert_rel_sup = "INSERT INTO supervisor_relacion (id_supervisor,id_subordinado) VALUES ('$id_usuario','$id_subordinado')";
				$inserta_rel_sup = mysql_query($insert_rel_sup, $conex) or die(mysql_error());
					
			}
			else if($valor_check == "false")
			{ /// ninguna accion
				
			}
			
			
		}
		}
	}

//// LISTA DE RECOLECTORES EN CASO DE SER COMPRADOR ///	
foreach ($list_recolectores_seleccionados as $key3 => $valor2) {
		//echo $key." * ".$value;
		if ($valor2 != "0"){
		$arr_valor2 = explode("_", $valor2);
		$id_recolector = $arr_valor2[0];
		$valor_check_recolector = $arr_valor2[1];
		//////
		//primero buscara la relacion id_comprador y de id_comprador
			$consulta_rel_rec = "SELECT * FROM recolector_relacion WHERE id_comprador = '$id_usuario' and id_recolector = '$id_recolector'";
			$resultado_rel_rec = mysql_query($consulta_rel_rec, $conex) or die(mysql_error());
			$row_rel_rec = mysql_fetch_assoc($resultado_rel_rec);
			$total_rel_rec = mysql_num_rows($resultado_rel_rec);
		//despues si existe se tomara el id 
		if ($total_rel_rec > 0)
		{
			$id_rel_rec = $row_rel_rec['id'];
		
		if ($valor_check_recolector == "true"){ }// no se hace nada 
		else if($valor_check_recolector == "false")
		{ /// se elimina el registro
			$delete_rel_rec = "DELETE FROM recolector_relacion WHERE id = '$id_rel_rec'";
			$executa_delete_rel_rec = mysql_query($delete_rel_rec, $conex) or die(mysql_error());
		}
		}
		else 
		{
			if ($valor_check_recolector == "true")
			{
				$insert_rel_rec = "INSERT INTO recolector_relacion (id_comprador,id_recolector) VALUES ('$id_usuario','$id_recolector')";
				$inserta_rel_rec = mysql_query($insert_rel_rec, $conex) or die(mysql_error());
					
			}
			else if($valor_check_recolector == "false")
			{ /// ninguna accion
				
			}
			
			
		}
		}
	}
	
//// LISTA DE CENTROS DE COSTO EN CASO DE SER COMPRADOR ///	
foreach ($list_cc_seleccionados as $key_cc => $valor_cc) {
		//echo $key." * ".$value;
		if ($valor_cc != "0"){
		$arr_valor_cc = explode("_", $valor_cc);
		$id_cc = $arr_valor_cc[0];
		$valor_check_cc = $arr_valor_cc[1];
		//////
		//primero buscara la relacion id_cc y de id_comprador
			$consulta_cc = "SELECT * FROM centro_costos_relacion WHERE id_usuario = '$id_usuario' and id_cc = '$id_cc'";
			$resultado_cc = mysql_query($consulta_cc, $conex) or die(mysql_error());
			$row_cc = mysql_fetch_assoc($resultado_cc);
			$total_cc = mysql_num_rows($resultado_cc);
		//despues si existe se tomara el id 
		if ($total_cc > 0)
		{
			$id_cc_r = $row_cc['id_cc_relacion'];
		
		if ($valor_check_cc == "true"){ }// no se hace nada 
		else if($valor_check_cc == "false")
		{ /// se elimina el registro
			$delete_cc = "DELETE FROM centro_costos_relacion WHERE id_relacion = '$id_cc_r'";
			$executa_delete_cc_r = mysql_query($delete_cc, $conex) or die(mysql_error());
		}
		}
		else 
		{
			if ($valor_check_cc == "true")
			{
				$insert_rel_cc = "INSERT INTO centro_costos_relacion (id_usuario,id_cc) VALUES ('$id_usuario','$id_cc')";
				$inserta_rel_cc = mysql_query($insert_rel_cc, $conex) or die(mysql_error());
					
			}
			else if($valor_check_cc == "false")
			{ /// ninguna accion
				
			}
			
			
		}
		}
	}
echo '<script>
mostrar_user();

</script>';

}
 else if ($total_rows > 0)
{
	
echo '<script>
alert("Ya existe el nombre de usuario o correo");
</script>';
}

}

function actualizar($tipo,$username,$nombre,$apellido,$contrasena,$empresa_id,$list_sucursales_permitidas,$list_subordinados_seleccionados,$list_recolectores_seleccionados,$list_cc_seleccionados,$correo,$telefono,$departamento_id,$puesto_id,$turno,$id,$aut_spend_limit){ // Funcion Actuaalizar datos de usuarios tipo_usuario,username,nombre,apellido,contrasena,id_empresa,correo,telefono,id_departamento,id_puesto,id_turno
global $database_conexion, $conex;
$defaul = 0;

$id_empresa_actual = id_empresa($id);
if ($empresa_id != $id_empresa_actual){
	$delete_sucursales = "DELETE FROM registros_sucursales WHERE id_usuario = '$id'";
	$executa_delete_sucursales = mysql_query($delete_sucursales, $conex) or die(mysql_error());
	$delete_relacion_supervisor = "DELETE FROM supervisor_relacion WHERE id_supervisor = '$id'";
	$executa_delete_relacion = mysql_query($delete_relacion_supervisor, $conex) or die(mysql_error());
}
////// LISTA DE SUCURSALES PERMITIDAS ////
foreach ($list_sucursales_permitidas as $key => $value) {
		//echo $key." * ".$value;
		$arr_val = explode("_", $value);
		$id_sucursal = $arr_val[0];
		$val_check = $arr_val[1];
		//////
		//primero buscara la sucursal con el id sucursal y el id usuario
			$consulta_reg_suc = "SELECT * FROM registros_sucursales WHERE id_sucursal = '$id_sucursal' and id_usuario = '$id'";
			$resultado_reg_suc = mysql_query($consulta_reg_suc, $conex) or die(mysql_error());
			$row_reg_suc = mysql_fetch_assoc($resultado_reg_suc);
			$total_rows_reg = mysql_num_rows($resultado_reg_suc);
		//despues si existe se tomara el id 
		if ($total_rows_reg > 0)
		{
			$id_reg_sucursal = $row_reg_suc['id_reg_sucursal'];
			
		//luego se verificara el estatus de check y si es true ni se hara nada ya que al existir en la tabla significa que tiene permitido usa la sucursal
		if ($val_check == "true")
		{
			/// no se hace nada
		}
		// de lo contrario si existe y esta marcada como false la eliminara de la tabla con el id de la sucursal que obtuvimos arriba
		else if($val_check == "false")
		{ /// se elimina el registros para quitarle el permiso
			$delete_reg_suc = "DELETE FROM registros_sucursales WHERE id_reg_sucursal = '$id_reg_sucursal'";
			$executa_delete = mysql_query($delete_reg_suc, $conex) or die(mysql_error());
		}
		}// else si no existe tambien hara la validacion de los check y si es true inserta en la tabla y false ninguna accion.
		else 
		{
			if ($val_check == "true")
			{
				$insert_reg_suc = "INSERT INTO registros_sucursales (id_sucursal,id_usuario) VALUES ('$id_sucursal','$id')";
				$inserta_suc_permitida = mysql_query($insert_reg_suc, $conex) or die(mysql_error());
				
				if ($defaul == 0){
					
					$update_suc = "UPDATE usuarios SET id_sucursal='$id_sucursal'  WHERE id='$id'";
					if (mysql_query($update_suc, $conex) or die(mysql_error()))
					{}
					$defaul = 1;
				}
				
			}
			else if($val_check == "false")
			{ /// ninguna accion
				
			}
			
			
		}
	}
//// LISTA DE SUBORDINADOS EN CASO DE SER SUPERVISOR ///	
foreach ($list_subordinados_seleccionados as $key2 => $valor) {
		//echo $key." * ".$value;
		if ($valor != "0"){
		$arr_valor = explode("_", $valor);
		$id_subordinado = $arr_valor[0];
		$valor_check = $arr_valor[1];
		//////
		//primero buscara la relacion id_supervisor y de id_subordinado
			$consulta_rel_sup = "SELECT * FROM supervisor_relacion WHERE id_supervisor = '$id' and id_subordinado = '$id_subordinado'";
			$resultado_rel_sup = mysql_query($consulta_rel_sup, $conex) or die(mysql_error());
			$row_rel_sup = mysql_fetch_assoc($resultado_rel_sup);
			$total_rel_sup = mysql_num_rows($resultado_rel_sup);
		//despues si existe se tomara el id 
		if ($total_rel_sup > 0)
		{
			$id_rel_sup = $row_rel_sup['id'];
		
		if ($valor_check == "true"){ }// no se hace nada 
		else if($valor_check == "false")
		{ /// se elimina el registro
			$delete_rel_sup = "DELETE FROM supervisor_relacion WHERE id = '$id_rel_sup'";
			$executa_delete_rel_sup = mysql_query($delete_rel_sup, $conex) or die(mysql_error());
		}
		}
		else 
		{
			if ($valor_check == "true")
			{
				$insert_rel_sup = "INSERT INTO supervisor_relacion (id_supervisor,id_subordinado) VALUES ('$id','$id_subordinado')";
				$inserta_rel_sup = mysql_query($insert_rel_sup, $conex) or die(mysql_error());
					
			}
			else if($valor_check == "false")
			{ /// ninguna accion
				
			}
			
			
		}
		}
	}

//// LISTA DE RECOLECTORES EN CASO DE SER COMPRADOR ///	
foreach ($list_recolectores_seleccionados as $key3 => $valor2) {
		//echo $key." * ".$value;
		if ($valor2 != "0"){
		$arr_valor2 = explode("_", $valor2);
		$id_recolector = $arr_valor2[0];
		$valor_check_recolector = $arr_valor2[1];
		//////
		//primero buscara la relacion id_comprador y de id_recolector
			$consulta_rel_rec = "SELECT * FROM recolector_relacion WHERE id_comprador = '$id' and id_recolector = '$id_recolector'";
			$resultado_rel_rec = mysql_query($consulta_rel_rec, $conex) or die(mysql_error());
			$row_rel_rec = mysql_fetch_assoc($resultado_rel_rec);
			$total_rel_rec = mysql_num_rows($resultado_rel_rec);
		//despues si existe se tomara el id 
		if ($total_rel_rec > 0)
		{
			$id_rel_rec = $row_rel_rec['id_relacion'];
		
		if ($valor_check_recolector == "true"){ }// no se hace nada 
		else if($valor_check_recolector == "false")
		{ /// se elimina el registro
			$delete_rel_rec = "DELETE FROM recolector_relacion WHERE id_relacion = '$id_rel_rec'";
			$executa_delete_rel_rec = mysql_query($delete_rel_rec, $conex) or die(mysql_error());
		}
		}
		else 
		{
			if ($valor_check_recolector == "true")
			{
				$insert_rel_rec = "INSERT INTO recolector_relacion (id_comprador,id_recolector) VALUES ('$id','$id_recolector')";
				$inserta_rel_rec = mysql_query($insert_rel_rec, $conex) or die(mysql_error());
					
			}
			else if($valor_check_recolector == "false")
			{ /// ninguna accion
				
			}
			
			
		}
		}
	}

//// LISTA DE CENTROS DE COSTO EN CASO DE SER COMPRADOR ///	
foreach ($list_cc_seleccionados as $key_cc => $valor_cc) {
		//echo $key." * ".$value;
		if ($valor_cc != "0"){
		$arr_valor_cc = explode("_", $valor_cc);
		$id_cc = $arr_valor_cc[0];
		$valor_check_cc = $arr_valor_cc[1];
		//////
		//primero buscara la relacion id_cc y de id_comprador
			$consulta_cc = "SELECT * FROM centro_costos_relacion WHERE id_usuario = '$id' and id_cc = '$id_cc'";
			$resultado_cc = mysql_query($consulta_cc, $conex) or die(mysql_error());
			$row_cc = mysql_fetch_assoc($resultado_cc);
			$total_cc = mysql_num_rows($resultado_cc);
		//despues si existe se tomara el id 
		if ($total_cc > 0)
		{
			$id_cc_r = $row_cc['id_cc_relacion'];
		
		if ($valor_check_cc == "true"){ }// no se hace nada 
		else if($valor_check_cc == "false")
		{ /// se elimina el registro
			$delete_cc = "DELETE FROM centro_costos_relacion WHERE id_cc_relacion = '$id_cc_r'";
			$executa_delete_cc_r = mysql_query($delete_cc, $conex) or die(mysql_error());
		}
		}
		else 
		{
			if ($valor_check_cc == "true")
			{
				$insert_rel_cc = "INSERT INTO centro_costos_relacion (id_usuario,id_cc) VALUES ('$id','$id_cc')";
				$inserta_rel_cc = mysql_query($insert_rel_cc, $conex) or die(mysql_error());
					
			}
			else if($valor_check_cc == "false")
			{ /// ninguna accion
				
			}
			
			
		}
		}
	}

 if ($contrasena == ''){
$update = "UPDATE usuarios SET 
tipo_usuario='$tipo', username='$username', nombre='$nombre', apellido='$apellido', id_empresa='$empresa_id', correo='$correo', telefono='$telefono', id_departamento='$departamento_id', id_puesto='$puesto_id', id_turno='$turno', autorizar_limit_spend='$aut_spend_limit' 
WHERE id='$id'";	 
	 
 }else if ($contrasena != ''){
$update = "UPDATE usuarios SET 
tipo_usuario='$tipo', username='$username', nombre='$nombre', apellido='$apellido', contrasena='$contrasena', id_empresa='$empresa_id', correo='$correo', telefono='$telefono', id_departamento='$departamento_id', id_puesto='$puesto_id', id_turno='$turno', autorizar_limit_spend='$aut_spend_limit' 
WHERE id='$id'";	 
	 
 }
	if (mysql_query($update, $conex) or die(mysql_error()))
		{
			echo '<script>
mostrar_user();

</script>';
		}

}

?>