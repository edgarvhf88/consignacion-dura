<?php include("conexion.php"); 
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
$Display = '';			
$display_empresas = display_empresas();
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	//echo $id." ** ".id_empresa($_SESSION["logged_user"])."<br />"; 
};

include("../displays/".$Display.".php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$id_usuario = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	 
	  if ($id_usuario != ''){
	  
			mis_pedidos($id_usuario);
	  }
	  else
	  {
		  echo 0;
	  }
	 /*  function estatus_autorizaciones($id_pedido){
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
		  
	  } */
	 
	  
     function mis_pedidos($id_usuario){ 
global $database_conexion, $conex, $fecha_tabla_mis_pedidos, $folio_tabla_mis_pedidos, $estatus_tabla_mis_pedidos ,$traking_tabla_mis_pedidos,$total_tabla_mis_pedidos ,$orden_cliente_tabla_mis_pedidos,$estatus_tipo_ordenado,$estatus_tipo_proceso ,$estatus_tipo_ruta,$estatus_tipo_entregado ,$titulo_modal_lista_articulos,$btn_cerrar_modal_lista_articulos,$msj_sin_pedidos_mis_pedidos,$cc_tabla_mis_pedidos,$recolector_tabla_mis_pedidos,$estatus_pedido_preparado;

$id_empresa = id_empresa($id_usuario);
$proceso = '0';
$pausada = '0p';
$pend_aut = '4';
$th_aprovacion = "";
$btn_aprovacion = "";
$th_usuario = "";

$usuario_puede_autorizar = 0;

$consulta_usu = "SELECT *
			FROM usuarios
			WHERE id = '$id_usuario' ";
$res_usu = mysql_query($consulta_usu, $conex) or die(mysql_error());
$row_usu = mysql_fetch_assoc($res_usu);
$total_usu = mysql_num_rows($res_usu);

if ($total_usu > 0){
	
	$usuario_puede_autorizar = $row_usu['autorizar_limit_spend'];
}

if ($usuario_puede_autorizar == 1){
		$consulta = "SELECT p.estatus as estatus, p.orden_compra as orden_compra, p.id as id, p.folio as folio, p.total_pedido as total_pedido, p.fecha_pedido_oficial as fecha_pedido_oficial, p.id_usuario as id_usuario, cc.nombre_cc as nombre_cc, user.nombre as nombre_r, user.apellido as apellido_r
			FROM pedidos p 
			LEFT JOIN centro_costos cc on cc.id_cc = p.id_cc
			LEFT JOIN usuarios user on user.id = p.id_recolector
			WHERE p.id_empresa = '$id_empresa' and  p.estatus = '$pend_aut' 
			ORDER BY p.folio DESC";
		$th_aprovacion = "<th> Solicitud </th>";
		$th_usuario = "<th> Solicitante </th>";		
	}else {
		$consulta = "SELECT p.estatus as estatus, p.orden_compra as orden_compra, p.id as id, p.folio as folio, p.total_pedido as total_pedido, p.fecha_pedido_oficial as fecha_pedido_oficial, p.id_usuario as id_usuario, cc.nombre_cc as nombre_cc, user.nombre as nombre_r, user.apellido as apellido_r
			FROM pedidos p 
			LEFT JOIN centro_costos cc on cc.id_cc = p.id_cc
			LEFT JOIN usuarios user on user.id = p.id_recolector
			WHERE p.id_usuario = '$id_usuario' and p.id_empresa = '$id_empresa' and  p.estatus = '$pend_aut' 
			ORDER BY p.folio DESC";
	}

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados

// <th>'.$traking_tabla_mis_pedidos.'</th>
echo '<table id="mis_pedidos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
								'.$th_usuario.'
                    			<th>'.$fecha_tabla_mis_pedidos.'</th>
                    			<th>'.$folio_tabla_mis_pedidos.'</th>
                    			<th>'.$estatus_tabla_mis_pedidos.'</th>
                    			<th>'.$cc_tabla_mis_pedidos.'</th>
                    			<th>'.$recolector_tabla_mis_pedidos.'</th>
                    			<th>'.$total_tabla_mis_pedidos.'</th>
                    			<th>'.$orden_cliente_tabla_mis_pedidos.'</th>
                    			'.$th_aprovacion.'
                    		</tr>
                    	</thead><tbody>';
                    		$estatus = '';						
                    		$clase_td = '';	
							$nombre_cc = '';
							$nombre_recolector = ''; 
						 
                    		while($row2 = mysql_fetch_array($resultado,MYSQL_ASSOC)) // html de articulos a mostrar
                    		{
								if ($th_aprovacion != ""){
									
									
									$btn_aprovacion = '<td align="center" style="width:100px;">
                    									<button style="margin-top:10px;" class="btn btn-warning btn_detalle" id="btndetalleaut_'.$row2['id'].'" onclick="detalle_autorizacion('.$row2['id'].');">
                    							<i class="fa fa-file"></i>
                    							Detalle 
                    							</button>
												
   									</td>';
									$user_req = '<td>'.Nombre($row2['id_usuario']).'</td>';	

									
								}else {
									$btn_aprovacion = '';
									$user_req = '';
								}
								
							$estatus_autorizaciones = estatus_autorizaciones($row2['id']);
							//echo $estatus_autorizaciones;
							$arr_cant_aut = explode('-',$estatus_autorizaciones);
							$cant_total = $arr_cant_aut[0];
							$cant_aprob = $arr_cant_aut[1];
							$cant_deneg = $arr_cant_aut[2];
							$cant_pend = $arr_cant_aut[3];
							$porcentaje_aprob = round((($cant_aprob / $cant_total) * 100),2);
							$porcentaje_deneg = round((($cant_deneg / $cant_total) * 100),2);
							
							
							
							$clase_td = 'class="btn-info"';	/// estatus default cuando no se a aprobado ni denegado nada
							$txt_estatus = 'Solicitud de Aprobacion';
							
								if ($cant_deneg > 0){ // si hay algun rechazo o mas
									$clase_td = 'class="btn-warning"';
									$txt_estatus = 'Proceso';
									
								}else if($cant_aprob > 0){ // si hay una aprovacion o mas
								$clase_td = 'class="btn-warning"';
								$txt_estatus = 'Proceso';
								}
							
							
							
                    		$estatus = $txt_estatus.' <div class="progress" style="margin-top:10px;">
  <div class="progress-bar progress-bar-success" role="progressbar" style="width: '.$porcentaje_aprob.'%" aria-valuenow="'.$porcentaje_aprob.'" aria-valuemin="0" aria-valuemax="100"> '.$porcentaje_aprob.'% aprobado</div>
  <div class="progress-bar progress-bar-danger" role="progressbar" style="width: '.$porcentaje_deneg.'%" aria-valuenow="'.$porcentaje_deneg.'" aria-valuemin="0" aria-valuemax="100"> '.$porcentaje_deneg.'% denegado</div>
</div>';
							
							
							$orden_compra = "";
                    		if ($row2['orden_compra'] != '')
                    		{
                    			$orden_compra = $row2['orden_compra'];
                    		}
                    		else	
                    		{
                    		/* 	$orden_compra = '<button class="btn btn-warning btn_addorden" id="ocbtnpedido_'.$row2['id'].'" >
                    							<i class="fa fa-file"></i>
                    							O.C.
                    							</button>'; */
                    		}
							if ($row2['nombre_cc'] == ""){
								$nombre_cc = '-';
							}else
							{
								$nombre_cc = $row2['nombre_cc'];
							}
							if ($row2['nombre_r'] == ""){
								$nombre_recolector = 'Personalmente';
							}else
							{
								$nombre_recolector = $row2['nombre_r'].''.$row2['apellido_r'];
							}
							
                    		echo ' 
							<tr id="trmispedidos_'.$row2['id'].'" align="center">
								'.$user_req.'
                    			<td onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									'.$row2['fecha_pedido_oficial'].'
                    									</td>
                    			<td onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									-
                    									</td>
                    			<td onclick="detalle_autorizacion('.$row2['id'].');" '.$clase_td.' align="center">
                    									'.$estatus.'
														
                    									</td>
                    			<td onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									'.$nombre_cc.'
                    									</td>
                    			<td onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									'.$nombre_recolector.'
                    									</td>
                    			<td align="right" onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									$'.number_format($row2['total_pedido'],2).'
                    									</td>
                    			<td align="right" style="width:120px;" onclick="detalle_pedido('.$row2['id'].','.$row2['folio'].','.$row2['total_pedido'].');">
                    									<input type="hidden" id="txt_folio_pedido_'.$row2['id'].'" value="'.$row2['folio'].'"/>
                    									'.$orden_compra.'
                    									</td>
								'.$btn_aprovacion.'						
                    																
                    		</tr>
                    							';
                    							
                    							
                    					//<td><button id="" onclick="enviar('.$row2['id'].');">Enviar</button></td>
                    		
                    							
                    		}				
                    		 echo ' </tbody></table>';
 
 
 echo ' <div class="modal fade" id="pedido_detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            '.$titulo_modal_lista_articulos.'
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;>
                                      
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">'.$btn_cerrar_modal_lista_articulos.'</button>
                                    </div>
                                    
                                </div>
                            </div>
         </div>
				
					
					
			<script>
			
	$(document).ready(function(){
				
                $(".btn_detalle").click(function(){
                               var btn_id = $(this).attr("id");
                         							   
							   var arr_id = btn_id.split("_");
							   var id_pedido = arr_id[1];
							   var folio = document.getElementById("txt_folio_pedido_"+id_pedido).value;
							
							   $("#txt_id_pedido").val(id_pedido);
							   
							  
							   //jQuery("#modal_orden .modal-header").html("Folio Pedido: "+folio) ;
							
							 // $("#autorizacion_detalle").modal("show");

								
								
                });
				
                $("#mis_pedidos").DataTable({
						"order": [[ 1, "desc" ]]
					});   
				
		});
	 
		</script>		
					
					
					';
 
} 
else /// sin resultados '.$msj_sin_pedidos_mis_pedidos.'
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#"> No tiene pedidos pendientes de autorizar </a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                