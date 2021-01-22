<?php include("../data/conexion.php"); 

		$id_usuario = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	 
	  if ($id_usuario != ''){
	  
			lista_pedidos_nef();
	  }
	  
function lista_pedidos_nef(){ ///Mostrara la lista de los pedidos_nef con el estatus y datos de folios
global $database_conexion, $conex;



$pedidos_nef = "
SELECT p.fecha_pedido_oficial as fecha_pedido_oficial, p.id_pedido as id_pedido, p.folio as folio, p.folio_pedido_microsip as folio_pedmicro , p.total_pedido as total_pedido, p.estatus as estatus, p.id_usuario as id_requi, user.nombre as nombre_r, user.apellido as apellido_r, alm.almacen as almacen, p.remisiones as rems, p.orden_compra as orden_compra, p.recepciones as recepciones
FROM pedido_nef p 
LEFT JOIN almacenes alm on alm.almacen_id = p.almacen_id
LEFT JOIN usuarios user on user.id = p.id_usuario

WHERE p.estatus <> '0' and p.estatus <> '0p'
ORDER BY p.id_pedido DESC";
$resultado_pednef= mysql_query($pedidos_nef, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado_pednef);
$total_pednef = mysql_num_rows($resultado_pednef);


if ($total_pednef > 0){ // con resultados

echo '<table id="lista_pedidos_nef" class="table table-striped table-bordered table-hover table-responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Requisitor</th>
                    		    <th>Almacen</th>
                    		    <th>Folio</th>
                    		    <th>Fecha Requerido</th>
                    		    <th>Pedido Nef</th>
                    		    <th>Remision Nef</th>
                    		    <th>OC allpart</th>
                    		    <th>Recepcion allpart</th>
                    		    <th>Taspaso allpart</th>
                    		    <th>Total </th>
                    		    <th>Estatus </th>
                    		</tr>
                    	</thead><tbody>';
					$estatus = '';
					$atributo = '';	
					$almacen = '';
					$id_pedido = '';
					$folio = '';
					$total_pedido = 0;
					$validacion_btn_tracking = '';					
					$pedido_nef = '';					
					
                   while($row2 = mysql_fetch_array($resultado_pednef,MYSQL_BOTH)) // html de articulos a mostrar
                   {
                   $id_pedido = $row2['id_pedido'];
				   $folio = $row2['folio'];
				   $total_pedido = $row2['total_pedido'];
				   
                   switch($row2['estatus'])
                   {
                   	
                   	case 1:		// en estado "Ordenado" opcion de requerir pedido a almacen
                  
                   	$estatus = 'Pendiente';
                   							
                   	break;
                   	case 2:			//// en estado "En proceso"
                   
                   	$estatus = 'Pedido generado';
                   	break;
                   	case 3: // en estado "Surtido" esto cuando ya se complete la recepcion al almacen correspondiente
                   	
                   	$estatus = 'Remisionado';
                   	break;
                   }
                   
				if ($row2['almacen'] == ""){
					$almacen = '-';
				}else
				{
					$almacen = $row2['almacen'];
				}
                if ($row2['nombre_r'] != '')
                {
                	$requisitor = $row2['nombre_r'].' '.$row2['apellido_r'] ;
                }
                else	
                {
                	$requisitor = '';
                } 
				   
				if ($row2['folio_pedmicro'] != '')
                {
                	$pedido_nef = $row2['folio_pedmicro'];
					
                }
                else	
                {
                	$pedido_nef = '<button class="btn btn-danger btn-block " type="button" id="btnestatus_'.$id_pedido.'" onclick="insertar_pedido_nef('.$id_pedido.');"> Generar Pedido Microsip </button>';
                }
				
				////////////////PERSONAL DE TRASPASOS////////////////////////////////////////////////
				//reviso si tiene permiso para buscar las remisiones
				//esta vista es para el de traspasos para editar las remisiones 
				
			$permiso=permisos();
			if ($permiso == 17)
			{ 
				///////ORDEN DE COMPRA///////////////////////////// 
				if($row2['orden_compra'] ==""){
				$folio_microsip=$row2['folio_pedmicro'];
				$roworden_c ='<div class="dropdown">
						<button class="btn btn-danger btn-block dropdown-toggle btn_estatus" type="button" id="btnestatus_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Orden Compra
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li class="btn btn-info btn-block btn_solicitud_traspaso" onclick="generar_orden_compra('.$folio_microsip.');" title="Genera una orden de compra en Allpart a partir del pedido en Nef.">Generar orden compra</li>
				</div>';}
				else {$roworden_c =$row2['orden_compra'] ;}
				//////////////////////////////////////////////////////////////////////////	
				//REMISIONES////////////////////////////////////////////////////////// 
				//////////////////////////////////////////////////////////////////////////
					$rowrems='';
					//boton aqui
				if($row2['rems'] ==""){
					$pedido_nef_original =$row2['folio_pedmicro'];
					$rowrems='<div class="dropdown">
							<button class="btn btn-danger btn-block dropdown-toggle btn_estatus" type="button" id="btnestatus_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Remision
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li class="btn btn-info btn-block btn_solicitud_traspaso" id="btn_solicitudtraspaso_1" onclick="buscar_remisiones(\''.$pedido_nef_original.'\', \'nef\', \'1\');" title="Busca en microsip si existen remisiones para ese pedido.">Buscar remisiones</li></div>';}
				//YA SE REMISIONO COMPLETA ///////////////////////////////////////////
				else if ($row2['estatus'] == 3)
				{//contenido
					$remisiones = array();
					$remsiones = explode(",", $row2['rems']);
					$orden_c_enciar =$row2['orden_compra'];
					foreach ($remsiones as $rem)
					{
						$rowrems .='<a onclick="detalle_remision ('.$rem.', '.$orden_c_enciar.', '.$id_pedido.');">'.$rem.'</a> <br>' ;
						
					}
					
					
				}
				//SE REMISIONO PARCIALMENTE///////////////////////////// //////////////
				else if ($row2['rems'] != "")
				{
						
						$remisiones = array();
						$remsiones = explode(",", $row2['rems']);
						$orden_c_enciar =$row2['orden_compra'];
					foreach ($remsiones as $rem)
						{
							$rowrems .='<a onclick="detalle_remision ('.$rem.', '.$orden_c_enciar.', '.$id_pedido.');">'.$rem.'</a> <br>' ;
						}
						$pedido_nef_original =$row2['folio_pedmicro'];
						
						$rowrems .='<span onclick="buscar_remisiones(\''.$pedido_nef_original.'\', \'nef\', \'1\');" ><i class="fa fa-refresh" aria-hidden="true" ></i></span>';
						
				}
				//////////////////////////////////////////////////////////////////////////////
				//RECEPCIONES//////////////////////////////////////////////////////////
				//////////////////////////////////////////////////////////////////////////
					$rowrec='';
					//boton aqui
				if($row2['recepciones'] ==""){
					$orden_compra_allpart =$row2['orden_compra'];
					
					$rowrec='<div class="dropdown">
							<button class="btn btn-danger btn-block dropdown-toggle btn_estatus" type="button" id="btnestatus_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Recepcion
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li class="btn btn-info btn-block btn_solicitud_traspaso" id="btn_solicitudtraspaso_1" onclick="buscar_remisiones(\''.$orden_compra_allpart.'\', \'nef\', \'2\');" title="Busca en microsip si existen recepciones para esta ORDEN.">Buscar recepciones</li></div>';}
				//YA SE RECEPCIONO COMPLETA ///////////////////////////////////////////
				else if ($row2['estatus'] == 4)
				{//contenido
					$recepciones = array();
					$recepciones = explode(",", $row2['recepciones']);
					
					foreach ($recepciones as $rec)
					{
						$print_rec = str_replace("RAP","",$rec);
						$print_rec =str_replace("," , "", number_format($print_rec, 0));
						$print_rec = 'RAP'.$print_rec;
						$rowrec .='<a onclick="detalle_recepcion (\''.$rec.'\', '.$id_pedido.');">'.$print_rec.'</a> <br>' ;
						
					}
					
					
				}
				//SE RECEPCIONO PARCIALMENTE///////////////////////////// //////////////
				else if ($row2['rems'] != "")
				{
						
						$recepciones = array();
						$recepciones = explode(",", $row2['recepciones']);
						$orden_c_enciar =$row2['orden_compra'];
					foreach ($recepciones as $rec)
						{
						
						$print_rec = str_replace("RAP","",$rec);
						$print_rec =str_replace("," , "", number_format($print_rec, 0));
						$print_rec = 'RAP'.$print_rec;
						
							$rowrec .='<a onclick="detalle_recepcion (\''.$rec.'\', '.$orden_c_enciar.', '.$id_pedido.');">'.$print_rec.'</a> <br>' ;
						}
						$orden_compra_allpart =$row2['orden_compra'];
						
						$rowrec .='<span onclick="buscar_remisiones(\''.$orden_compra_allpart.'\', \'nef\', \'2\');" ><i class="fa fa-refresh" aria-hidden="true" ></i></span>';
						
				}
				
					
			}
			////////////////VENDEDOR//////////////////////////////////////////////////////////
               else//cuando soy vendedor 
			{//contenido
			$rowrems =$row2['rems'] ;
			$rowrec =$row2['recepciones'] ;
			$roworden_c =$row2['orden_compra'] ;
			}
			////////////////////////////////////////////////////////////////////////////////////////////////
                    echo ' <tr >
                   	
					<td onclick="">'.$requisitor.'
					<input type="hidden" id="txt_folio_pedidonef_'.$id_pedido.'" value="'.$folio.'"/></td>
					<td onclick="">'.$almacen.'</td>
					<td onclick="">'.$folio.'</td>
					<td onclick="">'.$row2['fecha_pedido_oficial'].'</td>
					<td onclick="">'.$pedido_nef.'</td>
					<td onclick="">'.$rowrems.'</td>
					<td onclick="">'.$roworden_c.'</td>
					<td onclick="">'.$rowrec.'</td>
					<td onclick=""></td>
					<td align="right" onclick="">$'.number_format($total_pedido,2).'</td>
					<td align="right" id="td_estatus_'.$id_pedido.'">'.$estatus.'</td>	
							</tr>';
                   
                   }				
                    echo ' </tbody></table>';


 
 echo ' 	 <div class="modal fade" id="remision_detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title" id="detalle_modal_titulo">
                                           Detalle de remision
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;>
                                        <p class="h4">Modal para pedido.</p>
                                        <div class="table-responsive">
                                          
                                        </div>
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">Cerrar</button>
                                    </div>
                                    
                                </div>
                            </div>
                    </div>
	<script>
	
	$(document).ready(function()
	{
		
		$("#modal_cargando").modal("hide");
        $("#lista_pedidos_nef").DataTable(
		{
				"order": [[ 2, "desc" ]]
		});
		
	});
	 
	</script>';
 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No tiene ningun pedido</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}

function permisos()
{
$id_user=$_SESSION["logged_user"];	
global $database_conexion, $conex;

$pedidos_nef = "
SELECT tipo_usuario
FROM usuarios
WHERE id= '$id_user' ";
$resultado_pednef= mysql_query($pedidos_nef, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_pednef);
$total_pednef = mysql_num_rows($resultado_pednef);
if ($total_pednef > 0)
	{
		$permiso=$row['tipo_usuario'];
		
	}
	return $permiso;
}


?>





                    	
                    	
                