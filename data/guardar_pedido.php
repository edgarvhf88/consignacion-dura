<?php include("conexion.php");

		$id_pedido = $_POST['id_pedido'];
		$id_cc = $_POST['orden_cc'];
		$id_recolector = $_POST['orden_recolector'];
		$orden_cliente = $_POST['orden_cliente'];
		$almacen_id = $_POST['almacen_id'];
	  
	  if ($id_pedido != ''){
			
		Validacion($id_pedido);	
	  }

	function verificar_registradas($id_pedido){
		global $database_conexion, $conex;
		$consulta = "SELECT * FROM requi_autorizacion WHERE id_pedido = '$id_pedido'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$lista_registradas = ''; 
		if ($total_rows > 0){
			while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
				{
					$lista_registradas [] = array("value" => $row['id_requi'], 
									"tipo" => $row['tipo'], 
									"id_aplicado" => $row['id_aplicado'],	
									"descripcion" => $row['descripcion'],
									"total_evaluado" => $row['total_evaluado'], 
									"id_limite" => $row['id_limite'], 
									"estatus" => $row['estatus'],
									"justificacion" => $row['justificacion']);
				}
			
		}
		return $lista_registradas;
		
	}
	function  TotalPeriodo($fecha_inicial, $fecha_final, $tipo, $id_aplicado){
		global $database_conexion, $conex;
		$total_en_periodo = '0.00';
		if ($tipo == 1){ // si es tipo articulo
			$consulta = "SELECT SUM(pd.precio_total) AS Total_periodo 
						 FROM pedidos_det pd
						 LEFT JOIN pedidos p on p.id = pd.id_pedido
						 WHERE pd.id_articulo = '$id_aplicado' AND p.estatus <> '0' AND p.estatus <> '0p' AND
						 p.fecha_pedido_oficial BETWEEN '$fecha_inicial' AND '$fecha_final'";
		}else if ($tipo == 2){ // si es tipo centro de costos
			$consulta = "SELECT SUM(pd.precio_total) AS Total_periodo 
						 FROM pedidos_det pd
						 LEFT JOIN pedidos p on p.id = pd.id_pedido
						 WHERE p.id_cc = '$id_aplicado' AND p.estatus <> '0' AND p.estatus <> '0p' AND
						 p.fecha_pedido_oficial BETWEEN '$fecha_inicial' AND '$fecha_final'";
		}else if ($tipo == 3){ // sie es tipo departamento
			$consulta = "SELECT SUM(pd.precio_total) AS Total_periodo 
						 FROM pedidos_det pd
						 LEFT JOIN pedidos p on p.id = pd.id_pedido
						 WHERE p.id_departamento = '$id_aplicado' AND p.estatus <> '0' AND p.estatus <> '0p' AND
						 p.fecha_pedido_oficial BETWEEN '$fecha_inicial' AND '$fecha_final'";
		}else if ($tipo == 4){ // si es tipo usuario
			$consulta = "SELECT SUM(pd.precio_total) AS Total_periodo 
						 FROM pedidos_det pd
						 LEFT JOIN pedidos p on p.id = pd.id_pedido
						 WHERE p.id_usuario = '$id_aplicado' AND p.estatus <> '0' AND p.estatus <> '0p' AND
						 p.fecha_pedido_oficial BETWEEN '$fecha_inicial' AND '$fecha_final'";
		}
		
		
		
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		if ($total_rows > 0){
			$total_en_periodo = $row['Total_periodo'];
		}
		return $total_en_periodo;
	}
	function check_rango($fecha_inicio, $fecha_fin, $fecha){
		$fecha_inicio = strtotime($fecha_inicio);
		$fecha_fin = strtotime($fecha_fin);
		$fecha = strtotime($fecha);
		if(($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
		return true;
		} else {
		return false;
		}
	}
function CheckLimit($tipo, $id_aplicado, $id_empresa){
	global $database_conexion, $conex;
	date_default_timezone_set('America/Mexico_City');
	$fecha_hoy = date("Y-m-d H:i:s");	  
		  
	$consulta_existencia = "SELECT * FROM validacion_limit WHERE tipo = $tipo AND id_aplicado = $id_aplicado AND id_empresa = $id_empresa ";
	$resultado_existencia = mysql_query($consulta_existencia, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($resultado_existencia);
	$total_rows = mysql_num_rows($resultado_existencia);
	if ($total_rows > 0){ // si existe registro de limite con el tipo y id_aplicado
		$valor_retorno = 0;
		$fecha_inicial = $row['fecha_inicia'];
		$fecha_final = '';
		$monto_limite = $row['cantidad_dinero'];
		$total_periodo = 0;
		$presupuesto_disponible = '';
		$tipo_periodo = $row['duracion_medida'];
		$cantidad_periodo = $row['cantidad_dm'];
		$ciclo = $row['ciclo'];
		$id_limit = $row['id_limit'];
		if ($tipo_periodo == 1){ // mes
			$fecha_final = strtotime ('+'.$cantidad_periodo.' month',strtotime($fecha_inicial));
		}else if ($tipo_periodo == 2){ //dia
			$fecha_final = strtotime ('+'.$cantidad_periodo.' day',strtotime($fecha_inicial));
		}
			$fecha_final = date("Y-m-d H:i:s", $fecha_final);
			$fecha_final = date_create($fecha_final);
			$fecha_final = date_format($fecha_final, "Y-m-d H:i:s");
			
		/* 
		 */	
		for ($i = 1; ; $i++) {
			if (check_rango($fecha_inicial, $fecha_final, $fecha_hoy)){ // si esta dentro del rango
			// debe encontrar el rango en que se encuentra la fecha actual para medir el consumo del pariodo 
				$total_periodo = TotalPeriodo($fecha_inicial, $fecha_final, $tipo, $id_aplicado);
			
				break;
			}else{ // si no esta dentro del rango
			 
					$fecha_inicial = strtotime($fecha_final) ;
					$fecha_inicial = date("Y-m-d H:i:s", $fecha_inicial);
					$fecha_inicial = date_create($fecha_inicial);
					$fecha_inicial = date_format($fecha_inicial, "Y-m-d H:i:s");
					
					if ($tipo_periodo == 1){ // mes
						$fecha_final = strtotime('+'.$cantidad_periodo.' month',strtotime($fecha_inicial));
					}
					else if ($tipo_periodo == 2){ //dia
						$fecha_final = strtotime('+'.$cantidad_periodo.' day' ,strtotime($fecha_inicial));
					}
					
					$fecha_final = date("Y-m-d H:i:s", $fecha_final);
					$fecha_final = date_create($fecha_final);
					$fecha_final = date_format($fecha_final, "Y-m-d H:i:s");
					
			}
			/* echo '<script> 
					console.log("'.$fecha_inicial.' -  '.$fecha_final.' - '.$fecha_hoy.'");
					</script> '; */
			if ($i == 100){
				echo '<script> 
					console.log("el bucle se esta ciclando");
					</script> ';
				break;}
		}
		$presupuesto_disponible = $monto_limite - $total_periodo;
		
		 /* if ($presupuesto_disponible < 0){
			 // si el limite ha sido sobrepasado  
			 $valor_retorno = str_replace("-","", $presupuesto_disponible);
		}else if ($presupuesto_disponible = 0){ // si el limite ha sido sobrepasado  
			$valor_retorno = '0';
		}else {} */
			$valor_retorno = $presupuesto_disponible.'_'.$monto_limite.'_'.$id_limit;
			// validar si con el presupuesto disponible se cubre la compra del concepto en el pedido		
		 
		
		return $valor_retorno;
	}else { // no tiene limitacion
		return 'xxx';
	}
	
}
	 function Validacion($id_pedido){
		  global $database_conexion, $conex, $id_cc, $id_recolector, $orden_cliente, $almacen_id;
		  
			//////// VALIDA ARTICULOS DE PEDIDO /////////
			$lista_articulos_pedido = array();
			$lista_limites_excedidos = array();
			$total_pedido = 0;
			$datos_mostar = 0;
			$disponible_articulo = 0;
			$disponible_cc = '';
			$disponible_departamento = '';
			$disponible_usuario = '';
			$id_empresa = id_empresa($_SESSION["logged_user"]);
			$consulta = "SELECT * FROM pedidos_det WHERE id_pedido = '$id_pedido'";
			$resultado = mysql_query($consulta, $conex) or die(mysql_error());
			//$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
			if ($total_rows > 0){
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
				{
					
					$total_pedido = $total_pedido + $row['precio_total'];
					$disponible_articulo = CheckLimit(1, $row['id_articulo'], $id_empresa);
					
					if ($disponible_articulo != "xxx"){
					// explode varios datos, disponible - cantidad_limite - fecha de siguiente ciclo
					$arr_dispo = explode("_",$disponible_articulo);
					$monto_disponible = $arr_dispo[0];
					$cantidad_limite  = $arr_dispo[1];
					$id_limit = $arr_dispo[2];
//////NOTA: actualizar validacion de articculos por si hubieran partidas iguales en el pedido y de este metodo no se puede validar consolidado, agregar codigo de cosolidacion y validacion de cantidad real.
					 
						if ($row['precio_total'] > $monto_disponible){
							//$datos_mostar = $row['articulo']." (".$row['clave_empresa'].") ".$row['cantidad']." ".$row['unidad_medida']." total= $".number_format($row['precio_total'],2);
							//$lista_articulos_pedido[$row['id_articulo']] = $datos_mostar;
							$lista_limites_excedidos [] = array("value" => $row['articulo'], 
									"tipo" => '1', 
									"id_aplicado" => $row['id_articulo'], 
									"disponible" => $monto_disponible, 
									"total" => $row['precio_total'], 
									"id_limit" => $id_limit, 
									"cantidad_limite" => $cantidad_limite);					
						}
					}
				}
				
			}
				
			 $consulta_p = "SELECT * FROM pedidos WHERE id = '$id_pedido'";
			$resultado_p = mysql_query($consulta_p, $conex) or die(mysql_error());
			$rowp = mysql_fetch_assoc($resultado_p);
			$total_rows_p = mysql_num_rows($resultado_p);
			if ($total_rows_p > 0){
				
				////// VALIDACION DE CENTRO DE COSTOS /////
				if ($id_cc <> 0){
					/*  echo '<script> 
							alert("CENTRO DE COSTOS '.$id_cc.'");
						</script>';  */
				$disponible_cc =  CheckLimit(2, $id_cc, $id_empresa);
					if ($disponible_cc != "xxx"){
						$arr_dispo_cc = explode("_",$disponible_cc);
					$monto_disponible_cc = $arr_dispo_cc[0];
					$cantidad_limite_cc  = $arr_dispo_cc[1];
					$id_limit_cc = $arr_dispo_cc[2];
						
						if ($monto_disponible_cc < $total_pedido){
					
							// esta exediendo el limite de centro de costos
							$lista_limites_excedidos [] = array("value" => CC_NOMBRE($id_cc), 
								"tipo" => '2', 
								"id_aplicado" => $id_cc, 
									"disponible" => $monto_disponible_cc, 
									"total" => $total_pedido, 
									"id_limit" => $id_limit_cc, 
									"cantidad_limite" => $cantidad_limite_cc);	
						}
					}	
				}
				
					$id_departamento = DEPARTAMENTO($rowp['id_usuario']);
				 $disponible_departamento =  CheckLimit(3, $id_departamento, $id_empresa);
				$disponible_usuario =  CheckLimit(4, $rowp['id_usuario'], $id_empresa);
				
				if ($disponible_departamento != "xxx"){
					$arr_dispo_dep = explode("_",$disponible_departamento);
					$monto_disponible_dep = $arr_dispo_dep[0];
					$cantidad_limite_dep  = $arr_dispo_dep[1];
					$id_limit_dep = $arr_dispo_dep[2];
					
				 if ($monto_disponible_dep < $total_pedido){
					// si esta exediendo el limite de depatamento
					$lista_limites_excedidos [] = array("value" => DEPARTAMENTO_NOMBRE($rowp['id_departamento']), 
							   "tipo" => '3', 
							   "id_aplicado" => $rowp['id_departamento'], 
									"disponible" => $monto_disponible_dep, 
									"total" => $total_pedido, 
									"id_limit" => $id_limit_dep, 
									"cantidad_limite" => $cantidad_limite_dep);
					
				 }
				}
				if ($disponible_usuario != "xxx"){
					$arr_dispo_usu = explode("_",$disponible_usuario);
					$monto_disponible_usu = $arr_dispo_usu[0];
					$cantidad_limite_usu = $arr_dispo_usu[1];
					$id_limit_usu = $arr_dispo_usu[2];
				 if($monto_disponible_usu < $total_pedido){
					// si esta exediendo el limite de usuario 
					$lista_limites_excedidos [] = array("value" => Nombre($rowp['id_usuario']), 
							   "tipo" => '4', 
							   "id_aplicado" => $rowp['id_usuario'], 
									"disponible" => $monto_disponible_usu, 
									"total" => $total_pedido, 
									"id_limit" => $id_limit_usu, 
									"cantidad_limite" => $cantidad_limite_usu);
					
				 }				
				} 
				
			} 
			if (count($lista_limites_excedidos) > 0){
				// echo con script para mostrar modal con razones de bloqueo de pedido	
				$razones_html = '<table>';
				date_default_timezone_set('America/Mexico_City');
				$fecha_registro = date("Y-m-d H:i:s");
	
				 $lista_registrada = verificar_registradas($id_pedido);
				 if ($lista_registrada <> ''){  // si existen registros de limites exedidos en el pedido
					if (count($lista_registrada) > 0){
						// VALIDAR SI ESTAN APROBADAS AL 100% 
						$estatus_autorizaciones =  estatus_autorizaciones($id_pedido);
							$arr_cant_aut = explode('-',$estatus_autorizaciones);
							$cant_total = $arr_cant_aut[0];
							$cant_aprob = $arr_cant_aut[1];
							//$cant_deneg = $arr_cant_aut[2];
							//$cant_pend = $arr_cant_aut[3];
							//$porcentaje_aprob = ($cant_aprob / $cant_total) * 100;
							//$porcentaje_deneg = ($cant_deneg / $cant_total) * 100;
						if ($cant_total == $cant_aprob){ // si todas estan aprobadas
						$datos_mostar = 1;
						actualizar($id_pedido, $id_cc, $id_recolector, $orden_cliente, $almacen_id);	
						
						} 
						else 
						{
							
					 	foreach($lista_registrada as $lista_revisar){
							
									$encontrado = 0;
									$reg_id_requi = $lista_revisar['value'];
						    foreach($lista_limites_excedidos as $lista_razones_act){
								if (($lista_revisar['tipo'] == $lista_razones_act['tipo']) && ($lista_revisar['id_aplicado'] == $lista_razones_act['id_aplicado'])){ // al encontrar conincidencia 
									$encontrado = 1;
									
								}
								
					 		}
							if ($encontrado == 0){ // si el registro no esta en la lista actual se elimina
								//ELIMINAR
								
								$delete_reg = "DELETE FROM requi_autorizacion WHERE id_requi = $reg_id_requi ";
								if (mysql_query($delete_reg, $conex) or die(mysql_error())){}
							}
						}
						
						foreach($lista_limites_excedidos as $lista_razones_act){
						
									$encontrado = 0;
									$reg_id_requi = "";
									$reg_total_evaluado = "";
									$texto_justificacion = "";
									$reg_estatus = 0;
									
						    foreach($lista_registrada as $lista_revisar){
								if (($lista_revisar['tipo'] == $lista_razones_act['tipo']) && ($lista_revisar['id_aplicado'] == $lista_razones_act['id_aplicado'])){ // al encontrar conincidencia 
									$encontrado = 1;
									
								$reg_id_requi = $lista_revisar['value']; 
								$reg_justificacion = $lista_revisar['justificacion']; // para actualizar el areatext con la justificacion registrada
								$reg_estatus = $lista_revisar['estatus'];
								}
								
					 		}
							if ($encontrado == 0){ // si el registro no esta en la lista actual se insertara
								// INSERTAR
								$r_id_aplicado = $lista_razones_act['id_aplicado'];
								$r_tipo = $lista_razones_act['tipo'];
								$r_total_evaluado = $lista_razones_act['total'];
								$r_id_limite = $lista_razones_act['id_limit'];
								$r_cantidad_limite = $lista_razones_act['cantidad_limite'];
								$r_cantidad_diponible = $lista_razones_act['disponible'];
								$r_id_pedido = $id_pedido;
								$r_id_usuario_req = $_SESSION["logged_user"];
								$r_fecha_req = $fecha_registro;
								$r_estatus = 0;
													
								$insert_requiauto = "INSERT INTO requi_autorizacion	
													(id_aplicado,tipo,total_evaluado,id_limite, id_pedido,id_usuario_requiere,fecha_requirio,estatus,total_limite, total_disponible)
													VALUES 
													('$r_id_aplicado','$r_tipo','$r_total_evaluado','$r_id_limite','$r_id_pedido','$r_id_usuario_req','$r_fecha_req','$r_estatus','$r_cantidad_limite','$r_cantidad_diponible')";
								if (mysql_query($insert_requiauto, $conex) or die(mysql_error()))
								{ $id_reg_limit =  mysql_insert_id();}
							
							}
							else if($encontrado == 1){
								  // ACTUALIZAR $reg_id_requi '$r_total_evaluado','$r_id_limite','$r_fecha_req'
								  
								  
								 $id_reg_limit = $reg_id_requi;
								$texto_justificacion = $reg_justificacion;	
							}
							/// GENERAR NUEVA LISTA PARA MOSTRAR 
					///////////////////if estatus = 2 //aprobado // de lo contrario btn justificar y textarea
								if ($reg_estatus == 2){
									$mostrar_estatus = 'Aprobado';
									$clase_css = 'class=\"bg-success\"';
								}
								else{
									$mostrar_estatus = '<input type=\"button\" id=\"justificar_'.$id_reg_limit.'\" value=\"Justificar\" class=\"btn btn-info justificacion\" /> <textarea id=\"txt_justif_'.$id_reg_limit.'\" class= \"textareajustificaciones\"> '.$texto_justificacion.'</textarea>';
									$clase_css = '';
								}
							if ($lista_razones_act['tipo'] == 1){
								$razones_html .= '<tr '.$clase_css.'><td >El Articulo '.$lista_razones_act['value'].' excede el limite establecido, disponible = '.$lista_razones_act['disponible'].', total en pedido = '.$lista_razones_act['total'].'</td><td>  '.$mostrar_estatus.'	</td></tr>';
							}else if ($lista_razones_act['tipo'] == 2){
								$razones_html .= '<tr '.$clase_css.'><td>El Centro de costos '.$lista_razones_act['value'].' excede el limite establecido, disponible = '.$lista_razones_act['disponible'].', total en pedido = '.$lista_razones_act['total'].'</td><td> '.$mostrar_estatus.'	</td></tr>';
							}else if ($lista_razones_act['tipo'] == 3){
								$razones_html .= '<tr '.$clase_css.'><td>El Departamento '.$lista_razones_act['value'].' excede el limite establecido, disponible = '.$lista_razones_act['disponible'].', total en pedido = '.$lista_razones_act['total'].'</td><td> '.$mostrar_estatus.'	</td></tr>';
							}else if ($lista_razones_act['tipo'] == 4){
								$razones_html .= '<tr '.$clase_css.'><td>El Usuario '.$lista_razones_act['value'].' excede el limite establecido, disponible = '.$lista_razones_act['disponible'].', total en pedido = '.$lista_razones_act['total'].'</td><td> '.$mostrar_estatus.'</td></tr>';
							}
						}
						}
					}
				 }
				 else
				 { // SI NO EXISTEN REGISTROS DE LIMITES EXCEDIDOS EN EL PEDIDO ENTONCES SOLO LOS INSERTA 
			foreach($lista_limites_excedidos as $lista_razones){
					// insert tabla requi_autorizacion
			
					$r_id_aplicado = $lista_razones['id_aplicado'];
					$r_tipo = $lista_razones['tipo'];
					$r_total_evaluado = $lista_razones['total'];
					$r_id_limite = $lista_razones['id_limit'];
					$r_cantidad_limite = $lista_razones['cantidad_limite'];
					$r_cantidad_diponible = $lista_razones['disponible'];
					$r_id_pedido = $id_pedido;
					$r_id_usuario_req = $_SESSION["logged_user"];
					$r_fecha_req = $fecha_registro;
					$r_estatus = 0;
										
					$insert_requiauto = "INSERT INTO requi_autorizacion	
										(id_aplicado,tipo,total_evaluado,id_limite, id_pedido,id_usuario_requiere,fecha_requirio,estatus,total_limite,total_disponible)
										VALUES 
										('$r_id_aplicado','$r_tipo','$r_total_evaluado','$r_id_limite','$r_id_pedido','$r_id_usuario_req','$r_fecha_req','$r_estatus','$r_cantidad_limite','$r_cantidad_diponible')";
				if (mysql_query($insert_requiauto, $conex) or die(mysql_error()))
				{
					$id_reg_limit =  mysql_insert_id();
			
				}
					
					
					
					
					if ($lista_razones['tipo'] == 1){
						$razones_html .= '<tr><td>El Articulo '.$lista_razones['value'].' excede el limite establecido, disponible = '.$lista_razones['disponible'].', total en pedido = '.$lista_razones['total'].'</td><td> <input type=\"button\" id=\"justificar_'.$id_reg_limit.'\" value=\"Justificar\" class=\"btn btn-info justificacion\" /> 			<textarea id=\"txt_justif_'.$id_reg_limit.'\" class= \"textareajustificaciones\"> </textarea></td></tr>';
					}else if ($lista_razones['tipo'] == 2){
						$razones_html .= '<tr><td>El Centro de costos '.$lista_razones['value'].' excede el limite establecido, disponible = '.$lista_razones['disponible'].', total en pedido = '.$lista_razones['total'].'</td><td> <input type=\"button\" id=\"justificar_'.$id_reg_limit.'\" value=\"Justificar\" class=\"btn btn-info justificacion\" /> <textarea id=\"txt_justif_'.$id_reg_limit.'\" class= \"textareajustificaciones\"> </textarea>	</td></tr>';
					}else if ($lista_razones['tipo'] == 3){
						$razones_html .= '<tr><td>El Departamento '.$lista_razones['value'].' excede el limite establecido, disponible = '.$lista_razones['disponible'].', total en pedido = '.$lista_razones['total'].'</td><td> <input type=\"button\" id=\"justificar_'.$id_reg_limit.'\" value=\"Justificar\" class=\"btn btn-info justificacion\" /> 				<textarea id=\"txt_justif_'.$id_reg_limit.'\" class= \"textareajustificaciones\"> </textarea></td></tr>';
					}else if ($lista_razones['tipo'] == 4){
						$razones_html .= '<tr><td>El Usuario '.$lista_razones['value'].' excede el limite establecido, disponible = '.$lista_razones['disponible'].', total en pedido = '.$lista_razones['total'].'</td><td> <input type=\"button\" id=\"justificar_'.$id_reg_limit.'\" value=\"Justificar\" class=\"btn btn-info justificacion\" />         <textarea id=\"txt_justif_'.$id_reg_limit.'\" class= \"textareajustificaciones\"> </textarea></td></tr>';
					}
			} //foreach
					}
				
				$razones_html .= '</table><input type=\"hidden\" value=\"'.$id_pedido.'\" id=\"txt_auto_id_pedido\">';
				
				if ($datos_mostar == 0){
					echo '<script> 
					$("#modal_cargando").modal("hide");
					
					$("#t_limites_excedidos").html("'.$razones_html.'"); 
					$("#validacion_autorizacion").modal("show"); 
					$(".textareajustificaciones").hide();
					
					</script>
					
					<script>
							
					$(document).ready(function(){
				
						$(".justificacion").click(function(){
							// desplegar textarea para justificacion
							var btn_id = $(this).attr("id");
                         	var arr_id = btn_id.split("_");
							var id_reg_limit = arr_id[1];
							$("#txt_justif_"+id_reg_limit).show();
						});
						
						$(".textareajustificaciones").focusout(function(){
						/// AL DESENFOCAR EL TEXTO EN TEXT AREA SE GUARDARA EN AUTOMATICO, (UPDATE JUSTIFICACION)
						
						//alert("guardar justificacion");
						var txt_id = $(this).attr("id");
						var arr_id = txt_id.split("_");
						var id_justify = arr_id[2];
						var justificacion = $("#txt_justif_"+id_justify).val();
							jQuery.ajax({ 
								type: "POST",
								url: "data/actualizar_justificacion.php",
								data: {id_justify:id_justify,justificacion:justificacion},
								success: function(resultados)
								{ 
							
								}
							});
					
						});
				
					});
					</script>';
				}
			}else {
				// continua con la funcion actualizar pedido ordenar
				actualizar($id_pedido, $id_cc, $id_recolector, $orden_cliente,$almacen_id);
			} 
		
		
		
	}
	  
	  
     function actualizar($id_pedido, $id_cc, $id_recolector, $orden_cliente,$almacen_id){ 
global $database_conexion, $conex;

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");

$total_pedido = "";
$consulta_lista = "SELECT SUM(precio_total) as Total FROM pedidos_det WHERE id_pedido = '$id_pedido'";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_lista);
$total_rows2 = mysql_num_rows($resultado_lista);

if ($total_rows2 > 0){
	$total_pedido = $row['Total'];
}
$delete_reg = "DELETE FROM requi_autorizacion WHERE id_pedido = $id_pedido ";
								if (mysql_query($delete_reg, $conex) or die(mysql_error())){}

	$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
	$folio = folio_consecutivo($id_empresa_user_activo,"PED");
	
$estatus = '1';
$update_pedido = "UPDATE pedidos SET folio='$folio', estatus='$estatus', total_pedido='$total_pedido', id_cc='$id_cc', id_recolector='$id_recolector', orden_compra='$orden_cliente', id_sucursal='$almacen_id', fecha_pedido_oficial='$fecha_actual' WHERE id='$id_pedido'";

		if (mysql_query($update_pedido, $conex) or die(mysql_error()))
		{	
			$folio_consecutivo = $folio + 1;
			$update_folio = "UPDATE folios SET folio='$folio_consecutivo'  WHERE id_empresa='$id_empresa_user_activo' and tipo_folio='PED'";
			if (mysql_query($update_folio, $conex) or die(mysql_error())){}
			
			echo '<script>
					$("#span_folio_pedido").html('.$folio.');
					//enviar('.$id_pedido.');
					$("#modal_cargando").modal("hide");
					$("#modal_pedido").modal("show");
					mis_pedidos();
					
					</script>';
			
		}
		else 
		{
			echo 0;
		}
		

}

?>