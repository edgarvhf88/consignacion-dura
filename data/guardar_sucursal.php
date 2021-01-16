
<?php include("conexion.php");

  
	  if ((isset($_POST['id_empresa'])) && (isset($_POST['sucursal'])) && (!isset($_POST['id_sucursal']))){
				$sucursal= $_POST['sucursal'];
				$direccion= $_POST['direccion'];
				$pais= $_POST['pais'];
				$estado= $_POST['estado'];
				$ciudad= $_POST['ciudad'];
				$cp= $_POST['cp'];
				$id_empresa = $_POST['id_empresa'];
				$chk_dir_fac = $_POST['chk_dir_fac'];
				$chk_dir_suc = $_POST['chk_dir_suc'];
		
				guardar($sucursal,$direccion,$pais,$estado,$ciudad,$cp,$id_empresa,$chk_dir_fac,$chk_dir_suc);
	  } 
	  if ((isset($_POST['id_empresa'])) && (isset($_POST['sucursal'])) && (isset($_POST['id_sucursal']))){
				$id_sucursal = $_POST['id_sucursal'];
				$sucursal= $_POST['sucursal'];
				$direccion= $_POST['direccion'];
				$pais= $_POST['pais'];
				$estado= $_POST['estado'];
				$ciudad= $_POST['ciudad'];
				$cp= $_POST['cp'];
				$id_empresa = $_POST['id_empresa'];
				$chk_dir_fac = $_POST['chk_dir_fac'];
				$chk_dir_suc = $_POST['chk_dir_suc'];
		
				actualizar($sucursal,$direccion,$pais,$estado,$ciudad,$cp,$id_empresa,$id_sucursal,$chk_dir_fac,$chk_dir_suc);
	  }
	
     function guardar($sucursal,$direccion,$pais,$estado,$ciudad,$cp,$id_empresa,$chk_dir_fac,$chk_dir_suc){ // Funcion para agregar una relacion nueva
global $database_conexion, $conex;

		$consulta = "SELECT * FROM sucursales WHERE id_empresa =  '$id_empresa' and sucursal =  '$sucursal' ";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);

		if ($total_rows > 0)
		{ // si ya existe la sucursal guarda los cambios
			echo '<script>
			alert("La sucursal '.$sucursal.' ya esta se encuentra agregada a la empresa seleccionada");
			
			</script>';
		}
		else 
		{
		if ($chk_dir_fac == false){
	$check_fac = 0;
}else if ($chk_dir_fac == true){
	$check_fac = 1;
}
if ($chk_dir_suc == false){
	$check_suc = 0;
}else if ($chk_dir_suc == true){
	$check_suc = 1;
}
			$insert_sucursal = "INSERT INTO sucursales (id_empresa,sucursal,direccion,id_pais,id_estado,id_ciudad,codigo_postal,uso_dir_fac,uso_suc)
								VALUES ('$id_empresa','$sucursal','$direccion','$pais','$estado','$ciudad','$cp','$check_fac','$check_suc')";
			if (mysql_query($insert_sucursal, $conex) or die(mysql_error()))
			{
				//$id_pedido =  mysql_insert_id();
				//echo 1;
				echo '<script>
			mostrar_sucursales();
			
			</script>';
		
			}
		}
}
     function actualizar($sucursal,$direccion,$pais,$estado,$ciudad,$cp,$id_empresa,$id_sucursal,$chk_dir_fac,$chk_dir_suc){ // Funcion para agregar una relacion nueva
	 
global $database_conexion, $conex;
if ($chk_dir_fac == "false"){
	$check_fac = 0;
}else if ($chk_dir_fac == "true"){
	$check_fac = 1;
}
if ($chk_dir_suc == "false"){
	$check_suc = 0;
}else if ($chk_dir_suc == "true"){
	$check_suc = 1;
}			
			$update = "UPDATE sucursales SET id_empresa='$id_empresa', sucursal='$sucursal', direccion='$direccion', id_pais='$pais', id_estado='$estado', id_ciudad='$ciudad', codigo_postal='$cp', uso_dir_fac='$check_fac', uso_suc='$check_suc' WHERE id_sucursal='$id_sucursal'";

					if (mysql_query($update, $conex) or die(mysql_error()))
					{
					
						echo '<script>
				
						mostrar_sucursales();
				
						</script>';
						
					}
		
}


function eliminar($id_relacion){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;

$delete_relacion = "DELETE FROM relaciones WHERE id_relacion = $id_relacion ";
		if (mysql_query($delete_relacion, $conex) or die(mysql_error()))
		{
			echo '<script>
				list_relaciones();
				
				</script>';
			
		}
		else 
		{
			//echo 0;
		}

}
?>