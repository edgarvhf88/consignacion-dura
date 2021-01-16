<?php include("conexion.php");
		
		
		$id_user = $_POST['id_user'];
		$tipo = $_POST['tipo'];
		$cantidad_limite = $_POST['cantidad_limite'];
		$fecha_inicial = $_POST['fecha_inicial'];
		$tipo_periodo = $_POST['tipo_periodo'];
		$cantidad_periodo = $_POST['cantidad_periodo'];
		$ciclo = $_POST['ciclo'];
		$valor_concepto = $_POST['valor_concepto'];
	  
	  if ($id_user != ''){
			  
			establecer_limite($id_user, $tipo, $cantidad_limite, $fecha_inicial, $tipo_periodo, $cantidad_periodo,$ciclo, $valor_concepto);
	  }
     function establecer_limite($id_user, $tipo, $cantidad_limite, $fecha_inicial, $tipo_periodo, $cantidad_periodo,$ciclo, $valor_concepto){ 
global $database_conexion, $conex;

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");

$consulta_existencia = "SELECT * FROM validacion_limit WHERE tipo = $tipo AND id_aplicado = $valor_concepto ";
$resultado_existencia = mysql_query($consulta_existencia, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_existencia);
$total_rows = mysql_num_rows($resultado_existencia);
if ($total_rows > 0){ // si existe registro de limite con el tipo y id_aplicado

if ($tipo == 1){ // si es articulo
		echo '<script>
			$("#modal_cargando").modal("hide");
			alert("Ya existe un registro de limite de spend de articulo seleccionado");
			</script>';
} else if ($tipo == 2){// si est tipo centro de costos
	echo '<script>
			$("#modal_cargando").modal("hide");
			alert("Ya existe un registro de limite de spend del centro de costos seleccionado");
			</script>';
} else if ($tipo == 3){// si est tipo departamento
	echo '<script>
			$("#modal_cargando").modal("hide");
			alert("Ya existe un registro de limite de spend del departamento seleccionado");
			</script>';
} else if ($tipo == 4){// si est tipo usuario
	echo '<script>
			$("#modal_cargando").modal("hide");
			alert("Ya existe un registro de limite de spend de usuario seleccionado");
			</script>';
}

}
else
{
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
$valor_ciclo = "";
if ($ciclo == "true"){
	$valor_ciclo = 1;
}else if ($ciclo == "false"){
	$valor_ciclo = 0;
}
$fechaFormato = date_create($fecha_inicial);
$fechaInsertar = date_format($fechaFormato, 'Y-m-d H:i');

	
$insert_limit = "
INSERT INTO validacion_limit (id_usuario_requiere,tipo,cantidad_dinero,fecha_inicia,fecha_creacion,duracion_medida,cantidad_dm,ciclo,id_aplicado,id_empresa)
VALUES ('$id_user','$tipo','$cantidad_limite','$fechaInsertar','$fecha_actual','$tipo_periodo','$cantidad_periodo','$valor_ciclo','$valor_concepto','$id_empresa_user_activo')";

		if (mysql_query($insert_limit, $conex) or die(mysql_error()))
		{
			echo '<script>
					
					lista_limite_spend();
					$("#modal_cargando").modal("hide");
					alert("Se ha establecido el limite de spend");
					</script>';
			
		}
		else 
		{
			echo 0;
		} 
		/* echo '<script>
					alert("Se ha establecido el limite de spend ('.$fechaInsertar.')");
					lista_limite_spend();
					$("#modal_cargando").modal("hide");
					
					</script>';*/
	
}

}

?>