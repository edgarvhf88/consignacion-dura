<?php include("../data/conexion.php"); 
/* if ($_SESSION["logged_user"] <> ''){ header('Location: ../index.php'); } */
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
		$id_pedido = '';
		$segundos = '';
		$folio = '';
		$total_pedido = '';
		  
	  if (isset($_POST['folio'])){
      $folio = $_POST['folio'];
      }
	  if (isset($_POST['total_pedido'])){
      $total_pedido = $_POST['total_pedido'];
      }
	  if (isset($_POST['id_pedido'])){
      $id_pedido = $_POST['id_pedido'];
      }
	  if (isset($_POST['segundos'])){
      $segundos = $_POST['segundos'];
      }
	  if ($id_pedido != ''){
	  
			busca_solicitudes($id_pedido,$segundos,$folio,$total_pedido);
	  }
	  else
	  {
		  echo 0;
	  }
     function busca_solicitudes($id_pedido,$segundos,$folio,$total_pedido){ 
global $database_conexion, $conex, $folio_tabla_mis_pedidos, $clave_lista_pedido_index, $nombre_articulo_lista_pedido_index, $cantidad_lista_pedido_index, $precio_unitario_lista_pedido_index, $total_lista_pedido_index ;
if ($segundos != '')
{
	sleep($segundos);
}
$texto_folio = "";
if ($folio == 0){
	$folio = "-";
}
else
{
	$texto_folio = '<h3>'.$folio_tabla_mis_pedidos.': '.$folio.'</h3>';
}

$usuario_activo = $_SESSION["logged_user"];
$usuario_puede_autorizar = 0;
$consulta_usu = "SELECT *
			FROM usuarios
			WHERE id = '$usuario_activo' ";
$res_usu = mysql_query($consulta_usu, $conex) or die(mysql_error());
$row_usu = mysql_fetch_assoc($res_usu);
$total_usu = mysql_num_rows($res_usu);
if ($total_usu > 0){
	$usuario_puede_autorizar = $row_usu['autorizar_limit_spend'];
}

$minimo =1;

$consulta_lista = "SELECT rq.id_requi as id_requi,rq.tipo as tipo, rq.id_aplicado as id_aplicado, rq.total_evaluado as total_evaluado, rq.justificacion as justificacion, rq.estatus as estatus, rq.total_disponible as total_disponible, vl.cantidad_dinero as cantidad_dinero, us.nombre as nombre, us.apellido as apellido
					FROM requi_autorizacion rq
					INNER JOIN validacion_limit vl on vl.id_limit = rq.id_limite
					INNER JOIN usuarios us on us.id = rq.id_usuario_requiere
					WHERE rq.id_pedido = $id_pedido";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$total_rows2 = mysql_num_rows($resultado_lista);
if ($total_rows2 > 0){
	
echo '
<table id="aut_det_table" class="table table-striped table-bordered table-hover display responsive ">
                    	<thead>
                            <tr class="info">
                                
                                <th>Para</th>
                                <th>Descripcion</th>
                                <th>Limite establecido</th>
                                <th>Disponible</th>
                                <th>Monto en pedido</th>
                                <th>Justificacion</th>
                                <th>Opciones</th>
                                
                            </tr>
                        </thead><tbody>';
						
						$tipo = '';
						$nombre_concepto = '';
						$usuario_requiere = '';
						$td_estatus = '';
						$td_estatus_clase = '';
					
                            while($row2 = mysql_fetch_array($resultado_lista,MYSQL_BOTH)) // html de articulos a mostrar
                            {
                             $usuario_requiere = $row2['nombre'].' '.$row2['apellido'];
							
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
							switch($row2['estatus']){
								case 0:
								$td_estatus = 'Sin Autorizar';
								$td_estatus_clase = '';	
								break;
								case 1:
									if ($usuario_puede_autorizar == 1){
										$td_estatus = '</br>
								<button type="button" class="btn btn-success btn_aprobar" id="btnaprobar_'.$row2['id_requi'].'">Aprobar </button>
												</br></br>
                                <button type="button" class="btn btn-danger btn_denegar" id="btndenegar_'.$row2['id_requi'].'">Denegar </button>';
									} else {
									$td_estatus = 'Pendiente';	
									}
								
								$td_estatus_clase = '';		
								break;
								case 2:
								$td_estatus = 'Aprobado';
								$td_estatus_clase = 'class = "btn-success"';	
								break;
								case 3:
								$td_estatus = 'Denegado';
								$td_estatus_clase = 'class = "btn-danger"';
								break;
								
							}	
                            
                             echo ' <tr>               
                                    <td>'.$tipo.'</td>
                                    <td>'.$nombre_concepto.'</td>
									<td align="right">$'.number_format($row2['cantidad_dinero'],2).'</td>
									<td align="right">$'.number_format($row2['total_disponible'],2).'</td>
                                    <td align="right">$'.number_format($row2['total_evaluado'],2).' </td>
                                    <td>'.$row2['justificacion'].'</td>
                                    <td id="tdrequi_'.$row2['id_requi'].'" align="center" '.$td_estatus_clase.'>
									'.$td_estatus.'
									</td>
                                    </tr>
                                    ';
                            }  
							echo '</tbody></table>';							
                         

                                 echo '

                                <script>

                                $(document).ready( function () {
                                    $("#aut_det_table").DataTable({
										"order": [[ 1, "desc" ]]
									});
									
									$(".btn_aprobar").click(function(){
										var btn_id = $(this).attr("id");
                         				var arr_id = btn_id.split("_");
										var id_requi = arr_id[1];
										aprobar(id_requi);
										//alert("aprobado");
										//detalle_autorizacion("'.$id_pedido.'");
										$("#tdrequi_"+id_requi).html("Aprobado");
										$("#tdrequi_"+id_requi).addClass("btn-success");
									});
									$(".btn_denegar").click(function(){
										var btn_id = $(this).attr("id");
                         				var arr_id = btn_id.split("_");
										var id_requi = arr_id[1];
										denegar(id_requi);
										//alert("denegado");
										//detalle_autorizacion("'.$id_pedido.'");
										$("#tdrequi_"+id_requi).html("Denegado");
										$("#tdrequi_"+id_requi).addClass("btn-danger");
									});
									
                                } );

                                </script>
								<script>
								$("#span_usuario").html("'.$usuario_requiere.'");
								
								</script>
                                    ';

}  
 
else /// sin resultados
{
	echo ' <script>
					
								$("#autorizacion_detalle").modal("hide");
								</script>';		
		


}

}




?>
