<?php include("conexion.php"); 

		
	  if (isset($_POST['id_usuario'])){
      $id_usuario = $_POST['id_usuario'];
		datos_usuario($id_usuario);
	 }
	 
     function datos_usuario($id_usuario){ 
global $database_conexion, $conex;

$chk_aut_limit_spend ="";

$consulta_usuarios = "SELECT * FROM usuarios WHERE id=$id_usuario";
$resultado = mysql_query($consulta_usuarios, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados

		$id = $row['id'];
		$tipo = $row['tipo_usuario'];
		$username = $row['username'];
		$nombre = $row['nombre'];
		$apellido= $row['apellido'];
		//$contrasena= $row['contrasena'];
		$empresa_id= $row['id_empresa'];
		//$sucursal_id= $row['id_sucursal'];
		$correo= $row['correo'];
		$telefono= $row['telefono'];
		$departamento_id= $row['id_departamento'];
		$puesto_id= $row['id_puesto'];
		$turno= $row['id_turno'];
		$mostrar_segun_tipo = '';
		
		if ($row['autorizar_limit_spend'] == 0){
			$chk_aut_limit_spend = '$("#chk_autspend").prop("checked",false);';
		} else if($row['autorizar_limit_spend'] == 1){
			$chk_aut_limit_spend = '$("#chk_autspend").prop("checked",true);';
		}
		
		
		if ($tipo == '5'){ /// si es supervisor
			$mostrar_segun_tipo = '$("#btn_subordinados").show();
									$("#btn_add_recolector").show();
									$("#btn_add_cc").show();';
		}
		else if ($tipo == '2') /// si es comprador
		{
			$mostrar_segun_tipo = '$("#btn_add_recolector").show();
									$("#btn_subordinados").hide();
									$("#btn_add_cc").show();';
		}
		else // si no es nunguno de los anteriores
		{
			$mostrar_segun_tipo = '$("#btn_subordinados").hide();
									$("#btn_add_recolector").hide();
									$("#btn_add_cc").hide();';
		}	
		
		
 
 echo '<script> 

	$("#txt_id_usuario").val("'.$id.'");
	$("#select_tipo").val("'.$tipo.'");
	$("#nombre").val("'.$nombre.'");
	$("#username").val("'.$username.'");
	$("#apellido").val("'.$apellido.'");
	$("#contrasena").val("");
	$("#select_empresa").val("'.$empresa_id.'");
	lista_sucursales('.$empresa_id.');
	lista_subor_recolect();
	$("#correo").val("'.$correo.'");
	$("#telefono").val("'.$telefono.'");
	$("#select_departamento").val("'.$departamento_id.'");
	$("#select_puesto").val("'.$puesto_id.'");
	$("#select_turno").val("'.$turno.'");
	'.$mostrar_segun_tipo.'	
	'.$chk_aut_limit_spend.'	
	
	$("#div_lista_recolectores").hide();					   
	$("#div_lista_subordinados").hide();
	$("#div_lista_cc").hide();
</script>';  

 
} 

}




?>





                    	
                    	
                