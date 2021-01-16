<?php include("conexion.php");
		
		$id_user = $_POST['id_user'];
		
		if ($id_user != ''){  
			lista_limites($id_user);
		}
function lista_limites($id_user){
global $database_conexion, $conex;	

	$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
	$consulta_lista = "SELECT vl.id_limit as id_limit,vl.tipo as tipo, vl.id_aplicado as id_aplicado, vl.fecha_inicia as fecha_inicia, vl.cantidad_dinero as cantidad_dinero, vl.duracion_medida as duracion_medida, vl.cantidad_dm as cantidad_dm, vl.ciclo as ciclo, vl.id_usuario_requiere as id_usuario_requiere 
	FROM validacion_limit vl
	WHERE  id_empresa = '$id_empresa_user_activo' 
	ORDER BY tipo";
$resultado = mysql_query($consulta_lista, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
if ($total_rows > 0){
	echo '<table id="limites_spend" class="table table-striped table-bordered table-hover responsive display" >
                    	<thead>
                    		<tr class="info">
                    			<th>Apicado a</th>
								<th>Nombre</th>
                    			<th>Monto</th>
                    			<th>Fecha inicio</th>
                    			<th>Tipo Periodo</th>
                    			<th>Periodo</th>
                    			<th>Ciclo</th>
                    			<th>Establecio</th>
                    			
                    		</tr>
                    	</thead><tbody>';
                    		$tipo = '';						
                    		//$clase_td = '';	
							$nombre_concepto='';
							$tipo_periodo = '';
							$fechaFormato = '';
							$fechaMostrar = '';
							$ciclo = '';
                    		while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
                    		{
								switch($row2['tipo'])
								{
									case 1:
									$tipo = "Articulo";
									$nombre_concepto= ARTICULO_NOMBRE($row2['id_aplicado']);
									//$clase_td = 'class="btn-warning"';	
									break;
									case 2:
									$tipo = "Centro de Costos";
									//$clase_td = 'class="btn-info"';	
									$nombre_concepto= CC_NOMBRE($row2['id_aplicado']);
									break;
									case 3:
									$tipo = "Departamento";
									//$clase_td = 'class="btn-success"';
									$nombre_concepto= DEPARTAMENTO_NOMBRE($row2['id_aplicado']);
									break;
									case 4:
									$tipo = "Usuario";
									$nombre_concepto= Nombre($row2['id_aplicado']);
									break;
								}
								switch ($row2['duracion_medida']){
									case 1: //meses
									$tipo_periodo = "Mes";
									break;
									case 2:
									$tipo_periodo = "dia";
								}
								switch ($row2['ciclo']){
									case 0: //meses
									$ciclo = "No";
									break;
									case 1:
									$ciclo = "Si";
								}
								$fechaFormato = date_create($row2['fecha_inicia']);
								$fechaMostrar = date_format($fechaFormato, 'M/d/Y H:i');
								$fechaMostrar = str_replace("Jan","Ene",$fechaMostrar);		
								$fechaMostrar = str_replace("Aug","Ago",$fechaMostrar);		
								$fechaMostrar = str_replace("Dec","Dic",$fechaMostrar);		
													
								echo ' <tr id="trlistalimit_'.$row2['id_limit'].'" align="center">
									<td>'.$tipo.'</td>
									<td>'.$nombre_concepto.'</td>
									<td align="right">$'.number_format($row2['cantidad_dinero'], 2).'</td>
									<td>'.$fechaMostrar.'</td>
									<td>'.$tipo_periodo.'</td>
									<td>'.$row2['cantidad_dm'].'</td>
									<td>'.$ciclo.'</td>
									<td>'.Nombre($row2['id_usuario_requiere']).'</td>
								</tr>';		
                    			//<td><button id="" onclick="enviar('.$row2['id'].');">Enviar</button></td>
                    		}				
	echo '</tbody></table>
			<script>
				$(document).ready(function(){
					$("#limites_spend").DataTable();
				});
			</script>';
							 
}else {
	echo "No se han registrado limites de spend";
}
}
		
		
		?>